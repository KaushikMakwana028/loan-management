<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Kreditmitraa | Instant Personal Loans & Referral Rewards</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #063d32;
            --primary-dark: #042921;
            --primary-light: #0d5c4b;
            --secondary: #c59b27;
            --secondary-light: #e0b43c;
            --bg-light: #f6f8fb;
            --surface: #ffffff;
            --ink: #111827;
            --muted: #667085;
            --line: #e3ebf4;
            --radius-sm: 10px;
            --radius-md: 18px;
            --radius-lg: 30px;
            --shadow-sm: 0 4px 12px rgba(6, 61, 50, 0.05);
            --shadow-md: 0 12px 36px rgba(6, 61, 50, 0.08);
            --shadow-lg: 0 24px 64px rgba(6, 61, 50, 0.16);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-light);
            color: var(--ink);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        img {
            max-width: 100%;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* Container helper */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            position: relative;
        }

        /* Reveal-on-scroll */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1), transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal.in-view {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-stagger.in-view>* {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-stagger>* {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal-stagger>*:nth-child(1) {
            transition-delay: 0.05s;
        }

        .reveal-stagger>*:nth-child(2) {
            transition-delay: 0.15s;
        }

        .reveal-stagger>*:nth-child(3) {
            transition-delay: 0.25s;
        }

        .reveal-stagger>*:nth-child(4) {
            transition-delay: 0.35s;
        }

        /* Navbar */
        .navbar {
            height: 76px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--line);
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        .navbar.scrolled {
            height: 66px;
            box-shadow: var(--shadow-sm);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            gap: 12px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            flex: none;
        }

        .logo img {
            height: 89px;
            width: auto;
            object-fit: contain;
            transition: var(--transition);
        }

        .navbar.scrolled .logo img {
            height: 59px;
        }

        .logo-text {
            white-space: nowrap;
            letter-spacing: -0.2px;
        }

        .logo-text span {
            color: var(--secondary);
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
            list-style: none;
        }

        .nav-links a {
            position: relative;
            text-decoration: none;
            color: var(--muted);
            font-weight: 600;
            font-size: 14px;
            transition: var(--transition);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 0;
            height: 2px;
            background: var(--secondary);
            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            border: none;
            white-space: nowrap;
        }

        .btn-outline {
            border: 1px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(6, 61, 50, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 6px 18px rgba(6, 61, 50, 0.3);
            transform: translateY(-2px);
        }

        .btn-primary:active,
        .btn-secondary:active,
        .btn-outline:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-secondary {
            background: var(--secondary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(197, 155, 39, 0.2);
        }

        .btn-secondary:hover {
            background: var(--secondary-light);
            box-shadow: 0 6px 18px rgba(197, 155, 39, 0.3);
            transform: translateY(-2px);
        }

        /* Mobile-only compact actions beside the hamburger */
        .nav-mobile-actions {
            display: none;
            align-items: center;
            gap: 8px;
            flex: none;
        }

        .nav-icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--primary);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 16px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(6, 61, 50, 0.22);
            transition: var(--transition);
            flex: none;
        }

        .nav-icon-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .nav-icon-btn:active {
            transform: scale(0.94);
        }

        .nav-toggle {
            display: none;
            background: var(--bg-light);
            border: 1px solid var(--line);
            font-size: 18px;
            color: var(--primary);
            cursor: pointer;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            transition: var(--transition);
            align-items: center;
            justify-content: center;
            flex: none;
        }

        .nav-toggle:hover {
            background: var(--line);
        }

        /* Hero Section */
        .hero {
            padding: 64px 0 90px;
            background:
                radial-gradient(circle at 10% 20%, rgba(6, 61, 50, 0.05), transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(197, 155, 39, 0.06), transparent 40%);
            position: relative;
            overflow: hidden;
        }

        .hero-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
            z-index: 0;
        }

        .hero-blob.one {
            width: 340px;
            height: 340px;
            background: rgba(6, 61, 50, 0.08);
            top: -100px;
            right: -80px;
            animation: blobFloat 9s ease-in-out infinite;
        }

        .hero-blob.two {
            width: 240px;
            height: 240px;
            background: rgba(197, 155, 39, 0.10);
            bottom: -60px;
            left: -60px;
            animation: blobFloat 11s ease-in-out infinite reverse;
        }

        @keyframes blobFloat {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(20px, -20px) scale(1.08);
            }
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 48px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--surface);
            padding: 6px 16px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--line);
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
            animation: fadeInUp 0.7s ease both;
        }

        .hero-badge i {
            color: var(--secondary);
            animation: pulseIcon 2s ease-in-out infinite;
        }

        @keyframes pulseIcon {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.25);
            }
        }

        .hero-title {
            font-size: clamp(30px, 5.2vw, 48px);
            font-weight: 800;
            line-height: 1.2;
            color: var(--primary);
            margin-bottom: 20px;
            animation: fadeInUp 0.7s ease 0.08s both;
        }

        .hero-title span {
            color: var(--secondary);
            position: relative;
        }

        .hero-desc {
            font-size: 16px;
            color: var(--muted);
            margin-bottom: 32px;
            max-width: 540px;
            animation: fadeInUp 0.7s ease 0.16s both;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            margin-bottom: 44px;
            flex-wrap: wrap;
            animation: fadeInUp 0.7s ease 0.24s both;
        }

        .hero-actions .btn {
            padding: 14px 28px;
            font-size: 15px;
        }

        /* --- Elevated Stats & Trust Showcase Section --- */
        .stats-container {
            margin-top: 54px;
            position: relative;
            z-index: 5;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid rgba(6, 61, 50, 0.08);
            border-radius: var(--radius-md);
            padding: 24px 22px;
            box-shadow: 0 10px 30px rgba(6, 61, 50, 0.05);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(6, 61, 50, 0.12);
            border-color: rgba(6, 61, 50, 0.2);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: var(--transition);
        }

        .icon-users {
            background: rgba(6, 61, 50, 0.08);
            color: var(--primary);
        }

        .icon-investors {
            background: rgba(197, 155, 39, 0.12);
            color: #b38515;
        }

        .icon-support {
            background: rgba(13, 92, 75, 0.1);
            color: var(--primary-light);
        }

        .icon-loans {
            background: rgba(6, 61, 50, 0.08);
            color: var(--primary);
        }

        .stat-card:hover .stat-icon-wrapper {
            transform: scale(1.08);
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .pill-users {
            background: rgba(6, 61, 50, 0.07);
            color: var(--primary);
        }

        .pill-investors {
            background: rgba(197, 155, 39, 0.12);
            color: #8c670a;
        }

        .pill-support {
            background: rgba(13, 92, 75, 0.1);
            color: var(--primary-light);
        }

        .pill-loans {
            background: rgba(6, 61, 50, 0.07);
            color: var(--primary);
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-ring 1.8s infinite;
        }

        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .stat-card-body {
            margin-bottom: 16px;
        }

        .stat-number {
            font-size: 34px;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.1;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .stat-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .stat-desc {
            font-size: 12.5px;
            color: var(--muted);
            line-height: 1.45;
            font-weight: 500;
        }

        .stat-card-footer {
            padding-top: 14px;
            border-top: 1px dashed var(--line);
            display: flex;
            align-items: center;
        }

        .trust-badge {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .trust-badge i {
            color: var(--primary);
            font-size: 12px;
        }

        /* Avatar group styling for Users stat */
        .avatar-group {
            display: flex;
            align-items: center;
        }

        .avatar-sm {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            margin-left: -6px;
        }

        .avatar-sm:first-child {
            margin-left: 0;
        }

        .avatar-more {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--primary);
            margin-left: 8px;
        }

        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 420px;
        }

        /* --- Phone mockup frame: fixed real-device aspect ratio so any
             screenshot dropped in (kreditmitraa_mockup.png) fills it cleanly
             without stretching, cropping oddly, or leaving checker gaps --- */
        .phone-mockup {
            position: relative;
            width: 250px;
            aspect-ratio: 9 / 19.4;
            background: #10131a;
            border-radius: 44px;
            padding: 10px;
            box-shadow: var(--shadow-lg), 0 0 0 2px rgba(255, 255, 255, 0.06) inset;
            animation: float 6s ease-in-out infinite;
            z-index: 2;
        }

        .phone-mockup:hover {
            animation-play-state: paused;
            transform: translateY(-6px) scale(1.02);
        }

        .phone-mockup::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 84px;
            height: 22px;
            background: #10131a;
            border-radius: 0 0 14px 14px;
            z-index: 3;
        }

        .phone-mockup::after {
            content: '';
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            width: 84px;
            height: 4px;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.35);
            z-index: 3;
        }

        .phone-screen {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 34px;
            overflow: hidden;
            background: linear-gradient(160deg, var(--primary), var(--primary-dark));
        }

        .phone-screen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top center;
            display: block;
        }

        .phone-shadow {
            position: absolute;
            bottom: -22px;
            width: 70%;
            height: 20px;
            background: rgba(6, 61, 50, 0.18);
            border-radius: 50%;
            filter: blur(10px);
            z-index: 1;
            animation: shadow-float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-14px);
            }
        }

        @keyframes shadow-float {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.8;
            }

            50% {
                transform: scale(0.88);
                opacity: 0.5;
            }
        }

        .float-chip {
            position: absolute;
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--surface);
            border-radius: 14px;
            padding: 10px 14px;
            box-shadow: var(--shadow-md);
            font-size: 12px;
            font-weight: 700;
            color: var(--primary);
            z-index: 4;
            animation: chipFloat 5s ease-in-out infinite;
        }

        .float-chip .chip-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 13px;
            color: #fff;
            flex: none;
        }

        .float-chip.chip-a {
            top: 8%;
            left: -6%;
        }

        .float-chip.chip-a .chip-icon {
            background: var(--secondary);
        }

        .float-chip.chip-b {
            bottom: 12%;
            right: -10%;
            animation-delay: 1.2s;
        }

        .float-chip.chip-b .chip-icon {
            background: var(--primary);
        }

        .float-chip small {
            display: block;
            font-weight: 500;
            color: var(--muted);
            font-size: 10.5px;
        }

        @keyframes chipFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* Calculator Section */
        .calc-section {
            padding: 76px 0;
            background: var(--surface);
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 52px;
        }

        .section-eyebrow {
            display: inline-block;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--secondary);
            margin-bottom: 10px;
        }

        .section-header h2 {
            font-size: clamp(24px, 4vw, 32px);
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .section-header p {
            color: var(--muted);
            font-size: 15px;
        }

        .calc-card {
            background: var(--bg-light);
            border-radius: var(--radius-md);
            padding: 40px;
            box-shadow: var(--shadow-md);
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
            border: 1px solid var(--line);
        }

        .calc-sliders {
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .slider-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .slider-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .slider-header label {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
        }

        .slider-header .value {
            font-size: 18px;
            font-weight: 800;
            color: var(--secondary);
            transition: transform 0.15s ease;
        }

        .range-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 100%;
            height: 8px;
            border-radius: 4px;
            background: var(--line);
            outline: none;
            transition: var(--transition);
        }

        .range-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary);
            cursor: pointer;
            box-shadow: 0 0 10px rgba(6, 61, 50, 0.2);
            transition: var(--transition);
        }

        .range-slider::-webkit-slider-thumb:hover {
            background: var(--secondary);
            transform: scale(1.15);
        }

        .range-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border: none;
            border-radius: 50%;
            background: var(--primary);
            cursor: pointer;
            transition: var(--transition);
        }

        .slider-footer {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 600;
            color: var(--muted);
        }

        .calc-summary {
            background: var(--primary);
            border-radius: var(--radius-md);
            padding: 30px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(6, 61, 50, 0.2);
        }

        .calc-summary::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(197, 155, 39, 0.1);
            border-radius: 50%;
            pointer-events: none;
        }

        .summary-header {
            margin-bottom: 24px;
        }

        .summary-header p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
        }

        .summary-header .emi-value {
            font-size: clamp(26px, 6vw, 36px);
            font-weight: 800;
            color: var(--secondary);
            margin-top: 4px;
        }

        .summary-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }

        .summary-row span:first-child {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .summary-row span:last-child {
            font-weight: 700;
        }

        /* Features Section */
        .features {
            padding: 90px 0;
            background-color: var(--bg-light);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .feature-card {
            background: var(--surface);
            border-radius: var(--radius-md);
            padding: 40px 30px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--line);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }

        .feature-icon {
            width: 54px;
            height: 54px;
            background: var(--bg-light);
            border-radius: var(--radius-sm);
            color: var(--primary);
            font-size: 22px;
            display: grid;
            place-items: center;
            transition: var(--transition);
        }

        .feature-card:hover .feature-icon {
            background: var(--primary);
            color: #fff;
            transform: rotate(-6deg) scale(1.05);
        }

        .feature-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        .feature-card p {
            font-size: 14px;
            color: var(--muted);
        }

        /* How It Works */
        .how-it-works {
            padding: 90px 0;
            background: var(--surface);
            position: relative;
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            position: relative;
        }

        .step-card {
            position: relative;
            background: var(--bg-light);
            padding: 36px 24px;
            border-radius: var(--radius-md);
            border: 1px solid var(--line);
            text-align: center;
            transition: var(--transition);
        }

        .step-card:hover {
            background: var(--surface);
            border-color: var(--secondary);
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }

        .step-num {
            width: 44px;
            height: 44px;
            background: var(--primary);
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            border-radius: 50%;
            display: grid;
            place-items: center;
            margin: 0 auto 20px;
            border: 4px solid #fff;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .step-card:hover .step-num {
            background: var(--secondary);
            transform: scale(1.1);
        }

        .step-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .step-card p {
            font-size: 13px;
            color: var(--muted);
        }

        /* Referral Showcase */
        .referral-section {
            padding: 90px 0;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .referral-section .hero-blob {
            filter: blur(80px);
        }

        .referral-section .hero-blob.one {
            background: rgba(197, 155, 39, 0.14);
            top: -120px;
            left: -100px;
        }

        .referral-section .hero-blob.two {
            background: rgba(255, 255, 255, 0.06);
            bottom: -100px;
            right: -80px;
        }

        .referral-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .referral-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 16px;
            border-radius: var(--radius-lg);
            font-size: 12px;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .referral-title {
            font-size: clamp(26px, 4.4vw, 36px);
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .referral-title span {
            color: var(--secondary);
        }

        .referral-desc {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 36px;
        }

        .referral-steps {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .ref-step {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            transition: var(--transition);
        }

        .ref-step:hover {
            transform: translateX(6px);
        }

        .ref-icon-wrap {
            width: 40px;
            height: 40px;
            background: rgba(197, 155, 39, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary);
            font-size: 16px;
            flex: none;
        }

        .ref-text h4 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .ref-text p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .referral-visual {
            display: flex;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .invitation-card {
            background: var(--surface);
            border-radius: var(--radius-md);
            padding: 30px;
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 380px;
            color: var(--ink);
            border: 1px solid var(--line);
            transition: var(--transition);
        }

        .invitation-card:hover {
            transform: translateY(-6px);
        }

        .invitation-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .code-box {
            background: var(--bg-light);
            border: 1px dashed var(--muted);
            border-radius: var(--radius-sm);
            padding: 14px;
            text-align: center;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 2px;
            color: var(--primary);
            margin: 20px 0;
            cursor: pointer;
            transition: var(--transition);
        }

        .code-box:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        /* Download Section */
        .download-section {
            padding: 90px 0;
            background-color: var(--bg-light);
            position: relative;
            overflow: hidden;
        }

        .download-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
        }

        .download-content h2 {
            font-size: clamp(26px, 4.4vw, 36px);
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .download-content p {
            font-size: 15px;
            color: var(--muted);
            margin-bottom: 36px;
        }

        .store-btns {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .store-btn {
            background: #111827;
            color: #fff;
            padding: 12px 22px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .store-btn:hover {
            background: #1f2937;
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .store-btn i {
            font-size: 24px;
        }

        .store-btn-text span {
            display: block;
            font-size: 10px;
            color: #9ca3af;
        }

        .store-btn-text strong {
            display: block;
            font-size: 14px;
            font-weight: 700;
        }

        .download-visual {
            display: flex;
            justify-content: center;
            position: relative;
            /* extra breathing room reserved for a real screenshot */
            min-height: 460px;
            align-items: center;
        }

        .download-visual .phone-mockup {
            width: 260px;
        }

        /* Footer */
        .footer {
            background: #0f1d19;
            color: #d1d5db;
            padding: 76px 0 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 60px;
        }

        .footer-logo {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-logo img {
            height: 59px;
            width: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: 0.9;
            transition: var(--transition);
        }

        .footer-logo img:hover {
            opacity: 1;
        }

        .footer-desc {
            font-size: 13.5px;
            color: #9ca3af;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .footer-col h4 {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-col ul a {
            text-decoration: none;
            color: #9ca3af;
            font-size: 13.5px;
            transition: var(--transition);
        }

        .footer-col ul a:hover {
            color: #fff;
            padding-left: 4px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 14px;
            font-size: 13.5px;
            color: #9ca3af;
        }

        .contact-info li {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .contact-info li i {
            color: var(--secondary);
            margin-top: 3px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            font-size: 13px;
            color: #9ca3af;
        }

        .social-links {
            display: flex;
            gap: 16px;
        }

        .social-links a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: grid;
            place-items: center;
            color: #9ca3af;
            font-size: 14px;
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-3px);
        }

        /* Mobile Menu */
        .mobile-menu-wrapper {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            position: fixed;
            top: 76px;
            left: 0;
            width: 100%;
            background: var(--surface);
            border-bottom: 1px solid var(--line);
            padding: 0 24px;
            z-index: 999;
            box-shadow: var(--shadow-md);
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease, padding 0.4s ease;
        }

        .mobile-menu-wrapper.open {
            max-height: 480px;
            opacity: 1;
            padding: 24px;
        }

        .mobile-menu-wrapper ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 18px;
            margin-bottom: 22px;
        }

        .mobile-menu-wrapper ul a {
            font-size: 15px;
            font-weight: 700;
            color: var(--ink);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mobile-menu-wrapper ul a i {
            color: var(--secondary);
            font-size: 14px;
            width: 18px;
        }

        .mobile-menu-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .mobile-menu-actions .btn {
            width: 100%;
            padding: 12px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 980px) {
            .hero-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-badge,
            .hero-actions {
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .hero-desc {
                margin: 0 auto 32px;
            }

            .hero-visual {
                margin-top: 20px;
                min-height: 380px;
            }

            .float-chip.chip-a {
                left: 2%;
                top: 4%;
            }

            .float-chip.chip-b {
                right: 2%;
                bottom: 8%;
            }

            .calc-card {
                grid-template-columns: 1fr;
            }

            .features-grid {
                grid-template-columns: 1fr 1fr;
            }

            .steps-container {
                grid-template-columns: 1fr 1fr;
            }

            .referral-grid {
                grid-template-columns: 1fr;
            }

            .referral-visual {
                margin-top: 20px;
            }

            .download-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .store-btns {
                justify-content: center;
            }

            .download-visual {
                margin-top: 20px;
                min-height: 400px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {

            .nav-links,
            .nav-actions {
                display: none;
            }

            .nav-mobile-actions {
                display: flex;
            }

            .nav-toggle {
                display: flex;
            }

            .logo img {
                height: 75px;
            }

            .logo-text {
                font-size: 17px;
            }

            .hero {
                padding: 40px 0 64px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .steps-container {
                grid-template-columns: 1fr;
            }

            .calc-section,
            .features,
            .how-it-works,
            .referral-section,
            .download-section {
                padding: 56px 0;
            }

            .float-chip {
                font-size: 11px;
                padding: 8px 11px;
            }

            .float-chip .chip-icon {
                width: 26px;
                height: 26px;
                font-size: 11px;
            }
        }

        @media (max-width: 640px) {
            .stats-container {
                margin-top: 36px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .stat-number {
                font-size: 28px;
            }

            .stat-card {
                padding: 20px 18px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 16px;
            }

            .logo-text {
                font-size: 15px;
            }

            .logo img {
                height: 50px;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .hero-actions .btn {
                width: 100%;
            }

            .hero-visual {
                min-height: 340px;
            }

            .phone-mockup {
                width: 210px;
            }

            .float-chip {
                display: none;
            }

            .calc-card {
                padding: 22px;
            }

            .calc-summary {
                padding: 20px;
            }

            .download-visual {
                min-height: 340px;
            }

            .download-visual .phone-mockup {
                width: 210px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 36px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 360px) {
            .logo-text {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="container nav-container">
            <a href="#" class="logo">
                <img src="<?php echo base_url('assets/images/logo/bg-remove-sidelogo.png'); ?>" alt="Kreditmitraa logo">
                <!-- <span class="logo-text">Kredit<span>mitraa</span></span> -->
            </a>
            <ul class="nav-links">
                <li><a href="#how-it-works">How it Works</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#referral">Referral Program</a></li>
                <li><a href="#download">Download App</a></li>
            </ul>
            <div class="nav-actions">
                <a href="<?php echo base_url('assets/app/Kreditmitraa.apk'); ?>" download="Kreditmitraa.apk" class="btn btn-primary"><i class="fa-solid fa-download" style="margin-right: 8px;"></i> Download App</a>
            </div>
            <div class="nav-mobile-actions">
                <a href="<?php echo base_url('assets/app/Kreditmitraa.apk'); ?>" download="Kreditmitraa.apk" class="nav-icon-btn" aria-label="Download app">
                    <i class="fa-solid fa-download"></i>
                </a>
                <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                    <i class="fa-solid fa-bars" id="toggleIcon"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Wrapper -->
    <div class="mobile-menu-wrapper" id="mobileMenu">
        <ul>
            <li><a href="#how-it-works" class="mob-link"><i class="fa-solid fa-diagram-project"></i> How it Works</a></li>
            <li><a href="#features" class="mob-link"><i class="fa-solid fa-star"></i> Features</a></li>
            <li><a href="#referral" class="mob-link"><i class="fa-solid fa-gift"></i> Referral Program</a></li>
            <li><a href="<?php echo base_url('assets/app/Kreditmitraa.apk'); ?>" download="Kreditmitraa.apk" class="mob-link"><i class="fa-solid fa-mobile-screen"></i> Download App</a></li>
        </ul>
        <div class="mobile-menu-actions">
            <a href="<?php echo base_url('assets/app/Kreditmitraa.apk'); ?>" download="Kreditmitraa.apk" class="btn btn-primary mob-link"><i class="fa-solid fa-download" style="margin-right: 8px;"></i> Download App</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-blob one"></div>
        <div class="hero-blob two"></div>
        <div class="container hero-grid">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fa-solid fa-bolt"></i> Instant Approvals under 24 Hours
                </div>
                <h1 class="hero-title">
                    Instant Loans Made <span>Simple</span>, Quick & Secure.
                </h1>
                <p class="hero-desc">
                    Get disbursed instantly, calculate repayments transparently, and earn cash rewards by inviting friends to Kreditmitraa.
                </p>
                <div class="hero-actions">
                    <a href="<?php echo base_url('assets/app/Kreditmitraa.apk'); ?>" download="Kreditmitraa.apk" class="btn btn-primary"><i class="fa-solid fa-download" style="margin-right: 8px;"></i> Download App</a>
                    <a href="#calculator" class="btn btn-outline">Check EMI</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <img src="<?php echo base_url('assets/images/kreditmitraa_mockup.png'); ?>" alt="Kreditmitraa mobile app">
                    </div>
                </div>
                <div class="phone-shadow"></div>
                <div class="float-chip chip-a">
                    <div class="chip-icon"><i class="fa-solid fa-check"></i></div>
                    <div>Approved in 2 mins<br><small>KYC verified instantly</small></div>
                </div>
                <div class="float-chip chip-b">
                    <div class="chip-icon"><i class="fa-solid fa-wallet"></i></div>
                    <div>INR 25,000 disbursed<br><small>Straight to bank</small></div>
                </div>
            </div>
        </div>

        <!-- Elevated Stats & Trust Showcase Section -->
        <div class="container stats-container reveal">
            <div class="stats-grid">
                <!-- Stat Card 1: Happy Users -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon-wrapper icon-users">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <span class="stat-pill pill-users">
                            <span class="pulse-dot"></span> Active
                        </span>
                    </div>
                    <div class="stat-card-body">
                        <h3 class="stat-number">300k+</h3>
                        <p class="stat-title">Happy Users</p>
                        <p class="stat-desc">Borrowers across India trusting Kreditmitraa daily</p>
                    </div>
                    <div class="stat-card-footer">
                        <div class="avatar-group">
                            <div class="avatar-sm"><i class="fa-solid fa-user"></i></div>
                            <div class="avatar-sm"><i class="fa-solid fa-user"></i></div>
                            <div class="avatar-sm"><i class="fa-solid fa-user"></i></div>
                            <span class="avatar-more">+300k borrowers</span>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 2: Trusted Investors -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon-wrapper icon-investors">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <span class="stat-pill pill-investors">
                            <i class="fa-solid fa-shield-halved"></i> Verified
                        </span>
                    </div>
                    <div class="stat-card-body">
                        <h3 class="stat-number">2k+</h3>
                        <p class="stat-title">Trusted Investors</p>
                        <p class="stat-desc">Active capital partners providing transparent funds</p>
                    </div>
                    <div class="stat-card-footer">
                        <span class="trust-badge"><i class="fa-solid fa-circle-check"></i> 100% Risk Assessed</span>
                    </div>
                </div>

                <!-- Stat Card 3: Instant Support -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon-wrapper icon-support">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <span class="stat-pill pill-support">
                            <i class="fa-solid fa-clock"></i> 24/7 Live
                        </span>
                    </div>
                    <div class="stat-card-body">
                        <h3 class="stat-number">24/7</h3>
                        <p class="stat-title">Instant Support</p>
                        <p class="stat-desc">Dedicated human help whenever you need assistance</p>
                    </div>
                    <div class="stat-card-footer">
                        <span class="trust-badge"><i class="fa-solid fa-bolt"></i> Avg Response &lt; 2 mins</span>
                    </div>
                </div>

                <!-- Stat Card 4: Loans Disbursed -->
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon-wrapper icon-loans">
                            <i class="fa-solid fa-indian-rupee-sign"></i>
                        </div>
                        <span class="stat-pill pill-loans">
                            <i class="fa-solid fa-vault"></i> Direct Bank
                        </span>
                    </div>
                    <div class="stat-card-body">
                        <h3 class="stat-number">INR 25L+</h3>
                        <p class="stat-title">Loans Disbursed</p>
                        <p class="stat-desc">Seamless, instant transfers directly into bank accounts</p>
                    </div>
                    <div class="stat-card-footer">
                        <span class="trust-badge"><i class="fa-solid fa-lock"></i> 256-bit SSL Encrypted</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Calculator Section -->
    <section class="calc-section" id="calculator">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow">EMI Calculator</span>
                <h2>Calculate Your Repayments</h2>
                <p>Use our slider to estimate your loan cost and choose the scheme that fits your budget. No hidden fees.</p>
            </div>
            <div class="calc-card reveal">
                <div class="calc-sliders">
                    <div class="slider-group">
                        <div class="slider-header">
                            <label for="amountSlider">Loan Amount</label>
                            <span class="value" id="amountValue">INR 5,000</span>
                        </div>
                        <input type="range" class="range-slider" id="amountSlider" min="5000" max="100000" step="5000" value="25000">
                        <div class="slider-footer">
                            <span>INR 5,000</span>
                            <span>INR 1,00,000</span>
                        </div>
                    </div>

                    <div class="slider-group">
                        <div class="slider-header">
                            <label for="tenureSlider">Loan Tenure</label>
                            <span class="value" id="tenureValue">30 Days</span>
                        </div>
                        <input type="range" class="range-slider" id="tenureSlider" min="1" max="30" step="1" value="30">
                        <div class="slider-footer">
                            <span>1 Day</span>
                            <span>30 Days</span>
                        </div>
                    </div>
                </div>

                <div class="calc-summary">
                    <div class="summary-header">
                        <p>Estimated Weekly Repayment</p>
                        <div class="emi-value" id="emiValue">INR 6,125.00</div>
                    </div>
                    <div class="summary-list">
                        <div class="summary-row">
                            <span>Interest Rate (PM)</span>
                            <span id="summaryInterest">1.5%</span>
                        </div>
                        <div class="summary-row">
                            <span>Processing Fee</span>
                            <span id="summaryFee">INR 500.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Total Repayment Amount</span>
                            <span id="summaryTotal">INR 26,125.00</span>
                        </div>
                    </div>
                    <a href="<?php echo base_url('assets/app/Kreditmitraa.apk'); ?>" download="Kreditmitraa.apk" class="btn btn-secondary" style="width:100%; text-align:center;"><i class="fa-solid fa-download" style="margin-right: 8px;"></i> Download App to Apply</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow">Why Kreditmitraa</span>
                <h2>Why Choose Kreditmitraa?</h2>
                <p>We combine modern financial technology with customer-first services to offer you the best personal loan platform.</p>
            </div>
            <div class="features-grid reveal-stagger">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <h3>Lightning Fast Processing</h3>
                    <p>Apply in less than 5 minutes, complete KYC instantly, and receive funds into your wallet as soon as administrators approve.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3>Secure & Encrypted</h3>
                    <p>Your documents, details, and transactions are protected by industry-standard encryption, maintaining absolute privacy.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <h3>Flexible Loan Schemes</h3>
                    <p>Choose from multiple loan options and repayment tenures tailored to match your specific financial flow.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow">Process</span>
                <h2>Simple Step-by-Step Process</h2>
                <p>Getting a personal loan has never been easier. Follow our simple process to secure your loan.</p>
            </div>
            <div class="steps-container reveal-stagger">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <h3>Quick Registration</h3>
                    <p>Register using your mobile number and verify via OTP instantly.</p>
                </div>

                <div class="step-card">
                    <div class="step-num">2</div>
                    <h3>Complete Profile</h3>
                    <p>Fill in details and securely upload your Aadhaar/PAN documents for verification.</p>
                </div>

                <div class="step-card">
                    <div class="step-num">3</div>
                    <h3>Apply for Loan</h3>
                    <p>Choose your desired loan amount and scheme from the dashboard.</p>
                </div>

                <div class="step-card">
                    <div class="step-num">4</div>
                    <h3>Get Disbursed</h3>
                    <p>Upon approval, funds are immediately credited to your bank account.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Referral Showcase -->
    <section class="referral-section" id="referral">
        <div class="hero-blob one"></div>
        <div class="hero-blob two"></div>
        <div class="container referral-grid">
            <div class="referral-info reveal">
                <div class="referral-badge">
                    <i class="fa-solid fa-gift"></i> Share & Earn Program
                </div>
                <h2 class="referral-title">
                    Invite Friends, Earn <span>Rewards</span>
                </h2>
                <p class="referral-desc">
                    Spread the word about Kreditmitraa and earn instant referral commissions. When your friends register and successfully disburse their first loan, you get a bonus directly in your wallet!
                </p>
                <div class="referral-steps">
                    <div class="ref-step">
                        <div class="ref-icon-wrap">
                            <i class="fa-solid fa-share-nodes"></i>
                        </div>
                        <div class="ref-text">
                            <h4>1. Share Code</h4>
                            <p>Get your unique referral link/code from the user dashboard.</p>
                        </div>
                    </div>

                    <div class="ref-step">
                        <div class="ref-icon-wrap">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <div class="ref-text">
                            <h4>2. Friend Registers</h4>
                            <p>Your friend registers on our platform using your code.</p>
                        </div>
                    </div>

                    <div class="ref-step">
                        <div class="ref-icon-wrap">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                        <div class="ref-text">
                            <h4>3. Earn Commission</h4>
                            <p>Once their loan is approved and active, you receive cash rewards!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="referral-visual reveal">
                <div class="invitation-card">
                    <div class="invitation-header" style="margin-bottom: 24px;">
                        <div style="width: 44px; height: 44px; background: var(--primary); border-radius: 50%; display: grid; place-items: center; color: #fff;">
                            <i class="fa-solid fa-gift" style="font-size: 18px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 16px; font-weight: 700; color: var(--primary);">Referral Wallet</h4>
                            <p style="font-size: 12px; color: var(--muted);">Track your referral bonus</p>
                        </div>
                    </div>

                    <div style="background: var(--bg-light); padding: 20px; border-radius: var(--radius-sm); border: 1px solid var(--line); margin-bottom: 20px; text-align: center; color: var(--ink);">
                        <span style="font-size: 12px; color: var(--muted); font-weight: 600; display: block; margin-bottom: 4px;">TOTAL EARNED</span>
                        <span style="font-size: 32px; font-weight: 800; color: var(--primary);">INR 12,500.00</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; color: var(--ink);">
                        <div style="background: var(--bg-light); padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--line); text-align: center;">
                            <span style="font-size: 10px; color: var(--muted); font-weight: 600; display: block; margin-bottom: 2px;">SUCCESSFUL REFERS</span>
                            <span style="font-size: 16px; font-weight: 800; color: var(--secondary);">25 Friends</span>
                        </div>
                        <div style="background: var(--bg-light); padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--line); text-align: center;">
                            <span style="font-size: 10px; color: var(--muted); font-weight: 600; display: block; margin-bottom: 2px;">COMMISSION RATE</span>
                            <span style="font-size: 16px; font-weight: 800; color: var(--secondary);">INR 500 / Friend</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Download Section -->
    <section class="download-section" id="download">
        <div class="container download-grid">
            <div class="download-content reveal">
                <h2>Manage Everything on Our Mobile App</h2>
                <p>
                    Download our mobile application today to get instant push notifications about your loan status, track EMIs easily, update bank details securely, and share referral invites on the go.
                </p>
                <div class="store-btns">
                    <a href="<?php echo base_url('assets/app/Kreditmitraa.apk'); ?>" download="Kreditmitraa.apk" class="store-btn">
                        <i class="fa-solid fa-android"></i>
                        <div class="store-btn-text">
                            <span>DIRECT DOWNLOAD</span>
                            <strong>Download Android APK</strong>
                        </div>
                    </a>
                    <a href="<?php echo base_url('assets/app/Kreditmitraa.apk'); ?>" download="Kreditmitraa.apk" class="store-btn">
                        <i class="fa-brands fa-google-play"></i>
                        <div class="store-btn-text">
                            <span>GET IT ON</span>
                            <strong>Google Play</strong>
                        </div>
                    </a>
                </div>
            </div>
            <div class="download-visual reveal">
                <!-- Phone frame keeps a fixed 9:19.4 ratio so any real screenshot
                     you drop into kreditmitraa_mockup.png fills the screen area
                     cleanly with no stretching or empty space -->
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <img src="<?php echo base_url('assets/images/kreditmitraa_mockup.png'); ?>" alt="Kreditmitraa application screenshot">
                    </div>
                </div>
                <div class="phone-shadow"></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-info">
                <a href="#" class="footer-logo">
                    <img src="<?php echo base_url('assets/images/logo/bg-remove-sidelogo.png'); ?>" alt="Kreditmitraa logo">
                </a>
                <p class="footer-desc">
                    India's leading personal finance platform offering lightning fast loan disbursements, transparent pricing structures, and commission rewards.
                </p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#how-it-works">How it Works</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#referral">Referral Program</a></li>
                    <li><a href="#download">Download App</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contact Us</h4>
                <ul class="contact-info">
                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?php echo !empty($admin->address) ? html_escape($admin->address) : 'Nobal Nagar, Ahmedabad, Gujarat, India'; ?></span>
                    </li>
                    <li>
                        <i class="fa-solid fa-phone"></i>
                        <span><?php echo !empty($admin->mobile) ? html_escape($admin->mobile) : '+91 9099780464'; ?></span>
                    </li>
                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        <span><?php echo !empty($admin->email) ? html_escape($admin->email) : 'support@kreditmitraa.com'; ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Kreditmitraa. All rights reserved. Registered NBFC partner platform.</p>
            <p>Designed with ❤️ for premium performance.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Navbar Scrolled Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile Menu Toggle
        const navToggle = document.getElementById('navToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const toggleIcon = document.getElementById('toggleIcon');

        navToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('open');
            toggleIcon.className = mobileMenu.classList.contains('open') ?
                'fa-solid fa-xmark' :
                'fa-solid fa-bars';
        });

        document.querySelectorAll('.mob-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                toggleIcon.className = 'fa-solid fa-bars';
            });
        });

        // Scroll reveal animations
        const revealTargets = document.querySelectorAll('.reveal, .reveal-stagger');
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                        io.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15
            });
            revealTargets.forEach(el => io.observe(el));
        } else {
            revealTargets.forEach(el => el.classList.add('in-view'));
        }

        // Loan Calculator Script
        const amountSlider = document.getElementById('amountSlider');
        const tenureSlider = document.getElementById('tenureSlider');
        const amountValue = document.getElementById('amountValue');
        const tenureValue = document.getElementById('tenureValue');

        const emiValue = document.getElementById('emiValue');
        const summaryInterest = document.getElementById('summaryInterest');
        const summaryFee = document.getElementById('summaryFee');
        const summaryTotal = document.getElementById('summaryTotal');

        function formatCurrency(val) {
            return 'INR ' + Number(val).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function calculateLoan() {
            const amount = parseFloat(amountSlider.value);
            const tenure = parseInt(tenureSlider.value);

            amountValue.textContent = 'INR ' + Number(amount).toLocaleString('en-IN');
            tenureValue.textContent = tenure + (tenure === 1 ? ' Day' : ' Days');

            const monthlyRate = 1.5; // 1.5% Per month
            const dailyRate = (monthlyRate / 30) / 100;
            const interest = amount * dailyRate * tenure;
            const feeRate = 0.02; // 2% processing fee
            const fee = Math.max(100, amount * feeRate); // min 100

            const totalRepay = amount + interest + fee;

            const weeks = Math.max(1, Math.ceil(tenure / 7));
            const weeklyPayment = totalRepay / weeks;

            emiValue.textContent = formatCurrency(weeklyPayment);
            summaryInterest.textContent = (monthlyRate).toFixed(1) + '%';
            summaryFee.textContent = formatCurrency(fee);
            summaryTotal.textContent = formatCurrency(totalRepay);
        }

        amountSlider.addEventListener('input', calculateLoan);
        tenureSlider.addEventListener('input', calculateLoan);

        calculateLoan();
    </script>
</body>

</html>