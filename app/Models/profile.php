<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profile extends Model
{
    use HasFactory;

    protected $fillable = ['firstname', 'lastname','date_of_birth', 'gender', 'address', 'phonenumber', 'education', 'skills','user_id'];
     protected $casts = [
        'date_of_birth' => 'date',
    ];
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }


}
