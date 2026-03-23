<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    // Thêm 'order' vào danh sách cho phép cập nhật
    protected $fillable = ['topic_type_id', 'name', 'grade', 'order', 'total_periods'];

    // 1 Chuyên đề THUỘC VỀ 1 Loại chuyên đề (n-1)
    public function topicType()
    {
        return $this->belongsTo(TopicType::class);
    }

    // 1 Chuyên đề có NHIỀU Nội dung (1-n)
    public function contents()
    {
        return $this->hasMany(Content::class);
    }
}
