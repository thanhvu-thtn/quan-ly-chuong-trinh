<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Topic;
use App\Models\TopicType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\File;

// THÊM 3 DÒNG NÀY VÀO ĐÂY NHÉ:
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

// THÊM DÒNG NÀY CHO PDF BẰNG BROWSERSHOT:
use Spatie\Browsershot\Browsershot;

//Validate
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;

class ContentController extends Controller
{
    // Lấy query chuẩn để dùng chung cho cả Index và Export
    private function getSortedContents()
    {
        return Content::select('contents.*')
            ->join('topics', 'contents.topic_id', '=', 'topics.id')
            ->with(['topic.topicType']) // Lấy luôn thông tin topic và topicType để tránh N+1 query
            ->orderBy('topics.grade', 'asc')
            ->orderBy('topics.order', 'asc')
            ->orderBy('contents.order', 'asc');
    }

    public function index(Request $request)
    {
        // 1. Khởi tạo query kết nối 3 bảng: contents, topics, topic_types
        $query = Content::select('contents.*')
            ->join('topics', 'contents.topic_id', '=', 'topics.id')
            ->join('topic_types', 'topics.topic_type_id', '=', 'topic_types.id');

        // 2. Xử lý 3 Bộ lọc (Filters)
        if ($request->filled('topic_type_id')) {
            $query->where('topics.topic_type_id', $request->topic_type_id);
        }
        if ($request->filled('grade')) {
            $query->where('topics.grade', $request->grade);
        }
        if ($request->filled('topic_id')) {
            $query->where('contents.topic_id', $request->topic_id);
        }

        // 3. Xử lý Sắp xếp: Khối (tăng) -> Chủ đề (tăng) -> Nội dung (tăng)
        // Lưu ý: Cột 'order' là từ khóa nhạy cảm trong SQL, Laravel tự động xử lý an toàn qua Eloquent
        $contents = $query->orderBy('topics.grade', 'asc')
            ->orderBy('topics.order', 'asc')
            ->orderBy('contents.order', 'asc')
            ->with(['topic.topicType']) // Eager load để view không bị lỗi N+1
            ->paginate(20)
            ->withQueryString();

        // 4. Lấy dữ liệu cho 3 Dropdown lọc
        $topicTypes = TopicType::orderBy('name')->get();
        $grades = Topic::select('grade')->distinct()->orderBy('grade', 'asc')->pluck('grade');
        $topics = Topic::orderBy('grade')->orderBy('order')->get();

        // dd($topicTypes);
        return view('contents.index', compact('contents', 'topicTypes', 'grades', 'topics'));
    }

    /**
     * Hiển thị chi tiết một Nội dung
     */
    public function show(string $id)
    {
        // Lấy Nội dung kèm theo thông tin Chuyên đề và Loại chuyên đề
        $content = Content::with(['topic.topicType', 'objectives'])->findOrFail($id);

        return view('contents.show', compact('content'));
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getExportData($request);
    
    // Render View thành chuỗi HTML
    $html = view('contents.export', $data)->render();
    
    $pdfPath = storage_path('app/temp/Danh_Sach_Chuyen_De.pdf');

    // Chạy Chrome ẩn, render xong (đợi MathJax dịch) rồi lưu PDF
    Browsershot::html($html)
        ->format('A4')
        ->landscape()
        ->margins(10, 10, 10, 10)
        ->waitUntilNetworkIdle() // Bắt buộc: Đợi MathJax tải và dịch xong công thức
        ->save($pdfPath);

    return response()->download($pdfPath)->deleteFileAfterSend(true);
    }

    public function exportWord(Request $request)
    {
        $data = $this->getExportData($request);
        $htmlContent = view('contents.export.export', $data)->render();

        $tempDir = storage_path('app/temp');
        if (! File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $htmlFilePath = $tempDir.'/input_'.time().'.html';
        $docxFilePath = $tempDir.'/output_'.time().'.docx';

        File::put($htmlFilePath, $htmlContent);

        // ĐƯỜNG DẪN TỚI FILE MẪU QUAY NGANG & CÓ VIỀN
        $referenceDocPath = storage_path('app/custom-reference.docx');

        // CẬP NHẬT LỆNH PANDOC TẠI ĐÂY:
        // 1. Thêm cờ +tex_math_dollars để dịch Toán
        // 2. Thêm --reference-doc để lấy định dạng bảng và trang
        $process = new Process([
            '/opt/homebrew/bin/pandoc',
            $htmlFilePath,
            '-f', 'html+tex_math_dollars+tex_math_single_backslash',
            '-t', 'docx',
            '--reference-doc', $referenceDocPath,
            '-o', $docxFilePath,
        ]);

        $process->run();

        if (! $process->isSuccessful()) {
            File::delete($htmlFilePath);
            throw new ProcessFailedException($process);
        }

        File::delete($htmlFilePath);

        return response()->download($docxFilePath, 'Danh_Sach_Chuyen_De.docx')->deleteFileAfterSend(true);
    }

    /**mã cũ
     * Hiển thị Form Thêm mới Nội dung
     */
    //public function create()
   /*  {
        // Lấy danh sách Loại chuyên đề để làm bộ lọc
        $topicTypes = TopicType::all();

        // Lấy toàn bộ Chuyên đề (kèm loại) để nạp vào thẻ <select>
        $topics = Topic::with('topicType')->orderBy('grade')->orderBy('order')->get();

        return view('contents.create', compact('topicTypes', 'topics'));
    } */
    public function create()
   {
        $topics = Topic::with('topicType')->orderBy('grade')->orderBy('order')->get();
        $topicTypes = TopicType::orderBy('name')->get();
    
        // Ghi nhớ URL của trang trước đó (ví dụ: trang topics.show hoặc contents.index)
        $backUrl = url()->previous(); 

        return view('contents.create', compact('topics', 'topicTypes', 'backUrl'));
    }

    /**
     * Xử lý lưu Nội dung mới vào Database
     */

    public function store(StoreContentRequest $request)
    {
        // Nhờ dùng StoreContentRequest, xuống đến dòng này tức là dữ liệu ĐÃ CHUẨN 100%
        Content::create($request->all());

        $redirectUrl = $request->input('back_url', route('contents.index'));
        return redirect($redirectUrl)->with('success', 'Thêm mới nội dung thành công!');
    }
    //Mã cũ
    // public function store(Request $request) // Đổi Content $content thành $id
    // {
    //     // 1. Kiểm tra tính hợp lệ của dữ liệu (Validation)
    //     $request->validate([
    //         'topic_id' => 'required|exists:topics,id',
    //         'name' => 'required|string|max:255',
    //         'order' => 'required|integer|min:1',
    //         'periods' => 'required|integer|min:1',
    //     ], [
    //         'topic_id.required' => 'Vui lòng chọn Chủ đề.',
    //         'name.required' => 'Vui lòng nhập tên Nội dung.',
    //         'order.required' => 'Vui lòng nhập số thứ tự.',
    //         'periods.required' => 'Vui lòng nhập số tiết học.',
    //     ]);

    //     // 2. Lưu vào Database
    //     Content::create($request->all());

    //     // 3. Chuyển hướng về trang danh sách kèm thông báo thành công
    //     // Chuyển hướng về lại đúng URL xuất phát thay vì contents.index
    //     $redirectUrl = $request->input('back_url', route('contents.index'));

    //     return redirect($redirectUrl)
    //         ->with('success', 'Thêm mới nội dung Toán học thành công!');
    // }

    /**
     * Hiển thị Form Sửa Nội dung
     */
    /*  */

    public function edit($id)
    {
        // Tìm nội dung cần sửa
        $content = Content::findOrFail($id);
    
        $topics = Topic::with('topicType')->orderBy('grade')->orderBy('order')->get();
        $topicTypes = TopicType::orderBy('name')->get();
    
        // Ghi nhớ URL của trang trước đó (ví dụ: trang topics.show)
        $backUrl = url()->previous(); 

        return view('contents.edit', compact('content', 'topics', 'topicTypes', 'backUrl'));
    }

    // Mã cũ
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'topic_id' => 'required|exists:topics,id',
    //         'name'     => 'required|string|max:255',
    //         'order'    => 'required|integer|min:1',
    //         'periods'  => 'required|integer|min:1',
    //     ]);

    //     $content = Content::findOrFail($id);
    //     $content->update($request->all());

    //     // Đọc URL xuất phát từ form ẩn, nếu không có thì về contents.index
    //     $redirectUrl = $request->input('back_url', route('contents.index'));

    //     return redirect($redirectUrl)->with('success', 'Cập nhật nội dung thành công!');
    // }

    public function update(UpdateContentRequest $request, string $id)
    {
        // Tương tự, dữ liệu đã được kiểm tra nghiêm ngặt
        $content = Content::findOrFail($id);
        $content->update($request->all());

        $redirectUrl = $request->input('back_url', route('contents.index'));
        return redirect($redirectUrl)->with('success', 'Cập nhật nội dung thành công!');
    }

    /**
     * Hiển thị trang xác nhận xóa Nội dung
     */
    public function delete(string $id)
    {
        // Lấy thông tin Nội dung kèm theo Chuyên đề
        $content = Content::with('topic.topicType')->findOrFail($id);
        
        // Ghi nhớ URL của trang trước đó (topics.show hoặc contents.index)
        $backUrl = url()->previous();

        return view('contents.delete', compact('content', 'backUrl'));
    }

    /**
     * Xử lý xóa thực sự vào Database
     */
    public function destroy(Request $request, string $id)
    {
        $content = Content::findOrFail($id);
        
        // (Sau này có thể thêm code xóa các dữ liệu con ở đây)
        
        $content->delete();

        // Lấy URL từ form ẩn truyền lên, nếu không có thì về trang chủ
        $redirectUrl = $request->input('back_url', route('contents.index'));

        return redirect($redirectUrl)->with('success', 'Đã xóa nội dung thành công!');
    }

    /**
     * Hàm dùng chung để lấy dữ liệu xuất file theo bộ lọc
     */
    private function getExportData(Request $request)
    {
        // 1. Query dữ liệu (Lấy tất cả, KHÔNG phân trang)
        $query = Content::with(['topic.topicType', 'objectives'])
            ->select('contents.*')
            ->join('topics', 'contents.topic_id', '=', 'topics.id')
            ->join('topic_types', 'topics.topic_type_id', '=', 'topic_types.id');

        $typeName = '';
        if ($request->filled('topic_type_id')) {
            $query->where('topics.topic_type_id', $request->topic_type_id);
            $typeName = TopicType::find($request->topic_type_id)->name;
        }
        if ($request->filled('grade')) {
            $query->where('topics.grade', $request->grade);
        }
        if ($request->filled('topic_id')) {
            $query->where('contents.topic_id', $request->topic_id);
        }

        $contents = $query->orderBy('topics.grade', 'asc')
            ->orderBy('topics.order', 'asc')
            ->orderBy('contents.order', 'asc')
            ->get();

        // 2. Tạo Tiêu đề động
        $title = 'DANH SÁCH CHUYÊN ĐỀ';
        $title .= $request->filled('grade') ? ' LỚP '.$request->grade : ' TOÀN KHOÁ';
        if ($request->filled('topic_type_id') && $typeName != '') {
            $title .= ' '.mb_strtoupper($typeName, 'UTF-8');
        }

        // 3. Tính tổng số
        $totalPeriods = $contents->sum('periods');
        // Lấy ra danh sách các topic_id duy nhất để đếm số chuyên đề
        $totalTopics = $contents->pluck('topic_id')->unique()->count();

        return compact('contents', 'title', 'totalPeriods', 'totalTopics');
    }
}
