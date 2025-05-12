<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\Resource;
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

    public function index(){
        $user = Auth::user();
                
        if (!$user) {
            return redirect()->route('login');
        }
        $resources = \App\Models\Resource::all()->groupBy('semester_id');
        return view('resources.resources', [
            'user' => $user,
            'resources' => $resources
        ]);
    }

    public function show($id, $subject_name)
{
    $resources = DB::table('resources')
        ->where('semester_id', $id)
        ->where('subject_name', $subject_name)
        ->get();

    return view('resources.table', compact('resources', 'id', 'subject_name'));
}


    public function delete(Request $request){
        $resources = Resource::findOrFail($request->id);
        $resources->delete();
        return back()->with('success', 'Resource deleted successfully.');
    }

    public function add(Request $request){ 
        $resource = new Resource();
        $resource->semester_id = $request->semester_id;
        $resource->subject_name = $request->subject_name;
        if($request->file_name){
            $file = $request->file('file_name');
            $fileName = time() . '_' . $file->getClientOriginalName(); // optional: Str::random(10) for unique names
            $file->move(public_path('docs'), $fileName); // Moves to public/images/
            $resource->file_name = $fileName; // Save just the name or 'images/'.$fileName if needed
        }
        $resource->save();
        return back()->with('success', 'Resource added successfully');
    }
}