<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $fillable = [
        'slug', 'title', 'content', 'content_json',
        'meta_description', 'image_path', 'is_published',
    ];

    protected $casts = [
        'is_published'  => 'boolean',
        'content_json'  => 'array',
    ];
}
