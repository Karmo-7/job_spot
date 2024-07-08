<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Job;
use App\Models\User_job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getDashboardData(Request $request)
    {


        // Number of total users
        $totalUsers = User::count();

        // Number of companies
        $companiesCount = User::where('role', 'company')->count();

        // Number of job seekers
        $jobSeekersCount = User::where('role', 'jobseekers')->count();

        // Number of jobs
        $totalJobs = Job::count();

        // Number of applications
        $totalApplications = User_job::count();

        // Most active companies



        return response()->json([
            'status' => 'success',
            'data' => [
                'total_users' => $totalUsers,
                'companies_count' => $companiesCount,
                'job_seekers_count' => $jobSeekersCount,
                'total_jobs' => $totalJobs,
                'total_applications' => $totalApplications,

            ]
        ], 200);
    }
}
