@extends('master')

@section('title', 'Detail Order')
@section('request', 'open')
@section('request-list', 'active')

@section('head-title', 'Order')
@section('head-sub-title', 'Detail')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/js/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/js/plugins/select2/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@php
        $fitur = session()->get('fitur');
    @endphp
	@include('partial.notification')
	<div class="row">
        <div class="col-md-3">
            <!-- Static Labels -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Data Order</h3>
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
                            <div class="form-material">
                            	@if (isset($data['approved_by']))
                            		<span class="badge badge-success">DITERIMA</span>
                                @else
                                	@if (isset($data['rejected_by']))
                            			<span class="badge badge-danger">DITOLAK</span>
                            		@else
                            			<span class="badge badge-warning">MENUNGGU</span>
                            		@endif
                                @endif
                                <label for="material-password">Status Order</label>
                            </div>
                        </div>
                    </div>
                    @if (!isset($data['kategori']))
	                    <div class="form-group row">
	                        <div class="col-md-9">
	                            <div class="form-material">
	                                <input type="text" class="form-control" value="{{ $data['created_user']['nama'] }}" disabled>
	                                <label for="material-password">Dibuat Oleh</label>
	                            </div>
	                        </div>
	                    </div>
	                @endif
                    <div class="form-group row">
                        <div class="col-md-9">
                            <div class="form-material">
                                <input type="text" class="form-control" value="{{ strtoupper($data['nama_user_request']) }}" disabled>
                                <label for="material-password">Nama User Request</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-9">
                            <div class="form-material">
                                <input type="text" class="form-control" value="{{ strtoupper($data['created_user']['no_hp']) }}" disabled>
                                <label for="material-password">No HP User Request</label>
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
        	@if (isset($data['approved_by']))
	        	<div class="block">
			        <div class="block-header block-header-default">
			            <h3 class="block-title">Data Penerima</small></h3>
			        </div>
			        <div class="block-content block-content-full">
			            <div class="form-group row">
	                        <div class="col-md-3">
	                            <div class="form-material">
	                                <input type="text" class="form-control" disabled value="{{ date('d F Y H:i', strtotime($data['tanggal_approve'])) }}">
	                                <label for="material-text">Tanggal Diterima</label>
	                            </div>
	                        </div>
	                        <div class="col-md-3">
	                            <div class="form-material">
	                                <input type="text" class="form-control" disabled value="{{ $data['approved_user']['nama'] }}">
	                                <label for="material-text">Diterima Oleh</label>
	                            </div>
	                        </div>
	                        <div class="col-md-3">
	                            <div class="form-material">
	                                <input type="text" class="form-control" disabled value="{{ $data['approved_user']['divisi']['nama'] }}">
	                                <label for="material-text">Divisi</label>
	                            </div>
	                        </div>
	                    </div>
			        </div>
			    </div>
			@else
				@if (isset($data['rejected_by']))
					<div class="block">
				        <div class="block-header block-header-default">
				            <h3 class="block-title">Data Penolak</small></h3>
				        </div>
				        <div class="block-content block-content-full">
				            <div class="form-group row">
		                        <div class="col-md-3">
		                            <div class="form-material">
		                                <input type="text" class="form-control" disabled value="{{ date('d F Y H:i', strtotime($data['tanggal_reject'])) }}">
		                                <label for="material-text">Tanggal Ditolak</label>
		                            </div>
		                        </div>
		                        <div class="col-md-3">
		                            <div class="form-material">
		                                <input type="text" class="form-control" disabled value="{{ $data['rejected_user']['nama'] }}">
		                                <label for="material-text">Ditolak Oleh</label>
		                            </div>
		                        </div>
		                        <div class="col-md-3">
		                            <div class="form-material">
		                                <input type="text" class="form-control" disabled value="{{ $data['rejected_user']['divisi']['nama'] }}">
		                                <label for="material-text">Divisi</label>
		                            </div>
		                        </div>
		                        <div class="col-md-3">
		                            <div class="form-material">
		                                <input type="text" class="form-control" disabled value="{{ strtoupper($data['rejected_text']) }}">
		                                <label for="material-text">Alasan</label>
		                            </div>
		                        </div>
		                    </div>
				        </div>
				    </div>
				@else
					@if (count($old) > 0)
						<div class="block mb-20">
		                    <div class="block-content">
		                        <b>	<p style="color: red">PERINGATAN !!! </p>
		                        	@if ($data['id_divisi'] == 13 || $data['id_divisi'] == 23)
		                        		{{ strtoupper($data['created_user']['nama']) }} telah melakukan pemesanan sebanyak {{ count($old) }} kali pada bulan {{ $data['bulan'] }}
		                        	@else
		                        		Divisi {{ strtoupper($data['divisi']['nama']) }} telah melakukan pemesanan sebanyak {{ count($old) }} kali pada bulan {{ $data['bulan'] }}
		                        	@endif
		                        	<br><br>
		                        	Tanggal pemesanan :
		                        	
		                        	@foreach ($old as $key => $value)
		                        		<br>- {{ $value->all }} <a href="{{ url('order/detail', $value->id) }}" target="__blank" title="Detail pemesanan barang">(Klik disini untuk melihat detail pemesanan barang)</a>
		                        	@endforeach
		                        <br>
		                        <br>
		                    </div>
		                </div>
		            @endif
	            @endif
		    @endif
            <div class="block">
		        <div class="block-header block-header-default">
		            <h3 class="block-title">Detail Barang <small>({{ $data['no_order'] }})</small></h3>
	            	@if (in_array(32, $fitur))
		            	@if (!isset($data['approved_by']))
		            		@if (!isset($data['rejected_by']))
					            <div class="block-options">
			                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-popin">
			                            Terima Order
			                        </button>
			                    </div>
			                    <div class="block-options">
			                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $data['id'] }}">
			                            Tolak Order
			                        </button>
			                    </div>
		                    @endif
	                    @endif
                    @endif

                    @if (!in_array(31, $fitur))
                    <div class="block-options">
                        <a href="{{ url('/') }}" class="btn btn-sm btn-info">
                           <i class="fa fa-home"></i> Kembali ke Dashboard
                        </a>
                    </div>
                    <div class="block-options">
                    	@if (isset($req))
	                        <a href="{{ url('order') }}?status={{ $req }}" class="btn btn-sm btn-danger">
	                           <i class="fa fa-arrow-left"></i> Kembali ke List Order
	                        </a>
	                    @else
	                    	<a href="{{ url('order') }}" class="btn btn-sm btn-danger">
	                           <i class="fa fa-arrow-left"></i> Kembali ke List Order
	                        </a>
	                    @endif
                    </div>
                    @endif
		        </div>
		        <div class="block-content block-content-full">
		            <!-- DataTables functionality is initialized with .js-dataTable-full class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
		            <table class="table table-bordered table-striped table-vcenter datatable-bgsd">
		                <thead>
		                    <tr>
		                        <th class="text-center">No</th>
		                        <th>Kode Barang</th>
		                        <th>Nama Barang</th>
		                        <th>Jumlah Order</th>
		                        @if (isset($data['approved_by']))
		                        	<th>Jumlah Diterima</th>
                                @else
		                        	<th>Stok Sekarang</th>
		                        @endif 	
		                    </tr>
		                </thead>
		                <tbody>
		                	@foreach ($data['details'] as $key => $row)
			                    <tr>
			                        <td class="text-center">{{ $key+1 }}</td>
			                        <td class="font-w600">{{ $row['stokBarang']['kode_barang'] }}</td>
			                        <td class="font-w600">{{ $row['stokBarang']['nama_barang'] }}</td>
			                        <td class="font-w600">{{ number_format($row['qty_barang']) }} {{ $row['stokBarang']['stokBarangSatuan']['nama_satuan'] }}</td>
			                        @if (isset($data['approved_by']))
				                        <td class="font-w600">{{ number_format($row['jumlah_approve']) }} {{ $row['stokBarang']['stokBarangSatuan']['nama_satuan'] }}</td>
	                                @else
	                                	@if (Auth::user()->id_divisi != 10)
				                        	<td class="font-w600" style="font-style: italic;">NONE</td>
				                        @else
				                        	<td class="font-w600">{{ number_format($row['stokBarang']['qty_barang']) }} {{ $row['stokBarang']['stokBarangSatuan']['nama_satuan'] }}</td>
				                        @endif
			                        @endif
			                    </tr>
		                    @endforeach
		                </tbody>
		            </table>
		        </div>
		    </div>
            <!-- END Static Labels -->
        </div>
    </div>

    <div class="modal fade" id="modal-popin" tabindex="-1" role="dialog" aria-labelledby="modal-popin" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin modal-xl" role="document">
            <div class="modal-content">
            	<form action="{{ url('order/approve') }}" method="post" id="formWithPrice">
				@csrf
	                <div class="block block-themed block-transparent mb-0">
	                    <div class="block-header bg-primary-dark">
	                        <h3 class="block-title">Data terima order</h3>
	                        <div class="block-options">
	                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
	                                <i class="si si-close"></i>
	                            </button>
	                        </div>
	                    </div>
	                    <input type="hidden" name="id_order" value="{{ $data['id'] }}">
	                    <div class="block-content">
	                    	<div class="block">
		                        <div class="block-content">
		                            <table class="table table-borderless datatable-bgsd">
		                                <thead>
		                                    <tr>
		                                        <th class="text-center"><b>No</th>
		                                        <th><b>Nama Barang</b></th>
		                                        <th><b>Jumlah Order</b></th>
		                                        <th><b>Stok Sekarang</b></th>
		                                        <th><b>Jumlah Diterima</b></th>
		                                    </tr>
		                                </thead><br>
		                                <tbody>
		                                	@foreach ($data['details'] as $key => $row)
		                                		@php
		                                			if (($key+1) % 2 == 0) { 
		                                				$class = 'table-warning';
		                                			} else {
		                                				$class = 'table-active';
		                                			}
		                                		@endphp
			                                    <tr class="{{ $class }}">
			                                        <th class="text-center" scope="row">{{ $key + 1 }}</th>
			                                        <td>{{ $row['stokBarang']['nama_barang'] }}</td>
			                                        <td><b>{{ $row['qty_barang'] }}</b> {{ $row['stokBarang']['stokBarangSatuan']['nama_satuan'] }}</td>
			                                        <td><b>{{ $row['stokBarang']['qty_barang'] }}</b> {{ $row['stokBarang']['stokBarangSatuan']['nama_satuan'] }}</td>
			                                        <td>
			                                        	<input type="hidden" class="stok_sekarang_{{ $key }}" value="{{ $row['stokBarang']['qty_barang'] }}">
			                                        	<input type="hidden" name="id_detail_order[]" value="{{ $row['id'] }}">
			                                        	<div class="input-group" style="width: 75%">
		                                                    <input type="text" class="form-control number jumlah_barang" data-kelas="{{ $key }}" id="example-input2-group2{{ $key }}" name="jumlah[]" placeholder="Jumlah barang yang diterima" required>
		                                                    <div class="input-group-append">
		                                                        <button type="button" class="btn btn-secondary">{{ $row['stokBarang']['stokBarangSatuan']['nama_satuan'] }}</button>
		                                                    </div>
		                                                </div>
		                                            </td>
			                                    </tr>
		                                    @endforeach
		                                </tbody>
		                            </table><br>
		                            <div class="form-group row">
				                        <div class="col-md-9">
				                            <div class="form-material">
				                                <div class="row no-gutters items-push">
			                                        <div class="col-6">
			                                            <label class="css-control css-control-primary css-radio">
			                                                <input type="radio" class="css-control-input distribusi_ya" name="distribusi_sales" value="1"  @if ($data['id_divisi'] == 13) checked @endif>
			                                                <span class="css-control-indicator"></span> Ya
			                                            </label>
			                                            <label class="css-control css-control-danger css-radio">
			                                                <input type="radio" class="css-control-input distribusi_tidak" name="distribusi_sales" value="0" @if ($data['id_divisi'] != 13) checked @endif>
			                                                <span class="css-control-indicator"></span> Tidak
			                                            </label>
			                                        </div>
			                                    </div>
				                                <label for="id_divisi">Distribusi Sales</label>
				                            </div>
				                        </div>
				                    </div>
		                        </div>
		                    </div>
	                    </div>
	                </div>
	                <div class="modal-footer">
	                    <button type="submit" class="btn btn-alt-success">
	                        <i class="fa fa-check"></i> Terima Order
	                    </button>
	                </div>
	            </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>jQuery(function(){ Codebase.helpers(['datepicker', 'select2']); });</script>
	<script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ url('assets/js/custom.js?') }}"></script>

    <script type="text/javascript">
    	$(document).on('keyup', '.jumlah_barang', function() {
    		var kelas = $(this).data('kelas');
    		var value = parseInt($(this).val());
    		var stok = parseInt($('.stok_sekarang_'+kelas).val());

    		if (value > stok) {
    			Swal.fire({
					title: 'Error',
					text: "Stok tidak mencukupi, stok barang hanya "+stok,
					icon: 'error',
				}).then((result) => {
	  				$(this).val(stok);
				});

				return;
    		}
    	});

    	$(document).on('click', '.btn-delete', function() {
			var id = $(this).data('id');
			Swal.fire({
                title: 'Masukkan alasan penolakan order',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Tolak',
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    return fetch(`{{ url('order/tolak') }}/${id}/${login}`)
                        .then(response => {
                            console.log(response);
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                        return response.json()
                    }).catch(error => {
                        Swal.showValidationMessage(
                            `Alasan tidak boleh kosong`
                        )
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    if (result.value.status) {
                        Swal.fire({
                          icon: 'success',
                          title: 'Success',
                          text: 'Update data berhasil',
                        }).then((result) => {
                            var bulan = $('.select_bulan').val();
                            var tahun = $('.select_tahun').val();

                            var url = "{{ url('order/detail') }}/"+id;
                            window.location.href = url;
                        })
                    } else {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Update data gagal',
                        })
                    }
                }
            })
		});
    </script>
@endsection
