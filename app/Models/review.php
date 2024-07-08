<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class review extends Model
{
    use HasFactory;
    protected $fillable=['job_id','user_id','rate','comment'];

    public function usser(){
        return $this->belongsTo(User::class)->withTimestamps();
    }
    public function joob(){
        return $this->belongsTo(job::class)->withTimestamps();
    }

}
