<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {        

        // return $activities;

        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
            // $this->getActivity($user)
        ]);
    }

    // protected function getActivity(User $user)
    // {
    //     return $user->activity()->latest()->with('subject')
    //     ->take(50)->get()->groupBy(
    //         function ($activity) {
    //             return $activity->created_at->format('Y-m-d');
    //         }
    //     );
    // }
}

