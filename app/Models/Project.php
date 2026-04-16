<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name'];
    //
    public function tasks()
    {
        // A project has many tasks
        return $this->hasMany(Task::class)->orderBy('priority');
    }
}
