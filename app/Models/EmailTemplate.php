<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = ['slug', 'name', 'subject', 'body', 'is_active', 'is_default'];
    protected $casts    = ['is_active' => 'boolean', 'is_default' => 'boolean'];
}
