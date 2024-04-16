<?php

namespace App\Models;

use App\Models\Obat;
use App\Models\Pasien;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medis extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id'];


    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
