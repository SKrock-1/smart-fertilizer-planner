<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Smart Fertilizer Planner') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --soil: #1A2E1A;
            --leaf: #2D6A4F;
            --leaf-dark: #1B4332;
            --leaf-light: #40916C;
            --lime: #d9ef6f;
            --cream: #f4f6f4;
            --mist: #eef4ec;
            --ink: #111827;
        }

        body {
            margin: 0;
            color: var(--ink);
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--cream);
            overflow-x: hidden;
        }

        h1, h2, h3, .brand-mark {
            font-family: "DM Serif Display", Georgia, serif;
            letter-spacing: -0.02em;
        }

        .site-shell {
            min-height: 100vh;
        }

        /* --- HERO SECTION --- */
        .hero {
            position: relative;
            min-height: 100vh;
            color: #fff;
            display: flex;
            flex-direction: column;
            background: #111;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("https://images.unsplash.com/photo-1592982537447-7440770cbfc9?q=80&w=2000&auto=format&fit=crop") center / cover;
            opacity: 0.6;
            z-index: 0;
        }

        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(145deg, rgba(27, 67, 50, 0.95) 0%, rgba(27, 67, 50, 0.4) 60%, transparent 100%);
            z-index: 1;
        }

        .nav-bar {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem clamp(1.5rem, 5vw, 6rem);
            animation: fadeInDown 0.8s ease-out;
        }

        .brand-mark {
            font-size: clamp(1.5rem, 2.5vw, 2.2rem);
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark span {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--lime);
            color: var(--leaf-dark);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            font-size: 1.2rem;
            transform: rotate(-10deg);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-field {
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 999px;
            padding: 0.75rem 1.5rem;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .btn-field:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .btn-lime {
            background: var(--lime);
            color: var(--leaf-dark) !important;
            border: none;
            box-shadow: 0 4px 14px rgba(217, 239, 111, 0.3);
        }

        .btn-lime:hover {
            background: #c5df55;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(217, 239, 111, 0.4);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            flex: 1;
            display: flex;
            align-items: center;
            padding: 0 clamp(1.5rem, 5vw, 6rem);
            padding-bottom: 5rem;
        }

        .hero-text-box {
            max-width: 800px;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            color: var(--lime);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.85rem;
            background: rgba(217, 239, 111, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 999px;
            border: 1px solid rgba(217, 239, 111, 0.2);
            backdrop-filter: blur(8px);
        }

        .hero h1 {
            font-size: clamp(3rem, 7vw, 5.5rem);
            line-height: 1.05;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: clamp(1.1rem, 2vw, 1.35rem);
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            max-width: 650px;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* --- WORKFLOW SECTION --- */
        .section {
            padding: clamp(5rem, 10vw, 8rem) clamp(1.5rem, 5vw, 6rem);
        }

        .section-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 4rem;
            animation: fadeUp 0.8s ease-out both;
            animation-timeline: view();
            animation-range: entry 10% cover 30%;
        }

        .section-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            line-height: 1.1;
            margin-bottom: 1.5rem;
            color: var(--soil);
        }

        .workflow-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            perspective: 1000px;
        }

        .workflow-step {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem 2rem;
            position: relative;
            box-shadow: 0 10px 40px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeUp 0.8s ease-out both;
            animation-timeline: view();
            animation-range: entry 10% cover 40%;
        }

        .workflow-step:hover {
            transform: translateY(-10px) rotateX(2deg);
            box-shadow: 0 20px 50px rgba(45, 106, 79, 0.1);
            border-color: rgba(45, 106, 79, 0.1);
        }

        .step-icon-box {
            width: 60px;
            height: 60px;
            background: var(--mist);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--leaf);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .workflow-step:hover .step-icon-box {
            background: var(--leaf);
            color: #fff;
            transform: scale(1.1) rotate(-5deg);
        }

        .step-number {
            position: absolute;
            top: 2rem;
            right: 2rem;
            font-family: "DM Serif Display", serif;
            font-size: 3rem;
            color: rgba(45, 106, 79, 0.05);
            line-height: 1;
            transition: all 0.3s ease;
        }

        .workflow-step:hover .step-number {
            color: rgba(45, 106, 79, 0.15);
            transform: scale(1.1);
        }

        .workflow-step h3 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: var(--soil);
        }

        .workflow-step p {
            color: #4B5563;
            line-height: 1.6;
            margin: 0;
            font-size: 1.05rem;
        }

        /* --- DATA SECTION --- */
        .data-band {
            background: var(--soil);
            color: #fff;
            border-radius: 40px;
            margin: 0 clamp(1rem, 3vw, 3rem) clamp(2rem, 5vw, 5rem);
            padding: clamp(4rem, 8vw, 6rem) clamp(2rem, 6vw, 5rem);
            position: relative;
            overflow: hidden;
        }

        .data-band::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(45, 106, 79, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .data-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: clamp(3rem, 6vw, 6rem);
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .nutrient-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 2rem;
            animation: fadeLeft 0.8s ease-out both;
            animation-timeline: view();
            animation-range: entry 10% cover 40%;
        }

        .nutrient-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nutrient-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .nutrient-row:first-child {
            padding-top: 0;
        }

        .nutrient-name {
            font-size: 1.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nutrient-name::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--lime);
        }

        .nutrient-val {
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        /* --- FOOTER --- */
        .site-footer {
            text-align: center;
            padding: 3rem 1.5rem;
            border-top: 1px solid rgba(0,0,0,0.05);
            color: #6B7280;
        }

        /* --- ANIMATIONS --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeLeft {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @media (max-width: 992px) {
            .data-grid { grid-template-columns: 1fr; }
            .data-band { border-radius: 24px; margin: 0 1rem 2rem; }
            .nav-actions .btn-field:not(.btn-lime) { display: none; }
        }
    </style>
</head>
<body>
    <main class="site-shell">
        <section class="hero">
            <nav class="nav-bar">
                <a class="brand-mark" href="{{ url('/') }}">
                    <span>🌱</span>
                    FertiPlan
                </a>
                <div class="nav-actions">
                    @auth
                        <a class="btn-field btn-lime" href="{{ url('/dashboard') }}">Open Dashboard</a>
                    @else
                        <a class="btn-field" href="{{ route('login') }}">Log in</a>
                        @if (Route::has('register'))
                            <a class="btn-field btn-lime" href="{{ route('register') }}">Start Planning</a>
                        @endif
                    @endauth
                </div>
            </nav>

            <div class="hero-content">
                <div class="hero-text-box">
                    <div class="eyebrow">Precision Agriculture Platform</div>
                    <h1>Data-driven fertilizer economics for modern farms.</h1>
                    <p>Transform raw soil test data into actionable, cost-optimized fertilizer plans. Maximize yield while minimizing input costs with our intelligent recommendation engine.</p>
                    <div class="hero-actions">
                        @auth
                            <a class="btn-field btn-lime" style="padding: 1rem 2rem; font-size: 1.1rem;" href="{{ url('/dashboard') }}">Access Workspace &rarr;</a>
                        @else
                            <a class="btn-field btn-lime" style="padding: 1rem 2rem; font-size: 1.1rem;" href="{{ route('register') }}">Create Free Account</a>
                            <a class="btn-field" style="padding: 1rem 2rem; font-size: 1.1rem;" href="{{ route('login') }}">Sign In</a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="section-header">
                <div class="eyebrow" style="color: var(--leaf); background: var(--mist); border-color: rgba(45,106,79,0.1);">Planning Workflow</div>
                <h2 class="section-title">From soil test to optimized schedule in four steps.</h2>
            </div>
            <div class="workflow-grid">
                <div class="workflow-step">
                    <div class="step-number">01</div>
                    <div class="step-icon-box">🗺️</div>
                    <h3>Map Parcels</h3>
                    <p>Register your land parcels with exact area sizes and soil types to establish a baseline for calculations.</p>
                </div>
                <div class="workflow-step">
                    <div class="step-number">02</div>
                    <div class="step-icon-box">🧪</div>
                    <h3>Log Soil Tests</h3>
                    <p>Input lab results for NPK, pH, Organic Carbon, and Micronutrients to detect exact deficiencies.</p>
                </div>
                <div class="workflow-step">
                    <div class="step-number">03</div>
                    <div class="step-icon-box">🌾</div>
                    <h3>Target Crop</h3>
                    <p>Select your seasonal crop. The engine retrieves the exact Recommended Dose of Fertilizer (RDF).</p>
                </div>
                <div class="workflow-step">
                    <div class="step-number">04</div>
                    <div class="step-icon-box">📊</div>
                    <h3>Generate Plan</h3>
                    <p>Our algorithm computes the most cost-effective fertilizer mix and generates a scheduled timeline.</p>
                </div>
            </div>
        </section>

        <section class="section data-band">
            <div class="data-grid">
                <div>
                    <div class="eyebrow" style="background: rgba(255,255,255,0.1); border:none; color:#fff;">Powered by ICAR Data</div>
                    <h2 class="section-title" style="color:#fff;">Comprehensive Indian Fertilizer Database.</h2>
                    <p class="lead" style="color: rgba(255,255,255,0.8); font-size: 1.2rem; line-height: 1.7; margin-bottom: 2rem;">
                        Our system comes pre-seeded with exact chemical compositions and real-time subsidized pricing for all major fertilizers used across India.
                    </p>
                    <a href="{{ route('register') }}" class="btn-field btn-lime">Explore the Database</a>
                </div>
                <div class="nutrient-card">
                    <div class="nutrient-row">
                        <span class="nutrient-name">Urea</span>
                        <span class="nutrient-val">46% N</span>
                    </div>
                    <div class="nutrient-row">
                        <span class="nutrient-name">DAP</span>
                        <span class="nutrient-val">18% N / 46% P</span>
                    </div>
                    <div class="nutrient-row">
                        <span class="nutrient-name">MOP</span>
                        <span class="nutrient-val">60% K</span>
                    </div>
                    <div class="nutrient-row">
                        <span class="nutrient-name">SSP</span>
                        <span class="nutrient-val">16% P / 11% S</span>
                    </div>
                    <div class="nutrient-row">
                        <span class="nutrient-name">NPK 10:26:26</span>
                        <span class="nutrient-val">Complex</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section class="section" style="text-align: center; background: #fff; padding: clamp(4rem, 8vw, 6rem) 1.5rem;">
            <div style="max-width: 700px; margin: 0 auto; animation: fadeUp 0.8s ease-out both; animation-timeline: view(); animation-range: entry 10% cover 30%;">
                <h2 class="section-title" style="margin-bottom: 1.5rem;">Ready to optimize your yield?</h2>
                <p style="font-size: 1.2rem; color: #4B5563; margin-bottom: 2.5rem; line-height: 1.6;">
                    Join modern farmers who are making data-driven decisions to reduce fertilizer costs and improve soil health.
                </p>
                <a href="{{ route('register') }}" class="btn-field btn-lime" style="padding: 1.25rem 2.5rem; font-size: 1.2rem; display: inline-block;">
                    Create Your Free Account
                </a>
                <p style="margin-top: 1.5rem; font-size: 0.9rem; color: #9CA3AF;">No credit card required. Setup takes less than 2 minutes.</p>
            </div>
        </section>
        
        <footer class="site-footer">
            <div class="brand-mark" style="color: var(--soil); justify-content: center; margin-bottom: 1rem;">
                <span style="width: 30px; height: 30px; font-size: 0.9rem;">🌱</span>
                FertiPlan
            </div>
            <p>&copy; {{ date('Y') }} Smart Fertilizer Planner. Designed for modern agriculture.</p>
        </footer>
    </main>
</body>
</html>
