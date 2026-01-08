<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function getFullInfoAttribute()
    {
        return "{$this->user->name} ({$this->nik})";
    }
}