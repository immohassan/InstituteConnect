@extends('layouts.app')
@section('title', 'Terms of Service')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/footer-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
@endpush
@section('content')
    <div class="main-heading">Campus Connect Terms of Service</div>
    <div class="container main-section">
        <p class="terms">
            We agree to provide you with the Campus Connect Service. This includes all products, features, applications,
            services, technologies, and software we offer to fulfill our mission: <strong>To help students connect,
                collaborate, and thrive in their academic and social lives</strong>.
        </p>

        <h2 class="term-heading">1. Personalized tools to connect, communicate, and share</h2>
        <p class="sub-term">
            Campus Connect is designed to support diverse student experiences. Whether you're looking to chat with peers,
            share updates, comment on posts, or follow others, we provide features to help you express yourself, grow your
            presence, and build meaningful relationships. We also tailor your experience by highlighting content, events,
            resources, and people based on your activity on and off Campus Connect, helping you engage with what matters to
            you.
        </p>


        <h2 class="term-heading">2. Cutting-edge technologies to support your experience</h2>
        <p class="sub-term">
            We use advanced technologies, including artificial intelligence and machine learning, to personalize, protect,
            and enhance the Campus Connect experience. These tools help us scale and maintain the reliability and integrity
            of our services for a growing global community of students.
        </p>

        <h2 class="term-heading">3. Connecting you with relevant services and opportunities</h2>
        <p class="sub-term">
            We may use data from Campus Connect and its partners to show you opportunities such as academic resources,
            events, offers, or other sponsored content that aligns with your interests, helping you make the most of your
            campus experience.
        </p>

        <h2 class="term-heading">4. Research and continuous improvement</h2>
        <p class="sub-term">
            We use the information available to improve Campus Connect through research and partnerships. These efforts help
            us innovate responsibly and support the well-being and academic success of our student community.
        </p>
        <h2 class="term-heading">5. A positive, inclusive, and safe environment</h2>
        <p class="sub-term">
            We are committed to creating a welcoming and respectful community. Our tools and teams work to promote positive
            interactions, provide support when needed, and enforce our rules to prevent abuse, harmful content, or
            violations of our Terms. We may share relevant information with Campus Connect affiliates or legal authorities
            where appropriate to ensure safety. Learn more in our <a href="{{ route('privacy') }}"
                class="text-white-600 underline">Privacy Policy</a>.
        </p>
    </div>
@endsection
