<?php

namespace App\Models;

use App\Models\Medis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pasien extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id'];

    // Di dalam model Pasien.php

    public function scopeCari($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orWhere('no_rm', 'like', '%' . $search . '%');
        });
    }

    public function medis()
    {
        return $this->hasMany(Medis::class);
    }
}
