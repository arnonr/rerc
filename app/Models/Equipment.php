<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipment extends Model
{

    use HasFactory;
    protected $table = 'equipment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'lab_room_id',
        'equipment_file',
        'equipment_vdo_file',
        'detail',
        'level',
        'is_publish',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
