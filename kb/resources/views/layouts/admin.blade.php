<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | AcelleMail KB</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<aside class="kb-admin-sidebar" id="adminSidebar">
    <a href="{{ route('admin.articles.index') }}" class="kb-admin-sidebar__brand">
        <img src="{{ asset('images/kb-icon.svg') }}" alt="">
        AcelleMail KB
    </a>
    <nav class="kb-admin-sidebar__nav">
        <div class="kb-admin-sidebar__section">Content</div>
        <a href="{{ route('admin.articles.index') }}" class="kb-admin-sidebar__link {{ request()->routeIs('admin.articles.*') ? 'kb-admin-sidebar__link--active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Articles
        </a>
        <a href="{{ route('admin.categories.index') }}" class="kb-admin-sidebar__link {{ request()->routeIs('admin.categories.*') ? 'kb-admin-sidebar__link--active' : '' }}">
            <i class="bi bi-folder2"></i> Categories
        </a>
        <a href="{{ route('admin.tags.index') }}" class="kb-admin-sidebar__link {{ request()->routeIs('admin.tags.*') ? 'kb-admin-sidebar__link--active' : '' }}">
            <i class="bi bi-tags"></i> Tags
        </a>

        <div class="kb-admin-sidebar__section mt-3">Links</div>
        <a href="{{ route('home') }}" class="kb-admin-sidebar__link" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> View Site
        </a>
        <a href="https://acellemail.com" class="kb-admin-sidebar__link" target="_blank">
            <i class="bi bi-globe"></i> AcelleMail
        </a>
    </nav>
</aside>

{{-- Main --}}
<div class="kb-admin-main">
    {{-- Topbar --}}
    <div class="kb-admin-topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary" id="themeToggle" title="Toggle theme">
                <i class="bi bi-moon-stars" id="themeIcon"></i>
            </button>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person"></i> {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="kb-admin-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Dark/light theme toggle
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const savedTheme = localStorage.getItem('kb-admin-theme') || 'light';

document.documentElement.setAttribute('data-bs-theme', savedTheme);
themeIcon.className = savedTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';

themeToggle.addEventListener('click', () => {
    const current = document.documentElement.getAttribute('data-bs-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', next);
    themeIcon.className = next === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
    localStorage.setItem('kb-admin-theme', next);
});
</script>
@stack('scripts')
</body>
</html>
