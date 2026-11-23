<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'school', 'skor_praktik', 'points', 'nis', 'kelas'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'skor_praktik' => 'integer',
            'points' => 'integer',
        ];
    }

    /**
     * Get all quiz attempts for the user.
     *
     * @return HasMany<QuizAttempt, $this>
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get all activity logs for the user.
     *
     * @return HasMany<ActivityLog, $this>
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Check if user is an Administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a Guru.
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Check if user is a Siswa.
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa' || empty($this->role);
    }

    /**
     * Get readable role label.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'guru' => 'Guru SMK',
            default => 'Siswa SMK',
        };
    }

    /**
     * Get formatted NIS with fallback.
     */
    public function getDisplayNisAttribute(): string
    {
        return $this->nis ?: '220914';
    }

    /**
     * Get formatted Class with fallback.
     */
    public function getDisplayKelasAttribute(): string
    {
        if (! empty($this->kelas)) {
            return $this->kelas;
        }

        return $this->school ? 'Kelas X RPL ('.$this->school.')' : 'Kelas X Rekayasa Perangkat Lunak 1';
    }
}
