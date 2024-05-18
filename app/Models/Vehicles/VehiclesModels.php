<?php

namespace App\Models\Vehicles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehiclesModels extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(VehiclesBrand::class, 'brand_id');
    }
}
