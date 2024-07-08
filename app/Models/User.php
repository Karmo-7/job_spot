<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Middleware\job;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_confirmed',
        'role',
        'license',
    ];
    protected $table="users";
    protected $guarded=['id'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function profile(){
        return $this->hasOne('App\Models\Profile');
    }

    public function work(){
        return $this->hasMany(job::class);

    }


    public function job(){
        return $this ->belongsToMany(job::class,'user_jobs','id_job','id_user')->withTimestamps();
    }


    public function joob(){
        return $this->belongsToMany(job::class,'reviews','job_id','user_id')->withTimestamps();
    }

    public function reportjob(){
        return $this ->belongsToMany(job::class,'reports','job_id','user_id')->withTimestamps();
    }
    public function favjob(){
        return $this ->belongsToMany(job::class,'favorites','job_id','user_id')->withTimestamps();
    }

    public function chats():HasMany
    {
        return $this->hasMany(Chat::class,'created_by');
    }

}
