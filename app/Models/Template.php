<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use     HasFactory;

    protected $fillable = ['model', 'can_create', 'can_update', 'can_delete', 'can_approve','can_show'];



    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_templates')
            ->withTimestamps();
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_templates')
            ->withPivot(['can_create', 'can_update', 'can_delete', 'can_approve']);
    }
}
