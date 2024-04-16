<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{

    use HasFactory, HasUuids;
    protected $guarded = ['id'];
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nama_obat', 'like', '%' . $search . '%')->OrWhere('sediaan', 'like', '%' . $search . '%');
        });
    }
}
