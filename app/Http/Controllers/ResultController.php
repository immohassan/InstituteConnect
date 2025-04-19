<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Attendance;
use App\Models\Subject;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    /**
     * Display student's results.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user has permission to view results
        if (!$user->can('view_results')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view results.');
        }
        
        // Get current semester/year
        $currentSemester = $user->semester ?? request('semester');
        $currentYear = $user->year ?? request('year');
        
        // Get results for current semester/year
        $results = Result::with('subject')
            ->forUser($user->id)
            ->when($currentSemester && $currentYear, function($query) use ($currentSemester, $currentYear) {
                return $query->forSemester($currentSemester, $currentYear);
            })
            ->latest()
            ->get();
            
        // Calculate GPA
        $totalPoints = 0;
        $totalCredits = 0;
        
        foreach ($results as $result) {
            $creditHours = $result->subject->credit_hours;
            $totalPoints += $result->gpa * $creditHours;
            $totalCredits += $creditHours;
        }
        
        $gpa = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;
        
        // Get all years/semesters for dropdown
        $allResults = Result::where('user_id', $user->id)
            ->select('year', 'semester')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();
            
        return view('resources.results', compact('results', 'gpa', 'currentSemester', 'currentYear', 'allResults'));
    }

    /**
     * Display student's attendance.
     */
    public function attendance()
    {
        $user = Auth::user();
        
        // Check if user has permission to view attendance
        if (!$user->can('view_attendance')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to view attendance.');
        }
        
        // Get current semester/year
        $currentSemester = $user->semester ?? request('semester');
        $currentYear = $user->year ?? request('year');
        
        // Get unique subjects for the semester
        $subjects = Subject::whereHas('attendances', function($query) use ($user, $currentSemester, $currentYear) {
                $query->where('user_id', $user->id)
                    ->where('semester', $currentSemester)
                    ->where('year', $currentYear);
            })
            ->get();
            
        // Get attendance statistics for each subject
        $attendanceStats = [];
        
        foreach ($subjects as $subject) {
            $attendances = Attendance::where('user_id', $user->id)
                ->where('subject_id', $subject->id)
                ->where('semester', $currentSemester)
                ->where('year', $currentYear)
                ->get();
                
            $total = $attendances->count();
            $present = $attendances->where('status', 'present')->count();
            $absent = $attendances->where('status', 'absent')->count();
            $late = $attendances->where('status', 'late')->count();
            
            $presentPercent = $total > 0 ? round(($present / $total) * 100, 2) : 0;
            
            $attendanceStats[$subject->id] = [
                'subject' => $subject,
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'percentage' => $presentPercent,
                'attendances' => $attendances,
            ];
        }
        
        // Get all years/semesters for dropdown
        $allAttendances = Attendance::where('user_id', $user->id)
            ->select('year', 'semester')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();
            
        return view('resources.attendance', compact('attendanceStats', 'currentSemester', 'currentYear', 'allAttendances'));
    }

    /**
     * Display admin results management page.
     */
    public function adminIndex(Request $request)
    {
        // Get filter parameters
        $subjectId = $request->subject_id;
        $year = $request->year;
        $semester = $request->semester;
        
        // Build query
        $resultsQuery = Result::with(['user', 'subject'])
            ->when($subjectId, function($query) use ($subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->when($year, function($query) use ($year) {
                return $query->where('year', $year);
            })
            ->when($semester, function($query) use ($semester) {
                return $query->where('semester', $semester);
            })
            ->latest();
            
        $results = $resultsQuery->paginate(15);
        
        // Get subjects, years, semesters for filter
        $subjects = Subject::orderBy('code')->get();
        $years = Result::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $semesters = Result::select('semester')->distinct()->orderBy('semester')->pluck('semester');
        
        return view('admin.results.index', compact('results', 'subjects', 'years', 'semesters', 'subjectId', 'year', 'semester'));
    }

    /**
     * Show the form for creating a new result.
     */
    public function create()
    {
        $subjects = Subject::orderBy('code')->get();
        $users = User::whereHas('roles', function($query) {
                $query->where('name', 'user');
            })
            ->orderBy('name')
            ->get();
            
        return view('admin.results.create', compact('subjects', 'users'));
    }

    /**
     * Store a newly created result in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|numeric|min:0|max:100',
            'grade' => 'required|string|max:5',
            'semester' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'comments' => 'nullable|string|max:500',
        ]);
        
        // Check if result already exists for this student and subject
        $existingResult = Result::where('user_id', $request->user_id)
            ->where('subject_id', $request->subject_id)
            ->where('semester', $request->semester)
            ->where('year', $request->year)
            ->first();
            
        if ($existingResult) {
            return redirect()->route('admin.results.create')
                ->with('error', 'A result for this student, subject, semester and year already exists.');
        }
        
        // Create result
        $result = Result::create([
            'user_id' => $request->user_id,
            'subject_id' => $request->subject_id,
            'marks' => $request->marks,
            'grade' => $request->grade,
            'semester' => $request->semester,
            'year' => $request->year,
            'comments' => $request->comments,
        ]);
        
        // Create notification for student
        $subject = Subject::find($request->subject_id);
        $user = User::find($request->user_id);
        
        Notification::create([
            'user_id' => $request->user_id,
            'from_user_id' => Auth::id(),
            'type' => 'result',
            'content' => "Your result for {$subject->name} has been uploaded",
            'link' => route('resources.results', ['semester' => $request->semester, 'year' => $request->year]),
        ]);
        
        return redirect()->route('admin.results.index')
            ->with('success', 'Result created successfully!');
    }

    /**
     * Show the form for editing the specified result.
     */
    public function edit(Result $result)
    {
        $subjects = Subject::orderBy('code')->get();
        $users = User::whereHas('roles', function($query) {
                $query->where('name', 'user');
            })
            ->orderBy('name')
            ->get();
            
        return view('admin.results.edit', compact('result', 'subjects', 'users'));
    }

    /**
     * Update the specified result in storage.
     */
    public function update(Request $request, Result $result)
    {
        // Validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|numeric|min:0|max:100',
            'grade' => 'required|string|max:5',
            'semester' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'comments' => 'nullable|string|max:500',
        ]);
        
        // Check if result already exists for this student and subject (except this one)
        $existingResult = Result::where('user_id', $request->user_id)
            ->where('subject_id', $request->subject_id)
            ->where('semester', $request->semester)
            ->where('year', $request->year)
            ->where('id', '!=', $result->id)
            ->first();
            
        if ($existingResult) {
            return redirect()->route('admin.results.edit', $result->id)
                ->with('error', 'A result for this student, subject, semester and year already exists.');
        }
        
        // Update result
        $result->user_id = $request->user_id;
        $result->subject_id = $request->subject_id;
        $result->marks = $request->marks;
        $result->grade = $request->grade;
        $result->semester = $request->semester;
        $result->year = $request->year;
        $result->comments = $request->comments;
        $result->save();
        
        // Create notification for student if the user_id is the same
        if ($result->user_id == $request->user_id) {
            $subject = Subject::find($request->subject_id);
            
            Notification::create([
                'user_id' => $request->user_id,
                'from_user_id' => Auth::id(),
                'type' => 'result_updated',
                'content' => "Your result for {$subject->name} has been updated",
                'link' => route('resources.results', ['semester' => $request->semester, 'year' => $request->year]),
            ]);
        }
        
        return redirect()->route('admin.results.index')
            ->with('success', 'Result updated successfully!');
    }

    /**
     * Remove the specified result from storage.
     */
    public function destroy(Result $result)
    {
        // Store user ID for notification
        $userId = $result->user_id;
        $subjectName = $result->subject->name;
        $semester = $result->semester;
        $year = $result->year;
        
        // Delete the result
        $result->delete();
        
        // Create notification for student
        Notification::create([
            'user_id' => $userId,
            'from_user_id' => Auth::id(),
            'type' => 'result_deleted',
            'content' => "Your result for {$subjectName} has been removed",
            'link' => route('resources.results', ['semester' => $semester, 'year' => $year]),
        ]);
        
        return redirect()->route('admin.results.index')
            ->with('success', 'Result deleted successfully!');
    }

    /**
     * Display admin attendance management page.
     */
    public function adminAttendance(Request $request)
    {
        // Get filter parameters
        $subjectId = $request->subject_id;
        $userId = $request->user_id;
        $date = $request->date;
        
        // Build query
        $attendancesQuery = Attendance::with(['user', 'subject'])
            ->when($subjectId, function($query) use ($subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($date, function($query) use ($date) {
                return $query->whereDate('date', $date);
            })
            ->latest();
            
        $attendances = $attendancesQuery->paginate(15);
        
        // Get subjects, users for filter
        $subjects = Subject::orderBy('code')->get();
        $users = User::whereHas('roles', function($query) {
                $query->where('name', 'user');
            })
            ->orderBy('name')
            ->get();
        
        return view('admin.attendance.index', compact('attendances', 'subjects', 'users', 'subjectId', 'userId', 'date'));
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function createAttendance()
    {
        $subjects = Subject::orderBy('code')->get();
        $users = User::whereHas('roles', function($query) {
                $query->where('name', 'user');
            })
            ->orderBy('name')
            ->get();
            
        return view('admin.attendance.create', compact('subjects', 'users'));
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function storeAttendance(Request $request)
    {
        // Validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late',
            'semester' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'remarks' => 'nullable|string|max:500',
        ]);
        
        // Check if attendance record already exists for this student, subject and date
        $existingAttendance = Attendance::where('user_id', $request->user_id)
            ->where('subject_id', $request->subject_id)
            ->whereDate('date', $request->date)
            ->first();
            
        if ($existingAttendance) {
            return redirect()->route('admin.attendance.create')
                ->with('error', 'An attendance record for this student, subject and date already exists.');
        }
        
        // Create attendance record
        $attendance = Attendance::create([
            'user_id' => $request->user_id,
            'subject_id' => $request->subject_id,
            'date' => $request->date,
            'status' => $request->status,
            'semester' => $request->semester,
            'year' => $request->year,
            'remarks' => $request->remarks,
        ]);
        
        // Create notification for student if absent or late
        if ($request->status != 'present') {
            $subject = Subject::find($request->subject_id);
            
            Notification::create([
                'user_id' => $request->user_id,
                'from_user_id' => Auth::id(),
                'type' => 'attendance',
                'content' => "You were marked {$request->status} for {$subject->name} on " . date('d M, Y', strtotime($request->date)),
                'link' => route('resources.attendance', ['semester' => $request->semester, 'year' => $request->year]),
            ]);
        }
        
        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance record created successfully!');
    }

    /**
     * Show the form for editing an attendance record.
     */
    public function editAttendance(Attendance $attendance)
    {
        $subjects = Subject::orderBy('code')->get();
        $users = User::whereHas('roles', function($query) {
                $query->where('name', 'user');
            })
            ->orderBy('name')
            ->get();
            
        return view('admin.attendance.edit', compact('attendance', 'subjects', 'users'));
    }

    /**
     * Update the specified attendance record in storage.
     */
    public function updateAttendance(Request $request, Attendance $attendance)
    {
        // Validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late',
            'semester' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'remarks' => 'nullable|string|max:500',
        ]);
        
        // Check if attendance record already exists for this student, subject and date (except this one)
        $existingAttendance = Attendance::where('user_id', $request->user_id)
            ->where('subject_id', $request->subject_id)
            ->whereDate('date', $request->date)
            ->where('id', '!=', $attendance->id)
            ->first();
            
        if ($existingAttendance) {
            return redirect()->route('admin.attendance.edit', $attendance->id)
                ->with('error', 'An attendance record for this student, subject and date already exists.');
        }
        
        // Track if status changed
        $oldStatus = $attendance->status;
        
        // Update attendance record
        $attendance->user_id = $request->user_id;
        $attendance->subject_id = $request->subject_id;
        $attendance->date = $request->date;
        $attendance->status = $request->status;
        $attendance->semester = $request->semester;
        $attendance->year = $request->year;
        $attendance->remarks = $request->remarks;
        $attendance->save();
        
        // Create notification for student if status changed to absent or late
        if ($oldStatus != $request->status && $request->status != 'present') {
            $subject = Subject::find($request->subject_id);
            
            Notification::create([
                'user_id' => $request->user_id,
                'from_user_id' => Auth::id(),
                'type' => 'attendance_updated',
                'content' => "Your attendance for {$subject->name} on " . date('d M, Y', strtotime($request->date)) . " has been updated to {$request->status}",
                'link' => route('resources.attendance', ['semester' => $request->semester, 'year' => $request->year]),
            ]);
        }
        
        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance record updated successfully!');
    }
}
