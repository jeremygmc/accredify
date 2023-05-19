<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class verifyDB extends Model
{
    use HasFactory;
    protected $table = 'your_table_name';
    protected $fillable = [
        'id',
        'file_type',
        'verification_result',
        'timestamp'
    ];
    public $timestamps = false;
}
