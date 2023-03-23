<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slide extends Model
{

    use HasFactory;
    protected $table = 'slide';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'slide_file',
        'link_url',
        'level',
        'is_publish',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
