@extends('master')

@section('title', 'Request / Order')
@section('request', 'open')
@section('request-list', 'active')

@section('head-title', 'Request / Order')
@section('head-sub-title', 'Tambah')

@section('css')
		<link rel="stylesheet" href="{{ url('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/js/plugins/flatpickr/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<form action="{{ url('order/update', $data['id']) }}" method="post" id="formWithPrice">
	@csrf
		<div class="row">
	        <div class="col-md-4">
	            <!-- Static Labels -->
	            <div class="block">
	                <div class="block-header block-header-default">
	                    <h3 class="block-title">Data order</h3>
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
	                                <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-default" placeholder="Masukkan tanggal barang masuk" data-allow-input="true" @if ($data['tanggal'] != '') value="{{ date('Y-m-d', strtotime($data['tanggal'])) }}" @else value="{{ date('Y-m-d') }}" @endif disabled>
	                                <label for="material-text">Tanggal</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-time-standalone-24"data-allow-input="true" data-enable-time="true" data-no-calendar="true" data-date-format="H:i" data-time_24hr="true"  placeholder="Masukkan jam barang masuk" @if ($data['tanggal'] != '') value="{{ date('H:i', strtotime($data['tanggal'])) }}" @else value="{{ date('H:i') }}" @endif disabled>
	                                <label for="material-password">Jam</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                            	@if (Auth::user()->id_divisi == 13 || Auth::user()->id_divisi == 23)
	                                	<input type="text" name="nama_user" class="form-control" placeholder="Nama user yang request" value="{{ $data['nama_user_request'] }}" required>
	                                @else
	                                	<input type="text" name="nama_user" class="form-control" value="{{ $data['nama_user_request'] }}" placeholder="Nama user yang request" required>
	                                @endif
	                                <label for="id_divisi">Nama</label>
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
	        <div class="col-md-8">
	            <!-- Static Labels -->
	            <div class="block">
	                <div class="block-header block-header-default">
	                    <h3 class="block-title">Detail barang</h3>
	                    @if (Auth::user()->id_divisi != 10)
		                    <div class="block-options">
		                    	<a href="{{ url('/') }}">
			                        <button type="button" class="btn btn-sm btn-success">
			                            Kembali ke dashboard
			                        </button>
		                    	</a>
		                    </div>
		                @endif
	                </div>
	                <div class="block-content">
	                	@if (isset($data['details']))
	                		@foreach ($data['details'] as $key => $det)
	                			<div class="form-group row">
			                        <div class="col-3">
			                            <div class="form-material">
			                                <select class="js-select2 form-control select_barang" id="example2-select2{{ $key+1 }}" name="id_barang[]" style="width: 100%;" data-placeholder="Pilih barang" required>
			                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
			                                    @foreach ($barang as $value)
			                                    	<option value="{{ $value['id'] }}" @if ($det['id_barang'] == $value['id']) selected @endif data-satuan="{{ $value['stok_barang_satuan']['nama_satuan'] }}" data-id="example2-select2{{ $key+1 }}">{{ $value['nama_barang'] }}</option>
			                                    @endforeach
			                                </select>
			                                <label for="id_barang[]">Nama Barang</label>
			                            </div>
			                        </div>
			                        <input type="hidden" name="id_detail_order[]" value="{{  $det['id']}}">
			                        <div class="col-2">
			                            <div class="form-material">
			                                <input type="text" class="form-control price number" value="{{ $det['qty_barang'] }}" name="jumlah_barang[]" placeholder="jumlah barang" maxlength="10" required>
			                                <label for="jumlah_barang[]">Jumlah</label>
			                            </div>
			                        </div>
			                        <div class="col-2">
			                            <div class="form-material">
			                                <input type="text" class="form-control" id="satuan_example2-select2{{ $key+1 }}" placeholder="satuan barang" disabled>
			                                <label>Satuan</label>
			                            </div>
			                        </div>
			                        @if ($key == 0)
				                        <div class="col-3" style="padding-top: 20px">
				                            <button type="button" class="btn btn-alt-success btn-tambah">Tambah Barang</button>
				                        </div>
				                    @else
				                    	<div class="col-3" style="padding-top: 20px">
					                        <button type="button" class="btn btn-alt-danger btn-hapus">Hapus</button>
					                    </div>
				                    @endif
			                    </div>
	                		@endforeach
	                	@endif
	                    <div class="form-tambah"></div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <button type="submit" class="btn btn-alt-primary">Update Order</button>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!-- END Static Labels -->

	        </div>
	    </div>

	    <input type="hidden" class="all-barang" value="{{ json_encode($barang) }}">
    </form>
@endsection

@section('script')
    <script src="{{ url('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>jQuery(function(){ Codebase.helpers(['flatpickr', 'datepicker', 'select2']); });</script>

    <script type="text/javascript">
    	$(document).ready(function() {
    		$('.select_barang').trigger('change');
		});
    </script>

    <script type="text/javascript">
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
    		var satuan = $(this).children('option:selected').data('satuan');
    		
    		$('#satuan_'+id).val(satuan);
    	});

    	$(document).on('click', '.btn-tambah', function() {
    		var x = Math.floor((Math.random() * 873) + 1);
    		var y = Math.floor((Math.random() * 873) + 1);

    		var bar = JSON.parse($('.all-barang').val());

    		var html_bar = '';

    		$.each( bar, function( key, value ) {
			  	html_bar += '<option value="'+value.id+'" data-satuan="'+value.stok_barang_satuan.nama_satuan+'" data-id="example2-select2'+x+'">'+value.nama_barang+'</option>';
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
                    <input type="hidden" name="id_detail_order[]" value="0">\
                    <div class="col-2">\
                        <div class="form-material">\
                            <input type="text" class="form-control price number" name="jumlah_barang[]" placeholder="jumlah barang" maxlength="10" required>\
                            <label for="jumlah_barang[]">Jumlah</label>\
                        </div>\
                    </div>\
                    <div class="col-2">\
                        <div class="form-material">\
                            <input type="text" class="form-control" id="satuan_example2-select2'+x+'" placeholder="satuan barang" disabled>\
                            <label>Satuan</label>\
                        </div>\
                    </div>\
                    <div class="col-3" style="padding-top: 20px">\
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