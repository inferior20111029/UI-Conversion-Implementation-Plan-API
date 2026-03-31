<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'breed',
        'birthday',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function insuranceProfile()
    {
        return $this->hasOne(InsuranceProfile::class);
    }
}
