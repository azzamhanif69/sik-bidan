<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resep extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id'];

    public function medis()
    {
        return $this->belongsTo(Medis::class, 'medis_id');
    }

    // Relasi ke Obat
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
    public function reseps()
    {
        return $this->hasMany(Resep::class, 'medis_id');
    }
}
