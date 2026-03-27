<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    use HasFactory;

    protected $fillable = ['content_id', 'description'];

    // 1 Objective thuộc về 1 Content
    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
}