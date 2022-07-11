<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfPost extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const availableRelationships = [
        'post'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
