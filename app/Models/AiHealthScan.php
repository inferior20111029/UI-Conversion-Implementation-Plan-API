<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiHealthScan extends Model
{
    use HasFactory;

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'pet_id',
        'image_path',
        'status',
        'confidence',
        'score',
        'estimated_saving',
        'issues',
        'scanned_at',
    ];

    protected $casts = [
        'issues' => 'array',
        'scanned_at' => 'datetime',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
