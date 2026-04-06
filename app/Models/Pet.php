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
        'gender',
        'breed',
        'birthday',
        'weight',
    ];

    protected $casts = [
        'birthday' => 'date',
        'weight' => 'float',
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

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function aiHealthScans()
    {
        return $this->hasMany(AiHealthScan::class);
    }
}
