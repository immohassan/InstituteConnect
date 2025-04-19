<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Educational Social Platform')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            color: #212529;
            background: linear-gradient(135deg, #0a66c2 0%, #0077b5 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: transparent;
            padding: 1rem 0;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #ffffff;
        }
        .navbar-nav .nav-link:hover {
            color: rgba(255, 255, 255, 0.8);
        }
        .hero {
            padding: 6rem 0;
            color: white;
        }
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            max-width: 600px;
        }
        .features {
            background-color: white;
            padding: 5rem 0;
            border-radius: 30px 30px 0 0;
            margin-top: auto;
        }
        .feature-card {
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background-color: #e7f1fd;
            color: #0a66c2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        .feature-card h3 {
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .btn-primary {
            background-color: #ffffff;
            color: #0a66c2;
            border-color: #ffffff;
            padding: 0.75rem 2rem;
            font-weight: 500;
            border-radius: 50px;
        }
        .btn-primary:hover {
            background-color: #f3f3f3;
            color: #004182;
            border-color: #f3f3f3;
        }
        .btn-outline-light {
            border-color: #ffffff;
            color: #ffffff;
            padding: 0.75rem 2rem;
            font-weight: 500;
            border-radius: 50px;
        }
        .btn-outline-light:hover {
            background-color: #ffffff;
            color: #0a66c2;
        }
        footer {
            background-color: #ffffff;
            padding: 1.5rem 0;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md">
            <div class="container">
                <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                    <?php echo e(config('app.name', 'Educational Social Platform')); ?>

                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        <?php if(auth()->guard()->guest()): ?>
                            <?php if(Route::has('login')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if(Route::has('register')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <?php echo e(Auth::user()->name); ?>

                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">
                                        <?php echo e(__('Profile')); ?>

                                    </a>
                                    
                                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <?php echo e(__('Logout')); ?>

                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Connect, Collaborate, and Learn Together</h1>
                    <p>Join our educational social platform designed to bring students, faculty, and societies together in one collaborative space.</p>
                    <div class="d-flex gap-3">
                        <?php if(auth()->guard()->guest()): ?>
                            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-lg">Get Started</a>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light btn-lg">Sign In</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary btn-lg">Go to Dashboard</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="<?php echo e(asset('images/hero-image.svg')); ?>" alt="Educational Social Platform" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">Everything you need to succeed</h2>
                    <p class="text-muted">Our platform provides all the tools you need to connect with peers, communicate with faculty, and access your academic resources.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3>Connect with Societies</h3>
                        <p>Join and interact with societies relevant to your interests. Get notifications about events, announcements, and opportunities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                        <h3>Instant Messaging</h3>
                        <p>Chat with classmates, faculty, and society members. Build connections and collaborate on projects in real-time.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                        </div>
                        <h3>Academic Resources</h3>
                        <p>Access your results, attendance records, and subject materials all in one place. Stay on top of your academic performance.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'Educational Social Platform')); ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-decoration-none text-muted me-3">Terms of Service</a>
                    <a href="#" class="text-decoration-none text-muted me-3">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-muted">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH A:\New folder\InstituteConnect\resources\views/welcome.blade.php ENDPATH**/ ?>