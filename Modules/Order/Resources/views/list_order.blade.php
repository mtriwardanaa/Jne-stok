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
                    	<button type="button" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Request / Order</button>
                	</a>
	                @if (Auth::user()->id_divisi != 10)
                        <a href="{{ url('/') }}" class="btn btn-sm btn-info">
                           <i class="fa fa-home"></i> Kembali ke Dashboard
                        </a>
	                @endif
                </div>
            </div>
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter datatable-bgsd">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>No Order</th>
                        <th>Tanggal</th>
                        <th>Divisi</th>
                        <th>Sub / Nama</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">Status</th>
                        <th class="text-center" style="width: 15%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach ($list as $key => $value)
	                    <tr>
	                        <td class="text-center">{{ $key+1 }}</td>
	                        <td class="font-w600">{{ $value['no_order'] }}</td>
	                        <td class="font-w600">{{ date('d F Y H:i', strtotime($value['tanggal'])) }}</td>
	                        <td class="font-w600">{{ $value['divisi']['nama'] }}</td>
	                        <td class="font-w600">{{ $value['kategori']['nama'] ?? $value['created_user']['nama'] }}</td>
	                        <td class="d-none d-sm-table-cell">
	                        	@if (isset($value['approved_by']))
	                            	<span class="badge badge-success">DITERIMA</span>
	                            @else
	                            	<span class="badge badge-warning">MENUNGGU</span>
	                            @endif
	                        </td>
	                        <td class="text-center">
                                @if (!isset($value['approved_by']))
                                    @if (Auth::user()->id != $value['created_by'])
                                        <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Anda tidak diperbolehkan meng-edit data order ini, silahkan hubungi {{ ucwords(strtolower($value['created_user']['nama'])) }}">
                                            Edit
                                        </button>
                                    @else
                                        <a href="{{ url('order/edit', $value['id']) }}?status={{ $req }}">
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="tooltip" title="Edit Order">
                                                Edit
                                            </button>
                                        </a>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Order telah di terima oleh admin, tidak dapat diedit kembali">
                                            Edit
                                    </button>
                                @endif
	                        	<a href="{{ url('order/detail', $value['id']) }}?status={{ $req }}">
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
	<script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ url('assets/js/custom.js?') }}"></script>
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