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

    public function scopeFilter($query, $search)
    {
        if ($search) {
            return $query->whereHas('pasien', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
    public function reseps()
    {
        return $this->hasMany(Resep::class);
    }
}
