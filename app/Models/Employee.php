<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'pf_number',
        'address',
        'email',
        'nic',
        'contact_number',
        'date_of_birth',
        'grade',
        'current_designation',
        'date_of_first_appointment',
        'date_of_confirmation',
        'unit_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'date_of_first_appointment' => 'date',
            'date_of_confirmation' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function workHistories()
    {
        return $this->hasMany(WorkHistory::class)->orderBy('from_date', 'desc');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    // ── Scopes ─────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('pf_number', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('nic', 'like', "%{$search}%")
              ->orWhere('contact_number', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByUnit(Builder $query, ?int $unitId): Builder
    {
        if (!$unitId) return $query;

        return $query->where('unit_id', $unitId);
    }
}
