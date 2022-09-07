<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_details extends Model {
    use HasFactory;
    public $fillable = [
        'course_name',
        'course_years',
    ];
}
