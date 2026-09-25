<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MarketLink') - Farm Fresh Just a Click Away</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Leaflet CSS for OpenStreetMap -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <style>
        :root {
            --primary-green: #198754;
            --primary-dark: #146c43;
            --primary-light: #e8f5e9;
            --accent-gold: #f59e0b;
            --accent-warm: #d97706;
            --text-main: #1f2937;
            --bg-light: #f9fafb;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            background-color: var(--bg-light);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.45rem;
            color: var(--primary-green) !important;
            letter-spacing: -0.5px;
        }
        .navbar-brand span {
            color: var(--accent-gold);
        }

        .nav-link {
            font-weight: 500;
            color: #374151;
            padding: 0.5rem 0.9rem !important;
            transition: all 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary-green) !important;
        }

        .btn-success {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            font-weight: 600;
        }
        .btn-success:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-outline-success {
            color: var(--primary-green);
            border-color: var(--primary-green);
            font-weight: 600;
        }
        .btn-outline-success:hover {
            background-color: var(--primary-green);
            color: white;
        }

        .badge-green {
            background-color: var(--primary-light);
            color: var(--primary-green);
            font-weight: 600;
        }

        .card-custom {
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
        }
        .card-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        /* AI Assistant Floating Widget */
        .ai-chat-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: linear-gradient(135deg, #198754, #10b981);
            color: white;
            box-shadow: 0 8px 20px rgba(25, 135, 84, 0.4);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            cursor: pointer;
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .ai-chat-btn:hover {
            transform: scale(1.1) rotate(5deg);
            color: white;
        }

        .ai-chat-window {
            position: fixed;
            bottom: 95px;
            right: 25px;
            width: 375px;
            max-width: calc(100vw - 40px);
            height: 520px;
            max-height: calc(100vh - 120px);
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
            display: none;
            flex-direction: column;
            z-index: 1050;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        .ai-chat-header {
            background: linear-gradient(135deg, #198754, #059669);
            color: white;
            padding: 14px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ai-chat-body {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            background: #fdfdfd;
        }
        .chat-msg {
            margin-bottom: 12px;
            max-width: 82%;
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        .chat-msg.bot {
            background: #f3f4f6;
            color: #1f2937;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }
        .chat-msg.user {
            background: var(--primary-green);
            color: white;
            align-self: flex-end;
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }
        .ai-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }
        .ai-chip {
            background: #e8f5e9;
            color: #198754;
            font-size: 0.78rem;
            padding: 4px 10px;
            border-radius: 20px;
            cursor: pointer;
            border: 1px solid #c8e6c9;
            transition: all 0.2s ease;
        }
        .ai-chip:hover {
            background: #198754;
            color: white;
        }
        .ai-chat-footer {
            padding: 10px 12px;
            background: white;
            border-top: 1px solid #e5e7eb;
        }

        footer {
            margin-top: auto;
            background: #111827;
            color: #9ca3af;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <i class="bi bi-basket2-fill me-2 fs-3 text-success"></i>
                Market<span>Link</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('markets.*') ? 'active' : '' }}" href="{{ route('markets.index') }}">
                            <i class="bi bi-geo-alt me-1"></i> Markets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('farmers.*') ? 'active' : '' }}" href="{{ route('farmers.index') }}">
                            <i class="bi bi-person-badge me-1"></i> Farmers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-shop me-1"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}" href="{{ route('map') }}">
                            <i class="bi bi-map me-1"></i> Map Finder
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <!-- Pre-order Basket Button -->
                    @php
                        $cart = session()->get('cart', []);
                        $cartCount = array_sum(array_column($cart, 'quantity'));
                    @endphp
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-success btn-sm position-relative rounded-pill px-3">
                        <i class="bi bi-bag-check me-1"></i> Basket
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Authentication Links -->
                    @auth
                        @php
                            $user = Auth::user();
                            $unreadCount = $user->announcements()->where('is_read', false)->count();
                        @endphp

                        @if($user->isCustomer())
                            <!-- Notifications Bell -->
                            <a href="{{ route('customer.notifications') }}" class="btn btn-light btn-sm rounded-circle position-relative p-2 text-secondary" title="Notifications">
                                <i class="bi bi-bell-fill"></i>
                                @if($unreadCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger p-1">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>
                        @endif

                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-pill dropdown-toggle d-flex align-items-center gap-2 border px-3" type="button" data-bs-toggle="dropdown">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-medium">{{ Str::limit($user->name, 14) }}</span>
                                <span class="badge bg-secondary" style="font-size: 0.65rem;">{{ ucfirst($user->role) }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                @if($user->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-primary"></i> Admin Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users') }}"><i class="bi bi-people me-2"></i> Manage Users</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.markets') }}"><i class="bi bi-geo-alt me-2"></i> Manage Markets</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.reports') }}"><i class="bi bi-graph-up me-2"></i> Reports & Analytics</a></li>
                                @elseif($user->isFarmer())
                                    <li><a class="dropdown-item" href="{{ route('farmer.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-success"></i> Farmer Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('farmer.products') }}"><i class="bi bi-box-seam me-2"></i> Weekly Stock & Pricing</a></li>
                                    <li><a class="dropdown-item" href="{{ route('farmer.orders') }}"><i class="bi bi-clipboard-check me-2"></i> Manage Pre-Orders</a></li>
                                    <li><a class="dropdown-item" href="{{ route('farmer.reviews') }}"><i class="bi bi-star me-2"></i> Customer Reviews</a></li>
                                    <li><a class="dropdown-item" href="{{ route('farmer.settings') }}"><i class="bi bi-gear me-2"></i> Stall Settings</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-success"></i> My Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('customer.orders.index') }}"><i class="bi bi-receipt me-2"></i> My Pre-Orders</a></li>
                                    <li><a class="dropdown-item" href="{{ route('customer.favorites.index') }}"><i class="bi bi-heart me-2 text-danger"></i> Saved Favorites</a></li>
                                    <li><a class="dropdown-item" href="{{ route('customer.profile') }}"><i class="bi bi-person me-2"></i> My Profile</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Log In</a>
                        <a href="{{ route('register') }}" class="btn btn-success btn-sm rounded-pill px-3">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Toast / Alert Notifications -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill fs-5 me-2 text-info"></i>
                <div>{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content Injection -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Floating AI Assistant Chatbot -->
    <button class="ai-chat-btn" id="aiChatToggle" title="MarketLink AI Assistant">
        <i class="bi bi-robot"></i>
    </button>

    <div class="ai-chat-window" id="aiChatWindow">
        <div class="ai-chat-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-robot fs-5"></i>
                <div>
                    <h6 class="mb-0 fw-bold">MarketLink Assistant</h6>
                    <small style="font-size: 0.75rem; opacity: 0.85;">Live Market & Stock Helper</small>
                </div>
            </div>
            <button class="btn btn-sm btn-link text-white p-0" id="aiChatClose">
                <i class="bi bi-x-lg fs-5"></i>
            </button>
        </div>
        <div class="ai-chat-body d-flex flex-column" id="aiChatBody">
            <div class="chat-msg bot">
                👋 Hello! Welcome to <strong>MarketLink</strong>. I can help you find fresh products, check market timings, locate farmer stalls, or explain our pre-order pickup system!
                <div class="ai-suggestions mt-2">
                    <span class="ai-chip" onclick="sendQuickQuery('What are market timings?')">Market timings</span>
                    <span class="ai-chip" onclick="sendQuickQuery('Show fresh organic vegetables')">Organic vegetables</span>
                    <span class="ai-chip" onclick="sendQuickQuery('How do pre-orders work?')">How it works</span>
                </div>
            </div>
        </div>
        <div class="ai-chat-footer">
            <form id="aiChatForm" class="d-flex gap-2">
                <input type="text" id="aiChatInput" class="form-control form-control-sm rounded-pill" placeholder="Ask about timings, stock, farmers..." autocomplete="off">
                <button type="submit" class="btn btn-success btn-sm rounded-circle px-2" style="width: 32px; height: 32px;">
                    <i class="bi bi-send-fill" style="font-size: 0.75rem;"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white fw-bold d-flex align-items-center">
                        <i class="bi bi-basket2-fill me-2 text-success"></i> MarketLink
                    </h5>
                    <p class="text-muted small mt-2">
                        MarketLink connects local farmers-market producers directly with consumers. Browse weekly stock, reserve your harvest for pickup, and build sustainable local food networks.
                    </p>
                    <span class="badge bg-success text-white px-3 py-2 rounded-pill small">
                        Theme: eGreen Basket | Aptech TechWiz 7
                    </span>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Quick Navigation</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('markets.index') }}" class="text-muted text-decoration-none">Farmers Markets</a></li>
                        <li class="mb-2"><a href="{{ route('farmers.index') }}" class="text-muted text-decoration-none">Local Producers</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}" class="text-muted text-decoration-none">Seasonal Stock</a></li>
                        <li class="mb-2"><a href="{{ route('map') }}" class="text-muted text-decoration-none">Interactive Map</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3">User Portals</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-muted text-decoration-none">Customer Login</a></li>
                        <li class="mb-2"><a href="{{ route('register', ['role' => 'farmer']) }}" class="text-muted text-decoration-none">Register as Farmer / Stall</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-muted text-decoration-none">Admin Dashboard</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-muted text-decoration-none">About Platform</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Pickup Policy</h6>
                    <p class="text-muted small">
                        <i class="bi bi-cash-stack text-warning me-1"></i> Cash / direct payment settled in person at market pickup stall.
                    </p>
                    <p class="text-muted small">
                        <i class="bi bi-clock-history text-info me-1"></i> Orders can be modified or cancelled prior to farmer cutoff hours.
                    </p>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center small text-muted">
                <div>&copy; 2026 MarketLink Web Application. All rights reserved.</div>
                <div>Designed for TechWiz 7 World Tech Championship</div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS for OpenStreetMap -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- AI Chatbot Script -->
    <script>
        const chatToggle = document.getElementById('aiChatToggle');
        const chatClose = document.getElementById('aiChatClose');
        const chatWindow = document.getElementById('aiChatWindow');
        const chatForm = document.getElementById('aiChatForm');
        const chatInput = document.getElementById('aiChatInput');
        const chatBody = document.getElementById('aiChatBody');

        chatToggle.addEventListener('click', () => {
            chatWindow.style.display = (chatWindow.style.display === 'flex') ? 'none' : 'flex';
            if (chatWindow.style.display === 'flex') {
                chatInput.focus();
            }
        });

        chatClose.addEventListener('click', () => {
            chatWindow.style.display = 'none';
        });

        function appendMessage(text, sender, suggestions = []) {
            const msgDiv = document.createElement('div');
            msgDiv.className = `chat-msg ${sender}`;
            msgDiv.innerHTML = text;

            if (suggestions && suggestions.length > 0) {
                const suggDiv = document.createElement('div');
                suggDiv.className = 'ai-suggestions mt-2';
                suggestions.forEach(s => {
                    const chip = document.createElement('span');
                    chip.className = 'ai-chip';
                    chip.innerText = s;
                    chip.onclick = () => sendQuickQuery(s);
                    suggDiv.appendChild(chip);
                });
                msgDiv.appendChild(suggDiv);
            }

            chatBody.appendChild(msgDiv);
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        function sendQuickQuery(query) {
            chatInput.value = query;
            chatForm.dispatchEvent(new Event('submit'));
        }

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = chatInput.value.trim();
            if (!text) return;

            appendMessage(text, 'user');
            chatInput.value = '';

            // Typing indicator
            const typingDiv = document.createElement('div');
            typingDiv.className = 'chat-msg bot text-muted fst-italic';
            typingDiv.id = 'typingIndicator';
            typingDiv.innerHTML = '<i class="bi bi-three-dots"></i> Consulting market catalog...';
            chatBody.appendChild(typingDiv);
            chatBody.scrollTop = chatBody.scrollHeight;

            try {
                const response = await fetch("{{ route('api.chat') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: text })
                });

                const data = await response.json();
                const indicator = document.getElementById('typingIndicator');
                if (indicator) indicator.remove();

                appendMessage(data.reply, 'bot', data.suggestions);
            } catch (err) {
                const indicator = document.getElementById('typingIndicator');
                if (indicator) indicator.remove();
                appendMessage('Sorry, I had trouble checking the market database. Please try again.', 'bot');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
