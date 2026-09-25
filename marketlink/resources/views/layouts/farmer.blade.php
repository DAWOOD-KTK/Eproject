<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Farmer Stall Portal') - MarketLink</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-green: #198754;
            --primary-dark: #146c43;
            --sidebar-bg: #0f172a;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            min-height: 100vh;
            color: #cbd5e1;
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
            padding: 10px 15px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }
        .sidebar-link:hover, .sidebar-link.active {
            color: white;
            background: rgba(25, 135, 84, 0.25);
            border-left: 3px solid #10b981;
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
            padding: 12px 25px;
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

    <!-- Sidebar -->
    <aside class="sidebar" id="farmerSidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <a href="{{ route('farmer.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-basket2-fill text-success me-2 fs-4"></i>
                <span>Farmer<span class="text-success">Portal</span></span>
            </a>
            <button class="btn btn-sm btn-link text-white d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="px-3 py-3 border-bottom border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-success text-white rounded-circle p-2 fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    {{ strtoupper(substr(Auth::user()->stall_name ?? Auth::user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-white fw-bold text-truncate" style="font-size: 0.88rem;">{{ Auth::user()->stall_name ?? Auth::user()->name }}</div>
                    <span class="badge bg-success" style="font-size: 0.65rem;">Active Stall</span>
                </div>
            </div>
        </div>

        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('farmer.dashboard') }}" class="sidebar-link {{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('farmer.products') }}" class="sidebar-link {{ request()->routeIs('farmer.products*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Weekly Stock & Pricing
                </a>
            </li>
            <li>
                <a href="{{ route('farmer.orders') }}" class="sidebar-link {{ request()->routeIs('farmer.orders*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check"></i> Manage Pre-Orders
                </a>
            </li>
            <li>
                <a href="{{ route('farmer.analytics') }}" class="sidebar-link {{ request()->routeIs('farmer.analytics*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow"></i> Sales & Insights
                </a>
            </li>
            <li>
                <a href="{{ route('farmer.reviews') }}" class="sidebar-link {{ request()->routeIs('farmer.reviews*') ? 'active' : '' }}">
                    <i class="bi bi-star-half"></i> Customer Reviews
                </a>
            </li>
            <li>
                <a href="{{ route('farmer.settings') }}" class="sidebar-link {{ request()->routeIs('farmer.settings*') ? 'active' : '' }}">
                    <i class="bi bi-sliders"></i> Stall & Pickup Settings
                </a>
            </li>

            <li class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                <a href="{{ route('farmers.show', Auth::id()) }}" target="_blank" class="sidebar-link text-info">
                    <i class="bi bi-eye"></i> View Public Stall
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}" class="sidebar-link">
                    <i class="bi bi-house"></i> MarketLink Main Site
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
        <!-- Topbar -->
        <header class="topbar d-flex align-items-center justify-content-between">
            <button class="btn btn-outline-secondary btn-sm d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="fw-semibold text-secondary">
                <i class="bi bi-geo-alt text-success me-1"></i>
                Attending Market: <span class="text-dark fw-bold">{{ Auth::user()->market ? Auth::user()->market->market_name : 'No market selected' }}</span>
                | Operating Days: <span class="badge bg-light text-dark border">{{ Auth::user()->operating_days ?? 'Weekend' }}</span>
            </div>
            <div>
                <a href="{{ route('farmer.products') }}" class="btn btn-success btn-sm rounded-pill px-3">
                    <i class="bi bi-plus-circle me-1"></i> Add Product
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
            document.getElementById('farmerSidebar').classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>
