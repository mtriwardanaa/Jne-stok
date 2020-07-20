<div class="content-side content-side-full">
    <ul class="nav-main">
        <li class="@yield('dashboard')">
            <a class="@yield('dashboard-menu')" href="{{ url('dashboard') }}"><i class="si si-cup"></i><span class="sidebar-mini-hide">Dashboard</span></a>
        </li>
        <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">Management</span></li>
        <li class="@yield('barang-masuk')">
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span class="sidebar-mini-hide">Barang Masuk</span></a>
            <ul>
                <li>
                    <a class="@yield('barang-masuk-list')" href="{{ url('barangmasuk') }}">List</a>
                </li>
                <li>
                    <a class="@yield('barang-masuk-tambah')" href="{{ url('barangmasuk/create') }}">Tambah</a>
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
        <li class="@yield('barang')">
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-vector"></i><span class="sidebar-mini-hide">Barang</span></a>
            <ul>
                <li>
                    <a class="@yield('barang-list')" href="{{ url('barang') }}">List</a>
                </li>
                <li>
                    <a class="@yield('barang-tambah')" href="{{ url('barang/create') }}">Tambah</a>
                </li>
            </ul>
        </li>
        <li class="@yield('supplier')">
            <a class="@yield('supplier-menu')" href="{{ url('supplier') }}"><i class="si si-calculator"></i><span class="sidebar-mini-hide">Supplier</span></a>
        </li>
        <li class="@yield('user')">
            <a class="@yield('user-menu')" href="{{ url('user') }}"><i class="si si-trophy"></i><span class="sidebar-mini-hide">User</span></a>
        </li>
    </ul>
</div>