@extends('master')

@section('title', 'Barang Keluar')
@section('barang-keluar', 'open')
@section('barang-keluar-tambah', 'active')

@section('head-title', 'Barang Keluar')
@section('head-sub-title', 'Tambah')

@section('css')
		<link rel="stylesheet" href="{{ url('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/js/plugins/flatpickr/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<form action="{{ url('barangkeluar/store') }}" method="post">
	@csrf
		<div class="row">
	        <div class="col-md-4">
	            <!-- Static Labels -->
	            <div class="block">
	                <div class="block-header block-header-default">
	                    <h3 class="block-title">Data barang keluar</h3>
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
	                            <div class="form-material">
	                                <select class="js-select2 form-control" id="example2-select2220" name="id_divisi" style="width: 100%;" data-placeholder="Pilih divisi" required>
	                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
	                                    @foreach ($divisi as $value)
	                                    	<option value="{{ $value['id'] }}" @if ($data['id_divisi'] == $value['id']) selected @endif>{{ $value['nama'] }}</option>
	                                    @endforeach
	                                </select>
	                                <label for="id_divisi">Divisi</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row div_agen_kategori">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <select class="js-select2 form-control" id="example2-select2221" name="id_kategori" style="width: 100%;" data-placeholder="Pilih kategori" required>
	                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
	                                    @foreach ($kategori as $value)
	                                    	<option value="{{ $value['id'] }}" @if ($data['id_kategori'] == $value['id']) selected @endif>{{ $value['nama'] }}</option>
	                                    @endforeach
	                                </select>
	                                <label for="id_divisi">Kategori</label>
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
	                                <select class="js-select2 form-control select_barang" id="example2-select23" name="id_barang[]" style="width: 100%;" data-placeholder="Pilih barang" required>
	                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
	                                    @foreach ($barang as $value)
	                                    	<option value="{{ $value['id'] }}" data-satuan="{{ $value['stok_barang_satuan']['nama_satuan'] }}" data-qty="{{ $value['qty_barang'] }}" data-id="example2-select23">{{ $value['nama_barang'] }}</option>
	                                    @endforeach
	                                </select>
	                                <label for="id_barang[]">Nama Barang</label>
	                            </div>
	                        </div>
	                        <div class="col-2">
	                            <div class="form-material">
	                                <input type="text" class="form-control price number input_jumlah_barang" id="jumlah_example2-select23" data-id="example2-select23" name="jumlah_barang[]" placeholder="jumlah barang" maxlength="10" required>
	                                <label for="jumlah_barang[]">Jumlah</label>
	                            </div>
	                        </div>
	                        <div class="col-2">
	                            <div class="form-material">
	                                <input type="text" class="form-control price number" id="stok_example2-select23" placeholder="stok saat ini" maxlength="10" disabled>
	                                <label>Stok</label>
	                            </div>
	                        </div>
	                        <div class="col-2">
	                            <div class="form-material">
	                                <input type="text" class="form-control" id="satuan_example2-select23" placeholder="satuan barang" disabled>
	                                <label>Satuan</label>
	                            </div>
	                        </div>
	                        <div class="col-2" style="padding-top: 20px">
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
			                                    	<option value="{{ $value['id'] }}" data-satuan="{{ $value['stok_barang_satuan']['nama_satuan'] }}" data-qty="{{ $value['qty_barang'] }}" data-id="example2-select2{{ $key }}" @if ($detail['id_barang'] == $value['id']) selected @endif>{{ $value['nama_barang'] }}</option>
			                                    @endforeach
			                                </select>
			                                <label for="id_barang[]">Nama Barang</label>
			                            </div>
			                        </div>
			                        <div class="col-2">
			                            <div class="form-material">
			                                <input type="text" class="form-control price number input_jumlah_barang" id="jumlah_example2-select2{{ $key }}" data-id="example2-select2{{ $key }}" name="jumlah_barang[]" placeholder="jumlah barang" maxlength="10" required value="{{ $detail['qty_barang'] }}">
			                                <label for="jumlah_barang[]">Jumlah</label>
			                            </div>
			                        </div>
			                        <div class="col-2">
			                            <div class="form-material">
			                                <input type="text" class="form-control price number" id="stok_example2-select2{{ $key }}" placeholder="stok saat ini" maxlength="10" disabled>
			                                <label>Stok</label>
			                            </div>
			                        </div>
			                        <div class="col-2">
			                            <div class="form-material">
			                                <input type="text" class="form-control" id="satuan_example2-select2{{ $key }}" placeholder="satuan barang" disabled>
			                                <label>Satuan</label>
			                            </div>
			                        </div>
			                        @if ($key == 0)
			                        	<div class="col-2" style="padding-top: 20px">
				                            <button type="button" class="btn btn-alt-success btn-tambah">Tambah</button>
				                        </div>
			                        @else
			                        	<div class="col-2" style="padding-top: 20px">
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
    </form>
@endsection

@section('script')
    <script src="{{ url('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>jQuery(function(){ Codebase.helpers(['flatpickr', 'datepicker', 'select2']); });</script>

    <script type="text/javascript">
    	$(function() {
			$('#example2-select20').trigger('change');
			$('.select_barang').trigger('change');
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
    		var qty = $(this).children('option:selected').data('qty');
    		var id = $(this).children('option:selected').data('id');
    		var satuan = $(this).children('option:selected').data('satuan');
    		
    		$('#stok_'+id).val(qty);
    		$('#satuan_'+id).val(satuan);
    	});

    	$(document).on('change', '#example2-select20', function() {
			var val = $(this).val();
			if (val != 23) {
				$('.div_agen_kategori').hide();
				$('#example2-select21').attr('required', false);
			} else {
				$('.div_agen_kategori').show();
				$('#example2-select21').attr('required', true);
			}
		});

    	$(document).on('keyup', '.input_jumlah_barang', function() {
			var id    = $(this).data('id');
			var value = $(this).val();
			var maks  = $('#stok_'+id).val();

    		console.log('id: '+id);
    		console.log('value: '+value);
    		console.log('maks: '+maks);
    		if (maks == '') {
    			Swal.fire({
					title: 'Error',
					text: "Pilih barang terlebih dahulu",
					icon: 'error',
				}).then((result) => {
	  				$(this).val('');
				});

				return;
    		}

    		value = parseInt(value.replace(".", ""));
    		maks = parseInt(maks.replace(".", ""));

    		if (value > maks) {
    			Swal.fire({
					title: 'Error',
					text: "Stok tidak mencukupi",
					icon: 'error',
				}).then((result) => {
	  				$(this).val(maks);
				});

				return;
    		}
		});

    	$(document).on('click', '.btn-tambah', function() {
    		var x = Math.floor((Math.random() * 873) + 1);

    		var bar = JSON.parse($('.all-barang').val());

    		var html_bar = '';

    		$.each( bar, function( key, value ) {
			  	html_bar += '<option value="'+value.id+'" data-satuan="'+value.stok_barang_satuan.nama_satuan+'" data-qty="'+value.qty_barang+'" data-id="example2-select2'+x+'">'+value.nama_barang+'</option>';
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
                            <input type="text" class="form-control price number input_jumlah_barang" id="jumlah_example2-select2'+x+'" data-id="example2-select2'+x+'" name="jumlah_barang[]" placeholder="jumlah barang" maxlength="10" required>\
                            <label for="jumlah_barang[]">Jumlah</label>\
                        </div>\
                    </div>\
                    <div class="col-2">\
                        <div class="form-material">\
                            <input type="text" class="form-control price number" id="stok_example2-select2'+x+'" placeholder="stok saat ini" maxlength="10" disabled>\
                            <label>Stok</label>\
                        </div>\
                    </div>\
                    <div class="col-2">\
                        <div class="form-material">\
                            <input type="text" class="form-control" id="satuan_example2-select2'+x+'" placeholder="satuan barang" disabled>\
                            <label>Satuan</label>\
                        </div>\
                    </div>\
                    <div class="col-2" style="padding-top: 20px">\
                        <button type="button" class="btn btn-alt-danger btn-hapus">Hapus</button>\
                    </div>\
                </div>';

            $(html).appendTo('.form-tambah');

            $('#example2-select2'+x).select2();
            $( ".price" ).on( "keyup", numberFormat);
			$( ".price" ).on( "blur", checkFormat);
    	});
    </script>
@endsection