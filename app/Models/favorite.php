<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class favorite extends Model
{
    use HasFactory;
    protected $fillable=['user_id','job_id'];
    public function favuser():BelongsTo{
        return $this->belongsTo(User::class,'user_id')->withTimestamp();

    }
    public function favJob():BelongsTo
    {
        return $this->belongsTo(Job::class,'job_id');
    }
}
