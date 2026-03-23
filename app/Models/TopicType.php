<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicType extends Model
{
    protected $fillable = ['name', 'description'];

    // 1 Loại chuyên đề có NHIỀU Chuyên đề (1-n)
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
