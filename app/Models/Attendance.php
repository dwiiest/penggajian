<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'status',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    const STATUS_HADIR = 'hadir';
    const STATUS_TERLAMBAT = 'terlambat';
    const STATUS_IZIN = 'izin';
    const STATUS_SAKIT = 'sakit';
    const STATUS_ALPHA = 'alpha';
    const STATUS_CUTI = 'cuti';

    public static function getStatuses()
    {
        return [
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_TERLAMBAT => 'Terlambat',
            self::STATUS_IZIN => 'Izin',
            self::STATUS_SAKIT => 'Sakit',
            self::STATUS_ALPHA => 'Alpha',
            self::STATUS_CUTI => 'Cuti',
        ];
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_HADIR => 'success',
            self::STATUS_TERLAMBAT => 'warning',
            self::STATUS_IZIN => 'info',
            self::STATUS_SAKIT => 'primary',
            self::STATUS_ALPHA => 'danger',
            self::STATUS_CUTI => 'secondary',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    public function getWorkHoursAttribute()
    {
        if (!$this->time_in || !$this->time_out) {
            return null;
        }

        $timeIn = Carbon::parse($this->time_in);
        $timeOut = Carbon::parse($this->time_out);

        $diff = $timeIn->diff($timeOut);
        
        return sprintf('%d jam %d menit', $diff->h, $diff->i);
    }

    public function isLate()
    {
        if (!$this->time_in) {
            return false;
        }

        $timeIn = Carbon::parse($this->time_in);
        $lateCutoff = Carbon::parse('08:00:00');

        return $timeIn->greaterThan($lateCutoff);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeMonth($query, $month, $year)
    {
        return $query->whereMonth('date', $month)
                     ->whereYear('date', $year);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}