<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
// use App\Models\user;

class user_job extends Model
{
    use HasFactory;
    protected $fillable=['id_user','id_job','status','cv','resume'];
    public function use(){
        return $this->belongsTo(User::class,'id_user');
    }
     public function job(){
        return $this->belongsTo(Job::class,'id_job');
    }

     public function disk(){
        return Storage::disk('public');
     }

}
