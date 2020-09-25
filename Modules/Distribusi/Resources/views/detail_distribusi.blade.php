@extends('master')

@section('title', 'Detail Distribusi')
@section('distribusi', 'open')
@section('distribusi-list', 'active')

@section('head-title', 'Distribusi')
@section('head-sub-title', 'Detail')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/js/plugins/select2/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@php
        $fitur = session()->get('fitur');
    @endphp
	@include('partial.notification')
		<div class="row">
	        <div class="col-md-3">
	            <!-- Static Labels -->
	            <div class="block">
	                <div class="block-header block-header-default">
	                    <h3 class="block-title">Data Detail</h3>
	                    <div class="block-options">
	                        <button type="button" class="btn-block-option">
	                            <i class="si si-wrench"></i>
	                        </button>
	                    </div>
	                </div>
	                <div class="block-content">
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-default" placeholder="Masukkan tanggal barang masuk" data-allow-input="true" value="{{ date('d-m-Y', strtotime($data['tanggal'])) }}" disabled>
	                                <label for="material-text">Tanggal</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="js-flatpickr form-control" value="{{ date('H:i', strtotime($data['tanggal'])) }}" disabled>
	                                <label for="material-password">Jam</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="form-control" value="{{ $data['divisi']['nama'] }}" disabled>
	                                <label for="material-password">Divisi</label>
	                            </div>
	                        </div>
	                    </div>
	                    @if (isset($data['kategori']))
	                    	<div class="form-group row">
		                        <div class="col-md-9">
		                            <div class="form-material">
		                                <input type="text" class="form-control" value="{{ $data['kategori']['nama'] }}" disabled>
		                                <label for="material-password">Agen Daerah</label>
		                            </div>
		                        </div>
		                    </div>
	                    @endif
	                    @if (isset($data['id_agen']))
	                    	<div class="form-group row">
		                        <div class="col-md-9">
		                            <div class="form-material">
		                                <input type="text" class="form-control" value="{{ $data['agen']['nama'] }}" disabled>
		                                <label for="material-password">Agen</label>
		                            </div>
		                        </div>
		                    </div>
	                    @endif
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="form-control" value="{{ strtoupper($data['nama_user_request']) }}" disabled>
	                                <label for="material-password">User Request</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="form-control" value="{{ $data['user']['nama'] }}" disabled>
	                                <label for="material-password">Dibuat Oleh</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!-- END Static Labels -->
	        </div>
	        <div class="col-md-9">
	        	<div class="block">
			        <div class="block-header block-header-default">
			            <h3 class="block-title">Status</small></h3>
			            <div class="block-options">
			            	@if (in_array(34, $fitur))
				            	@if (!isset($data['tanggal_distribusi']))
	                                <a href="#" class="btn btn-sm btn-warning btn-dis" data-id="{{ $data['id'] }}">
			                            Selesai
			                        </a>
	                            @endif
                            @endif
	                    </div>
			        </div>
			        <div class="block-content block-content-full">
			            <div class="form-group row">
	                        <div class="col-md-3">
	                            <div class="form-material">
	                            	@if (isset($data['tanggal_distribusi']))
	                                	<input type="text" class="form-control" disabled value="SUDAH DIANTAR">
	                                @else
	                                	<input type="text" class="form-control" disabled value="BELUM DIANTAR">
	                                @endif
	                                <label for="material-text">Status Barang</label>
	                            </div>
	                        </div>
	                        <div class="col-md-3">
	                            <div class="form-material">
	                                @if (isset($data['tanggal_distribusi']))
	                                	<input type="text" class="form-control" disabled value="{{ date('d-m-Y H:i', strtotime($data['tanggal_distribusi'])) }}">
	                                @else
	                                	<input type="text" class="form-control" disabled value="-">
	                                @endif
	                                <label for="material-text">Tanggal Distribusi</label>
	                            </div>
	                        </div>
	                        <div class="col-md-3">
	                            <div class="form-material">
	                                @if (isset($data['tanggal_distribusi']))
	                                	<input type="text" class="form-control" disabled value="{{ $data['userDis']['nama'] }}">
	                                @else
	                                	<input type="text" class="form-control" disabled value="-">
	                                @endif
	                                <label for="material-text">Penaggung Jawab</label>
	                            </div>
	                        </div>
	                    </div>
			        </div>
			    </div>
	            <div class="block">
			        <div class="block-header block-header-default">
			            <h3 class="block-title">Detail Barang <small>({{ $data['no_barang_keluar'] }})</small></h3>
			            @if (!in_array(31, $fitur))
		                    <div class="block-options">
		                        <a href="{{ url('/') }}" class="btn btn-sm btn-info">
		                           <i class="fa fa-home"></i> Kembali ke Dashboard
		                        </a>
		                    </div>
		                    <div class="block-options">
		                    	@if (isset($req))
			                        <a href="{{ url('distribusi') }}?status={{ $req }}" class="btn btn-sm btn-danger">
			                           <i class="fa fa-arrow-left"></i> Kembali ke List Order
			                        </a>
			                    @else
			                    	<a href="{{ url('distribusi') }}" class="btn btn-sm btn-danger">
			                           <i class="fa fa-arrow-left"></i> Kembali ke List Distribusi
			                        </a>
			                    @endif
		                    </div>
		                    @endif
			        </div>
			        <div class="block-content block-content-full">
			            <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
			            <table class="table table-bordered table-striped table-vcenter datatable-bgsd">
			                <thead>
			                    <tr>
			                        <th class="text-center">No</th>
			                        <th>Kode Barang</th>
			                        <th>Nama Barang</th>
			                        <th>Jumlah Barang</th>
			                        <th>Harga</th>
			                    </tr>
			                </thead>
			                <tbody>
			                	@foreach ($data['detailStok'] as $key => $row)
				                    <tr>
				                        <td class="text-center">{{ $key+1 }}</td>
				                        <td class="font-w600">{{ $row['stokBarang']['kode_barang'] }}</td>
				                        <td class="font-w600">{{ $row['stokBarang']['nama_barang'] }}</td>
				                        <td class="font-w600">{{ $row['qty_barang'] }} {{ $row['stokBarang']['stokBarangSatuan']['nama_satuan'] }}</td>
				                        <td class="font-w600">Rp. {{ number_format($row['harga_barang']) }}</td>
					                    </tr>
			                    @endforeach
			                </tbody>
			            </table>
			        </div>
			    </div>
	            <!-- END Static Labels -->
	        </div>
	    </div>
@endsection

@section('script')
    <script src="{{ url('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>jQuery(function(){ Codebase.helpers(['datepicker', 'select2']); });</script>
    <script src="{{ url('assets/js/custom.js?') }}"></script>
	<script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ url('assets/js/pages/be_tables_datatables.min.js') }}"></script>

    <script type="text/javascript">
    	$(document).on('click', '.btn-dis', function() {
			var id = $(this).data('id');
			Swal.fire({
				title: 'Are you sure?',
				text: "Distribusi akan diselesaikan",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then((result) => {
  				if (result.value) {
  					var url = "{{ url('distribusi/update') }}/"+id;
  					window.location.href = url;
  				}
			})
		});
    </script>
@endsection