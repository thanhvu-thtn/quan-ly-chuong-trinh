<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['topic_id', 'name', 'objectives', 'periods','order'];

    // 1 Nội dung THUỘC VỀ 1 Chuyên đề (n-1)
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
