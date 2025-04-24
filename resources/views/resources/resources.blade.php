@extends('layouts.app')
@section('title', 'Resources')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/resources.css') }}">
@endpush
@section('content')
<div class="main-heading">Resources</div>
<div class="container main-section" id="post-container">
    @foreach(range(1, 8) as $i)
    @if($i % 2 == 1) <div class="row g-4"> @endif

        <!-- Semester Card -->
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

            <!-- Collapsible content under each card -->
            <div class="collapse mt-4" id="semester-{{ $i }}">
                <div class="row g-3">
                    @for($j = 1; $j <= 6; $j++)
                    <div class="col-6">
                        <div class="folder-card p-3">
                            <i class="bi bi-journal-text text-info"></i>
                            <h6 class="mt-2 text-white">Subject {{ $j }}</h6>
                        </div>
                    </div>
                    @endfor
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