@extends('master')

@section('title', 'Supplier')
@section('supplier', 'open')
@section('supplier-list', 'active')

@section('head-title', 'Supplier')
@section('head-sub-title', 'List')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Supplier <small>List</small></h3>
        </div>
        <div class="block-content block-content-full">
            <!-- DataTables functionality is initialized with .js-dataTable-full-pagination class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter datatable-bgsd">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($supp))
                    	@foreach($supp as $key => $value)
	                    	</tr>
	                    		<td>{{ $key+1 }}</td>
	                    		<td>{{ $value['nama_supplier'] }}</td>
	                    		<td class="text-center">
	                                <div class="btn-group">
	                                	<a href="{{ url('supplier/edit', $value['id']) }}" class="btn btn-sm btn-info" data-toggle="click-ripple" title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $value['id'] }}" data-toggle="click-ripple" title="Delete">
                                            <i class="fa fa-times"></i> Hapus
                                        </button>
	                            </td>
                            </tr>
                    	@endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('assets/js/custom.js') }}"></script>
	<script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ url('assets/js/pages/be_tables_datatables.min.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', '.btn-delete', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "Supplier akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.value) {
                    var url = "{{ url('supplier/delete') }}/"+id;
                    window.location.href = url;
                }
            })
        });
    </script>
@endsection