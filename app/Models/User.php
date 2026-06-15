<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
        
    use HasFactory, HasRoles, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_MASTER = 'master';
    const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    protected static function booted()
    {
        static::created(function ($user) {
            $defaultRole = config('app.default_role', 'user');

            if ($user->email === config('app.engineer_email') ||
                in_array($user->email, array_map('trim', explode(',', config('app.master_emails', ''))))) {
                return;
            }

            $user->assignRole($defaultRole);
        });
    }

    // FIXED: Accessor for role attribute
    public function getRoleAttribute()
    {
        // Use the relationship method, not the attribute
        if ($this->roleRelation) {
            return $this->roleRelation->slug;
        }

        // Fallback to direct attribute if relationship isn't loaded
        return $this->attributes['role'] ?? null;
    }

    // Helper methods - FIXED to avoid circular references
    public function isAdmin(): bool
    {
        return $this->getRoleAttribute() === self::ROLE_ADMIN;
    }

    public function isMaster(): bool
    {
        return $this->getRoleAttribute() === self::ROLE_MASTER;
    }

    public function isUser(): bool
    {
        return $this->getRoleAttribute() === self::ROLE_USER;
    }

    // Remove the problematic safeIsMaster() method or fix it:
    public function safeIsMaster()
    {
        try {
            return $this->getRoleAttribute() === self::ROLE_MASTER;
        } catch (\Exception $e) {
            Log::warning('Error checking master role: '.$e->getMessage());

            return false;
        }
    }

    // Keep this relationship method but rename it to avoid conflict
    public function roleRelation()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
