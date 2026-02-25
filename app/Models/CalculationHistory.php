<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalculationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategorija',
        'input_payload',
        'result_payload',
    ];

    protected function casts(): array
    {
        return [
            'input_payload' => 'array',
            'result_payload' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
