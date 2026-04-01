<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'risk_score',
        'premium_discount',
        'last_calculated_at',
    ];

    protected $casts = [
        'last_calculated_at' => 'datetime',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
