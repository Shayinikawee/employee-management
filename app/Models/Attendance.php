<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'import_batch',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    // ── Relationships ──────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // ── Accessors ──────────────────────────────────

    /**
     * Calculate worked hours based on check-in and check-out times.
     */
    public function getWorkedHoursAttribute(): ?string
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return null;
        }

        $checkIn = Carbon::parse($this->check_in_time);
        $checkOut = Carbon::parse($this->check_out_time);
        $diff = $checkIn->diff($checkOut);

        return $diff->format('%H:%I');
    }

    // ── Scopes ─────────────────────────────────────

    public function scopeForDate(Builder $query, ?string $date): Builder
    {
        if (!$date) return $query;

        return $query->whereDate('date', $date);
    }

    public function scopeForEmployee(Builder $query, ?int $employeeId): Builder
    {
        if (!$employeeId) return $query;

        return $query->where('employee_id', $employeeId);
    }

    public function scopeForUnit(Builder $query, ?int $unitId): Builder
    {
        if (!$unitId) return $query;

        return $query->whereHas('employee', function ($q) use ($unitId) {
            $q->where('unit_id', $unitId);
        });
    }

    public function scopeDateRange(Builder $query, ?string $from, ?string $to): Builder
    {
        if ($from) {
            $query->whereDate('date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('date', '<=', $to);
        }

        return $query;
    }
}
