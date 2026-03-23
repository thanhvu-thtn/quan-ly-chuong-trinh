<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\TopicType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\JcTable;

class TopicController extends Controller
{
    /**
     * Hiển thị danh sách Chuyên đề.
     */
    public function index(Request $request)
    {
        $query = Topic::with('topicType');

        // Lọc theo khối lớp
        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        // Lọc theo loại chuyên đề
        if ($request->filled('topic_type_id')) {
            $query->where('topic_type_id', $request->topic_type_id);
        }

        $topics = $query->orderBy('grade', 'asc')
                        ->orderBy('order', 'asc')
                        ->orderBy('topic_type_id', 'asc') // Bổ sung ưu tiên 3: Loại chuyên đề
                        ->get();

        // Lấy danh sách Loại chuyên đề truyền ra giao diện để làm bộ lọc
        $topicTypes = TopicType::all();

        $selectedGrade = $request->grade;
        $selectedTopicType = $request->topic_type_id;

        return view('topics.index', compact('topics', 'selectedGrade', 'selectedTopicType', 'topicTypes'));
    }

    /**
     * Hàm phụ: Lấy dữ liệu theo bộ lọc (Dùng chung cho cả PDF và Word)
     */
    private function getFilteredData($grade, $topicTypeId)
    {
        $query = Topic::with('topicType');
        if ($grade) {
            $query->where('grade', $grade);
        }
        if ($topicTypeId) {
            $query->where('topic_type_id', $topicTypeId);
        }

        return $query->orderBy('grade', 'asc')
                     ->orderBy('order', 'asc')
                     ->orderBy('topic_type_id', 'asc') // Bổ sung ưu tiên 3
                     ->get();
    }

    /**
     * Hiển thị form thêm mới Chuyên đề.
     */
    public function create()
    {
        // Lấy toàn bộ danh sách Loại chuyên đề để đưa vào thẻ <select>
        $topicTypes = TopicType::all();

        return view('topics.create', compact('topicTypes'));
    }

    /**
     * Xử lý lưu dữ liệu vào Database.
     */
    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu hợp lệ (Validation)
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|integer|in:10,11,12',
            'order' => 'required|integer|min:1',
            'topic_type_id' => 'required|exists:topic_types,id',
            'total_periods' => 'required|integer|min:1',
        ], [
            'name.required' => 'Vui lòng nhập tên chuyên đề.',
            'grade.in' => 'Khối lớp chỉ được là 10, 11 hoặc 12.',
            'order.min' => 'Thứ tự phải là số nguyên dương.',
            'topic_type_id.exists' => 'Loại chuyên đề không hợp lệ.',
            'total_periods.min' => 'Tổng số tiết phải lớn hơn 0.',
        ]);

        // 2. Lưu vào Database
        Topic::create([
            'name' => $request->name,
            'grade' => $request->grade,
            'order' => $request->order,
            'topic_type_id' => $request->topic_type_id,
            'total_periods' => $request->total_periods,
        ]);

        // 3. Chuyển hướng về trang danh sách kèm thông báo
        return redirect()->route('topics.index')
            ->with('success', 'Đã thêm chuyên đề mới thành công!');
    }

    /**
     * Hiển thị chi tiết một Chuyên đề.
     */
    public function show(string $id)
    {
        // Dùng eager loading 'with' để lấy cả Loại chuyên đề VÀ Danh sách nội dung của nó
        // Tránh lỗi N+1 Query giúp trang web load siêu nhanh
        //$topic = Topic::with(['topicType', 'contents'])->findOrFail($id);
        // Lấy Topic, TopicType và danh sách Contents (được sắp xếp tăng dần theo cột order)
        $topic = Topic::with(['topicType', 'contents' => function($query) {
            $query->orderBy('order', 'asc');
        }])->findOrFail($id);

        return view('topics.show', compact('topic'));
    }

    /**
     * Hiển thị form Sửa chuyên đề.
     */
    public function edit(string $id)
    {
        // Lấy chuyên đề hiện tại theo ID
        $topic = Topic::findOrFail($id);
        
        // Lấy danh sách Loại chuyên đề cho thẻ <select>
        $topicTypes = \App\Models\TopicType::all();
        
        return view('topics.edit', compact('topic', 'topicTypes'));
    }

    /**
     * Xử lý lưu dữ liệu đã sửa vào Database.
     */
    public function update(Request $request, string $id)
    {
        // 1. Kiểm tra dữ liệu (Validation)
        $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|integer|in:10,11,12',
            'order' => 'required|integer|min:1',
            'topic_type_id' => 'required|exists:topic_types,id',
            'total_periods' => 'required|integer|min:1',
        ], [
            'name.required' => 'Vui lòng nhập tên chuyên đề.',
            'grade.in' => 'Khối lớp chỉ được là 10, 11 hoặc 12.',
            'order.min' => 'Thứ tự phải là số nguyên dương.',
            'topic_type_id.exists' => 'Loại chuyên đề không hợp lệ.',
            'total_periods.min' => 'Tổng số tiết phải lớn hơn 0.',
        ]);

        // 2. Tìm bản ghi và Cập nhật
        $topic = Topic::findOrFail($id);
        $topic->update([
            'name' => $request->name,
            'grade' => $request->grade,
            'order' => $request->order,
            'topic_type_id' => $request->topic_type_id,
            'total_periods' => $request->total_periods,
        ]);

        // 3. Chuyển hướng về danh sách kèm thông báo
        return redirect()->route('topics.index')
                         ->with('success', 'Đã cập nhật thông tin chuyên đề thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Xuất file PDF
     */
    public function exportPdf(Request $request)
    {
        // Truyền cả 2 biến lọc vào hàm
        $topics = $this->getFilteredData($request->grade, $request->topic_type_id);
        $selectedGrade = $request->grade;

        // Tìm tên loại chuyên đề để in ra tiêu đề PDF (nếu có chọn lọc)
        $topicTypeName = '';
        if ($request->filled('topic_type_id')) {
            $type = TopicType::find($request->topic_type_id);
            $topicTypeName = $type ? $type->name : '';
        }

        $pdf = Pdf::loadView('topics.export', compact('topics', 'selectedGrade', 'topicTypeName'));

        return $pdf->download('Danh_sach_Chuyen_de.pdf');
    }

    /**
     * Xuất file Word (.docx chuẩn)
     */
    public function exportWord(Request $request)
    {
        $topics = $this->getFilteredData($request->grade, $request->topic_type_id);
        $selectedGrade = $request->grade;

        $topicTypeName = '';
        if ($request->filled('topic_type_id')) {
            $type = TopicType::find($request->topic_type_id);
            $topicTypeName = $type ? $type->name : '';
        }

        $phpWord = new PhpWord;
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(13);
        $section = $phpWord->addSection();

        // Ghép chuỗi tiêu đề mượt mà theo đúng văn phong tiếng Việt
        $title = 'DANH SÁCH CHUYÊN ĐỀ';

        // Nếu có lọc loại chuyên đề thì chèn tên loại (IN HOA) vào giữa
        if ($topicTypeName) {
            $title .= ' '.mb_strtoupper($topicTypeName, 'UTF-8');
        }

        // Chốt lại bằng môn học và khối lớp
        $title .= ' VẬT LÝ '.($selectedGrade ? 'LỚP '.$selectedGrade : 'TOÀN CẤP');

        $section->addText($title, ['bold' => true, 'size' => 15], ['alignment' => 'center']);
        $section->addTextBreak(1);

        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80, 'alignment' => JcTable::CENTER];
        $phpWord->addTableStyle('TopicTable', $tableStyle);
        $table = $section->addTable('TopicTable');

        $table->addRow();
        $headerStyle = ['bold' => true];
        $table->addCell(1000)->addText('Khối', $headerStyle, ['alignment' => 'center']);
        $table->addCell(1500)->addText('Thứ tự', $headerStyle, ['alignment' => 'center']);
        $table->addCell(4500)->addText('Tên chuyên đề', $headerStyle, ['alignment' => 'center']);
        $table->addCell(2000)->addText('Phân loại', $headerStyle, ['alignment' => 'center']);
        $table->addCell(1500)->addText('Số tiết', $headerStyle, ['alignment' => 'center']);

        foreach ($topics as $topic) {
            $table->addRow();
            $table->addCell(1000)->addText($topic->grade, null, ['alignment' => 'center']);
            $table->addCell(1500)->addText($topic->order, null, ['alignment' => 'center']); // Đã sửa chỉ hiển thị số
            $table->addCell(4500)->addText($topic->name);
            $table->addCell(2000)->addText($topic->topicType->name, null, ['alignment' => 'center']);
            $table->addCell(1500)->addText($topic->total_periods, null, ['alignment' => 'center']);
        }

        // BỔ SUNG: Dòng tổng kết ở cuối bảng Word
        // Nếu có dữ liệu thì mới in dòng tổng
        if ($topics->count() > 0) {
            $table->addRow();
            
            // Gộp 4 cột đầu tiên lại (Tổng độ rộng 1000 + 1500 + 4500 + 2000 = 9000)
            $table->addCell(9000, ['gridSpan' => 4])->addText(
                'Tổng cộng: ' . $topics->count() . ' chuyên đề', 
                ['bold' => true, 'italic' => true], 
                ['alignment' => 'right']
            );
            
            // Cột cuối cùng in ra tổng số tiết
            $table->addCell(1500)->addText(
                $topics->sum('total_periods'), 
                ['bold' => true], 
                ['alignment' => 'center']
            );
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

        return response()->streamDownload(function () use ($objWriter) {
            $objWriter->save('php://output');
        }, 'Danh_sach_Chuyen_de.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }
    // Hàm xuất chi tiết chuyên đề ra Word
    public function exportDetailWord(string $id)
    {
        //$topic = Topic::with(['topicType', 'contents'])->findOrFail($id);
        // Lấy Topic, TopicType và danh sách Contents (được sắp xếp tăng dần theo cột order)
        $topic = Topic::with(['topicType', 'contents' => function($query) {
            $query->orderBy('order', 'asc');
        }])->findOrFail($id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(13);
        
        // Khởi tạo trang Word dọc bình thường
        $section = $phpWord->addSection(['orientation' => 'landscape']);

        // Tiêu đề và Thông tin
        $section->addText("CHI TIẾT CHUYÊN ĐỀ: " . mb_strtoupper($topic->name, 'UTF-8'), ['bold' => true, 'size' => 15], ['alignment' => 'center']);
        $section->addTextBreak(1);
        
        $infoTable = $section->addTable();
        $infoTable->addRow();
        $infoTable->addCell(4000)->addText("Chuyên đề: " . $topic->name, ['bold' => true]);
        $infoTable->addCell(4000)->addText("Loại: " . $topic->topicType->name, ['bold' => true]);
        $infoTable->addCell(3000)->addText("Tổng số tiết: " . $topic->total_periods, ['bold' => true]);
        $section->addTextBreak(1);

        // Bảng Nội dung
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80];
        $phpWord->addTableStyle('ContentTable', $tableStyle);
        $table = $section->addTable('ContentTable');

        $table->addRow();
        $headerStyle = ['bold' => true];
        $table->addCell(600)->addText('STT', $headerStyle, ['alignment' => 'center']);
        $table->addCell(3500)->addText('Nội dung', $headerStyle, ['alignment' => 'center']);
        $table->addCell(1000)->addText('Số tiết', $headerStyle, ['alignment' => 'center']);
        $table->addCell(6000)->addText('Yêu cầu cần đạt', $headerStyle, ['alignment' => 'center']);

        if ($topic->contents && $topic->contents->count() > 0) {
            foreach ($topic->contents as $index => $content) {
                $table->addRow();
                $table->addCell(600)->addText($index + 1, null, ['alignment' => 'center']);
                $table->addCell(3500)->addText($content->name);
                $table->addCell(1000)->addText($content->periods, null, ['alignment' => 'center']);
                
                $reqCell = $table->addCell(6000);
                $reqLines = explode("\n", $content->objectives);
                foreach ($reqLines as $line) {
                    if (trim($line) !== '') {
                        $reqCell->addText(trim($line));
                    }
                }
            }
            $table->addRow();
            $table->addCell(4100, ['gridSpan' => 2])->addText('Tổng cộng: ' . $topic->contents->count() . ' nội dung', ['bold' => true, 'italic' => true], ['alignment' => 'right']);
            $table->addCell(1000)->addText($topic->contents->sum('periods'), ['bold' => true], ['alignment' => 'center']);
            $table->addCell(6000)->addText('');
        } else {
            $table->addRow();
            $table->addCell(10000)->addText('Chuyên đề này chưa có nội dung nào được thêm vào.', null, ['alignment' => 'center']);
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        return response()->streamDownload(function () use ($objWriter) {
            $objWriter->save('php://output');
        }, 'Chi_tiet_' . \Illuminate\Support\Str::slug($topic->name) . '.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    /**
     * Xuất PDF Chi tiết chuyên đề
     */
    public function exportDetailPdf(string $id)
    {
        //$topic = Topic::with(['topicType', 'contents'])->findOrFail($id);
        // Lấy Topic, TopicType và danh sách Contents (được sắp xếp tăng dần theo cột order)
        $topic = Topic::with(['topicType', 'contents' => function($query) {
            $query->orderBy('order', 'asc');
        }])->findOrFail($id);
        
        $pdf = Pdf::loadView('topics.export_detail', compact('topic'));
        
        // Cấu hình khổ giấy A4 ngang (landscape) để đọc Yêu cầu cần đạt cho rộng rãi
        $pdf->setPaper('A4', 'landscape'); 
        
        return $pdf->download('Chi_tiet_' . \Illuminate\Support\Str::slug($topic->name) . '.pdf');
    }
}
