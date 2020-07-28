@extends('master')

@section('title', 'Barang')
@section('barang', 'open')
@section('barang-import', 'active')

@section('head-title', 'Barang')
@section('head-sub-title', 'Import')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Barang <small>Import</small></h3>
        </div>
        <div class="block-content block-content-full">
        	<form action="{{ url('barang/save') }}" method="post" enctype="multipart/form-data">
        	@csrf
	            <div class="form-group row">
	                <label class="col-12" for="example-file-input">Pilih File Excel</label>
	                <div class="col-12">
	                    <input type="file" id="example-file-input" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" name="import">
	                </div>
	            </div>
	            <div class="form-group row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-alt-primary">Import Barang</button>
                    </div>
                </div>
	        </form>
        </div>
    </div>
@endsection