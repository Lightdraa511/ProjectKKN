<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Get the users belonging to this faculty.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the quotas for this faculty across all locations.
     */
    public function quotas()
    {
        return $this->hasMany(FacultyQuota::class);
    }
}
