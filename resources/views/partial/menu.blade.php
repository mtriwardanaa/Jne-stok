<div class="content-side content-side-full">
    <ul class="nav-main">
    	@if (in_array(1, $fitur))
        <li class="@yield('dashboard')">
            <a class="@yield('dashboard-menu')" href="{{ url('dashboard') }}"><i class="si si-cup"></i><span class="sidebar-mini-hide">Dashboard</span></a>
        </li>
        @endif

        <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">Management</span></li>
        @if (in_array(2, $fitur) || in_array(3, $fitur))
        <li class="@yield('barang-masuk')">
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span class="sidebar-mini-hide">Barang Masuk</span></a>
            <ul>
            	@if (in_array(2, $fitur))
                <li>
                    <a class="@yield('barang-masuk-list')" href="{{ url('barangmasuk') }}">List</a>
                </li>
                @endif
                @if (in_array(3, $fitur))
                <li>
                    <a class="@yield('barang-masuk-tambah')" href="{{ url('barangmasuk/create') }}">Tambah</a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (in_array(7, $fitur) || in_array(8, $fitur))
        <li class="@yield('barang-keluar')">
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-moustache"></i><span class="sidebar-mini-hide">Barang Keluar</span></a>
            <ul>
            	@if (in_array(7, $fitur))
                <li>
                    <a class="@yield('barang-keluar-list')" href="{{ url('barangkeluar') }}">List</a>
                </li>
                @endif
                @if (in_array(8, $fitur))
                <li>
                    <a class="@yield('barang-keluar-tambah')" href="{{ url('barangkeluar/create') }}">Tambah</a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (in_array(13, $fitur))
        <li class="@yield('request')">
            <a class="@yield('request-list')" href="{{ url('order') }}">
            	<i class="si si-energy"></i><span class="sidebar-mini-hide">Request / Order</span>
            	@if(session('get_order') != null)
					@if (session('get_order') > 0)
            			<span class="badge badge-pill badge-danger banitem" style="color: black">{{ session('get_order') }}</span>
            		@endif
        		@endif
            </a>
        </li>
        @endif

        @if (in_array(16, $fitur))
    	<li class="@yield('invoice')">
            <a class="@yield('invoice-list')" href="{{ url('invoice') }}">
            	<i class="si si-folder-alt"></i><span class="sidebar-mini-hide">Invoice</span>
            </a>
        </li>
        @endif

        <li class="nav-main-heading"><span class="sidebar-mini-visible">BD</span><span class="sidebar-mini-hidden">Summary</span></li>

        @if (in_array(18, $fitur))
        <li class="@yield('report')">
            <a class="@yield('report-list')" href="{{ url('report') }}">
            	<i class="si si-layers"></i><span class="sidebar-mini-hide">Report</span>
            </a>
        </li>
        @endif

        <li class="nav-main-heading"><span class="sidebar-mini-visible">BD</span><span class="sidebar-mini-hidden">Data</span></li>

        @if (in_array(21, $fitur) || in_array(22, $fitur) || in_array(25, $fitur))
        <li class="@yield('barang')">
            <a class="nav-submenu" data-toggle="nav-submenu" href="#">
            	<i class="si si-vector"></i><span class="sidebar-mini-hide">Barang</span> 
            	@if(session('barang') != null)
					@if (session('barang') > 0)
            			<span class="badge badge-pill badge-warning banitem" style="color: black">{{ session('barang') }}</span>
            		@endif
        		@endif
            </a>
            <ul>
            	@if (in_array(21, $fitur))
                <li>
                    <a class="@yield('barang-list')" href="{{ url('barang') }}">List</a>
                </li>
                @endif
                @if (in_array(22, $fitur))
                <li>
                    <a class="@yield('barang-tambah')" href="{{ url('barang/create') }}">Tambah</a>
                </li>
                @endif
                @if (in_array(25, $fitur))
                <li>
                    <a class="@yield('barang-import')" href="{{ url('barang/import') }}">Import</a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (in_array(26, $fitur) || in_array(27, $fitur))
        <li class="@yield('supplier')">
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-calculator"></i><span class="sidebar-mini-hide">Supplier</span></a>
            <ul>
            	@if (in_array(22, $fitur))
                <li>
                    <a class="@yield('supplier-list')" href="{{ url('supplier') }}">List</a>
                </li>
                @endif
                @if (in_array(22, $fitur))
                <li>
                    <a class="@yield('supplier-tambah')" href="{{ url('supplier/create') }}">Tambah</a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (in_array(30, $fitur))
        <li class="@yield('user')">
            <a class="@yield('user-menu')" href="{{ url('user') }}"><i class="si si-trophy"></i><span class="sidebar-mini-hide">User</span></a>
    	</li>
    	@endif
    </ul>
</div>