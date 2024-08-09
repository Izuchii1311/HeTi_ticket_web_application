<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusEmployeeModel extends Model
{
    use HasFactory;
    protected $table = "status_employee";
    protected $guarded = [];
}
