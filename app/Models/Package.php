<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function templates()
    {
        return $this->belongsToMany(Template::class, 'package_templates');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_packages');
    }
}
