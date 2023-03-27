<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portfolio extends Model
{

    use HasFactory;
    protected $table = 'portfolio';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'portfolio_file',
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
