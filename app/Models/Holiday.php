<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'holiday_date',
        'holiday_type',
    ];

    protected function casts(): array
    {
        return [
            'holiday_date' => 'date',
        ];
    }

    // ── Scopes ─────────────────────────────────────

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('holiday_date', '>=', now()->toDateString())
                     ->orderBy('holiday_date', 'asc');
    }

    public function scopeByType(Builder $query, ?string $type): Builder
    {
        if (!$type) return $query;

        return $query->where('holiday_type', $type);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where('name', 'like', "%{$search}%");
    }
}
