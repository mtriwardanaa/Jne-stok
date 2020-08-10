@extends('master')

@section('title', 'Detail Invoice')
@section('invoice', 'open')
@section('invoice-list', 'active')

@section('head-title', 'Invoice')
@section('head-sub-title', 'Detail')

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
                <h3 class="block-title">#{{ $detail['no_invoice'] }}</h3>
                <div class="block-options">
                    <!-- Print Page functionality is initialized in Helpers.print() -->
                    <button type="button" class="btn-block-option" onclick="Codebase.helpers('print-page');">
                        <i class="si si-printer"></i> Print Invoice
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <!-- Invoice Info -->
                <div class="row my-20">
                    <!-- Company Info -->
                    <div class="col-6">
                        <p class="h3">Company</p>
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
                        <p class="h3">Client</p>
                        <address>
                            {{ $detail['stokBarangKeluar']['divisi']['nama'] }}<br>
                            @if (isset($detail['stokBarangKeluar']['kategori']))
                            	{{ $detail['stokBarangKeluar']['kategori']['nama'] }}<br>
                            @endif
                            @if (isset($detail['stokBarangKeluar']['agen']))
                            	{{ $detail['stokBarangKeluar']['agen']['nama'] }}<br>
                            @endif
                            a/n {{ strtoupper($detail['stokBarangKeluar']['nama_user_request']) }}<br>
                            {{ date('Y-m-d H:i', strtotime($detail['stokBarangKeluar']['tanggal'])) }}
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
                                <th class="text-right" style="width: 120px;">Harga</th>
                                <th class="text-right" style="width: 120px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@if (isset($detail['stokBarangKeluar']['detailStok']))
                        		@php
                        			$total = 0;
                        		@endphp
                        		@foreach ($detail['stokBarangKeluar']['detailStok'] as $key => $value)
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
		                                @if ($value['harga_barang_invoice'] > 0)
			                                <td class="text-right">Rp. {{ number_format($value['harga_barang_invoice']) }}</td>
			                                <td class="text-right">Rp. {{ number_format($value['harga_barang_invoice'] * $value['qty_barang']) }}</td>
			                            @else
			                            	<td class="text-right">FREE</td>
			                                <td class="text-right">FREE</td>
			                            @endif
		                            </tr>

		                            @php
		                            	$total = $total + ($value['harga_barang_invoice'] * $value['qty_barang']);
		                            @endphp
                        		@endforeach

	                            <tr>
	                                <td colspan="5" class="font-w600 text-right">Total</td>
	                                <td class="text-right">Rp. {{ number_format($total) }}</td>
	                            </tr>
	                        @else
	                        	<tr>
	                                <td class="text-center">-</td>
	                                <td>
	                                    <p class="font-w600 mb-5">-</p>
	                                </td>
	                                <td class="text-center">
	                                	-
	                                </td>
	                            	<td class="text-right">-</td>
	                                <td class="text-right">-</td>
	                            </tr>
	                            <tr>
	                                <td colspan="5" class="font-w600 text-right">Total</td>
	                                <td class="text-right">Rp. -</td>
	                            </tr>
                        	@endif
                        </tbody>
                    </table>
                </div>
                <!-- END Table -->

                <!-- Footer -->
                <p class="text-muted text-center">Terima kasih banyak telah berbisnis dengan kami. Kami berharap dapat selalu bekerja sama dengan Anda</p>
                <!-- END Footer -->
            </div>
        </div>
@endsection

@section('script')
@endsection