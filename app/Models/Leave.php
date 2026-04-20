<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'date_from',
        'date_to',
        'number_of_days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'date_from' => 'date',
            'date_to' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Auto-calculate number of days when dates are set.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($leave) {
            if ($leave->date_from && $leave->date_to && !$leave->number_of_days) {
                $leave->number_of_days = Carbon::parse($leave->date_from)
                    ->diffInDays(Carbon::parse($leave->date_to)) + 1;
            }
        });
    }

    // ── Relationships ──────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Scopes ─────────────────────────────────────

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForEmployee(Builder $query, ?int $employeeId): Builder
    {
        if (!$employeeId) return $query;

        return $query->where('employee_id', $employeeId);
    }

    public function scopeFilterByStatus(Builder $query, ?string $status): Builder
    {
        if (!$status) return $query;

        return $query->where('status', $status);
    }
}
