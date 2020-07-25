@extends('master')

@section('title', 'Request / Order')
@section('request', 'open')
@section('request-list', 'active')

@section('head-title', 'Request / Order')
@section('head-sub-title', 'List')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Request / Order <small>List</small></h3>
            <div class="block-options">
                <div class="block-options-item">
                	<a href="{{ url('order/create') }}">
                    	<button type="button" class="btn btn-primary">Tambah Request / Order</button>
                	</a>
                </div>
            </div>
        </div>
        <div class="block-content block-content-full">
	       	<table class="js-table-sections table table-hover">
                <thead>
                    <tr>
                        <th style="width: 30px;"></th>
                        <th>No Order</th>
                        <th>Tanggal</th>
                        <th>User Create</th>
                        <th>User Approve</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if (!empty($list))
                	@foreach ($list as $key => $value)
                	@php
                		// print_r($value);die();
                	@endphp
                		<tbody class="js-table-sections-header">
		                    <tr style="background-color: #f6f6f6;">
		                        <td class="text-center">
		                            <i class="fa fa-angle-right"></i>
		                        </td>
		                        <td class="font-w600">{{ $value['no_order'] }}</td>
		                        <td class="font-w600">{{ date('d F Y H:i', strtotime($value['tanggal'])) }}</td>
		                        <td class="font-w600">{{ $value['created_user']['nama'] }}</td>
		                        <td class="font-w600">{{ $value['approved_user']['nama'] ?? "-" }}</td>
		                        @if (isset($value['approved_by']))
		                        	<td class="font-w600"><span class="badge badge-success">Approved</span></td>
		                        @else
		                        	<td class="font-w600"><span class="badge badge-warning">Pending</span></td>
		                        @endif
		                        <td>
		                        	@if (isset($value['approved_by']))
		                        		<div class="btn-group">
		                                	<a href="#" class="btn btn-sm btn-info" data-toggle="click-ripple" title="Edit"><i class="fa fa-pencil"></i> Approve</a>
	                                    </div>
		                        	@else
		                                <div class="btn-group">
		                                	<a href="{{ url('order/approve', $value['id']) }}" class="btn btn-sm btn-info" data-toggle="click-ripple" title="Edit"><i class="fa fa-pencil"></i> Approve</a>
	                                    </div>
	                                @endif
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $value['id'] }}" data-toggle="click-ripple" title="Delete">
                                            <i class="fa fa-times"></i> Hapus
                                        </button>
                                    </div>
	                            </td>
		                    </tr>
		                </tbody>
		                <tbody>
		                	@if (!empty($value['details']))
	                			<tr>
			                        <td class="text-center"></td>
			                        <td class="font-w600 text-success">Nama Barang</td>
			                        <td class="font-w600 text-info">Jumlah Barang</td>
			                        <td class="font-w600 text-info">Total Approve</td>
			                    </tr>
		                		@foreach ($value['details'] as $row => $detail)
				                    <tr>
				                        <td class="text-center"></td>
				                        <td class="font-w600">{{ $detail['stok_barang']['nama_barang'] }}</td>
				                        <td class="font-w600">{{ $detail['qty_barang'] }} {{ $detail['stok_barang']['stok_barang_satuan']['nama_satuan'] }}</td>
				                        @if (isset($detail['jumlah_approve']))
				                        	<td class="font-w600">{{ $detail['jumlah_approve'] }} {{ $detail['stok_barang']['stok_barang_satuan']['nama_satuan'] }}</td>
				                        @else
				                        	<td class="font-w600">-</td>
				                        @endif
				                    </tr>
		                		@endforeach
		                	@endif
		                </tbody>
                	@endforeach
                @endif
            </table>
        </div>
    </div>
@endsection

@section('script')
	<script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ url('assets/js/pages/be_tables_datatables.min.js') }}"></script>
    <script>jQuery(function(){ Codebase.helpers('table-tools'); });</script>

    <script type="text/javascript">
    	$(document).on('click', '.btn-delete', function() {
			var id = $(this).data('id');
			Swal.fire({
				title: 'Are you sure?',
				text: "Barang masuk akan dihapus",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then((result) => {
  				if (result.value) {
  					var url = "{{ url('barangmasuk/delete') }}/"+id;
  					window.location.href = url;
  				}
			})
		});
    </script>
@endsection