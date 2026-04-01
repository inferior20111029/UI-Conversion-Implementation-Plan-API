<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_type',
        'name',
        'title',
        'partner_name',
        'logo_url',
        'description',
        'affiliate_url',
        'commission_rate',
        'is_active',
        'target_pet_types',
        'min_risk_score',
        'max_risk_score',
    ];

    protected $casts = [
        'target_pet_types' => 'array',
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
    ];
}
