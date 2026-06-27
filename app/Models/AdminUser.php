<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AdminUser extends Model
{
    protected $fillable = ['username', 'email', 'password_hash', 'role'];
    protected $hidden = ['password_hash'];
}
