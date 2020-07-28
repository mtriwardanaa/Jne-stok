@extends('master')

@section('title', 'Dashboard')
@section('dashboard', 'open')
@section('dashboard-menu', 'active')

@section('head-title', 'Dashboard')
@section('head-sub-title', 'Application')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<div class="row invisible" data-toggle="appear">
        <div class="col-6 col-xl-3">
            <a class="block text-center" href="javascript:void(0)">
                <div class="block-content ribbon" style="background-color: #26c6da">
                    <div class="ribbon-box">{{ $total_order }}</div>
                    <p class="mt-5">
                        <i class="si si-bar-chart fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white" style="font-size: 22px;">Data Order / Request</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block text-center" href="javascript:void(0)">
                <div class="block-content ribbon" style="background-color: #ffca28">
                    <div class="ribbon-box">{{ $total_pending }}</div>
                    <p class="mt-5">
                        <i class="si si-book-open fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white" style="font-size: 22px;">Order Pending</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block text-center" href="{{ url('order/create') }}">
                <div class="block-content bg-earth">
                    <p class="mt-5">
                        <i class="si si-basket-loaded fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white" style="font-size: 22px;">Request / Order</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block text-center" href="{{ route('logout') }}">
                <div class="block-content bg-red" style="background-color: #ef5350">
                    <p class="mt-5">
                        <i class="si si-settings fa-3x text-white-op"></i>
                    </p>
                    <p class="font-w600 text-white" style="font-size: 22px;">Logout</p>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
    </div>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">List Order<small>(10 Data Terakhir)</small></h3>
        </div>
        <div class="block-content block-content-full">
            <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>No Order</th>
                        <th>Divisi</th>
                        <th>Sub Agen</th>
                        <th class="d-none d-sm-table-cell">Tanggal</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">Status</th>
                        <th class="text-center" style="width: 15%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach ($order as $key => $value)
	                    <tr>
	                        <td class="text-center">{{ $key+1 }}</td>
	                        <td class="font-w600">{{ $value['no_order'] }}</td>
	                        <td class="font-w600">{{ $value['divisi']['nama'] }}</td>
	                        <td class="font-w600">{{ $value['kategori']['nama'] ?? "-" }}</td>
	                        <td class="d-none d-sm-table-cell">{{ date('d F Y', strtotime($value['tanggal'])) }}</td>
	                        <td class="d-none d-sm-table-cell">
	                        	@if (isset($value['approved_by']))
	                            	<span class="badge badge-success">DITERIMA</span>
	                            @else
	                            	<span class="badge badge-warning">MENUNGGU</span>
	                            @endif
	                        </td>
	                        <td class="text-center">
	                        	<a href="{{ url('order/detail', $value['id']) }}">
	                        		<button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Detail Order">
		                                Detail
		                            </button>
	                        	</a>
	                        </td>
	                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
	<script src="assets/js/pages/be_pages_dashboard.min.js"></script>

    <script src="{{ url('assets/js/custom.js') }}"></script>
	<script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ url('assets/js/pages/be_tables_datatables.min.js') }}"></script>
@endsection