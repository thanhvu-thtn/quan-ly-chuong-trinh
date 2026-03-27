<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Objective;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    /**
     * Lưu Yêu cầu cần đạt mới (Cách trực tiếp)
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
        ], [
            'description.required' => 'Vui lòng nhập nội dung yêu cầu cần đạt.',
        ]);

        // 1. Vẫn kiểm tra xem Nội dung có tồn tại không cho chắc chắn
        $content = \App\Models\Content::findOrFail($id);

        // 2. Tạo trực tiếp bằng Model Objective và tự tay gán content_id
        \App\Models\Objective::create([
            'content_id'  => $content->id, // Ép buộc lấy ID của Content hiện tại
            'description' => $request->description,
        ]);

        return back()->with('success', 'Đã thêm yêu cầu cần đạt thành công!');
    }

    /**
     * Xóa một Yêu cầu cần đạt
     */
    public function destroy($id)
    {
        $objective = Objective::findOrFail($id);
        $objective->delete();

        return back()->with('success', 'Đã xóa yêu cầu cần đạt!');
    }

    /**
     * Hiển thị form Sửa Yêu cầu cần đạt
     */
    public function edit($id)
    {
        // Tìm Yêu cầu cần đạt, lấy luôn thông tin Content để lỡ cần dùng
        $objective = \App\Models\Objective::with('content')->findOrFail($id);
        
        return view('objectives.edit', compact('objective'));
    }

    /**
     * Cập nhật Yêu cầu cần đạt vào Database
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
        ], [
            'description.required' => 'Vui lòng nhập nội dung yêu cầu cần đạt.',
        ]);

        $objective = \App\Models\Objective::findOrFail($id);
        $objective->update([
            'description' => $request->description,
        ]);

        // Sửa xong thì quay trở về trang Chi tiết của Nội dung đó
        return redirect()->route('contents.show', $objective->content_id)
                         ->with('success', 'Đã cập nhật yêu cầu cần đạt thành công!');
    }
}
