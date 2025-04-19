@extends('layouts.app')

@section('title', 'Attendance Records')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Attendance Records</h2>
        <p class="text-muted">View your attendance status for all enrolled subjects.</p>
    </div>
    <div class="col-md-6">
        <form id="attendance-filter-form" action="{{ route('resources.attendance') }}" method="GET" class="d-flex justify-content-end">
            <div class="input-group" style="max-width: 400px;">
                <select name="year" class="form-select">
                    <option value="">Select Year</option>
                    @foreach($allAttendances as $attendance)
                        <option value="{{ $attendance->year }}" {{ request('year', $currentYear) == $attendance->year ? 'selected' : '' }}>
                            Year {{ $attendance->year }}
                        </option>
                    @endforeach
                </select>
                <select name="semester" class="form-select">
                    <option value="">Select Semester</option>
                    @foreach($allAttendances as $attendance)
                        <option value="{{ $attendance->semester }}" {{ request('semester', $currentSemester) == $attendance->semester ? 'selected' : '' }}>
                            Semester {{ $attendance->semester }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
</div>

@if($currentYear && $currentSemester)
    <div class="alert alert-info">
        <h5 class="alert-heading">Viewing attendance for Year {{ $currentYear }}, Semester {{ $currentSemester }}</h5>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Attendance Summary</h5>
            </div>
            <div class="card-body">
                @if(count($attendanceStats) > 0)
                    <div class="row">
                        @foreach($attendanceStats as $subjectId => $stats)
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">{{ $stats['subject']->code }} - {{ $stats['subject']->name }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="progress attendance-progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['percentage'] }}%" aria-valuenow="{{ $stats['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="attendance-stats">
                                            <div>Present: <span class="attendance-value text-success">{{ $stats['present'] }}</span></div>
                                            <div>Late: <span class="attendance-value text-warning">{{ $stats['late'] }}</span></div>
                                            <div>Absent: <span class="attendance-value text-danger">{{ $stats['absent'] }}</span></div>
                                            <div>Total: <span class="attendance-value">{{ $stats['total'] }}</span></div>
                                            <div>Percentage: <span class="attendance-value">{{ number_format($stats['percentage'], 1) }}%</span></div>
                                        </div>
                                        
                                        <h6 class="mt-4 mb-3">Detailed Records</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm attendance-table">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($stats['attendances']->sortByDesc('date') as $attendance)
                                                        <tr>
                                                            <td>{{ $attendance->date->format('M d, Y') }}</td>
                                                            <td>
                                                                @if($attendance->status == 'present')
                                                                    <span class="status-present"><i class="bi bi-check-circle-fill"></i> Present</span>
                                                                @elseif($attendance->status == 'late')
                                                                    <span class="status-late"><i class="bi bi-clock-fill"></i> Late</span>
                                                                @else
                                                                    <span class="status-absent"><i class="bi bi-x-circle-fill"></i> Absent</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $attendance->remarks ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted display-4"></i>
                        <p class="mt-3 mb-0 text-muted">No attendance records found for the selected semester.</p>
                        @if($allAttendances->count() > 0)
                            <p class="text-muted">Try selecting a different semester from the filter above.</p>
                        @else
                            <p class="text-muted">Attendance records will be updated by your instructors soon.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Attendance Policy</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Minimum Attendance Requirements</h6>
                        <ul>
                            <li>Students are required to maintain a minimum of 75% attendance in all courses.</li>
                            <li>Attendance below 75% may result in being barred from final examinations.</li>
                            <li>Three "late" markings will be counted as one "absent".</li>
                            <li>Medical absences require proper documentation submitted within one week.</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Attendance Status Classification</h6>
                        <ul>
                            <li><span class="status-present"><i class="bi bi-check-circle-fill"></i> Present</span>: Student attended the class on time.</li>
                            <li><span class="status-late"><i class="bi bi-clock-fill"></i> Late</span>: Student was more than 10 minutes late to class.</li>
                            <li><span class="status-absent"><i class="bi bi-x-circle-fill"></i> Absent</span>: Student did not attend class or was more than 30 minutes late.</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-3 mb-0">
                    <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Important Notice</h6>
                    <p class="mb-0">If you notice any discrepancies in your attendance records, please contact your course instructor or the academic office within 7 days of the class.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
