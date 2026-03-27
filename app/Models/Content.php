<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Topic;

class Content extends Model
{
    protected $fillable = ['order', 'topic_id', 'name', 'periods']; // Đã xóa objectives

    // 1 Content có nhiều Objectives
    public function objectives()
    {
        return $this->hasMany(Objective::class, 'content_id');
    }

    // 1 Nội dung THUỘC VỀ 1 Chuyên đề (n-1)
   public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}
