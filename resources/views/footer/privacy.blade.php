@extends('layouts.app')
@section('title', 'Privacy Policy')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/footer-pages.css') }}">
@endpush
@section('content')
    <div class="main-heading">Campus Connect Privacy Policy</div>
    <div class="container main-section">
        <p class="terms">
            This Privacy Policy describes how Campus Connect collects, uses, and protects the
            personal information of users when you use our platform.
        </p>

        <h2 class="term-heading">1. Information We Collect</h2>
        <p class="sub-term">
            We collect personal information that you provide when registering or using our services, including:
        </p>
        <ul class="sub-term">
            <li>Name, email, and institution details</li>
            <li>Messages, posts, comments, and shared content</li>
            <li>Usage data such as logins, activity, and preferences</li>
        </ul>


        <h2 class="term-heading">2. How We Use Your Information</h2>
        <p class="sub-term">
            <p class="sub-term">
                We use your information to:
            </p>
            <ul class="sub-term">
                <li>Provide core features like chatting, posting, and academic resources</li>
                <li>Improve user experience and personalize content</li>
                <li>Send relevant updates and notifications</li>
                <li>Ensure platform security and prevent misuse</li>
            </ul>

        <h2 class="term-heading">3. Sharing of Information</h2>
        <p class="sub-term">
            We do not sell your personal data. We may share limited information with:
        </p>
        <ul class="sub-term">
            <li>Trusted third-party service providers (e.g., hosting or analytics)</li>
            <li>Law enforcement or authorities, when required by law</li>
        </ul>

        <h2 class="term-heading">4. Data Security</h2>
        <p class="sub-term">
            We take appropriate technical and organizational measures to protect your information from unauthorized access, loss, or misuse.
        </p>
    
        <h2 class="term-heading">5. Your Rights</h2>
        <p class="sub-term">
            You have the right to access, correct, or delete your personal information. You may also contact us for any data-related concerns at <a href="{{ route('contact') }}" class="text-blue-600 underline">Contact Us</a>.
        </p>
    
        <h2 class="term-heading">6. Updates to This Policy</h2>
        <p class="sub-term">
            We may update this policy from time to time. You will be notified of any major changes via the platform or email.
        </p>
    </div>
@endsection
