<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrowing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'room_id', 'start_date', 'end_date', 'status', 'notes'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'borrowing_equipment')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    public function statusForCurrentTime(): string
    {
        if (! $this->start_date || ! $this->end_date) {
            return $this->status;
        }

        $now = now();

        if ($now->greaterThanOrEqualTo($this->end_date)) {
            return 'completed';
        }

        if ($now->greaterThanOrEqualTo($this->start_date)) {
            return 'active';
        }

        return 'approved';
    }

    public function syncStatusWithTime(): self
    {
        $newStatus = $this->statusForCurrentTime();

        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
        }

        return $this;
    }
}