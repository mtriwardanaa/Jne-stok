@extends('master')

@section('title', 'Detail Surat Jalan')
@section('barang-keluar', 'open')
@section('barang-keluar-list', 'active')

@section('head-title', 'Surat Jalan')
@section('head-sub-title', 'Print')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/js/plugins/select2/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">

	<style type="text/css">
		@media print {
		   .noPrint {display:none;}
		}
	</style>
@endsection

@section('content')
	@include('partial.notification')
		<div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title noPrint">#{{ $detail['no_barang_keluar'] }}</h3>
                <div class="block-options">
                    <!-- Print Page functionality is initialized in Helpers.print() -->
                    <button type="button" class="btn-block-option" onclick="Codebase.helpers('print-page');">
                        <i class="si si-printer"></i> Print Surat Jalan
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
            	<div>
                	<center><h4>SURAT JALAN</h4></center>
                </div>
                <!-- Invoice Info -->
                <div class="row my-20">
                    <!-- Company Info -->
                    <div class="col-6">
                        <address>
                            GENERAL AFFAIR JNE<br>
                            Pontianak<br>
                            Jl. Gusti Hamzah No.35<br>
                            {{ $detail['user']['nama'] }}
                        </address>
                    </div>
                    <!-- END Company Info -->

                    <!-- Client Info -->
                    <div class="col-6 text-right">
                        <address>
                            {{ $detail['divisi']['nama'] }}<br>
                            @if (isset($detail['kategori']))
                            	{{ $detail['kategori']['nama'] }}<br>
                            @endif
                            @if (isset($detail['agen']))
                            	{{ $detail['agen']['nama'] }}<br>
                            @endif
                            a/n {{ strtoupper($detail['nama_user_request']) }}<br>
                            {{ date('Y-m-d H:i', strtotime($detail['tanggal'])) }}<br>
                            #{{ $detail['no_barang_keluar'] }}
                        </address>
                    </div>
                    <!-- END Client Info -->
                </div>
                <!-- END Invoice Info -->

                <!-- Table -->
                <div class="table-responsive push">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-center" style="width: 90px;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@if (isset($detail['detailStok']))
                        		@php
                        			$total = 0;
                        		@endphp
                        		@foreach ($detail['detailStok'] as $key => $value)
                        			<tr>
		                                <td class="text-center">{{ $key + 1 }}</td>
		                                <td>
		                                    <p class="font-w600 mb-5">{{ $value['stokBarang']['kode_barang'] }}</p>
		                                </td>
		                                <td>
		                                    <p class="font-w600 mb-5">{{ $value['stokBarang']['nama_barang'] }}</p>
		                                </td>
		                                <td class="text-center">
		                                	{{ $value['qty_barang'] }} {{ $value['stokBarang']['stokBarangSatuan']['nama_satuan'] }}
		                                </td>
		                            </tr>
                        		@endforeach
                        	@endif
                        </tbody>
                    </table>
                </div>

                <div class="row my-20">
                    <!-- Company Info -->
                    <div class="col-2">
                    </div>

                    <div class="col-3">
                        <address>
                            Penaggung Jawab<br>
                            <br>
                            <br>
                            <br>
                            {{ $detail['user']['nama'] }}
                        </address>
                    </div>
                    <!-- END Company Info -->

                    <!-- Client Info -->
                    <div class="col-3">
                    </div>

                    <div class="col-3">
                        <address>
                            Penerima<br>
                            <br>
                            <br>
                            <br>
                            {{ strtoupper($detail['nama_user_request']) }}
                        </address>
                    </div>
                    <!-- END Client Info -->
                </div><br>
                <!-- END Table -->

                <!-- Footer -->
                <p class="text-muted text-center noPrint">Terima kasih banyak telah berbisnis dengan kami. Kami berharap dapat selalu bekerja sama dengan Anda</p>
                <!-- END Footer -->
            </div>
        </div>
@endsection

@section('script')
@endsection