<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Training extends Model
{
    use HasFactory;

    // Types de séances
    const TYPE_TRAINING = 'training';
    const TYPE_MATCH = 'match';
    const TYPE_EVENT = 'event';
    const TYPE_MEETING = 'meeting';

    const TYPES = [
        self::TYPE_TRAINING => 'Entraînement',
        self::TYPE_MATCH => 'Match',
        self::TYPE_EVENT => 'Événement',
        self::TYPE_MEETING => 'Réunion',
    ];

    // Statuts
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'club_id',
        'coach_id',
        'title',
        'type',
        'description',
        'date',
        'start_time',
        'end_time',
        'location',
        'address',
        'status',
        'recurrence',
        'max_participants',
        'color',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'max_participants' => 'integer',
    ];

    // Relations
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'training_user')
                    ->withPivot('status', 'arrived_at', 'left_at', 'duration_minutes', 'notes')
                    ->withTimestamps();
    }

    public function presentParticipants()
    {
        return $this->participants()->wherePivot('status', 'present');
    }

    public function absentParticipants()
    {
        return $this->participants()->wherePivotIn('status', ['absent', 'excused']);
    }

    // Accessors
    public function getStartDatetimeAttribute()
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->start_time);
    }

    public function getEndDatetimeAttribute()
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->end_time);
    }

    public function getDurationAttribute()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        return $start->diffInMinutes($end);
    }

    public function getDurationFormattedAttribute()
    {
        $minutes = $this->duration;
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0 && $mins > 0) {
            return "{$hours}h{$mins}";
        } elseif ($hours > 0) {
            return "{$hours}h";
        }
        return "{$mins}min";
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            self::TYPE_TRAINING => 'M13 10V3L4 14h7v7l9-11h-7z',
            self::TYPE_MATCH => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            self::TYPE_EVENT => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
            self::TYPE_MEETING => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
            default => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            self::TYPE_TRAINING => 'Entraînement',
            self::TYPE_MATCH => 'Match',
            self::TYPE_EVENT => 'Événement',
            self::TYPE_MEETING => 'Réunion',
            default => 'Séance',
        };
    }

    public function getTypeColorAttribute()
    {
        return self::getTypeColor($this->type);
    }

    /**
     * Obtenir la couleur pour un type donné (méthode statique)
     */
    public static function getTypeColor(string $type): string
    {
        return match($type) {
            self::TYPE_TRAINING => '#10B981', // Emerald
            self::TYPE_MATCH => '#3B82F6',    // Blue
            self::TYPE_EVENT => '#8B5CF6',    // Violet
            self::TYPE_MEETING => '#F59E0B',  // Amber
            default => '#6B7280',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_SCHEDULED => 'Programmé',
            self::STATUS_ONGOING => 'En cours',
            self::STATUS_COMPLETED => 'Terminé',
            self::STATUS_CANCELLED => 'Annulé',
            default => 'Inconnu',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_SCHEDULED => 'bg-blue-100 text-blue-700',
            self::STATUS_ONGOING => 'bg-emerald-100 text-emerald-700',
            self::STATUS_COMPLETED => 'bg-slate-100 text-slate-600',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString())
                     ->where('status', '!=', self::STATUS_CANCELLED)
                     ->orderBy('date')
                     ->orderBy('start_time');
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', now()->toDateString())
                     ->orWhere(function ($q) {
                         $q->where('date', now()->toDateString())
                           ->where('end_time', '<', now()->format('H:i'));
                     })
                     ->orderBy('date', 'desc')
                     ->orderBy('start_time', 'desc');
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                     ->whereYear('date', now()->year);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helpers
    public function isUpcoming()
    {
        return $this->date >= now()->toDateString() && $this->status !== self::STATUS_CANCELLED;
    }

    public function isPast()
    {
        return $this->date < now()->toDateString() || 
               ($this->date == now()->toDateString() && $this->end_time < now()->format('H:i'));
    }

    public function isToday()
    {
        return $this->date->isToday();
    }

    public function isFull()
    {
        return $this->max_participants && $this->participants()->count() >= $this->max_participants;
    }

    public function hasAvailableSpots()
    {
        if (!$this->max_participants) return true;
        return $this->participants()->count() < $this->max_participants;
    }

    public function getAvailableSpotsAttribute()
    {
        if (!$this->max_participants) return null;
        return max(0, $this->max_participants - $this->participants()->count());
    }

    public function getAttendanceRateAttribute()
    {
        $total = $this->participants()->count();
        if ($total === 0) return 0;
        
        $present = $this->presentParticipants()->count();
        return round(($present / $total) * 100);
    }
}
