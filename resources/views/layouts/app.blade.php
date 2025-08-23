<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .navbar {
            background-color: #051650;
        }
        .navbar .nav-link, .navbar-brand, .navbar-text {
            color: rgba(255, 255, 255, 0.85);
        }
        .navbar .nav-link:hover, .navbar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
        .navbar-brand {
            font-weight: 700;
        }
        
        /* Clean Notification Styles */
        .notification-badge {
            position: relative;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .notification-badge:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .notification-count {
            position: absolute;
            top: 4px;
            right: 8px;
            background: #ff4757;
            color: white;
            border-radius: 10px;
            min-width: 18px;
            height: 18px;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #051650;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .notification-dropdown {
            width: 360px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            padding: 0;
            margin-top: 8px;
        }
        
        .notification-header {
            padding: 16px 20px 12px;
            border-bottom: 1px solid #f1f3f4;
            background: #fafbfc;
            border-radius: 12px 12px 0 0;
        }
        
        .notification-header h6 {
            margin: 0;
            font-weight: 600;
            color: #2c3e50;
            font-size: 15px;
        }
        
        .mark-all-btn {
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid #e9ecef;
            background: white;
            color: #6c757d;
            transition: all 0.2s ease;
        }
        
        .mark-all-btn:hover {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
        }
        
        .notification-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 0;
        }
        
        .notification-list::-webkit-scrollbar {
            width: 4px;
        }
        
        .notification-list::-webkit-scrollbar-track {
            background: #f8f9fa;
        }
        
        .notification-list::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 2px;
        }
        
        .notification-item {
            padding: 16px 20px;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .notification-item:last-child {
            border-bottom: none;
            border-radius: 0 0 12px 12px;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        
        .notification-item.unread {
            background-color: #f0f8ff;
            border-left: 3px solid #4285f4;
        }
        
        .notification-item.unread::before {
            content: '';
            position: absolute;
            top: 20px;
            right: 20px;
            width: 8px;
            height: 8px;
            background: #4285f4;
            border-radius: 50%;
        }
        
        .notification-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
            line-height: 1.4;
            margin-bottom: 4px;
        }
        
        .notification-message {
            color: #6c757d;
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 8px;
        }
        
        .notification-time {
            color: #9aa0a6;
            font-size: 12px;
            font-weight: 500;
        }
        
        .no-notifications {
            padding: 40px 20px;
            text-align: center;
            color: #9aa0a6;
        }
        
        .no-notifications i {
            font-size: 32px;
            margin-bottom: 12px;
            opacity: 0.5;
        }
        
        .refresh-btn {
            padding: 12px 20px;
            text-align: center;
            border-top: 1px solid #f1f3f4;
            background: #fafbfc;
            border-radius: 0 0 12px 12px;
        }
        
        .refresh-btn a {
            color: #6c757d;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .refresh-btn a:hover {
            color: #495057;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stats-card-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        .stats-card-warning {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }
        .stats-card-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="bi bi-building-fill me-2 fs-4"></i>
                <h4 class="m-0 text-white">PT. Company</h4>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}" href="{{ route('documents.index') }}">Dokumen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('progress.*') ? 'active' : '' }}" href="{{ route('progress.index') }}">Progress</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link notification-badge" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="notification-count" id="notificationCount" style="display: none;">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                            <div class="notification-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6>Notifikasi</h6>
                                    <button class="btn mark-all-btn" onclick="markAllAsRead()">
                                        <i class="bi bi-check2-all me-1"></i>Tandai Semua
                                    </button>
                                </div>
                            </div>
                            <div class="notification-list" id="notificationList">
                            </div>
                            <div class="refresh-btn">
                                <a href="#" onclick="loadNotifications(); return false;">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                                </a>
                            </div>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                             <i class="bi bi-person-circle fs-5 me-1"></i>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                             @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                            <li>
                                <a class="dropdown-item" href="{{ route('users.index') }}">
                                    <i class="bi bi-people me-2"></i>Kelola User
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid main-content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <h1 class="h2 mb-4">@yield('page-title', 'Dashboard')</h1>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Load notifications on page load
        $(document).ready(function() {
            loadNotifications();
            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);
        });

        function loadNotifications() {
            $.get('/dashboard/notifications', function(data) {
                $('#notificationCount').text(data.unread_count);

                if (data.unread_count > 0) {
                    $('#notificationCount').show();
                } else {
                    $('#notificationCount').hide();
                }

                let notificationHtml = '';
                if (data.notifications.length === 0) {
                    notificationHtml = `
                        <div class="no-notifications">
                            <i class="bi bi-bell-slash"></i>
                            <div>Tidak ada notifikasi</div>
                        </div>
                    `;
                } else {
                    data.notifications.forEach(function(notification) {
                        const isUnread = !notification.is_read ? 'unread' : '';
                        const timeAgo = moment(notification.created_at).fromNow();

                        notificationHtml += `
                            <div class="notification-item ${isUnread}" onclick="markAsRead(${notification.id})">
                                <div class="notification-title">${notification.title}</div>
                                <div class="notification-message">${notification.message}</div>
                                <div class="notification-time">${timeAgo}</div>
                            </div>
                        `;
                    });
                }

                $('#notificationList').html(notificationHtml);
            }).fail(function() {
                $('#notificationList').html(`
                    <div class="no-notifications">
                        <i class="bi bi-exclamation-circle"></i>
                        <div>Gagal memuat notifikasi</div>
                    </div>
                `);
            });
        }

        function markAsRead(notificationId) {
            $.post('/dashboard/notifications/read', { id: notificationId }, function() {
                loadNotifications();
            });
        }

        function markAllAsRead() {
            $.post('/dashboard/notifications/read-all', function() {
                loadNotifications();
            });
        }

        // Moment.js for time formatting (simple implementation)
        function moment(date) {
            const now = new Date();
            const past = new Date(date);
            const diff = Math.floor((now - past) / 1000);

            if (diff < 60) return { fromNow: () => 'Baru saja' };
            if (diff < 3600) return { fromNow: () => Math.floor(diff / 60) + ' menit lalu' };
            if (diff < 86400) return { fromNow: () => Math.floor(diff / 3600) + ' jam lalu' };
            return { fromNow: () => Math.floor(diff / 86400) + ' hari lalu' };
        }
    </script>

    @yield('scripts')
</body>
</html>