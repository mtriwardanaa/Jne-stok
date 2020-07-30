@extends('master')

@section('title', 'Supplier')
@section('supplier', 'open')
@section('supplier-tambah', 'active')

@section('head-title', 'Supplier')
@section('head-sub-title', 'Tambah')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Tambah Supplier</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-wrench"></i>
                </button>
            </div>
        </div>
        <div class="block-content">
            <form id="formWithPrice" action="{{ url('supplier/store') }}" method="post">
            	@csrf
                <div class="form-group row">
                    <div class="col-md-9">
                        <div class="form-material">
                            <input type="text" class="form-control" name="nama_supplier" placeholder="Masukkan nama supplier" value="{{ old('nama_supplier') }}" required>
                            <label for="material-password">Nama Supplier</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-alt-primary">Simpan Supplier</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection