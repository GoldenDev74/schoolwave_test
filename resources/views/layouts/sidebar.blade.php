<aside class="main-sidebar sidebar-white elevation-4">
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('images/logo.png') }}"
             alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3">
        <span class="brand-text text-black font-weight-light">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar sidebar-white elevation-4">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>

</aside>
