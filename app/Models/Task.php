<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_completed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
        ];
    }

    /**
     * Scope query to search by title or description.
     */
    public function scopeSearch($query, ?string $search)
    {
        if (filled($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Scope query to filter by completion status.
     */
    public function scopeStatus($query, ?string $status)
    {
        if ($status === 'completed') {
            $query->where('is_completed', true);
        } elseif ($status === 'pending') {
            $query->where('is_completed', false);
        }

        return $query;
    }

    /**
     * The user that owns the task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
