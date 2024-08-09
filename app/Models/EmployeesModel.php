<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesModel extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $guarded = [];

    public function status() {
        return $this->hasOne(StatusEmployeeModel::class, 'id', 'status_id');
    }
    public function user() {
        return $this->hasOne(User::class,  'id','users_id');
    }
    public function jurnal() {
        return $this->hasMany(Jurnal::class,  'id','employee_id');
    }
}
