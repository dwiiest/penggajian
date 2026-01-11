<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'title',
        'base_salary',
        'transport_allowance',
        'meal_allowance',
        'status',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function getTotalSalaryAttribute()
    {
        return $this->base_salary + $this->transport_allowance + $this->meal_allowance;
    }

    public function getFormattedBaseSalaryAttribute()
    {
        return 'Rp ' . number_format($this->base_salary, 0, ',', '.');
    }

    public function getFormattedTotalSalaryAttribute()
    {
        return 'Rp ' . number_format($this->total_salary, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function isActive()
    {
        return $this->status == 1;
    }

    public function getStatusLabelAttribute()
    {
        return $this->status == 1 ? 'Aktif' : 'Nonaktif';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status == 1 ? 'success' : 'secondary';
    }
}
