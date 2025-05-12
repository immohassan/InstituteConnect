@extends('layouts.app')
@section('title', 'Privacy Policy')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/footer-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
@endpush
@section('content')
    <div class="main-heading">Contact Us</div>
    <div class="container main-section">
        <p class="terms">
            If you have any questions, feedback, or concerns, feel free to get in touch with us. We're here to help!
        </p>


        <h2 class="term-heading">Support Email</h2>
        <p><a href="mailto:immohassan06@gmail.com" class="sub-term">support@campusconnect.com</a></p>

        <h2 class="term-heading">Office Address</h2>
        <p class="sub-term">Campus Connect Team <br>Tech Bugs<br>IBIT, University of the Punjab<br>Lahore, Pakistan</p>


    </div>
@endsection
