<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'tasks', 'start_date', 'end_date', 'leader_id', 'completed_tasks'
    ];
    
    protected $casts = [
        'tasks' => 'array',
        'completed_tasks' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Relacija: Project ima jednog lidera (korisnika).
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Relacija: Project ima mnoge članove (kroz pivot tablicu).
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id');
    }

    /**
     * Prikazivanje zadatka u JSON formatu.
     */
    public function getTasksAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Prikazivanje zadatka s kvadratićem (checkboxom).
     */
    public function setTasksAttribute($value)
    {
        $this->attributes['tasks'] = json_encode($value);
    }
}
