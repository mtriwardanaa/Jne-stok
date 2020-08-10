@extends('master')

@section('title', 'List Invoice')
@section('invoice', 'open')
@section('invoice-list', 'active')

@section('head-title', 'Invoice')
@section('head-sub-title', 'List')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Invoice <small>List</small></h3>
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>No Invoice</th>
                        <th>Tanggal</th>
                        <th>Divisi</th>
                        <th>Sub / Nama</th>
                        <th>Dibuat Oleh</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach ($list as $key => $value)
	                    <tr>
	                        <td class="text-center">{{ $key+1 }}</td>
	                        <td class="font-w600">{{ $value['no_invoice'] }}</td>
	                        <td class="font-w600">{{ date('d F Y H:i', strtotime($value['tanggal_invoice'])) }}</td>
	                        <td class="font-w600">{{ $value['stok_barang_keluar']['divisi']['nama'] }}</td>
	                        <td class="font-w600">{{ strtoupper($value['stok_barang_keluar']['kategori']['nama'] ?? $value['stok_barang_keluar']['agen']['nama'] ?? $value['stok_barang_keluar']['nama_user_request']) }}</td>
	                        <td class="font-w600">{{ $value['user']['nama'] }}</td>
	                        <td class="text-center">
	                        	<a href="{{ url('invoice/detail', $value['id']) }}">
	                        		<button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Detail Invoice">
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