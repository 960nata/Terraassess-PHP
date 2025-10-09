<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TerraAssessment IoT System - Welcome to the Future</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ url('/asset/css/space-theme.css') }}" rel="stylesheet">
    <style>
        .font-orbitron {
            font-family: 'Orbitron', monospace;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-content {
            z-index: 10;
            position: relative;
        }
        
        
        .feature-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
        }
        
        .feature-card:hover::before {
            left: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 20px 60px var(--glass-shadow);
            border-color: var(--space-cyan);
        }
        
        .feature-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }
        
        .cta-section {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 3rem;
            text-align: center;
            margin: 4rem 0;
        }
        
        .btn-space {
            background: linear-gradient(45deg, var(--space-cyan), var(--space-purple));
            border: none;
            border-radius: 20px;
            padding: 15px 40px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-space::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }
        
        .btn-space:hover::before {
            left: 100%;
        }
        
        .btn-space:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 212, 255, 0.4);
        }
        
        .stats-section {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 3rem;
            margin: 4rem 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 1.5rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--space-cyan);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-white-75);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="display-2 fw-bold text-white mb-4">
                            Welcome to the <span class="text-primary">Future</span> of Learning
                        </h1>
                        <p class="fs-4 text-white-75 mb-4">
                            <span class="font-orbitron">TerraAssessment</span> IoT System - Where technology meets education in the most innovative way possible.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="#" onclick="openLoginPopup()" class="btn btn-space text-white">
                                <i class="fas fa-rocket me-2"></i>Launch System
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Join Us
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <div class="space-animation-placeholder">
                            <i class="fas fa-satellite-dish fa-5x text-primary"></i>
                            <small class="text-white-75 fs-5">Advanced IoT Platform</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="cta-section">
                <h2 class="display-4 fw-bold text-white mb-4">Ready to Launch?</h2>
                <p class="fs-5 text-white-75 mb-4">
                    Join thousands of students and educators who are already experiencing the future of learning.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="#" onclick="openLoginPopup()" class="btn btn-space text-white">
                        <i class="fas fa-rocket me-2"></i>Start Your Journey
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="text-white mb-0 font-orbitron">TerraAssessment IoT System</h5>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>