<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(){
        $user = Auth::user();

        if(!$user){
            return redirect()->route('login');
        }
        $users = User::all();
        return view('search.search',[
            'users' => $users
        ]);
    }
}
