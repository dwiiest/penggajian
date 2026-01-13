<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Payroll extends Model 
{ 
    protected $guarded = ['id'];
    
    protected $casts = [
        'basic_salary' => 'decimal:2',
        'total_allowance' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PAID = 'paid';

    /**
     * Get the employee that owns the payroll
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get all available statuses
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PAID => 'Dibayar',
        ];
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === self::STATUS_PAID ? 'success' : 'warning';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Format basic salary
     */
    public function getFormattedBasicSalaryAttribute()
    {
        return 'Rp ' . number_format($this->basic_salary, 0, ',', '.');
    }

    /**
     * Format total allowance
     */
    public function getFormattedTotalAllowanceAttribute()
    {
        return 'Rp ' . number_format($this->total_allowance, 0, ',', '.');
    }

    /**
     * Format total deduction
     */
    public function getFormattedTotalDeductionAttribute()
    {
        return 'Rp ' . number_format($this->total_deduction, 0, ',', '.');
    }

    /**
     * Format net salary
     */
    public function getFormattedNetSalaryAttribute()
    {
        return 'Rp ' . number_format($this->net_salary, 0, ',', '.');
    }

    public function getWorkingDaysAttribute(): int
    {
        $monthNumber = is_numeric($this->month)
            ? (int) $this->month
            : Carbon::parse($this->month)->month;

        $startDate = Carbon::create($this->year, $monthNumber, 1)->startOfMonth();
        $endDate   = Carbon::create($this->year, $monthNumber, 1)->endOfMonth();

        $workingDays = 0;

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            if ($date->isWeekday()) {
                $workingDays++;
            }
        }

        return $workingDays;
    }

    /**
     * Get period attribute (Month Year)
     */
    public function getPeriodAttribute()
    {
        return "{$this->month} {$this->year}";
    }

    /**
     * Check if payroll is paid
     */
    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Check if payroll is draft
     */
    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Scope for filtering by month
     */
    public function scopeMonth($query, $month, $year)
    {
        return $query->where('month', $month)
                    ->where('year', $year);
    }

    /**
     * Scope for filtering by year
     */
    public function scopeYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for paid payrolls
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope for draft payrolls
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Calculate total gross salary (before deductions)
     */
    public function getGrossSalaryAttribute()
    {
        return $this->basic_salary + $this->total_allowance;
    }

    /**
     * Get month number
     */
    public function getMonthNumberAttribute()
    {
        return date('m', strtotime($this->month));
    }

    /**
     * Get Indonesian month name
     */
    public function getIndonesianMonthAttribute()
    {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        return $months[$this->month] ?? $this->month;
    }
}