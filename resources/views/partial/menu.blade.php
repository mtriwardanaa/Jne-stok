<div class="content-side content-side-full">
    <ul class="nav-main">
        <li class="@yield('dashboard')">
            <a class="@yield('dashboard-menu')" href="{{ url('dashboard') }}"><i class="si si-cup"></i><span class="sidebar-mini-hide">Dashboard</span></a>
        </li>
        <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">Management</span></li>
        <li>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span class="sidebar-mini-hide">Barang Masuk</span></a>
            <ul>
                <li>
                    <a href="be_blocks.html">List</a>
                </li>
                <li>
                    <a href="be_blocks_draggable.html">Tambah</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-moustache"></i><span class="sidebar-mini-hide">Barang Keluar</span></a>
            <ul>
                <li>
                    <a href="be_widgets_tiles.html">List</a>
                </li>
                <li>
                    <a href="be_widgets_users.html">Tambah</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#"><i class="si si-energy"></i><span class="sidebar-mini-hide">Request / Order</span></a>
        </li>
        <li class="nav-main-heading"><span class="sidebar-mini-visible">BD</span><span class="sidebar-mini-hidden">Data</span></li>
        <li>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-vector"></i><span class="sidebar-mini-hide">Barang</span></a>
            <ul>
                <li>
                    <a href="be_layout_api.html">List</a>
                </li>
                <li>
                    <a href="be_layout_api.html">Tambah</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-trophy"></i><span class="sidebar-mini-hide">User</span></a>
            <ul>
                <li>
                    <a href="be_comp_charts.html">List</a>
                </li>
                <li>
                    <a href="be_comp_nestable.html">Tambah</a>
                </li>
            </ul>
        </li>
    </ul>
</div>