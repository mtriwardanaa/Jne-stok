@extends('master')

@section('title', 'Detail Barang Masuk')
@section('barang-masuk', 'open')
@section('barang-masuk-list', 'active')

@section('head-title', 'Barang Masuk')
@section('head-sub-title', 'Detail')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/js/plugins/select2/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@include('partial.notification')
		<div class="row">
	        <div class="col-md-3">
	            <!-- Static Labels -->
	            <div class="block">
	                <div class="block-header block-header-default">
	                    <h3 class="block-title">Data Barang Masuk</h3>
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
	                                <input type="text" class="form-control" value="{{ $data['user']['nama'] }}" disabled>
	                                <label for="material-password">Dibuat Oleh</label>
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
	        	<div class="block">
			        <div class="block-header block-header-default">
			            <h3 class="block-title">Summary Barang Masuk</small></h3>
			        </div>
			        <div class="block-content block-content-full">
			            <div class="form-group row">
	                        <div class="col-md-3">
	                            <div class="form-material">
	                                <input type="text" class="form-control" disabled value="{{ $post['total_barang'] }} Jenis">
	                                <label for="material-text">Total Barang</label>
	                            </div>
	                        </div>
	                        <div class="col-md-3">
	                            <div class="form-material">
	                                <input type="text" class="form-control" disabled value="{{ $post['total_stok'] }} Stok">
	                                <label for="material-text">Total Stok</label>
	                            </div>
	                        </div>
	                        <div class="col-md-3">
	                            <div class="form-material">
	                                <input type="text" class="form-control" disabled value="Rp. {{ number_format($post['total_harga']) }}">
	                                <label for="material-text">Total Harga</label>
	                            </div>
	                        </div>
	                    </div>
			        </div>
			    </div>
	            <div class="block">
			        <div class="block-header block-header-default">
			            <h3 class="block-title">Detail Barang Masuk <small>({{ $data['no_barang_masuk'] }})</small></h3>
			        </div>
			        <div class="block-content block-content-full">
			            <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
			            <table class="table table-bordered table-striped table-vcenter datatable-bgsd">
			                <thead>
			                    <tr>
			                        <th class="text-center">No</th>
			                        <th>Kode Barang</th>
			                        <th>Nama Barang</th>
			                        <th>Jumlah Barang</th>
			                        <th>Harga</th>
			                        <th>Supplier</th>
			                    </tr>
			                </thead>
			                <tbody>
			                	@foreach ($data['details'] as $key => $row)
				                    <tr>
				                        <td class="text-center">{{ $key+1 }}</td>
				                        <td class="font-w600">{{ $row['stokBarang']['kode_barang'] }}</td>
				                        <td class="font-w600">{{ $row['stokBarang']['nama_barang'] }}</td>
				                        <td class="font-w600">{{ $row['qty_barang'] }} {{ $row['stokBarang']['stokBarangSatuan']['nama_satuan'] }}</td>
				                        <td class="font-w600">Rp. {{ number_format($row['harga_barang']) }}</td>
				                        <td class="font-w600">{{ $row['stokSupplier']['nama_supplier'] }}</td>
					                    </tr>
			                    @endforeach
			                </tbody>
			            </table>
			        </div>
			    </div>
	            <!-- END Static Labels -->
	        </div>
	    </div>
@endsection

@section('script')
    <script src="{{ url('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>jQuery(function(){ Codebase.helpers(['datepicker', 'select2']); });</script>
    <script src="{{ url('assets/js/custom.js?') }}"></script>
	<script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ url('assets/js/pages/be_tables_datatables.min.js') }}"></script>

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