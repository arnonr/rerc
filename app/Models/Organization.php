<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{

    use HasFactory;
    protected $table = 'organization';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'organization_file',
        'is_publish',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
