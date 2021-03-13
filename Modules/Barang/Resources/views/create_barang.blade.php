@extends('master')

@section('title', 'Barang')
@section('barang', 'open')
@section('barang-tambah', 'active')

@section('head-title', 'Barang')
@section('head-sub-title', 'Tambah')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Tambah Barang</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-wrench"></i>
                </button>
            </div>
        </div>
        <div class="block-content">
            <form id="formWithPrice" action="{{ url('barang/store') }}" method="post">
            	@csrf
                <div class="form-group row">
                    <div class="col-md-9">
                        <div class="form-material">
                            <input type="text" class="form-control" name="kode_barang" placeholder="Masukkan kode barang" value="{{ old('kode_barang') }}" required>
                            <label for="material-text">Kode Barang</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-9">
                        <div class="form-material">
                            <input type="text" class="form-control" name="nama_barang" placeholder="Masukkan nama barang" value="{{ old('nama_barang') }}" required>
                            <label for="material-password">Nama Barang</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                	<div class="col-4">
                        <div class="form-material">
                            <input type="text" class="form-control number price" maxlength="20" id="material-gridff" value="{{ old('harga_barang') }}" name="harga_barang" placeholder="Masukkan harga barang saat ini" required>
                            <label for="material-gridf">Harga Barang</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-material">
                            <input type="text" class="form-control number price" maxlength="20" id="material-gridf" value="{{ old('warning_stok') }}" name="warning_stok" placeholder="Masukkan jumlah minimal barang digudang" required>
                            <label for="material-gridf">Minimal Stok</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-material">
                            <select class="form-control select_satuan_barang" name="id_satuan_barang" required>
                            	@if (!empty($satuan))
                            		<option value="" @if (old('id_satuan_barang') == "") selected @endif>Pilih satuan</option>
                            		@foreach ($satuan as $key => $value)
                               	 		<option value="{{ $value['id'] }}" @if (old('id_satuan_barang') == $value['id']) selected @endif>{{ $value['nama_satuan'] }}</option>
                            		@endforeach
                            	@endif
                                <option value="#">+ Tambah Baru</strong></option>
                            </select>
                            <label for="material-select">Pilih satuan barang</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-9">
                        <div class="form-material">
                            <label for="material-password">Status Barang</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        <div class="form-material">
                            <label class="css-control css-control-primary css-switch">
                                <input type="checkbox" class="css-control-input" name="internal" checked>
                                <span class="css-control-indicator"></span> INTERNAL
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-material">
                            <label class="css-control css-control-warning css-switch">
                                <input type="checkbox" class="css-control-input" name="agen" checked>
                                <span class="css-control-indicator"></span> AGEN
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-material">
                            <label class="css-control css-control-success css-switch">
                                <input type="checkbox" class="css-control-input" name="subagen" checked>
                                <span class="css-control-indicator"></span> SUBAGEN
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-material">
                            <label class="css-control css-control-danger css-switch">
                                <input type="checkbox" class="css-control-input" name="corporate" checked>
                                <span class="css-control-indicator"></span> CORPORATE
                            </label>
                        </div>
                    </div>
                </div><hr>
                <div class="form-group row">
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-alt-primary">Simpan Barang</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
	<script type="text/javascript">
		$(document).on('change', '.select_satuan_barang', function() {
	        if ($(this).val() == '#') {
	          	var newThing = prompt('Tambah satuan baru:');
	          	if(newThing){
	             	var newValue = 'ThIsiSNeW#' + newThing;
	              	$('<option>')
	                  .text(newThing)
	                  .attr('value', newValue)
	                  .insertAfter($('option[value=""]', this));
	              	$(this).val(newValue);
	          	}else {
	              $(this).val('');
	          	}
	        }
	      });
	</script>
@endsection