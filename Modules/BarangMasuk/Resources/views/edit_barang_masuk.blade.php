@extends('master')

@section('title', 'Barang Masuk')
@section('barang-masuk', 'open')
@section('barang-masuk-list', 'active')

@section('head-title', 'Barang Masuk')
@section('head-sub-title', 'Edit')

@section('css')
		<link rel="stylesheet" href="{{ url('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/js/plugins/flatpickr/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<form action="{{ url('barangmasuk/update', $data['id']) }}" method="post" id="formWithPrice">
	@csrf
		<div class="row">
	        <div class="col-md-3">
	            <!-- Static Labels -->
	            <div class="block">
	                <div class="block-header block-header-default">
	                    <h3 class="block-title">Data barang masuk</h3>
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
	                                <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-default" name="tanggal" placeholder="Masukkan tanggal barang masuk" data-allow-input="true" @if ($data['tanggal'] != '') value="{{ date('Y-m-d', strtotime($data['tanggal'])) }}" @else value="{{ date('Y-m-d') }}" @endif>
	                                <label for="material-text">Tanggal</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-time-standalone-24" name="jam" data-allow-input="true" data-enable-time="true" data-no-calendar="true" data-date-format="H:i" data-time_24hr="true"  placeholder="Masukkan jam barang masuk" @if ($data['tanggal'] != '') value="{{ date('H:i', strtotime($data['tanggal'])) }}" @else value="{{ date('H:i') }}" @endif>
	                                <label for="material-password">Jam</label>
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
	            <!-- Static Labels -->
	            <div class="block">
	                <div class="block-header block-header-default">
	                    <h3 class="block-title">Detail barang</h3>
	                    <div class="block-options">
	                        <button type="button" class="btn-block-option">
	                            <i class="si si-wrench"></i>
	                        </button>
	                    </div>
	                </div>
	                <div class="block-content">
	                	@if (empty($data['details']))
	                		<div class="form-group row">
		                        <div class="col-3">
		                            <div class="form-material">
		                                <select class="js-select2 form-control" id="example2-select23" name="id_barang[]" style="width: 100%;" data-placeholder="Pilih barang" required>
		                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
		                                    @foreach ($barang as $value)
		                                    	<option value="{{ $value['id'] }}">{{ $value['nama_barang'] }}</option>
		                                    @endforeach
		                                </select>
		                                <label for="id_barang[]">Nama Barang</label>
		                            </div>
		                        </div>
		                        <div class="col-2">
		                            <div class="form-material">
		                                <input type="text" class="form-control price number" name="jumlah_barang[]" placeholder="jumlah barang" maxlength="10" required>
		                                <label for="jumlah_barang[]">Jumlah</label>
		                            </div>
		                        </div>
		                        <div class="col-3">
		                            <div class="form-material">
		                                <select class="js-select2 form-control" id="example2-select24" name="supplier_barang[]" style="width: 100%;" data-placeholder="Pilih supplier" required>
		                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
		                                    @foreach ($supplier as $value)
		                                    	<option value="{{ $value['id'] }}">{{ $value['nama_supplier'] }}</option>
		                                    @endforeach
		                                </select>
		                                <label for="supplier_barang[]">Supplier</label>
		                            </div>
		                        </div>
		                        <div class="col-2">
		                            <div class="form-material">
		                                <input type="text" class="form-control price number" id="harga_example2-select23" name="harga[]" placeholder="harga barang" maxlength="10" required>
		                                <label for="harga[]">Harga</label>
		                            </div>
		                        </div>
		                        <div class="col-1" style="padding-top: 20px">
		                            <button type="button" class="btn btn-alt-success btn-tambah">Tambah</button>
		                        </div>
		                    </div>
		                @else
		                	@foreach ($data['details'] as $key => $detail)
		                		<div class="form-group row">
			                        <div class="col-3">
			                            <div class="form-material">
			                                <select class="js-select2 form-control select_barang" id="example2-select2{{ $key }}" name="id_barang[]" style="width: 100%;" data-placeholder="Pilih barang" required>
			                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
			                                    @foreach ($barang as $value)
			                                    	<option value="{{ $value['id'] }}" data-id="example2-select2{{ $key }}" @if ($detail['id_barang'] == $value['id']) selected @endif data-harga="{{ $value['harga_barang'] }}">{{ $value['nama_barang'] }}</option>
			                                    @endforeach
			                                </select>
			                                <label for="id_barang[]">Nama Barang</label>
			                            </div>
			                        </div>
			                        <div class="col-2">
			                            <div class="form-material">
			                                <input type="text" class="form-control price number" name="jumlah_barang[]" placeholder="jumlah barang" maxlength="10" value="{{ $detail['qty_barang'] }}" required>
			                                <label for="jumlah_barang[]">Jumlah</label>
			                            </div>
			                        </div>
			                        <div class="col-3">
			                            <div class="form-material">
			                                <select class="js-select2 form-control" id="example2-select22{{ $key }}" name="supplier_barang[]" style="width: 100%;" data-placeholder="Pilih supplier" required>
			                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
			                                    @foreach ($supplier as $value)
			                                    	<option value="{{ $value['id'] }}" @if ($detail['id_supplier'] == $value['id']) selected @endif>{{ $value['nama_supplier'] }}</option>
			                                    @endforeach
			                                </select>
			                                <label for="supplier_barang[]">Supplier</label>
			                            </div>
			                        </div>
			                        <div class="col-2">
			                            <div class="form-material">
			                                <input type="text" class="form-control price number" id="harga_example2-select2{{ $key }}" name="harga[]" value="{{ $detail['harga_barang'] }}" placeholder="harga barang" maxlength="10" required>
			                                <label for="harga[]">Harga</label>
			                            </div>
			                        </div>
			                        @if ($key == 0)
			                        	<div class="col-1" style="padding-top: 20px">
				                            <button type="button" class="btn btn-alt-success btn-tambah">Tambah</button>
				                        </div>
			                        @else
			                        	<div class="col-1" style="padding-top: 20px">
					                        <button type="button" class="btn btn-alt-danger btn-hapus">Hapus</button>
					                    </div>
			                        @endif
			                        
			                    </div>
		                	@endforeach
	                	@endif
	                    
	                    <div class="form-tambah"></div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <button type="submit" class="btn btn-alt-primary">Submit</button>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!-- END Static Labels -->

	        </div>
	    </div>

	    <input type="hidden" class="all-barang" value="{{ json_encode($barang) }}">
	    <input type="hidden" class="all-sup" value="{{ json_encode($supplier) }}">
    </form>
@endsection

@section('script')
    <script src="{{ url('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>jQuery(function(){ Codebase.helpers(['flatpickr', 'datepicker', 'select2']); });</script>

    <script type="text/javascript">
    	$(document).ready(function() {
		    $( ".price" ).on( "keyup", numberFormat);
			$( ".price" ).on( "blur", checkFormat);
		});

    	$(document).on('click', '.btn-hapus', function() {
    		var txt;
			var r = confirm("Yakin ingin menghapus item??");
			if (r == false) {
				return false;
			}

    		$(this).parent().parent().remove();
    	});

    	$(document).on('change', '.select_barang', function() {
    		var id = $(this).children('option:selected').data('id');
    		var harga = $(this).children('option:selected').data('harga');
    		
    		$('#harga_'+id).val(harga);
    		$('#harga_'+id).trigger('keyup');
    	});

    	$(document).on('click', '.btn-tambah', function() {
    		var x = Math.floor((Math.random() * 873) + 1);
    		var y = Math.floor((Math.random() * 873) + 1);

    		var bar = JSON.parse($('.all-barang').val());
    		var sup = JSON.parse($('.all-sup').val());

    		var html_bar = '';
    		var html_sup = '';

    		$.each( bar, function( key, value ) {
			  	html_bar += '<option value="'+value.id+'" data-id="example2-select2'+x+'" data-harga="'+value.harga_barang+'">'+value.nama_barang+'</option>';
			});

    		$.each( sup, function( key, value ) {
			  	html_sup += '<option value="'+value.id+'">'+value.nama_supplier+'</option>';
			});

    		var html = '<div class="form-group row">\
                    <div class="col-3">\
                        <div class="form-material">\
                            <select class="js-select2 form-control select_barang" id="example2-select2'+x+'" name="id_barang[]" style="width: 100%;" data-placeholder="Pilih barang" required>\
                                <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->\
                                '+html_bar+'\
                            </select>\
                            <label for="id_barang[]">Nama Barang</label>\
                        </div>\
                    </div>\
                    <div class="col-2">\
                        <div class="form-material">\
                            <input type="text" class="form-control price number" name="jumlah_barang[]" placeholder="jumlah barang" maxlength="10" required>\
                            <label for="jumlah_barang[]">Jumlah</label>\
                        </div>\
                    </div>\
                    <div class="col-3">\
                        <div class="form-material">\
                            <select class="js-select2 form-control" id="example2-select2'+y+'" name="supplier_barang[]" style="width: 100%;" data-placeholder="Pilih supplier" required>\
                                <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->\
                                '+html_sup+'\
                            </select>\
                            <label for="supplier_barang[]">Supplier</label>\
                        </div>\
                    </div>\
                    <div class="col-2">\
                        <div class="form-material">\
                            <input type="text" class="form-control price number" id="harga_example2-select2'+x+'" name="harga[]" placeholder="harga barang" maxlength="10" required>\
                            <label for="harga[]">Harga</label>\
                        </div>\
                    </div>\
                    <div class="col-2" style="padding-top: 20px">\
                        <button type="button" class="btn btn-alt-danger btn-hapus">Hapus</button>\
                    </div>\
                </div>';

            $(html).appendTo('.form-tambah');

            $('#example2-select2'+x).select2();
            $('#example2-select2'+y).select2();

            $( ".price" ).on( "keyup", numberFormat);
			$( ".price" ).on( "blur", checkFormat);
    	});
    </script>
@endsection