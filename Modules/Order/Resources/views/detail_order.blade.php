@extends('master')

@section('title', 'Barang Masuk')
@section('barang-masuk', 'open')
@section('barang-masuk-tambah', 'active')

@section('head-title', 'Barang Masuk')
@section('head-sub-title', 'Tambah')

@section('css')
		<link rel="stylesheet" href="{{ url('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/js/plugins/flatpickr/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<form action="{{ url('order/approve/update', $data['id']) }}" method="post">
	@csrf
		<div class="row">
	        <div class="col-md-3">
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
	                                <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-default" placeholder="Masukkan tanggal barang masuk" data-allow-input="true" value="{{ date('Y-m-d', strtotime($data['tanggal'])) }}" disabled>
	                                <label for="material-text">Tanggal</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="js-flatpickr form-control" value="{{ date('H:i', strtotime($data['tanggal'])) }}" disabled>
	                                <label for="material-password">Jam</label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="form-control" value="{{ $data['divisi']['nama'] }}" disabled>
	                                <label for="material-password">Divisi</label>
	                            </div>
	                        </div>
	                    </div>
	                    @if (isset($data['kategori']))
	                    	<div class="form-group row">
		                        <div class="col-md-9">
		                            <div class="form-material">
		                                <input type="text" class="form-control" value="{{ $data['kategori']['nama'] }}" disabled>
		                                <label for="material-password">Agen Daerah</label>
		                            </div>
		                        </div>
		                    </div>
	                    @endif
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
	                    <h3 class="block-title">Detail order</h3>
	                    <div class="block-options">
	                        <button type="button" class="btn-block-option">
	                            <i class="si si-wrench"></i>
	                        </button>
	                    </div>
	                </div>
	                <div class="block-content">
	                	@foreach ($data['details'] as $key => $row)
		                    <div class="form-group row">
		                        <div class="col-9">
		                            <div class="form-material">
		                                <input type="text" class="form-control" placeholder="jumlah barang" maxlength="10" value="{{ $row['stokBarang']['nama_barang'] }}">
		                                <label for="jumlah_barang[]">Barang</label>
		                            </div>
		                        </div>
		                        <div class="col-3">
		                            <div class="form-material">
		                                <input type="text" class="form-control price number" placeholder="jumlah barang" maxlength="10" value="{{ $row['qty_barang'] }}">
		                                <label for="jumlah_barang[]">Jumlah</label>
		                            </div>
		                        </div>
		                    </div>
		                    <hr style="height:1px; color:#333; background-color:#333;">
	                    @endforeach
	                </div>
	            </div>
	            <!-- END Static Labels -->
	        </div>
	    </div>

    </form>
@endsection

@section('script')
    <script src="{{ url('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>jQuery(function(){ Codebase.helpers(['flatpickr', 'datepicker', 'select2']); });</script>

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

    	$(document).on('keyup', '.input_jumlah_barang', function() {
			var value = $(this).val();
			var maks  = $(this).data('stok');

			console.log(value);
			console.log(maks);

    		value = parseInt(value);
    		maks = parseInt(maks);

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
                    <div class="col-2" style="padding-top: 20px">\
                        <button type="button" class="btn btn-alt-danger btn-hapus">Hapus</button>\
                    </div>\
                </div>';

            $(html).appendTo('.form-tambah');

            $('#example2-select2'+x).select2();
            $('#example2-select2'+y).select2();
    	});
    </script>
@endsection