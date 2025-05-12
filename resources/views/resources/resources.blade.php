@extends('layouts.app')
@section('title', 'Resources | Campus Connect')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/resources.css') }}">
<link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
@endpush
@section('content')
<div class="main-heading">Resources</div>
<div class="container main-section" id="post-container">
@foreach(range(1, 8) as $i)
    @if($i % 2 == 1) <div class="row g-4 resource-tab"> @endif

    <div class="col-md-6 mb-4">
        <div class="folder-card d-flex justify-content-between align-items-start p-3"
            data-bs-toggle="collapse"
            data-bs-target="#semester-{{ $i }}"
            style="cursor: pointer;">
            <div>
                <i class="bi bi-folder-fill text-secondary"></i>
                <h6 class="mt-2 mb-1 text-white">
                    {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Semester
                </h6>
            </div>
            <i class="bi bi-three-dots-vertical text-white"></i>
        </div>

        <div class="collapse mt-4" id="semester-{{ $i }}">
            <div class="row g-3">
                @if(isset($resources[$i]))
                    @foreach($resources[$i]->unique('subject_name') as $subject)
                        <div class="col-6">
                            <a href="{{ route('resources.show', ['semester_id' => $i, 'subject_name' => $subject->subject_name]) }}" class="text-decoration-none">
                                <div class="folder-card p-3">
                                    <i class="bi bi-journal-text text-info"></i>
                                    <h6 class="mt-2 text-white">{{ str_replace('_', ' ', $subject->subject_name) }}</h6>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-white px-3">No subjects found.</div>
                @endif
            </div>
        </div>
    </div>

    @if($i % 2 == 0) </div> @endif
@endforeach


</div>

        </div>
    </div>
</div>
@push('scripts')

@endpush
@endsection