<!doctype html>
<html lang="en" class="no-focus">
    @php
        $fitur = session()->get('fitur');
    @endphp
    @include('partial.head')
    <body>
        <div id="page-container" class="@if (in_array(31, $fitur)) sidebar-o @endif sidebar-inverse enable-page-overlay side-scroll page-header-fixed">
            @include('partial.nav')

            <!-- Header -->
            @include('partial.header')
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="content">
                	<nav class="breadcrumb bg-white push noPrint">
	                	<a class="breadcrumb-item" href="javascript:void(0)" data-pjax="">@yield('head-title')</a>
	                	<span class="breadcrumb-item active">@yield('head-sub-title')</span>
	                </nav>
                    @yield('content')
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            @include('partial.footer')
            <!-- END Footer -->
        </div>

        @include('partial.script')
    </body>
</html>