<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'type',
        'value',
        'recorded_at',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    protected $casts = [
        'recorded_at' => 'datetime',
    ];
}
