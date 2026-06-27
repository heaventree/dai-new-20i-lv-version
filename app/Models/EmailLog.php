<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EmailLog extends Model
{
    protected $fillable = ['to_email','to_name','subject','template','status','error_message'];
}
