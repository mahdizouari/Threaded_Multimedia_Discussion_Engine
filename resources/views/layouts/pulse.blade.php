<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pulse - The Modern Multimedia Forum</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/pulse_logo.png') }}">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* CSS Variables for Light Theme */
        :root {
            --bg-dark: #f8fafc;
            --bg-glass: rgba(255, 255, 255, 0.7);
            --bg-glass-hover: rgba(0, 0, 0, 0.04);
            --border-glass: rgba(0, 0, 0, 0.08);

            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;

            --accent-primary: #7c3aed;
            /* Vibrant Purple */
            --accent-secondary: #db2777;
            /* Vibrant Pink */
            --accent-gradient: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-pill: 9999px;

            --shadow-glow: 0 8px 25px rgba(124, 58, 237, 0.2);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            background-image:
                radial-gradient(circle at 15% 50%, rgba(124, 58, 237, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(219, 39, 119, 0.08) 0%, transparent 50%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        button,
        input {
            border: none;
            outline: none;
            background: none;
            font-family: inherit;
        }

        button {
            cursor: pointer;
        }

        /* Utility Classes */
        .glass-panel {
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-glass);
        }

        /* Navbar */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            height: 72px;
            border-bottom: 1px solid var(--border-glass);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 16px;
            width: 250px;
        }

        .menu-btn {
            color: var(--text-secondary);
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            transition: var(--transition);
        }

        .menu-btn:hover {
            background: var(--bg-glass-hover);
            color: var(--text-primary);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 22px;
            letter-spacing: -0.5px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(124, 58, 237, 0.2));
            transition: var(--transition);
        }

        .logo:hover img {
            transform: scale(1.05) rotate(-2deg);
        }

        .nav-center {
            flex: 1;
            max-w: 600px;
            display: flex;
            justify-content: center;
        }

        .search-bar {
            width: 100%;
            max-width: 600px;
            height: 44px;
            border-radius: var(--radius-pill);
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 12px;
            transition: var(--transition);
            border: 1px solid var(--border-glass);
            background: rgba(0, 0, 0, 0.02);
        }

        .search-bar:hover,
        .search-bar:focus-within {
            background: var(--bg-glass-hover);
            border-color: rgba(0, 0, 0, 0.12);
        }

        .search-bar input {
            flex: 1;
            color: var(--text-primary);
            font-size: 15px;
        }

        .search-bar input::placeholder {
            color: var(--text-muted);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
            width: 250px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border-radius: var(--radius-pill);
            font-size: 14px;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-outline {
            border: 1px solid rgba(0, 0, 0, 0.1);
            color: var(--text-primary);
        }

        .btn-outline:hover {
            background: var(--bg-glass-hover);
            border-color: rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.2);
        }

        .btn-primary:hover {
            box-shadow: var(--shadow-glow);
            transform: translateY(-1px);
        }

        /* Layout */
        .layout {
            display: flex;
            flex: 1;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
        }

        /* Left Sidebar */
        .sidebar {
            width: 280px;
            flex-shrink: 0;
            height: calc(100vh - 72px);
            position: sticky;
            top: 72px;
            border-right: 1px solid var(--border-glass);
            background: var(--bg-glass);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow: visible;
        }

        .sidebar-inner {
            flex: 1;
            padding: 24px 16px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
            scrollbar-width: thin;
        }

        .sidebar.collapsed {
            margin-left: -280px;
        }

        .sidebar.collapsed .sidebar-inner {
            opacity: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .sidebar-toggle {
            position: absolute;
            left: 100%;
            top: 24px;
            width: 34px;
            height: 34px;
            transform: translateX(-50%);
            background: white;
            border: 1px solid var(--border-glass);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            color: var(--text-secondary);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 110;
        }

        .sidebar.collapsed .sidebar-toggle {
            transform: translateX(0) rotate(180deg);
            border-radius: 0 12px 12px 0;
            border-left: transparent;
        }

        .sidebar.collapsed .sidebar-toggle::after {
            display: none !important;
        }

        .sidebar-toggle:hover {
            color: var(--accent-primary);
            background: #f8fafc;
        }

        .sidebar-toggle::after {
            content: "Collapse Sidebar";
            position: absolute;
            left: 40px;
            top: 50%;
            transform: translateY(-50%) scale(0.9);
            background: #1a1a1b;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s ease;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar-toggle:hover::after {
            opacity: 1;
            transform: translateY(-50%) scale(1);
        }

        .nav-group {
            margin-bottom: 24px;
        }

        .nav-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 12px;
            padding: 0 16px;
            font-weight: 700;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .nav-title {
            opacity: 0;
            pointer-events: none;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            font-size: 15px;
            font-weight: 500;
            transition: var(--transition);
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
        }

        .nav-item:hover,
        .nav-item.active {
            background: var(--bg-glass-hover);
            color: var(--text-primary);
        }

        .nav-item.active {
            background: rgba(124, 58, 237, 0.1);
            color: var(--accent-primary);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .nav-icon svg {
            width: 100%;
            height: 100%;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .nav-text {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 32px;
            max-width: 1050px;
            min-width: 0;
            /* Critical for preventing flex blowout */
            transition: max-width 0.4s ease;
        }

        /* Trending section */
        .trending {
            margin-bottom: 40px;
        }

        /* Trending Section (Reddit Style) */
        .trending-cards-reddit {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 16px;
            scrollbar-width: none;
        }

        .trending-cards-reddit::-webkit-scrollbar {
            display: none;
        }

        .trending-card-reddit {
            min-width: 260px;
            width: 260px;
            height: 180px;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .trending-card-reddit .bg-image {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: transform 0.3s ease;
        }

        .trending-card-reddit:hover .bg-image {
            transform: scale(1.05);
        }

        .trending-card-reddit .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.4) 60%, rgba(0, 0, 0, 0.1) 100%);
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .trending-card-reddit .title {
            color: white;
            font-size: 17px;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        }

        /* Carousel System */
        .trending-carousel-wrapper {
            position: relative;
            padding: 0 40px;
        }

        .trending-viewport {
            overflow: hidden;
            border-radius: 20px;
        }

        .trending-track {
            display: flex;
            gap: 20px;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .trending-card-reddit {
            flex: 0 0 calc((100% - 40px) / 3);
            /* 3 visible on desktop */
            height: 200px;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 1200px) {
            .trending-card-reddit {
                flex: 0 0 calc((100% - 20px) / 2);
            }

            /* 2 visible */
        }

        @media (max-width: 768px) {
            .trending-card-reddit {
                flex: 0 0 100%;
            }

            /* 1 visible */
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .carousel-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-btn.prev {
            left: -10px;
        }

        .carousel-btn.next {
            right: -10px;
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background: var(--accent-primary);
            width: 24px;
            border-radius: 10px;
        }

        .trending-card-reddit .desc {
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .trending-card-reddit .footer {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: white;
            font-weight: 600;
        }

        .trending-card-reddit .footer img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            object-fit: cover;
        }

        .trending-meta {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            /* Always white inside overlay */
        }

        .trending-meta img {
            width: 16px;
            height: 16px;
            border-radius: 50%;
        }

        /* Feed Controls */
        .feed-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border-glass);
            padding-bottom: 16px;
        }

        .feed-tab {
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            font-size: 14px;
            font-weight: 600;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .feed-tab:hover {
            background: var(--bg-glass-hover);
            color: var(--text-primary);
        }

        .feed-tab.active {
            background: rgba(0, 0, 0, 0.06);
            color: var(--text-primary);
        }

        /* Feed Container (List vs Grid) */
        .feed-container {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .feed-container.grid-view {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            align-items: stretch;
        }

        /* Posts */
        .post {
            background: var(--bg-glass);
            border-radius: 20px;
            border: 1px solid var(--border-glass);
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .feed-container.grid-view .post {
            margin-bottom: 0;
            height: 100%;
        }

        .feed-container.grid-view .post-content {
            flex: 1;
            /* Pusher for footer */
        }

        .feed-container.grid-view .post-media {
            width: 100%;
            margin: 4px 0 12px 0;
            max-height: 200px;
            border-radius: var(--radius-sm);
        }

        .feed-container.grid-view .post-media img {
            max-height: 200px;
            object-fit: cover;
        }

        .post:hover {
            border-color: rgba(124, 58, 237, 0.2);
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }

        .post-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .post-header-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .post-community {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: var(--text-primary);
            font-size: 13px;
        }

        .community-avatar {
            width: 24px;
            height: 24px;
            background: var(--accent-gradient);
            color: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .post-meta {
            color: var(--text-muted);
            font-size: 13px;
        }

        .post-title {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.3;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .post-content {
            font-size: 15px;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .post-media {
            width: 100%;
            margin: 12px 0;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-glass);
            overflow: hidden;
            background: rgba(0, 0, 0, 0.02);
            max-height: 512px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .post-media img {
            width: 100%;
            height: auto;
            max-height: 512px;
            object-fit: cover;
        }

        .post-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 4px;
        }

        .action-group {
            display: flex;
            align-items: center;
            background: rgba(0, 0, 0, 0.04);
            border-radius: var(--radius-pill);
            padding: 2px;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--text-secondary);
            transition: var(--transition);
        }

        .action-btn:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-primary);
        }

        .action-btn.like-btn:hover {
            color: #22c55e;
            background: rgba(34, 197, 94, 0.12);
        }

        .action-btn.like-btn.active {
            color: #22c55e;
            background: rgba(34, 197, 94, 0.15);
        }

        .action-btn.like-btn.active svg {
            fill: #22c55e;
        }

        .action-btn.dislike-btn:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.12);
        }

        .action-btn.dislike-btn.active {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.15);
        }

        .action-btn.dislike-btn.active svg {
            fill: #ef4444;
        }

        .vote-count {
            font-size: 13px;
            font-weight: 800;
            padding: 0 8px;
            color: var(--text-primary);
        }

        .interaction-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            background: rgba(0, 0, 0, 0.04);
            font-size: 13px;
            font-weight: 700;
            color: var(--text-secondary);
            transition: var(--transition);
        }

        .interaction-btn:hover {
            background: rgba(0, 0, 0, 0.08);
            color: var(--text-primary);
        }

        .interaction-btn svg {
            width: 18px;
            height: 18px;
            stroke-width: 2.5;
        }

        /* Right Sidebar */
        .right-sidebar {
            width: 320px;
            padding: 32px 24px;
        }

        .widget {
            background: var(--bg-glass);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-glass);
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            /* Slight shadow */
        }

        .widget-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }

        .community-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-glass);
        }

        .community-list li:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .community-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .community-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            color: white;
        }

        .community-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .community-name {
            font-size: 14px;
            font-weight: 600;
        }

        .community-members {
            font-size: 12px;
            color: var(--text-muted);
        }

        .join-btn {
            padding: 6px 12px;
            border-radius: var(--radius-pill);
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-primary);
            font-size: 12px;
            font-weight: 600;
            transition: var(--transition);
        }

        .join-btn:hover {
            background: var(--accent-primary);
            color: white;
        }

        /* Modal System */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 550px;
            padding: 32px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
            transform: translateY(20px);
            transition: var(--transition);
            border: 1px solid var(--border-glass);
        }

        .modal-overlay.active .modal-card {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .modal-close {
            color: var(--text-muted);
            cursor: pointer;
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--text-primary);
        }

        /* User Widget */
        .user-widget {
            background: var(--accent-gradient);
            color: white;
            padding: 24px;
            border-radius: var(--radius-lg);
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(124, 58, 237, 0.2);
        }

        .user-widget::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .user-widget-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .user-widget-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
        }

        .user-widget-name {
            font-weight: 700;
            font-size: 16px;
            display: block;
        }

        .user-widget-handle {
            font-size: 13px;
            opacity: 0.8;
            display: block;
        }

        .user-widget-stats {
            display: flex;
            gap: 16px;
            font-size: 12px;
            margin-top: 10px;
        }

        .stat-btn {
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            color: white;
            font-weight: 600;
            transition: var(--transition);
            width: 100%;
            text-align: center;
        }

        .stat-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Post Media */
        .post-media {
            margin: 16px 0;
            background: rgba(0, 0, 0, 0.02);
            display: flex;
            justify-content: center;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-glass);
            max-height: 512px;
            overflow: hidden;
        }

        .post-media img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        #image-preview-container {
            display: none;
            margin-top: 12px;
            position: relative;
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--border-glass);
        }

        #image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .remove-preview {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            backdrop-filter: blur(4px);
        }

        /* Dashboard & Admin Components (ISO 9241-12 & Modern Style) */
        .page-header {
            padding: 40px;
            border-radius: var(--radius-lg);
            margin-bottom: 32px;
            background: var(--accent-gradient);
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .page-header::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            filter: blur(40px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .pulse-card {
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-lg);
            padding: 24px;
            transition: var(--transition);
        }

        .pulse-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-glow);
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-glass);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .stat-label {
            font-size: 11px;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-primary);
        }

        .stat-footer {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-secondary);
            opacity: 0.7;
        }

        .pulse-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .pulse-table th {
            padding: 20px 24px;
            font-size: 11px;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--border-glass);
        }

        .pulse-table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-glass);
            vertical-align: middle;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: var(--radius-pill);
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge.primary {
            background: rgba(124, 58, 237, 0.1);
            color: var(--accent-primary);
            border: 1px solid rgba(124, 58, 237, 0.2);
        }

        .badge.danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge.success {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .badge.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .btn-pill {
            padding: 10px 20px;
            border-radius: var(--radius-pill);
            font-size: 13px;
            font-weight: 700;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-pill.primary {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.2);
        }

        .btn-pill.primary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-glow);
        }

        .btn-pill.danger {
            background: #ef4444;
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .btn-pill.danger:hover {
            transform: translateY(-1px);
            background: #dc2626;
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
        }

        .btn-pill.ghost {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-secondary);
        }

        .btn-pill.ghost:hover {
            background: rgba(0, 0, 0, 0.1);
            color: var(--text-primary);
        }

        .data-list-item {
            padding: 24px;
            border-radius: var(--radius-md);
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            transition: var(--transition);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .data-list-item:hover {
            transform: translateX(8px);
            border-color: var(--accent-primary);
        }

        /* Premium Modal System */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(12px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            transition: var(--transition);
            animation: fadeIn 0.3s ease;
        }

        .modal-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: var(--radius-lg);
            width: 95%;
            max-width: 420px;
            margin: 15px;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.25);
            animation: slideUp 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .modal-header {
            padding: 16px 24px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar glass-panel">
        <div class="nav-left">
            <a href="/" class="logo">
                <img src="/images/pulse_logo.png" alt="Pulse Logo"
                    onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIzMiIgdmlld0JveD0iMCAwIDMyIDMyIiBmaWxsPSJub25lIj48Y2lyY2xlIGN4PSIxNiIgY3k9IjE2IiByPSIxNiIgZmlsbD0idXJsKCNncmFkKSIvPjxwYXRoIGQ9Ik0xMCAxN0wxNSAyMkwyMiAxMCIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIzIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz48ZGVmcz48bGluZWFyR3JhZGllbnQgaWQ9ImdyYWQiIHgxPSIwIiB5MT0iMCIgeDI9IjMyIiB5Mj0iMzIiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj48c3RvcCBzdG9wLWNvbG9yPSIjOGI1Y2Y2Ii8+PHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjZWM0ODk5Ii8+PC9saW5lYXJHcmFkaWVudD48L2RlZnM+PC9zdmc+'" />
                Pulse
            </a>
        </div>

        <form action="{{ route('home') }}" method="GET" class="nav-center"
            style="flex: 1; max-width: 600px; padding: 0 40px; position: relative;">
            <div class="search-container" style="width: 100%; position: relative; display: flex; align-items: center;">
                <svg style="position: absolute; left: 16px; color: var(--text-muted); pointer-events: none;" width="18"
                    height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search Pulse for posts, ideas, or results..."
                    style="width: 100%; height: 44px; padding: 0 16px 0 48px; background: rgba(0,0,0,0.03); border: 1.5px solid var(--border-glass); border-radius: var(--radius-pill); font-size: 14px; font-weight: 500; color: var(--text-primary); transition: all 0.3s ease; outline: none; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);"
                    onfocus="this.style.background='white'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 4px rgba(124, 58, 237, 0.1)';"
                    onblur="this.style.background='rgba(0,0,0,0.03)'; this.style.borderColor='var(--border-glass)'; this.style.boxShadow='inset 0 2px 4px rgba(0,0,0,0.02)';">
                @if(request('search'))
                    <a href="{{ route('home') }}"
                        style="position: absolute; right: 12px; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.05); border-radius: 50%; color: var(--text-muted); cursor: pointer; transition: all 0.2s ease;"
                        onmouseover="this.style.background='rgba(0,0,0,0.1)'"
                        onmouseout="this.style.background='rgba(0,0,0,0.05)'" title="Clear search">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        <style>
            .explore-pill:hover {
                background: var(--bg-glass-hover) !important;
                color: var(--text-primary) !important;
                transform: translateY(-1px);
            }
        </style>
        </div>

        <div class="nav-right" style="position: relative;">
            @auth
                <div class="user-dropdown"
                    onclick="const m = document.getElementById('userMenu'); m.style.display = m.style.display === 'none' ? 'block' : 'none';"
                    style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 6px 12px; border-radius: var(--radius-pill); transition: background 0.2s;"
                    onmouseover="this.style.background='rgba(0,0,0,0.03)'" onmouseout="this.style.background=''">
                    <img src="{{ Auth::user()->profile && Auth::user()->profile->avatar_path ? asset('storage/' . Auth::user()->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . (Auth::user()->username) }}"
                        alt="avatar"
                        style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div style="display: flex; flex-direction: column;">
                        <span
                            style="font-size: 14px; font-weight: 800; color: var(--text-primary); line-height: 1.2;">u/{{ Auth::user()->username }}</span>
                        <span
                            style="font-size: 11px; color: var(--accent-primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">{{ strtoupper(Auth::user()->role) }}</span>
                    </div>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        style="width: 14px; height: 14px; color: var(--text-muted); margin-left: 4px;">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>

                <div id="userMenu"
                    style="display: none; position: absolute; top: calc(100% + 12px); right: 0; width: 220px; background: white; border-radius: var(--radius-md); box-shadow: 0 10px 40px rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.05); padding: 8px; z-index: 100;">
                    @if(auth()->user()->role !== 'user')
                        <a href="{{ route('dashboard') }}"
                            style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: var(--accent-primary); text-decoration: none; font-size: 13px; font-weight: 700; border-radius: 8px; transition: background 0.2s;"
                            onmouseover="this.style.background='rgba(124, 58, 237, 0.05)'"
                            onmouseout="this.style.background=''">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            Staff Dashboard
                        </a>
                        <div style="height: 1px; background: rgba(0,0,0,0.05); margin: 4px 0;"></div>
                    @endif

                    <a href="{{ route('profile.edit') }}"
                        style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: var(--text-primary); text-decoration: none; font-size: 13px; font-weight: 600; border-radius: 8px; transition: background 0.2s;"
                        onmouseover="this.style.background='rgba(0,0,0,0.03)'" onmouseout="this.style.background=''">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Edit Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit"
                            style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #ef4444; background: none; border: none; font-size: 13px; font-weight: 600; text-align: left; border-radius: 8px; cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.background='rgba(239, 68, 68, 0.05)'"
                            onmouseout="this.style.background=''">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('register') }}" class="btn btn-outline">Sign Up</a>
                <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
            @endauth
        </div>
    </nav>

    {{-- ====================================================
         PERFECT VISION NOTIFICATION SYSTEM
         ISO 9241 : Feedback ergonomique & Contrôlabilité
    ===================================================== --}}
    <div id="pulse-toast-container" style="position: fixed; top: 24px; right: 24px; z-index: 10001; display: flex; flex-direction: column; gap: 12px; pointer-events: none;">
        @if(session('success') || session('error'))
            <div class="toast-notification {{ session('error') ? 'error' : 'success' }}" id="toast-message" style="pointer-events: auto;">
                <div class="toast-icon">
                    @if(session('success'))
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    @else
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    @endif
                </div>
                <div class="toast-content">
                    <div class="toast-title">Pulse System</div>
                    <div class="toast-desc">{{ session('success') ?? session('error') }}</div>
                </div>
                <button class="toast-close" onclick="pulseClearToast(this.parentElement)" title="Dismiss">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <div class="toast-progress"></div>
            </div>
        @endif
    </div>

    <style>
        .toast-notification {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            padding: 18px 24px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.08),
                inset 0 0 0 1.5px rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 16px;
            width: max-content;
            min-width: 340px;
            max-width: 460px;
            position: relative;
            overflow: hidden;
            animation: pulseToastBounce 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            transition: all 0.4s ease;
        }

        .toast-notification.success {
            border-bottom: 3px solid #22c55e;
            background: rgba(240, 253, 244, 0.45);
        }

        .toast-notification.error {
            border-bottom: 3px solid #ef4444;
            background: rgba(254, 242, 242, 0.45);
        }

        .toast-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: white;
        }

        .success .toast-icon {
            background: var(--accent-gradient);
            box-shadow: 0 8px 16px rgba(124, 58, 237, 0.2);
        }

        .error .toast-icon {
            background: linear-gradient(135deg, #ef4444, #b91c1c);
            box-shadow: 0 8px 16px rgba(239, 68, 68, 0.2);
        }

        .toast-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .toast-title {
            font-weight: 900;
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .toast-desc {
            font-weight: 700;
            font-size: 15px;
            color: var(--text-primary);
            line-height: 1.4;
        }

        .toast-close {
            background: rgba(0, 0, 0, 0.03);
            border: none;
            color: var(--text-muted);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-left: auto;
            transition: all 0.3s;
        }

        .toast-close:hover {
            background: var(--accent-gradient);
            color: white;
            transform: rotate(90deg) scale(1.1);
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: rgba(0, 0, 0, 0.05);
        }

        .toast-progress::after {
            content: '';
            position: absolute;
            inset: 0;
            background: currentColor;
            transform-origin: left;
            animation: pulseToastTimer 5s linear forwards;
        }

        .toast-notification:hover .toast-progress::after {
            animation-play-state: paused;
        }

        .success .toast-progress::after { color: #22c55e; }
        .error .toast-progress::after { color: #ef4444; }

        @keyframes pulseToastBounce {
            0% { opacity: 0; transform: translateX(60px) scale(0.8); }
            70% { transform: translateX(-10px) scale(1.02); }
            100% { opacity: 1; transform: translateX(0) scale(1); }
        }

        @keyframes pulseToastOut {
            to { opacity: 0; transform: translateX(40px) scale(0.9); filter: blur(10px); }
        }

        @keyframes pulseToastTimer {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }
    </style>

    <script>
        function pulseClearToast(el) {
            el.style.animation = 'pulseToastOut 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards';
            setTimeout(() => el.remove(), 400);
        }

        // Auto-dismiss with "Pause on Hover" logic
        document.querySelectorAll('.toast-notification').forEach(toast => {
            let timeout;
            let startTime = Date.now();
            let timeLeft = 5000;

            function startTimer() {
                timeout = setTimeout(() => pulseClearToast(toast), timeLeft);
                startTime = Date.now();
            }

            toast.addEventListener('mouseenter', () => {
                clearTimeout(timeout);
                timeLeft -= Date.now() - startTime;
            });

            toast.addEventListener('mouseleave', () => {
                if (timeLeft > 0) startTimer();
            });

            startTimer();
        });
    </script>

    @auth
        @php $unreadCount = auth()->user()->receivedMessages()->unread()->count(); @endphp
        @if($unreadCount > 0)
            <div class="message-banner" id="messageBanner"
                style="background: var(--accent-gradient); color: white; padding: 10px; text-align: center; font-size: 14px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; transition: all 0.3s ease;"
                onclick="handleBannerClick()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                You have {{ $unreadCount }} new chat{{ $unreadCount > 1 ? 's' : '' }}!
                @if(Auth::user()->role === 'user')
                    <a href="{{ route('messages.index') }}"
                        style="padding: 10px 24px; background: rgba(0,0,0,0.05); color: var(--text-primary); border-radius: var(--radius-pill); font-size: 14px; font-weight: 700; text-decoration: none;"
                        onclick="event.stopPropagation()">View my chats</a>
                @endif
            </div>
            <style>
                @keyframes slideDown {
                    from {
                        transform: translateY(-100%);
                    }

                    to {
                        transform: translateY(0);
                    }
                }
            </style>
        @endif
    @endauth

    <style>
        @keyframes pulseSlideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
    <script>setTimeout(() => { const msg = document.getElementById('flash-message'); if (msg) { msg.style.opacity = '0'; msg.style.transition = '0.3s'; setTimeout(() => msg.remove(), 300); } }, 4000);</script>

    <!-- App Layout -->
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>

            <div class="sidebar-inner">
                <div class="nav-group">
                    <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                        <div class="nav-icon"><svg viewBox="0 0 24 24">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg></div>
                        <span class="nav-text">Home</span>
                    </a>

                    @auth
                        <a href="{{ route('messages.index') }}"
                            class="nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}"
                            style="position: relative;">
                            <div class="nav-icon"><svg viewBox="0 0 24 24">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg></div>
                            <span class="nav-text">Chats</span>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <span
                                    style="position: absolute; left: 32px; top: 8px; width: 10px; height: 10px; background: #ef4444; border: 2px solid white; border-radius: 50%; box-shadow: 0 0 10px rgba(239, 68, 68, 0.4);"></span>
                            @endif
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <div class="nav-icon"><svg viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg></div>
                            <span class="nav-text">Profile</span>
                        </a>
                    @endauth
                </div>

                @auth
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'moderator')
                        <div class="nav-group"
                            style="margin-top: 8px; padding-top: 16px; border-top: 1px solid var(--border-glass);">
                            <div class="nav-title"
                                style="color: var(--accent-primary); font-weight: 800; letter-spacing: 0.5px; margin-bottom: 16px;">
                                STAFF MANAGEMENT</div>

                            <a href="{{ route('dashboard') }}"
                                class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                style="background: rgba(124, 58, 237, 0.03); margin-bottom: 8px; border: 1px solid rgba(124, 58, 237, 0.1);">
                                <div class="nav-icon" style="color: var(--accent-primary);"><svg width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <rect x="3" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="14" width="7" height="7"></rect>
                                        <rect x="3" y="14" width="7" height="7"></rect>
                                    </svg></div>
                                <span class="nav-text" style="font-weight: 700;">Admin Dashboard</span>
                            </a>

                            <a href="{{ route('admin.approvals') }}"
                                class="nav-item {{ request()->routeIs('admin.approvals') ? 'active' : '' }}">
                                <div class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    </svg></div>
                                <span class="nav-text">Approvals</span>
                            </a>

                            <a href="{{ route('admin.reports') }}"
                                class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                                <div class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path
                                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                        </path>
                                        <line x1="12" y1="9" x2="12" y2="13"></line>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg></div>
                                <span class="nav-text">Reports</span>
                            </a>

                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('categories.index') }}"
                                    class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                    <div class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg></div>
                                    <span class="nav-text">Categories</span>
                                </a>

                                <a href="{{ route('admin.team') }}"
                                    class="nav-item {{ request()->routeIs('admin.team') ? 'active' : '' }}">
                                    <div class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg></div>
                                    <span class="nav-text">Team Hub</span>
                                </a>
                            @endif
                        </div>
                    @endif
                @endauth

                <div class="nav-group" style="margin-top: 16px;">
                    <div class="nav-title">Explore</div>
                    @foreach($nav_categories as $cat)
                        @php
                            $isActive = false;
                            $currentCatId = is_object(request('category')) ? request('category')->id : request('category');
                            if ($currentCatId == $cat->id) {
                                $isActive = true;
                            } elseif (request('sort') === 'following' && Auth::check() && Auth::user()->interests->contains('id', $cat->id)) {
                                $isActive = true;
                            }
                        @endphp
                        <a href="{{ route('home', ['category' => $cat->id]) }}"
                            class="nav-item {{ $isActive ? 'active' : '' }}">
                            <div class="nav-icon" style="color: var(--accent-primary); opacity: 0.8;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                </svg>
                            </div>
                            <span class="nav-text">{{ $cat->label }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Main Feed -->
        <main class="main-content">


            @yield('content')


        </main>

        <!-- Right Sidebar -->
        <aside class="right-sidebar">
            <div class="sidebar-sticky"
                style="position: sticky; top: 104px; display: flex; flex-direction: column; gap: 24px; height: calc(100vh - 120px); overflow-y: auto; padding-right: 8px;">

                <!-- Top Creators Widget -->
                <div class="pulse-widget"
                    style="background: white; border-radius: var(--radius-lg); padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid var(--border-glass);">
                    <div
                        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                        <h3
                            style="font-size: 13px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
                            Top Creators</h3>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2.5" style="color: var(--accent-primary);">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @auth
                            @php $topUsers = \App\Models\User::where('id', '!=', auth()->id())->withCount('posts')->orderByDesc('posts_count')->take(3)->get(); @endphp
                        @else
                            @php $topUsers = \App\Models\User::withCount('posts')->orderByDesc('posts_count')->take(3)->get(); @endphp
                        @endauth

                        @foreach($topUsers as $topUser)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 6px; border-radius: 12px; transition: background 0.2s;"
                                onmouseover="this.style.background='rgba(0,0,0,0.02)'"
                                onmouseout="this.style.background=''">
                                <a href="{{ route('users.show', $topUser->username) }}"
                                    style="display: flex; align-items: center; gap: 12px; text-decoration: none; flex: 1; min-width: 0;">
                                    <div style="position: relative; flex-shrink: 0;">
                                        <img src="{{ $topUser->profile && $topUser->profile->avatar_path ? asset('storage/' . $topUser->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($topUser->username ?? $topUser->name) }}"
                                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                        <div
                                            style="position: absolute; bottom: 0; right: 0; width: 12px; height: 12px; background: #22c55e; border: 2px solid white; border-radius: 50%;">
                                        </div>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div
                                            style="font-size: 13.5px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $topUser->username }}</div>
                                        <div style="font-size: 11px; font-weight: 500; color: var(--text-muted);">
                                            {{ $topUser->posts_count }} posts</div>
                                    </div>
                                </a>
                                @auth
                                    @if(auth()->id() !== $topUser->id)
                                        <a href="{{ route('messages.show', $topUser->id) }}"
                                            title="Send a chat"
                                            style="background: rgba(124, 58, 237, 0.08); color: var(--accent-primary); padding: 8px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; text-decoration: none; flex-shrink: 0; margin-left: 8px;"
                                            onmouseover="this.style.background='var(--accent-gradient)'; this.style.color='white'; this.style.boxShadow='0 4px 10px rgba(124,58,237,0.2)';"
                                            onmouseout="this.style.background='rgba(124, 58, 237, 0.08)'; this.style.color='var(--accent-primary)'; this.style.boxShadow='none';">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    </div>
                </div>

                @auth
                    <!-- Aside Integrated Interactive Chat Widget -->
                    <div class="pulse-widget" id="aside-messenger"
                        style="background: white; border-radius: var(--radius-lg); box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid var(--border-glass); display: flex; flex-direction: column; overflow: hidden; margin-top: auto; min-height: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">

                        <!-- App Header -->
                        <div onclick="toggleAsideMessenger()"
                        <div onclick="toggleAsideMessenger()"
                            style="padding: 16px 20px; border-bottom: 1px solid var(--border-glass); background: white; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.background='rgba(124, 58, 237, 0.04)'"
                            onmouseout="this.style.background='white'">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div
                                    style="width: 36px; height: 36px; border-radius: 50%; background: var(--accent-gradient); color: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(124, 58, 237, 0.2);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3
                                        style="font-size: 14.5px; font-weight: 800; color: var(--text-primary); margin: 0; letter-spacing: -0.2px;">
                                        Chats</h3>
                                    <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;">Chat Live
                                    </div>
                                </div>
                            </div>
                            <svg id="messenger-icon-chevron" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5"
                                style="color: var(--text-muted); transition: transform 0.3s;">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>

                        <!-- App Body (Hidden by default) -->
                        <div id="messenger-body"
                            style="display: none; flex-direction: column; flex: 1; max-height: 400px; min-height: 0;">

                            <!-- Search & Contact List View -->
                            <div id="messenger-view-contacts"
                                style="display: flex; flex-direction: column; flex: 1; min-height: 0; overflow: hidden;">
                                <div style="padding: 16px 20px; border-bottom: 1px solid var(--border-glass);">
                                    <div style="display: flex; align-items: center; gap: 10px; background: rgba(0,0,0,0.03); padding: 10px 14px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05); transition: background 0.2s, border-color 0.2s;"
                                        onfocusin="this.style.background='white'; this.style.borderColor='var(--accent-primary)'"
                                        onfocusout="this.style.background='rgba(0,0,0,0.03)'; this.style.borderColor='rgba(0,0,0,0.05)'">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5" style="color: var(--text-muted);">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                        <input type="text" id="aside-user-search" onkeyup="filterAsideUsers()"
                                            placeholder="Search..."
                                            style="border: none; background: transparent; outline: none; width: 100%; font-size: 14px; color: var(--text-primary);">
                                    </div>
                                </div>
                                <div style="overflow-y: auto; padding: 8px 0; flex: 1; min-height: 0;"
                                    class="sidebar-sticky">
                                    @php
                                        $recentIds = [];
                                        $recentConvos = collect();
                                        if (auth()->check()) {
                                            $userId = auth()->id();
                                            $recentConvos = \App\Models\Message::where('sender_id', $userId)
                                                ->orWhere('receiver_id', $userId)
                                                ->with(['sender', 'receiver'])
                                                ->latest()
                                                ->get()
                                                ->map(function ($msg) use ($userId) {
                                                    return $msg->sender_id === $userId ? $msg->receiver : $msg->sender;
                                                })
                                                ->unique('id')
                                                ->take(10);
                                            $recentIds = $recentConvos->pluck('id')->toArray();
                                        }
                                    @endphp

                                    <div id="messenger-empty-state"
                                        style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 13.5px; display: {{ $recentConvos->count() > 0 ? 'none' : 'block' }};">
                                        Search for someone by name.
                                    </div>

                                    @php
                                        $otherUsers = \App\Models\User::where('id', '!=', auth()->id())
                                            ->whereNotIn('id', $recentIds)
                                            ->get();

                                        $allUsers = $recentConvos->concat($otherUsers);
                                    @endphp

                                    @foreach($allUsers as $u)
                                        @php $isRecent = in_array($u->id, $recentIds); @endphp
                                        <div class="aside-contact-item" data-recent="{{ $isRecent ? 'true' : 'false' }}"
                                            onclick="openAsideChat({{ $u->id }}, '{{ addslashes($u->username) }}', '{{ $u->profile && $u->profile->avatar_path ? asset('storage/' . $u->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($u->username ?? $u->name) }}')"
                                            style="display: {{ $isRecent ? 'flex' : 'none' }}; align-items: center; gap: 14px; padding: 12px 20px; cursor: pointer; transition: all 0.2s;"
                                            onmouseover="this.style.background='rgba(124, 58, 237, 0.05)'; this.style.paddingLeft='24px';"
                                            onmouseout="this.style.background=''; this.style.paddingLeft='20px';">
                                            <div style="position: relative;">
                                                <img src="{{ $u->profile && $u->profile->avatar_path ? asset('storage/' . $u->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($u->username ?? $u->name) }}"
                                                    style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                                <div
                                                    style="position: absolute; bottom: 0; right: 0; width: 12px; height: 12px; background: #22c55e; border: 2px solid white; border-radius: 50%;">
                                                </div>
                                            </div>
                                            <div
                                                style="font-weight: 800; color: var(--text-primary); font-size: 13.5px; letter-spacing: -0.2px;">
                                                {{ $u->username }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Active Chat View -->
                            <div id="messenger-view-chat"
                                style="display: none; flex-direction: column; flex: 1; min-height: 0; overflow: hidden;">

                                <div
                                    style="padding: 16px 20px; background: white; border-bottom: 1px solid var(--border-glass); display: flex; align-items: center; gap: 14px;">
                                    <button onclick="closeAsideChat()"
                                        style="background: white; border: 1px solid var(--border-glass); box-shadow: 0 2px 4px rgba(0,0,0,0.02); color: var(--text-muted); cursor: pointer; padding: 6px; border-radius: 50%; display: flex; transition: all 0.2s;"
                                        onmouseover="this.style.background='var(--accent-gradient)'; this.style.color='white'; this.style.borderColor='transparent';"
                                        onmouseout="this.style.background='white'; this.style.color='var(--text-muted)'; this.style.borderColor='var(--border-glass)';">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <polyline points="15 18 9 12 15 6"></polyline>
                                        </svg>
                                    </button>
                                    <div style="position: relative;">
                                        <img id="active-chat-avatar" src=""
                                            style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                        <div
                                            style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #22c55e; border: 2px solid white; border-radius: 50%;">
                                        </div>
                                    </div>
                                    <div>
                                        <div id="active-chat-name"
                                            style="font-size: 14px; font-weight: 800; color: var(--text-primary); letter-spacing: -0.2px;">
                                            User</div>
                                        <div style="font-size: 11px; color: #16a34a; font-weight: 700;">Online</div>
                                    </div>
                                </div>

                                <div id="aside-chat-messages"
                                    style="padding: 16px; flex: 1; min-height: 0; overflow-y: auto; display: flex; flex-direction: column; gap: 16px; background: #fafafa;"
                                    class="sidebar-sticky">
                                    <!-- Status text -->
                                    <div
                                        style="text-align: center; color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">
                                        Starting conversation...</div>
                                </div>

                                <div
                                    style="padding: 16px 20px; border-top: 1px solid var(--border-glass); background: white;">
                                    <form id="aside-chat-form" onsubmit="sendAsideMessage(event)"
                                        style="display: flex; align-items: center; gap: 10px; background: rgba(0,0,0,0.03); padding: 8px 16px; border-radius: 24px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); border: 1px solid var(--border-glass);">
                                        <input type="text" id="aside-chat-input" placeholder="Send a message..."
                                            style="border: none; background: transparent; outline: none; flex: 1; font-size: 14px; color: var(--text-primary);"
                                            autocomplete="off" required>
                                        <button type="submit"
                                            style="background: var(--accent-gradient); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 10px rgba(124, 58, 237, 0.3); transition: transform 0.2s;"
                                            onmouseover="this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                                                stroke="currentColor" stroke-width="2.5">
                                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                    <script>
                        function toggleAsideMessenger() {
                            const widget = document.getElementById('aside-messenger');
                            const body = document.getElementById('messenger-body');
                            const icon = document.getElementById('messenger-icon-chevron');
                            if (body.style.display === 'none' || body.style.display === '') {
                                body.style.display = 'flex';
                                widget.style.flex = '1';
                                icon.style.transform = 'rotate(180deg)';
                                document.getElementById('aside-user-search').focus();
                            } else {
                                body.style.display = 'none';
                                widget.style.flex = 'none';
                                icon.style.transform = 'rotate(0deg)';
                                document.getElementById('aside-user-search').value = '';
                                filterAsideUsers();
                                closeAsideChat(); // reset view
                            }
                        }

                        function filterAsideUsers() {
                            const query = document.getElementById('aside-user-search').value.toLowerCase().trim();
                            const items = document.querySelectorAll('.aside-contact-item');
                            const emptyState = document.getElementById('messenger-empty-state');
                            const hasRecent = document.querySelectorAll('.aside-contact-item[data-recent="true"]').length > 0;

                            if (!query) {
                                items.forEach(item => {
                                    item.style.display = item.dataset.recent === 'true' ? 'flex' : 'none';
                                });
                                if (emptyState) {
                                    emptyState.style.display = hasRecent ? 'none' : 'block';
                                }
                                return;
                            }

                            if (emptyState) emptyState.style.display = 'none';

                            items.forEach(item => {
                                const name = item.innerText.trim().toLowerCase();
                                item.style.display = name.startsWith(query) ? 'flex' : 'none';
                            });
                        }

                        let currentAsideChatUserId = null;

                        function openAsideChat(id, name, avatar) {
                            currentAsideChatUserId = id;
                            document.getElementById('messenger-view-contacts').style.display = 'none';
                            document.getElementById('messenger-view-chat').style.display = 'flex';
                            document.getElementById('active-chat-name').innerText = name;
                            document.getElementById('active-chat-avatar').src = avatar;

                            // Clear mock messages
                            const msgBox = document.getElementById('aside-chat-messages');
                            msgBox.innerHTML = '<div style="text-align: center; color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Loading history...</div>';

                            setTimeout(() => document.getElementById('aside-chat-input').focus(), 100);

                            fetch('/api/messages/' + id)
                                .then(res => res.json())
                                .then(data => {
                                    msgBox.innerHTML = '';
                                    if (data.messages && data.messages.length > 0) {
                                        data.messages.forEach(msg => {
                                            const isMine = msg.sender_id === data.current_user_id;
                                            const align = isMine ? 'flex-end' : 'flex-start';
                                            const bg = isMine ? 'var(--accent-gradient)' : 'rgba(0,0,0,0.05)';
                                            const color = isMine ? 'white' : 'var(--text-primary)';
                                            const radius = isMine ? '16px 2px 16px 16px' : '2px 16px 16px 16px';

                                            let attachHtml = '';
                                            if (msg.file_path && msg.file_type && msg.file_type.startsWith('image/')) {
                                                attachHtml = `<img src="/storage/${msg.file_path}" style="width: 140px; height: 140px; object-fit: cover; border-radius: 12px; margin-bottom: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">`;
                                            }

                                            let bodyHtml = msg.body ? `<div style="background: ${bg}; padding: 8px 12px; border-radius: ${radius}; max-width: 85%; font-size: 13px; color: ${color}; box-shadow: 0 4px 10px rgba(0,0,0,0.05); word-break: break-word; overflow-wrap: break-word; white-space: pre-wrap; line-height: 1.4;">${msg.body}</div>` : '';

                                            if (attachHtml || bodyHtml) {
                                                const html = `
                                                <div style="display: flex; flex-direction: column; align-items: ${align}; margin-top: 8px;">
                                                    ${attachHtml}
                                                    ${bodyHtml}
                                                </div>`;
                                                msgBox.insertAdjacentHTML('beforeend', html);
                                            }
                                        });
                                    } else {
                                        msgBox.innerHTML = '<div style="text-align: center; color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Starting with ' + name + '...</div>';
                                    }
                                    msgBox.scrollTop = msgBox.scrollHeight;
                                })
                                .catch(err => {
                                    msgBox.innerHTML = '<div style="text-align: center; color: #ef4444; font-size: 11px; margin-bottom: 8px;">Loading error.</div>';
                                });
                        }

                        function closeAsideChat() {
                            currentAsideChatUserId = null;
                            document.getElementById('messenger-view-chat').style.display = 'none';
                            document.getElementById('messenger-view-contacts').style.display = 'flex';
                            document.getElementById('aside-chat-input').value = '';
                        }

                        function sendAsideMessage(e) {
                            e.preventDefault();
                            if (!currentAsideChatUserId) return;

                            const input = document.getElementById('aside-chat-input');
                            const msg = input.value.trim();
                            if (!msg) return;

                            const msgBox = document.getElementById('aside-chat-messages');

                            const bubbleHtml = `<div style="background: var(--accent-gradient); padding: 8px 12px; border-radius: 16px 2px 16px 16px; max-width: 85%; font-size: 13px; color: white; box-shadow: 0 4px 10px rgba(124, 58, 237, 0.2); word-break: break-word; overflow-wrap: break-word; white-space: pre-wrap; line-height: 1.4;">${msg}</div>`;

                            const html = `
                            <div style="display: flex; flex-direction: column; align-items: flex-end; margin-top: 8px;">
                                ${bubbleHtml}
                            </div>`;
                            msgBox.insertAdjacentHTML('beforeend', html);

                            input.value = '';
                            msgBox.scrollTop = msgBox.scrollHeight;

                            // AJAX Send to backend
                            const token = document.querySelector('meta[name="csrf-token"]');
                            if (token) {
                                fetch('{{ route('messages.store') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token.content,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        receiver_id: currentAsideChatUserId,
                                        body: msg
                                    })
                                }).then(res => {
                                    if (!res.ok) console.error("Message sending error.");
                                }).catch(err => console.error("Erreur réseau: ", err));
                            }
                        }
                    </script>

                    <!-- Custom Scrollbar style for aside -->
                    <style>
                        .sidebar-sticky::-webkit-scrollbar {
                            width: 4px;
                        }

                        .sidebar-sticky::-webkit-scrollbar-track {
                            background: transparent;
                        }

                        .sidebar-sticky::-webkit-scrollbar-thumb {
                            background: rgba(0, 0, 0, 0.1);
                            border-radius: 4px;
                        }
                    </style>
                @endauth
            </div>
        </aside>
    </div>

    <!-- Modals -->
    @auth
        @if(!request()->is('admin/*') && !request()->is('dashboard') && !request()->routeIs('categories.*'))
            <!-- Create Post Modal -->
            <div class="modal-overlay" id="modalPost">
                <div class="modal-card"
                    style="background: #ffffff; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 20px 40px rgba(0,0,0,0.1); padding: 0; overflow: hidden;">
                    <div class="modal-header"
                        style="background: rgba(124, 58, 237, 0.02); border-bottom: 1px solid rgba(124, 58, 237, 0.05); padding: 16px 24px;">
                        <h2 class="modal-title"
                            style="font-weight: 800; color: var(--text-primary); letter-spacing: -0.5px; font-size: 16px;">
                            Create a New Post</h2>
                        <button onclick="pulseCloseModal('modalPost')"
                            style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; transition: color 0.2s;"
                            onmouseover="this.style.color='#ef4444'"
                            onmouseout="this.style.color='var(--text-muted)'">&times;</button>
                    </div>
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="padding: 20px 24px;">
                            <!-- Category Selection -->
                            <div style="margin-bottom: 16px;">
                                <label
                                    style="display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; color: var(--accent-primary); margin-bottom: 6px; letter-spacing: 0.5px;">Select
                                    Community Hub</label>
                                <div class="custom-select-wrapper" style="position: relative;">
                                    @php $firstCat = $nav_categories->first(); @endphp
                                    <input type="hidden" name="category_id" id="category_id_input"
                                        value="{{ $firstCat ? $firstCat->id : '' }}">

                                    <div id="custom-select-trigger" onclick="toggleCustomSelect()"
                                        style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid rgba(124, 58, 237, 0.15); background: rgba(248, 250, 252, 0.8); cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: all 0.2s;">
                                        <span id="custom-select-text"
                                            style="color: var(--text-primary); font-weight: 800; font-size: 14px;">{{ $firstCat ? $firstCat->label : 'Select Community...' }}</span>
                                        <svg id="custom-select-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="var(--accent-primary)" stroke-width="2.5"
                                            style="transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </div>

                                    <div id="custom-select-options"
                                        style="position: absolute; top: calc(100% + 8px); left: 0; right: 0; background: #ffffff; border: 1px solid rgba(124, 58, 237, 0.1); border-radius: 12px; box-shadow: 0 12px 35px rgba(124, 58, 237, 0.15); opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1); z-index: 50; overflow: hidden; max-height: 220px; overflow-y: auto;">
                                        @foreach($nav_categories as $cat)
                                            <div class="custom-option"
                                                onclick="selectCategory('{{ $cat->id }}', '{{ $cat->label }}')"
                                                style="padding: 12px 16px; font-weight: 700; font-size: 13px; color: var(--text-primary); cursor: pointer; transition: all 0.2s cubic-bezier(0.2, 0.8, 0.2, 1); border-bottom: 1px solid rgba(0,0,0,0.02);"
                                                onmouseover="this.style.background='rgba(124, 58, 237, 0.05)'; this.style.color='var(--accent-primary)'; this.style.paddingLeft='24px'"
                                                onmouseout="this.style.background='transparent'; this.style.color='var(--text-primary)'; this.style.paddingLeft='16px'">
                                                {{ $cat->label }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <script>
                                    function toggleCustomSelect() {
                                        const options = document.getElementById('custom-select-options');
                                        const trigger = document.getElementById('custom-select-trigger');
                                        const icon = document.getElementById('custom-select-icon');

                                        if (options.style.visibility === 'hidden') {
                                            options.style.visibility = 'visible';
                                            options.style.opacity = '1';
                                            options.style.transform = 'translateY(0)';
                                            trigger.style.borderColor = 'var(--accent-primary)';
                                            trigger.style.background = '#ffffff';
                                            trigger.style.boxShadow = '0 4px 15px rgba(124, 58, 237, 0.1)';
                                            icon.style.transform = 'rotate(180deg)';
                                        } else {
                                            closeCustomSelect();
                                        }
                                    }

                                    function selectCategory(id, label) {
                                        document.getElementById('category_id_input').value = id;
                                        const textSpan = document.getElementById('custom-select-text');
                                        textSpan.innerText = label;
                                        closeCustomSelect();
                                    }

                                    function closeCustomSelect() {
                                        const options = document.getElementById('custom-select-options');
                                        const trigger = document.getElementById('custom-select-trigger');
                                        const icon = document.getElementById('custom-select-icon');

                                        if (options && options.style.visibility === 'visible') {
                                            options.style.visibility = 'hidden';
                                            options.style.opacity = '0';
                                            options.style.transform = 'translateY(-10px)';
                                            trigger.style.borderColor = 'rgba(124, 58, 237, 0.15)';
                                            trigger.style.background = 'rgba(248, 250, 252, 0.8)';
                                            trigger.style.boxShadow = 'none';
                                            icon.style.transform = 'rotate(0deg)';
                                        }
                                    }

                                    document.addEventListener('click', function (e) {
                                        const wrapper = document.querySelector('.custom-select-wrapper');
                                        if (wrapper && !wrapper.contains(e.target)) {
                                            closeCustomSelect();
                                        }
                                    });
                                </script>
                            </div>

                            <!-- Post Content Group -->
                            <div
                                style="background: rgba(248, 250, 252, 0.5); border: 1.5px solid rgba(124, 58, 237, 0.1); border-radius: 12px; padding: 10px; margin-bottom: 16px;">
                                <input type="text" name="title" placeholder="Post Title" required
                                    style="width: 100%; padding: 10px; border: none; background: transparent; font-size: 15px; font-weight: 800; color: var(--text-primary); outline: none; border-bottom: 1.5px solid rgba(124, 58, 237, 0.05); margin-bottom: 6px;"
                                    onfocus="this.parentElement.style.borderColor='var(--accent-primary)'; this.parentElement.style.background='#ffffff'; this.parentElement.style.boxShadow='0 10px 25px -5px rgba(124, 58, 237, 0.08)'"
                                    onblur="this.parentElement.style.borderColor='rgba(124, 58, 237, 0.1)'; this.parentElement.style.background='rgba(248, 250, 252, 0.5)'; this.parentElement.style.boxShadow='none'">

                                <textarea name="content" placeholder="Share your thoughts with the community..." required
                                    style="width: 100%; height: 110px; padding: 10px; border: none; background: transparent; font-size: 14px; font-weight: 500; color: var(--text-primary); outline: none; resize: none; line-height: 1.5;"></textarea>
                            </div>

                            <!-- Media Dropzone -->
                            <div class="media-dropzone"
                                style="border: 2px dashed rgba(124, 58, 237, 0.2); border-radius: 10px; padding: 18px; text-align: center; cursor: pointer; transition: all 0.2s; background: rgba(124, 58, 237, 0.01);"
                                onclick="document.getElementById('modal-file').click()"
                                onmouseover="this.style.background='rgba(124, 58, 237, 0.04)'; this.style.borderColor='var(--accent-primary)'"
                                onmouseout="this.style.background='rgba(124, 58, 237, 0.01)'; this.style.borderColor='rgba(124, 58, 237, 0.2)'">
                                <div
                                    style="background: #ffffff; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1);">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-primary)"
                                        stroke-width="2.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                </div>
                                <p style="font-size: 12px; font-weight: 800; color: var(--accent-primary); margin-bottom: 2px;">
                                    Add Media</p>
                                <p style="font-size: 10px; color: var(--text-muted); font-weight: 600;">Images or video</p>
                                <input type="file" id="modal-file" name="media" style="display: none;"
                                    onchange="previewImage(this)">
                            </div>

                            <div id="image-preview-container" style="margin-top: 12px;">
                                <img id="image-preview" src=""
                                    style="width: 100%; border-radius: 10px; display: none; object-fit: cover; max-height: 180px; border: 3px solid #ffffff; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                                <div class="remove-preview" onclick="removePreview()" style="display: none;">&times;</div>
                            </div>
                        </div>

                        <div class="modal-footer"
                            style="padding: 16px 24px; background: rgba(248, 250, 252, 0.8); border-top: 1px solid rgba(124, 58, 237, 0.05); display: flex; justify-content: flex-end; gap: 10px;">
                            <button type="button" class="btn-pill ghost" onclick="pulseCloseModal('modalPost')"
                                style="padding: 10px 20px; font-size: 13px;">Discard</button>
                            <button type="submit" class="btn-pill primary" style="padding: 10px 24px; font-size: 13px;">Post to
                                Community</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report Modal -->
            <div class="modal-overlay" id="modalReport">
                <div class="modal-card" style="max-width: 400px; text-align: center;">
                    <div class="modal-header" style="justify-content: center;">
                        <h3 class="modal-title">Report Content</h3>
                    </div>
                    <p style="color: var(--text-secondary); margin-bottom: 24px;">Please select the reason for reporting this
                        post.</p>
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px;">
                        <button class="btn btn-outline" style="justify-content: flex-start; padding: 12px 20px; width: 100%;">
                            <span style="display: flex; align-items: center; gap: 10px;">🚩 Spam or misleading</span>
                        </button>
                        <button class="btn btn-outline" style="justify-content: flex-start; padding: 12px 20px; width: 100%;">
                            <span style="display: flex; align-items: center; gap: 10px;">🚫 Hate speech or harassment</span>
                        </button>
                        <button class="btn btn-outline" style="justify-content: flex-start; padding: 12px 20px; width: 100%;">
                            <span style="display: flex; align-items: center; gap: 10px;">🔞 Inappropriate media</span>
                        </button>
                    </div>
                    <div style="display:flex; justify-content: center; gap: 12px;">
                        <button type="button" class="btn-pill ghost" onclick="pulseCloseModal('modalReport')">Cancel</button>
                        <button type="button" class="btn-pill primary"
                            onclick="alert('Thank you for reporting!') || pulseCloseModal('modalReport')">Submit Report</button>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <script>
        function pulseOpenModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => modal.classList.add('active'), 10);
            }
        }

        function pulseCloseModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('active');
                setTimeout(() => modal.style.display = 'none', 300);
            }
        }

        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        // Load state from localStorage
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
            });
        }

        // Auto-collapse sidebar when clicking a nav item
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth < 1200) { // Optional: only auto-collapse on smaller screens
                    sidebar.classList.add('collapsed');
                    localStorage.setItem('sidebar-collapsed', 'true');
                }
            });
        });

        // Grid/List Toggle Logic
        function toggleGridLayout() {
            const feed = document.getElementById('feed-container');
            const iconGrid = document.getElementById('icon-grid');
            const iconList = document.getElementById('icon-list');

            if (feed) {
                feed.classList.toggle('grid-view');
                const isGrid = feed.classList.contains('grid-view');
                localStorage.setItem('feed-layout', isGrid ? 'grid' : 'list');

                if (isGrid) {
                    iconGrid.style.display = 'none';
                    iconList.style.display = 'block';
                } else {
                    iconGrid.style.display = 'block';
                    iconList.style.display = 'none';
                }
            }
        }

        // Apply grid preference on load
        if (localStorage.getItem('feed-layout') === 'grid') {
            const feed = document.getElementById('feed-container');
            const iconGrid = document.getElementById('icon-grid');
            const iconList = document.getElementById('icon-list');
            if (feed) feed.classList.add('grid-view');
            if (iconGrid) iconGrid.style.display = 'none';
            if (iconList) iconList.style.display = 'block';
        }

        function handleBannerClick() {
            const banner = document.getElementById('messageBanner');
            if (banner) {
                banner.style.opacity = '0';
                banner.style.transform = 'translateY(-100%)';
                setTimeout(() => {
                    banner.remove();
                    window.location.href = "{{ route('messages.index') }}";
                }, 300);
            }

            // Collapse sidebar when checking messages
            if (sidebar && !sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
                localStorage.setItem('sidebar-collapsed', 'true');
            }
        }

        // AJAX Reactions (no page refresh)
        function reactToPost(postId, type, btn) {
            fetch('/posts/' + postId + '/reactions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ type: type })
            })
                .then(r => r.json())
                .then(data => {
                    const group = btn.closest('.action-group');
                    // update like count
                    const likeCount = group.querySelector('[data-post-likes="' + postId + '"]');
                    if (likeCount) likeCount.textContent = data.likes;
                    // update dislike count
                    const dislikeCount = group.querySelector('[data-post-dislikes="' + postId + '"]');
                    if (dislikeCount) dislikeCount.textContent = data.dislikes;
                    // update active states
                    const likeBtn = group.querySelector('.like-btn');
                    const dislikeBtn = group.querySelector('.dislike-btn');
                    if (likeBtn) {
                        likeBtn.classList.toggle('active', data.userReaction === 'TOP');
                        likeBtn.querySelector('svg').style.fill = data.userReaction === 'TOP' ? '#22c55e' : 'none';
                    }
                    if (dislikeBtn) {
                        dislikeBtn.classList.toggle('active', data.userReaction === 'FLOP');
                        dislikeBtn.querySelector('svg').style.fill = data.userReaction === 'FLOP' ? '#ef4444' : 'none';
                    }
                })
                .catch(err => console.error('Reaction error:', err));
        }

        function reactToComment(commentId, type, btn) {
            fetch('/comments/' + commentId + '/reactions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ type: type })
            })
                .then(r => r.json())
                .then(data => {
                    const group = btn.closest('.comment-actions');
                    // update like count
                    const likeCount = group.querySelector('[data-comment-likes="' + commentId + '"]');
                    if (likeCount) likeCount.textContent = data.likes;
                    // update dislike count
                    const dislikeCount = group.querySelector('[data-comment-dislikes="' + commentId + '"]');
                    if (dislikeCount) dislikeCount.textContent = data.dislikes;
                    // update active states
                    const likeBtn = group.querySelector('.like-btn');
                    const dislikeBtn = group.querySelector('.dislike-btn');
                    if (likeBtn) {
                        likeBtn.classList.toggle('active', data.userReaction === 'TOP');
                        if (data.userReaction === 'TOP') {
                            likeBtn.style.color = '#22c55e';
                            likeBtn.style.background = 'rgba(34, 197, 94, 0.1)';
                        } else {
                            likeBtn.style.color = '';
                            likeBtn.style.background = '';
                        }
                    }
                    if (dislikeBtn) {
                        dislikeBtn.classList.toggle('active', data.userReaction === 'FLOP');
                        if (data.userReaction === 'FLOP') {
                            dislikeBtn.style.color = '#ef4444';
                            dislikeBtn.style.background = 'rgba(239, 68, 68, 0.1)';
                        } else {
                            dislikeBtn.style.color = '';
                            dislikeBtn.style.background = '';
                        }
                    }
                })
                .catch(err => console.error('Reaction error:', err));
        }

        // Close modal on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay').forEach(m => closeModal(m.id));
            }
        });

        function previewImage(input) {
            const container = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePreview() {
            document.getElementById('modal-file').value = "";
            document.getElementById('image-preview-container').style.display = 'none';
        }
        // Close user dropdown on click outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.user-dropdown') && !e.target.closest('#userMenu')) {
                const menu = document.getElementById('userMenu');
                if (menu && menu.style.display === 'block') {
                    menu.style.display = 'none';
                }
            }
        });
    </script>
</body>

</html>