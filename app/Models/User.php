<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rule',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];



    public  function isAdmin()
    {
        return $this->rule==='admin';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }


    public function packages()
    {
        return $this->belongsToMany(Package::class, 'user_packages');
    }

    public function templates()
    {
        return $this->belongsToMany(Template::class, 'user_templates');
    }

    public function findTemplate($modelValue)
    {
        // البحث في القوالب المباشرة أولاً
        $template = $this->templates->firstWhere('model', $modelValue);

        if ($template) {
            return $template;
        }

        // إذا لم يوجد في القوالب المباشرة، ابحث في قوالب الباقة
        $firstPackage = $this->packages->first();
        if ($firstPackage) {
            return $firstPackage->templates->firstWhere('model', $modelValue);
        }

        return null; // لم يتم العثور على القالب
    }

    public function permissions($modelValue)
    {
        return $this->findTemplate($modelValue)?? null;
    }



    public function findSection($modelValue)
    {
        $foundInDirect = $this->templates->contains(function ($template) use ($modelValue) {
            return str_starts_with($template->model, $modelValue);
        });

        if ($foundInDirect) {
            return true;
        }

        // البحث في قوالب الباقة
        $firstPackage = $this->packages->first();
        if ($firstPackage) {
            return $firstPackage->templates->contains(function ($template) use ($modelValue) {
                return str_starts_with($template->model, $modelValue);
            });
        }

        return false;
    }

    public function hasTemplateLike($modelValue)
    {
        return $this->findTemplatesLike($modelValue)->isNotEmpty();

    }
    public function sectionsPermissions($modelValue)
    {
        return $this->findSection($modelValue)?? null;
    }

//    public function permissions($model)
//    {
//        if ($this->templates->contains('model',$model)) {
//            return true;
//        }
//
//        $firstPackage = $this->packages->first();
//
//        return $firstPackage && $firstPackage->templates->contains('model',$model);
//    }
}
