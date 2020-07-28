<div class="content-side content-side-full">
    <ul class="nav-main">
        <li class="@yield('dashboard')">
            <a class="@yield('dashboard-menu')" href="{{ url('dashboard') }}"><i class="si si-cup"></i><span class="sidebar-mini-hide">Dashboard</span></a>
        </li>
        @if (Auth::user()->id_divisi == 10)
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
	        <li class="@yield('barang-keluar')">
	            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-moustache"></i><span class="sidebar-mini-hide">Barang Keluar</span></a>
	            <ul>
	                <li>
	                    <a class="@yield('barang-keluar-list')" href="{{ url('barangkeluar') }}">List</a>
	                </li>
	                <li>
	                    <a class="@yield('barang-keluar-tambah')" href="{{ url('barangkeluar/create') }}">Tambah</a>
	                </li>
	            </ul>
	        </li>
        @endif
        <li class="@yield('request')">
            <a class="@yield('request-list')" href="{{ url('order') }}"><i class="si si-energy"></i><span class="sidebar-mini-hide">Request / Order</span></a>
        </li>
        @if (Auth::user()->id_divisi == 10)
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
	            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-calculator"></i><span class="sidebar-mini-hide">Supplier</span></a>
	            <ul>
	                <li>
	                    <a class="@yield('supplier-list')" href="{{ url('supplier') }}">List</a>
	                </li>
	                <li>
	                    <a class="@yield('supplier-tambah')" href="{{ url('supplier/create') }}">Tambah</a>
	                </li>
	            </ul>
	        </li>
	        <li class="@yield('user')">
	            <a class="@yield('user-menu')" href="{{ url('user') }}"><i class="si si-trophy"></i><span class="sidebar-mini-hide">User</span></a>
        	</li>
        @endif
    </ul>
</div>