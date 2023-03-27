<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{

    use HasFactory;
    protected $table = 'member';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'prefix',
        'name',
        'surname',
        'member_file',
        'position',
        'degree',
        'level',
        'is_publish',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
