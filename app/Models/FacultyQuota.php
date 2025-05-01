<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacultyQuota extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'location_id',
        'faculty_id',
        'quota',
    ];

    /**
     * Get the location that this quota belongs to.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the faculty that this quota belongs to.
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the number of users assigned from this quota.
     *
     * @return int
     */
    public function getAssignedCount()
    {
        return User::where('location_id', $this->location_id)
            ->where('faculty_id', $this->faculty_id)
            ->count();
    }

    /**
     * Check if the quota is full.
     *
     * @return bool
     */
    public function isFull()
    {
        return $this->getAssignedCount() >= $this->quota;
    }
}
