<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'project_image_stage',
        'project_image_path'
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
