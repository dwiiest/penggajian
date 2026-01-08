<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
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

    public function hasRole($roleName)
    {
        return $this->role->name === $roleName;
    }

    public function isEmployee()
    {
        return $this->employee !== null;
    }
}
