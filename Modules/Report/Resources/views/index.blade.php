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
	                        <input type="text" class="js-flatpickr form-control" id="example-material-flatpickr-default" name="tanggal_selesai" data-allow-input="true" @if (old('tanggal') != '') value="{{ old('tanggal_selesai') }}" @else value="{{ date('Y-m-d') }}" @endif>
	                        <label for="material-text">Tanggal Selesai</label>
	                    </div>
	                </div>
	                <div class="col-md-3">
	                    <div class="form-material">
	                        <select class="js-select2 form-control" id="example2-select20" name="id_divisi" data-placeholder="Pilih divisi" required>
	                            <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
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
			} else {
				$('.div_agen_kategori').show();
				$('#example2-select21').attr('required', true);
			}
		});
	</script>
@endsection