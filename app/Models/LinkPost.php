<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkPost extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const availableRelationships = [
        'post'
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
