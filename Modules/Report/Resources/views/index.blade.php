@extends('master')

@section('title', 'Summary')
@section('report', 'open')
@section('report-list', 'active')

@section('head-title', 'Summary')
@section('head-sub-title', 'Report')

@section('css')
		<link rel="stylesheet" href="{{ url('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/js/plugins/flatpickr/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ url('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Summary <small>Report (pengeluaran divisi)</small></h3>
        </div>
        <div class="block-content block-content-full">
        	<form action="{{ url('report/print') }}" method="post">
			@csrf
	        	<div class="form-group row">
	                <div class="col-md-3">
	                    <div class="form-material">
	                        <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-default" name="tanggal_mulai" data-allow-input="true" @if (old('tanggal') != '') value="{{ old('tanggal_mulai') }}" @else value="{{ date('Y-m-d', strtotime('-7 days')) }}" @endif>
	                        <label for="material-text">Tanggal Mulai</label>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                    <div class="form-material">
	                        <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-default2" name="tanggal_selesai" data-allow-input="true" @if (old('tanggal') != '') value="{{ old('tanggal_selesai') }}" @else value="{{ date('Y-m-d') }}" @endif>
	                        <label for="material-text">Tanggal Selesai</label>
	                    </div>
	                </div>
	            </div>
	            <div class="form-group row">
	                <div class="col-md-3">
	                    <div class="form-material">
	                        <select class="js-select2 form-control" id="example2-select20" name="id_divisi" data-placeholder="Pilih divisi" required>
	                            <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
	                            <option value="all">SEMUA DIVISI</option>
	                            @foreach ($divisi as $value)
	                            	<option value="{{ $value['id'] }}">{{ $value['nama'] }}</option>
	                            @endforeach
	                        </select>
	                        <label for="id_divisi">Divisi</label>
	                    </div>
	                </div>
	                <div class="col-md-3 div_agen_kategori">
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
                    <div class="col-md-3 div_nama_agen">
                        <div class="form-material">
                            <select class="js-select2 form-control" id="example2-select22" name="id_agen" style="width: 100%;" data-placeholder="Pilih agen" required>
                                <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                                @foreach ($user as $value)
                                	<option value="{{ $value['id'] }}">{{ $value['nama'] }}</option>
                                @endforeach
                            </select>
                            <label for="id_divisi">Nama Agen</label>
                        </div>
                    </div>
                    <div class="col-md-3">
	                    <div class="form-material">
	                        <select class="js-select2 form-control" id="example2-select222" name="id_barang" data-placeholder="Pilih barang" required>
	                            <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
	                            <option value="all">SEMUA BARANG</option>
	                            @foreach ($barang as $value)
	                            	<option value="{{ $value['id'] }}">{{ $value['nama_barang'] }}</option>
	                            @endforeach
	                        </select>
	                        <label for="id_divisi">Barang</label>
	                    </div>
	                </div>
	            </div><br>
	            <div class="form-group row">
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-alt-primary">Print Report</button>
                    </div>
                </div>
	        </form>
        </div>
    </div>

    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Stok Opname <small>Report (bulanan)</small></h3>
        </div>
        <div class="block-content block-content-full">
        	<form action="{{ url('report/print/stok') }}" method="post">
			@csrf
	        	<div class="form-group row">
	                <div class="col-md-3">
	                    <div class="form-material">
	                        <select class="js-select2 form-control select_bulan" name="bulan" data-placeholder="Pilih bulan" required>
                            	<option ></option>
                            	<option value="01" @if ($bulan == "01") selected @endif>Januari</option>
                            	<option value="02" @if ($bulan == "02") selected @endif>Februari</option>
                            	<option value="03" @if ($bulan == "03") selected @endif>Maret</option>
                            	<option value="04" @if ($bulan == "04") selected @endif>April</option>
                            	<option value="05" @if ($bulan == "05") selected @endif>Mei</option>
                            	<option value="06" @if ($bulan == "06") selected @endif>Juni</option>
                            	<option value="07" @if ($bulan == "07") selected @endif>Juli</option>
                            	<option value="08" @if ($bulan == "08") selected @endif>Agustus</option>
                            	<option value="09" @if ($bulan == "09") selected @endif>September</option>
                            	<option value="10" @if ($bulan == "10") selected @endif>Oktober</option>
                            	<option value="11" @if ($bulan == "11") selected @endif>November</option>
                            	<option value="12" @if ($bulan == "12") selected @endif>Desember</option>
                            </select>
	                        <label for="material-text">Bulan</label>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                    <div class="form-material">
	                        <select class="js-select2 form-control select_tahun" name="tahun" data-placeholder="Pilih tahun" required>
                            	<option ></option>
                            	@for ($i=date('Y'); $i >= (date('Y')-2); $i--)
                            		<option value="{{ $i }}" @if ($tahun == $i) selected @endif>{{ $i }}</option>
                            	@endfor
                            </select>
	                        <label for="material-text">Tahun</label>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                    <div class="form-material">
	                        <input type="text" class="form-control" name="koordinator" value="Heri Setiawan" required>
	                        <label for="material-text">Koordinator GA</label>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                    <div class="form-material">
	                        <input type="text" class="form-control" name="audit" value="M. Ramdani" required>
	                        <label for="material-text">Autdit Internal</label>
	                    </div>
	                </div>
	            </div><br>
	            <div class="form-group row">
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-alt-primary">Print Report</button>
                    </div>
                </div>
	        </form>
        </div>
    </div>
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

		$(document).on('change', '#example2-select20', function() {
			var val = $(this).val();
			if (val != 23) {
				$('.div_agen_kategori').hide();
				$('#example2-select21').attr('required', false);
				$('#example2-select21').val('');

				if (val == 13) {
					$('.div_nama_agen').show();
					$('#example2-select22').attr('required', true);
				} else {
					$('.div_nama_agen').hide();
					$('#example2-select22').attr('required', false);
					$('#example2-select22').val('');
				}
			} else {
				$('.div_agen_kategori').show();
				$('#example2-select21').attr('required', true);
			}
		});
	</script>
@endsection