<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'properties',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    /**
     * User who triggered this activity.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to cleanly record activity logs anywhere in the application.
     *
     * @param  array<string, mixed>|null  $properties
     */
    public static function record(
        string $action,
        string $description,
        ?User $user = null,
        ?array $properties = null
    ): ?self {
        try {
            $user = $user ?? Auth::user();
            $request = request();

            return static::create([
                'user_id' => $user?->id,
                'action' => $action,
                'description' => $description,
                'ip_address' => $request?->ip(),
                'user_agent' => $request ? substr((string) $request->userAgent(), 0, 500) : null,
                'properties' => $properties,
            ]);
        } catch (Throwable) {
            // Activity logging should never disrupt user transactions
            return null;
        }
    }
}
