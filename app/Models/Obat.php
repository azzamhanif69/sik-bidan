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
        $query->when(isset($filters['search']), function ($query) use ($filters) {
            return $query->where(function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where('nama_obat', 'like', '%' . $search . '%')
                    ->orWhere('sediaan', 'like', '%' . $search . '%');
            });
        });
    }
    public function reseps()
    {
        return $this->hasMany(Resep::class);
    }
}
