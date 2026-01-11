<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
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
