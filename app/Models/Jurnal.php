<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jurnal extends Model
{
    use HasFactory;
    protected $table = "jurnal";
    protected $guarded = [];

    // public function presensi()
    // {
    //     return $this->belongsTo(Presensi::class, "presensi_id", "id");
    // }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeesModel::class, "employee_id", "id");
    }

}
