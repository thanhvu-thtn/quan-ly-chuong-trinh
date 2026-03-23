<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TopicType;

class TopicTypeController extends Controller
{
    /**
     * Hiển thị danh sách các Loại chuyên đề.
     */
    public function index()
    {
        // Lấy tất cả loại chuyên đề (vì số lượng ít nên dùng all() thay vì paginate)
        $topicTypes = TopicType::all();
        
        return view('topic_types.index', compact('topicTypes'));
    }

    /**
     * Hiển thị form thêm mới Loại chuyên đề.
     */
    public function create()
    {
        return view('topic_types.create');
    }

    /**
     * Xử lý lưu dữ liệu vào Database.
     */
    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu hợp lệ (Validation)
        $request->validate([
            'name' => 'required|string|max:255|unique:topic_types,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên loại chuyên đề.',
            'name.unique' => 'Tên loại chuyên đề này đã tồn tại trong hệ thống.',
        ]);

        // 2. Lưu vào Database
        TopicType::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 3. Chuyển hướng về trang danh sách kèm thông báo
        return redirect()->route('topic-types.index')
                         ->with('success', 'Đã thêm loại chuyên đề mới thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Hiển thị form Sửa dữ liệu.
     */
    public function edit(string $id)
    {
       // Lấy dữ liệu cũ từ Database dựa vào ID
        $topicType = TopicType::findOrFail($id);
        
        // Trả về view edit và truyền dữ liệu cũ sang
        return view('topic_types.edit', compact('topicType'));
    }

    /**
     * Xử lý lưu dữ liệu đã sửa vào Database.
     */
    public function update(Request $request, string $id)
    {
        // 1. Kiểm tra dữ liệu (Lưu ý phần unique có thêm biến $id để bỏ qua bản ghi hiện tại)
        $request->validate([
            'name' => 'required|string|max:255|unique:topic_types,name,' . $id,
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên loại chuyên đề.',
            'name.unique' => 'Tên loại chuyên đề này đã tồn tại trong hệ thống.',
        ]);

        // 2. Tìm bản ghi và Cập nhật
        $topicType = TopicType::findOrFail($id);
        $topicType->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 3. Chuyển hướng về danh sách
        return redirect()->route('topic-types.index')
                         ->with('success', 'Đã cập nhật thông tin loại chuyên đề thành công!');
    }

    /**
     * Xóa loại chuyên đề khỏi cơ sở dữ liệu.
     */
    public function destroy(string $id)
    {
        // Tìm loại chuyên đề theo ID, nếu không thấy sẽ tự báo lỗi 404
        $topicType = TopicType::findOrFail($id);

        // Lấy tên để đưa vào thông báo cho thân thiện
        $name = $topicType->name;

        // Thực hiện lệnh xóa
        // Lưu ý: Do đã cài đặt cascadeOnDelete ở Database, mọi Chuyên đề con của Loại này cũng sẽ tự động biến mất!
        $topicType->delete();

        // Chuyển hướng về trang danh sách kèm thông báo
        return redirect()->route('topic-types.index')
                         ->with('success', "Đã xóa thành công loại chuyên đề: {$name}.");
    }
}
