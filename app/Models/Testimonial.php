<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['quote', 'name', 'location', 'stars', 'highlight', 'sort_order'];
    protected $casts = ['highlight' => 'boolean', 'stars' => 'integer', 'sort_order' => 'integer'];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if ($model->sort_order === 0) {
                $model->sort_order = (static::max('sort_order') ?? 0) + 1;
            }
        });
    }
}
