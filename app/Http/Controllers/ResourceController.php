<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Result;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the student's results.
     *
     * @return \Illuminate\Http\Response
     */
    public function results()
    {
        $user = Auth::user();
        $subjects = Subject::all();
        $results = $user->results;

        return view('resources.results', compact('subjects', 'results'));
    }

    /**
     * Display the student's attendance.
     *
     * @return \Illuminate\Http\Response
     */
    public function attendance()
    {
        $user = Auth::user();
        $subjects = Subject::all();
        $attendances = Attendance::query()->where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('subject_id');

        return view('resources.attendance', compact('subjects', 'attendances'));
    }

    public function index(){
        $user = Auth::user();
                
        if (!$user) {
            return redirect()->route('login');
        }
        return view('resources.resources', [
            'user' => $user
        ]);
    }
}