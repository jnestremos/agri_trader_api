<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_status'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_status_history', 'project_status_id', 'project_id');
    }
}
