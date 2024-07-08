<?php

namespace App\Models;

// use App\Http\Middleware\job;
use App\Models\User;
use App\Models\job;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class report extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','job_id','is_report','reason' ];
    public function reportuser():BelongsTo{
        return $this-> BelongsTo(User::class,'user_id');

    }
    public function reportjob():BelongsTo{
        return $this-> BelongsTo(Job::class,'job_id');
    }
}
