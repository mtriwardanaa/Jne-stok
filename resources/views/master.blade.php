<!doctype html>
<html lang="en" class="no-focus">
    @include('partial.head')
    <body>
        <div id="page-container" class="sidebar-o sidebar-inverse enable-page-overlay side-scroll page-header-fixed">
            @include('partial.nav')

            <!-- Header -->
            @include('partial.header')
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="content">
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