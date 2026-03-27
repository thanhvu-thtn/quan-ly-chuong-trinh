<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateContentRequest extends FormRequest
{
    /**
     * Cho phép tất cả người dùng được dùng form này (sau này có phân quyền thì tính sau)
     */
    public function authorize(): bool
    {
        return true; // Nhớ đổi false thành true nhé!
    }

    /**
     * Các quy tắc kiểm tra dữ liệu
     */
    public function rules(): array
    {
        return [
            'topic_id' => 'required|exists:topics,id',
            'name'     => 'required|string|max:255',
            'order'    => 'required|integer|min:1',
            'periods'  => 'required|integer|min:0',
        ];
    }

    /**
     * Dịch các thông báo lỗi sang tiếng Việt cho thân thiện
     */
    public function messages(): array
    {
        return [
            'topic_id.required' => 'Vui lòng chọn Chuyên đề cho nội dung này.',
            'topic_id.exists'   => 'Chuyên đề đã chọn không tồn tại trong hệ thống.',
            'name.required'     => 'Tên nội dung không được bỏ trống.',
            'name.max'          => 'Tên nội dung không được vượt quá 255 ký tự.',
            'order.required'    => 'Vui lòng nhập thứ tự hiển thị.',
            'order.integer'     => 'Thứ tự phải là một số nguyên.',
            'order.min'         => 'Thứ tự nhỏ nhất là 1.',
            'periods.required'  => 'Vui lòng nhập số tiết học.',
            'periods.integer'   => 'Số tiết phải là số nguyên.',
            'periods.min'       => 'Số tiết tối thiểu phải là 0.',
        ];
    }
}
