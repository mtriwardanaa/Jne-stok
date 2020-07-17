@extends('master')

@section('title', 'User')
@section('user', 'open')
@section('user-menu', 'active')

@section('head-title', 'User')
@section('head-sub-title', 'List')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">User <small>List</small></h3>
        </div>
        <div class="block-content block-content-full">
            <!-- DataTables functionality is initialized with .js-dataTable-full-pagination class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>No HP</th>
                        <th>Divisi</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($user))
                    	@foreach($user as $key => $value)
	                    	</tr>
	                    		<td>{{ $key+1 }}</td>
	                    		<td>{{ $value['nama'] }}</td>
	                    		<td>{{ $value['username'] }}</td>
	                    		<td>{{ $value['no_hp'] }}</td>
	                    		<td>{{ $value['divisi']['nama'] ?? "-" }}</td>
	                    		<td>{{ $value['kategori']['nama'] ?? "-" }}</td>
                            </tr>
                    	@endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
	<script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ url('assets/js/pages/be_tables_datatables.min.js') }}"></script>
@endsection