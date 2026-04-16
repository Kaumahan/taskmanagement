<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'priority', 'project_id'];
    
    //
    public function project()
    {
        // A task belongs to one project
        return $this->belongsTo(Project::class);
    }
}
