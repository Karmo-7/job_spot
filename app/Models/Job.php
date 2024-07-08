<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
     protected $fillable = ['user_id','title', 'description','PostedAt', 'category', 'empolymentType', 'years_experience', 'required_age', 'gender', 'location'];

     public function use()
    {
        return $this->belongsTo(User::class);
    }


     public function user(){
        return $this->belongsToMany(User::class,'user_jobs','id_user','id_job')->withTimestamps();
     }


     public function usser(){
        return $this->belongsToMany(User::class,'reviews','user_id','job_id')->withTimestamps();
     }

     public function reportuser(){
        return $this->belongsToMany(User::class,'reports','user_id','job_id')->withTimestamps();
     }

     public function favuser(){
        return $this ->belongsToMany(User::class,'favorites','user_id','job_id')->withTimestamps();
     }

     public function job(){
        return $this->hasMany(Job::class);
     }
}
