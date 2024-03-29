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
	<form action="{{ url('barangkeluar/store') }}" method="post" id="formWithPrice">
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
	                                <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-default" name="tanggal" placeholder="Masukkan tanggal barang masuk" data-allow-input="true" @if (old('tanggal') != '') value="{{ old('value') }}" @else value="{{ date('Y-m-d') }}" @endif>
	                                <label for="material-text">Tanggal</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-time-standalone-24" name="jam" data-allow-input="true" data-enable-time="true" data-no-calendar="true" data-date-format="H:i" data-time_24hr="true"  placeholder="Masukkan jam barang masuk" @if (old('jam') != '') value="{{ old('value') }}" @else value="{{ date('H:i') }}" @endif>
	                                <label for="material-password">Jam</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <select class="js-select2 form-control" id="example2-select20" name="id_divisi" style="width: 100%;" data-placeholder="Pilih divisi" required>
	                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
	                                    @foreach ($divisi as $value)
	                                    	<option value="{{ $value['id'] }}">{{ $value['nama'] }}</option>
	                                    @endforeach
	                                </select>
	                                <label for="id_divisi">Divisi</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row div_agen_kategori">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <select class="js-select2 form-control" id="example2-select21" name="id_kategori" style="width: 100%;" data-placeholder="Pilih kategori" required>
	                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
	                                    @foreach ($kategori as $value)
	                                    	<option value="{{ $value['id'] }}">{{ $value['nama'] }}</option>
	                                    @endforeach
	                                </select>
	                                <label for="id_divisi">Kategori</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row div_nama_agen">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <select class="js-select2 form-control" id="example2-select22" name="id_agen" style="width: 100%;" data-placeholder="Pilih agen" required>
	                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
	                                    @foreach ($user as $value)
	                                    	<option value="{{ $value['id'] }}" data-lengkap="{{ $value['nama_lengkap'] }}">{{ $value['nama'] }}({{ $value['nama_lengkap'] ?? $value['username'] }})</option>
	                                    @endforeach
	                                </select>
	                                <label for="id_divisi">Nama Agen</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row div_nama_corporate">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <select class="js-select2 form-control" id="example2-select222" name="id_corporate" style="width: 100%;" data-placeholder="Pilih corporate" required>
	                                    <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
	                                    @foreach ($corporate as $value)
	                                    	<option value="{{ $value['id'] }}" data-lengkap="{{ $value['nama_lengkap'] }}">{{ $value['nama'] }}({{ $value['nama_lengkap'] ?? $value['username'] }})</option>
	                                    @endforeach
	                                </select>
	                                <label for="id_divisi">Nama Corporate</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" name="nama_user" class="form-control user-request" placeholder="Nama user yang request" required>
	                                <label for="id_divisi">Nama user request</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <div class="row no-gutters items-push">
                                        <div class="col-6">
                                            <label class="css-control css-control-primary css-radio">
                                                <input type="radio" class="css-control-input distribusi_ya" name="distribusi_sales" value="1">
                                                <span class="css-control-indicator"></span> Ya
                                            </label>
                                            <label class="css-control css-control-danger css-radio">
                                                <input type="radio" class="css-control-input distribusi_tidak" name="distribusi_sales" value="0" checked>
                                                <span class="css-control-indicator"></span> Tidak
                                            </label>
                                        </div>
                                    </div>
	                                <label for="id_divisi">Distribusi Sales</label>
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
	                    <div class="form-tambah"></div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <button type="submit" id="submit" class="btn btn-alt-primary">Simpan Barang Keluar</button>
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
		});

    	$(document).on('click', '.btn-hapus', function() {
    		var txt;
			var r = confirm("Yakin ingin menghapus item??");
			if (r == false) {
				return false;
			}

    		$(this).parent().parent().remove();
    	});

    	$(document).on('change', '#example2-select22', function() {
    		var lengkap = $(this).find(':selected').data('lengkap');
    		console.log(lengkap);

    		$('.user-request').val(lengkap);
    	});

    	$(document).on('change', '#example2-select222', function() {
    		var lengkap = $(this).find(':selected').data('lengkap');
    		console.log(lengkap);

    		$('.user-request').val(lengkap);
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
				console.log('tidak 23');
				$('.div_agen_kategori').hide();
				$('#example2-select21').attr('required', false);
				$('#example2-select21').val('');

				if (val == 13) {
					$('.div_nama_agen').show();
					$('#example2-select22').attr('required', true);

					$('.div_nama_corporate').hide();
					$('#example2-select222').attr('required', false);
					$('#example2-select222').val('');

					$('.distribusi_ya').prop('checked', true);
					$('.distribusi_tidak').prop('checked', false);
				} else if (val == 29) {
					$('.div_nama_agen').hide();
					$('#example2-select22').attr('required', false);
					$('#example2-select22').val('');

					$('.div_nama_corporate').show();
					$('#example2-select222').attr('required', true);

					$('.distribusi_ya').prop('checked', true);
					$('.distribusi_tidak').prop('checked', false);
				} else {
					$('.div_nama_agen').hide();
					$('#example2-select22').attr('required', false);
					$('#example2-select22').val('');

					$('.div_nama_corporate').hide();
					$('#example2-select222').attr('required', false);
					$('#example2-select222').val('');

					$('.distribusi_ya').prop('checked', false);
					$('.distribusi_tidak').prop('checked', true);

					$('.user-request').val('');
				}
			} else {
				console.log('23');
				$('.div_agen_kategori').show();
				$('#example2-select21').attr('required', true);

				$('.div_nama_agen').hide();
				$('#example2-select22').attr('required', false);
				$('#example2-select22').val('');

				$('.div_nama_corporate').hide();
				$('#example2-select222').attr('required', false);
				$('#example2-select222').val('');

				$('.distribusi_ya').prop('checked', false);
				$('.distribusi_tidak').prop('checked', true);
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