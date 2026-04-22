<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard - {{ config('app.name', 'EIC') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            background:#f5f5f5;
            font-family:'Poppins', sans-serif;
        }

        .dashboard-wrapper {
            display:flex;
            min-height:100vh;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width:250px;
            background:linear-gradient(180deg,#1E4BA6 0%,#0f2f6d 100%);
            color:white;

            position:fixed;
            left:0;
            top:0;
            height:100vh;

            display:flex;
            flex-direction:column;

            padding:15px 15px 10px; /* 🔥 reduced top padding */
        }

        /* HEADER (compact) */
        .sidebar-header {
            display:flex;
            align-items:center;
            margin-bottom:10px; /* 🔥 reduced */
            padding-bottom:10px;
            border-bottom:1px solid rgba(255,255,255,0.15);
        }

        .sidebar-logo {
            width:34px;
            height:34px;
            background:#F4C542;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:bold;
            color:#111;
            margin-right:8px;
            font-size:14px;
        }

        .sidebar-title {
            font-size:13px;
            font-weight:700;
            color:#F4C542;
        }

        /* NAV */
        .sidebar nav {
            flex:1;
            overflow-y:auto;
        }

        .sidebar-menu { list-style:none; }

        /* SECTION TITLE (tight spacing) */
        .menu-title {
            font-size:10px;
            text-transform:uppercase;
            opacity:0.6;
            margin:8px 8px 4px; /* 🔥 reduced */
            font-weight:600;
        }

        .sidebar-menu li { margin-bottom:4px; }

        .sidebar-menu a {
            display:flex;
            align-items:center;
            padding:8px 10px; /* 🔥 tighter */
            border-radius:6px;
            text-decoration:none;
            font-size:13px;
            font-weight:600;
            color:rgba(255,255,255,0.85);
            transition:0.25s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background:rgba(244,197,66,0.2);
            color:#F4C542;
        }

        /* SUBMENU */
        .submenu {
            list-style:none;
            padding-left:12px;

            max-height:0;
            overflow:hidden;
            transition:max-height 0.3s ease;
        }

        .submenu.open {
            max-height:400px;
        }

        .submenu li a {
            font-size:12px;
            padding:5px 8px;
            color:rgba(255,255,255,0.7);
        }

        /* FOOTER */
        .sidebar-footer {
            margin-top:auto;
            padding-top:10px;
            border-top:1px solid rgba(255,255,255,0.15);
        }

        .sidebar-footer a {
            display:block;
            font-size:12px;
            color:rgba(255,255,255,0.8);
            margin-bottom:5px;
            text-decoration:none;
        }

        .sidebar-footer a:hover {
            color:#F4C542;
        }

        /* ================= MAIN ================= */
        .main-content {
            margin-left:250px;
            flex:1;
            padding:25px;
        }

        .top-bar {
            display:flex;
            justify-content:space-between;
            align-items:center;
            background:white;
            padding:15px 20px;
            border-radius:8px;
            box-shadow:0 2px 8px rgba(0,0,0,0.08);
            margin-bottom:25px;
        }

        .top-bar-title h1 {
            font-size:22px;
            color:#1E4BA6;
        }

        .top-bar-title p {
            font-size:12px;
            color:#666;
        }

        /* RESPONSIVE */
        @media(max-width:768px){
            .sidebar {
                position:relative;
                width:100%;
                height:auto;
            }

            .main-content {
                margin-left:0;
                padding:15px;
            }
        }
    </style>

    @yield('styles')
</head>

<body>

<div class="dashboard-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <!-- HEADER -->
        <div class="sidebar-header">
            <div class="sidebar-logo">EIC</div>
            <div class="sidebar-title">Dashboard</div>
        </div>

        <!-- MENU -->
        <nav>
            <ul class="sidebar-menu">

                <li class="menu-title">Main</li>
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        🏠 Dashboard
                    </a>
                </li>

                @if(Auth::user()->isAdmin())
                <li class="menu-title">Admin</li>
                <li>
                    <a href="{{ route('manage-users') }}"
                       class="{{ request()->routeIs('manage-users') ? 'active' : '' }}">
                        👥 Users
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->isEmployee())

                <li class="menu-title">CRM</li>

                <li>
                    <a href="{{ route('country.index') }}" 
                    class="{{ request()->routeIs('country.*') ? 'active' : '' }}">
                        🌍 Countries
                    </a>
                </li>

                <li>
                    <a href="{{ route('vendor.index') }}" 
                    class="{{ request()->routeIs('vendor.*') ? 'active' : '' }}">
                        🏢 Vendors
                    </a>
                </li>

                <li>
                    <a href="{{ route('leads.index') }}" 
                    class="{{ request()->routeIs('leads.*') ? 'active' : '' }}">
                        📞 Leads Management
                    </a>
                </li>

                <li class="menu-title">Operations</li>

                <li>
                    <a href="{{ route('passports.index') }}"
                       class="{{ request()->routeIs('passports.*') ? 'active' : '' }}">
                        📘 Passports
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);"
                       onclick="toggleMenu('jobsMenu')"
                       class="{{ request()->routeIs('jobs.*') ? 'active' : '' }}">
                        💼 Jobs ▾
                    </a>

                    <ul id="jobsMenu"
                        class="submenu {{ request()->routeIs('jobs.*') ? 'open' : '' }}">

                        <li><a href="{{ route('jobs.services') }}">▸ Services</a></li>
                        <li><a href="{{ route('jobs.categories') }}">▸ Categories</a></li>
                        <li><a href="{{ route('jobs.assign') }}">▸ Assign</a></li>
                        <li><a href="{{ route('jobs.manage') }}">▸ Overview</a></li>
                        <li><a href="{{ route('jobs.status') }}">▸ Status</a></li>
                        <li><a href="{{ route('jobs.passport.status') }}">▸ Passport Status</a></li>

                    </ul>
                </li>
                @endif

                <li class="menu-title">Account</li>
                <li>
                    <a href="{{ route('accounts.index') }}"
                       class="{{ request()->routeIs('accounts.*') ? 'active' : '' }}">
                        💰 Accounts
                    </a>
                </li>
                <li><a href="{{ route('profile.edit') }}">👤 Profile</a></li>

            </ul>
        </nav>

        <!-- FOOTER -->
        <div class="sidebar-footer">
            <a href="#">Help</a>

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
               Logout
            </a>

            <form id="logout-form" method="POST"
                  action="{{ route('logout') }}" style="display:none;">
                @csrf
            </form>
        </div>

    </aside>

    {{-- CONTENT --}}
    @yield('content')

</div>

<script>
function toggleMenu(id){
    document.getElementById(id).classList.toggle('open');
}
@yield('scripts')
</script>

</body>
</html>