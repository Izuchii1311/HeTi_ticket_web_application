<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    protected $guarded = [];
    protected $appends = ['checkinCustom', 'checkoutCustom'];

    // public function jurnal()
    // {
    //     return $this->hasMany(Jurnal::class, "presensi_id", "id");
    // }

    public function getCheckinCustomAttribute()
    {
        return Carbon::parse($this->checkin)->format("H:i:s");
    }

    public function getCheckoutCustomAttribute()
    {
        if ($this->checkout) return Carbon::parse($this->checkout)->format("H:i:s");
        return null;
    }

}
