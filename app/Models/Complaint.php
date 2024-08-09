<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;
    protected $table = "complaints";
    protected $guarded = [];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeesModel::class, "employee_id", "id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "employee_id", "id");
    }

}
