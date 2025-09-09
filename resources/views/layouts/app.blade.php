<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Event Management') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS -->
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --border-color: #e5e7eb;
            --text-color: #374151;
            --text-muted: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-item {
            margin: 0.25rem 0;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }

        .menu-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
        }

        .menu-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid var(--border-color);
        }

        .header-content {
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .header-title h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .content-wrapper {
            padding: 2rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: #fafbfc;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-warning {
            background: var(--warning-color);
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .table th,
        .table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .table th {
            background: #f8fafc;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.875rem;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal.show .modal-content {
            transform: scale(1);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-muted);
            margin-left: auto;
        }

        .modal-close:hover {
            color: var(--text-color);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        /* Form */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-color);
        }

        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.15);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stat-card .stat-label {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Loading */
        .loading {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid #f3f4f6;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Alert */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background: #dcfce7;
            border-color: #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            border-color: #fecaca;
            color: #991b1b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                width: 95%;
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-calendar-alt"></i> Event Manager</h3>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-item">
                <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('events.index') }}" class="menu-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar"></i>
                    <span>Acara</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('sessions.index') }}" class="menu-link {{ request()->routeIs('sessions.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>Sesi</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('speakers.index') }}" class="menu-link {{ request()->routeIs('speakers.*') ? 'active' : '' }}">
                    <i class="fas fa-microphone"></i>
                    <span>Pembicara</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('participants.index') }}" class="menu-link {{ request()->routeIs('participants.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Peserta</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="{{ route('sponsors.index') }}" class="menu-link {{ request()->routeIs('sponsors.*') ? 'active' : '' }}">
                    <i class="fas fa-handshake"></i>
                    <span>Sponsor</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <div class="header-title">
                    <h1>@yield('title', 'Dashboard')</h1>
                </div>
                <div class="header-actions">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 600;">{{ Auth::user()->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ ucfirst(Auth::user()->role) }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script>
        // Modal Functions
        function openModal(modalId) {
            $('#' + modalId).addClass('show');
            $('body').css('overflow', 'hidden');
        }

        function closeModal(modalId) {
            $('#' + modalId).removeClass('show');
            $('body').css('overflow', 'auto');
        }

        // Close modal when clicking outside
        $(document).on('click', '.modal', function(e) {
            if (e.target === this) {
                closeModal($(this).attr('id'));
            }
        });

        // Close modal with escape key
        $(document).keydown(function(e) {
            if (e.keyCode === 27) {
                $('.modal.show').removeClass('show');
                $('body').css('overflow', 'auto');
            }
        });

        // CSRF Token Setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Show loading state for buttons
        function showLoading(btn) {
            const originalText = btn.html();
            btn.data('original-text', originalText);
            btn.html('<span class="loading"><span class="spinner"></span> Loading...</span>');
            btn.prop('disabled', true);
        }

        function hideLoading(btn) {
            btn.html(btn.data('original-text'));
            btn.prop('disabled', false);
        }

        // Show alerts
        function showAlert(message, type = 'success') {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            const alert = `<div class="alert ${alertClass}">${message}</div>`;
            
            if ($('.content-wrapper .alert').length > 0) {
                $('.content-wrapper .alert').first().replaceWith(alert);
            } else {
                $('.content-wrapper').prepend(alert);
            }
            
            setTimeout(() => {
                $('.alert').fadeOut();
            }, 5000);
        }

        // Mobile sidebar toggle
        function toggleSidebar() {
            $('#sidebar').toggleClass('open');
        }

        // Add mobile toggle button for small screens
        if ($(window).width() <= 768) {
            $('.header-title').prepend('<button onclick="toggleSidebar()" class="btn btn-primary btn-sm" style="margin-right: 1rem;"><i class="fas fa-bars"></i></button>');
        }
    </script>

    @stack('scripts')
</body>
</html>