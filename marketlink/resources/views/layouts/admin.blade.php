<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Console') - MarketLink Administration</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --admin-dark: #1e293b;
            --admin-nav: #0f172a;
            --admin-accent: #3b82f6;
            --admin-green: #10b981;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
        }
        .sidebar {
            width: 260px;
            background: var(--admin-nav);
            min-height: 100vh;
            color: #94a3b8;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .sidebar-brand {
            padding: 20px;
            font-size: 1.25rem;
            font-weight: 800;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-nav {
            list-style: none;
            padding: 15px 10px;
            margin: 0;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 11px 16px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: white;
            background: rgba(59, 130, 246, 0.2);
            border-left: 3px solid var(--admin-accent);
        }
        .sidebar-link i {
            margin-right: 12px;
            font-size: 1.15rem;
        }
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 28px;
        }
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Admin Sidebar -->
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-shield-lock-fill text-primary me-2 fs-4"></i>
                <span>Admin<span class="text-primary">Link</span></span>
            </a>
            <button class="btn btn-sm btn-link text-white d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="px-3 py-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary text-white rounded-circle p-2 fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    A
                </div>
                <div class="overflow-hidden">
                    <div class="text-white fw-bold text-truncate" style="font-size: 0.88rem;">{{ Auth::user()->name }}</div>
                    <span class="badge bg-danger" style="font-size: 0.65rem;">Platform Admin</span>
                </div>
            </div>
        </div>

        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard Overview
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Manage Users & Approvals
                </a>
            </li>
            <li>
                <a href="{{ route('admin.markets') }}" class="sidebar-link {{ request()->routeIs('admin.markets*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i> Manage Farmers Markets
                </a>
            </li>
            <li>
                <a href="{{ route('admin.moderation') }}" class="sidebar-link {{ request()->routeIs('admin.moderation*') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i> Content Moderation
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Reports & Analytics
                </a>
            </li>
            <li>
                <a href="{{ route('admin.configuration') }}" class="sidebar-link {{ request()->routeIs('admin.configuration*') ? 'active' : '' }}">
                    <i class="bi bi-gear-wide-connected"></i> System Configuration
                </a>
            </li>

            <li class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                <a href="{{ route('home') }}" class="sidebar-link text-info">
                    <i class="bi bi-box-arrow-up-right"></i> Go to Public Storefront
                </a>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link bg-transparent border-0 w-100 text-start text-danger">
                        <i class="bi bi-box-arrow-right"></i> Log Out
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <header class="topbar d-flex align-items-center justify-content-between">
            <button class="btn btn-outline-secondary btn-sm d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="fw-semibold text-secondary">
                <span class="badge bg-primary me-2">Superadmin</span>
                MarketLink Administration & Moderation Console
            </div>
            <div>
                <a href="{{ route('admin.reports') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="bi bi-download me-1"></i> Generate Report
                </a>
            </div>
        </header>

        <!-- Alerts -->
        <div class="container-fluid px-4 mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm rounded-3">
                    <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <main class="container-fluid px-4 py-3 flex-grow-1">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>
