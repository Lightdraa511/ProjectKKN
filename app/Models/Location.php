<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'total_quota',
    ];

    /**
     * The users assigned to this location.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * The faculty quotas for this location.
     */
    public function facultyQuotas()
    {
        return $this->hasMany(FacultyQuota::class);
    }

    /**
     * Check if the location has available quota for a specific faculty.
     *
     * @param int $facultyId
     * @return bool
     */
    public function hasAvailableQuota($facultyId)
    {
        $quota = $this->facultyQuotas()->where('faculty_id', $facultyId)->first();

        if (!$quota) {
            return false;
        }

        $currentCount = $this->users()->where('faculty_id', $facultyId)->count();

        return $currentCount < $quota->quota;
    }

    /**
     * Get the total number of assigned users.
     *
     * @return int
     */
    public function getAssignedCount()
    {
        return $this->users()->count();
    }
}
