<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_roles';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];
    public $timestamps = true;

    public function users()
    {
        return $this->hasMany(User::class, 'user_role_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
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