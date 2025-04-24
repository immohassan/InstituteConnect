<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function index(){
        
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        return view('events.events', [
            'user' => $user
        ]);
    }
}
