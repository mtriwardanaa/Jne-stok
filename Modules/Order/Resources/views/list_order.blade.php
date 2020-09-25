@extends('master')

@section('title', 'Request / Order')
@section('request', 'open')
@section('request-list', 'active')

@section('head-title', 'Request / Order')
@section('head-sub-title', 'List')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ url('assets/js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
    @php
        $fitur = session()->get('fitur');
    @endphp
	@include('partial.notification')
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Barang Keluar <small>Filter</small></h3>
        </div>
        <div class="block-content block-content-full">
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
            </div><br>
            <div class="form-group row">
                <div class="col-md-9">
                    <button type="button" class="btn btn-alt-primary btn-sub">Submit Filter</button>
                </div>
            </div>
        </div>
    </div>
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Request / Order <small>List</small></h3>
            <div class="block-options">
                <div class="block-options-item">
                    @if (in_array(14, $fitur))
                	<a href="{{ url('order/create') }}">
                    	<button type="button" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Request / Order</button>
                	</a>
                    @endif

	                @if (!in_array(31, $fitur))
                        <a href="{{ url('/') }}" class="btn btn-sm btn-info">
                           <i class="fa fa-home"></i> Kembali ke Dashboard
                        </a>
	                @endif
                </div>
            </div>
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter datatable-bgsd">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>No Order</th>
                        <th>Tanggal</th>
                        <th>Divisi</th>
                        <th>Sub / Nama</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">Status</th>
                        <th>Barang</th>
                        <th class="text-center" style="width: 15%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach ($list as $key => $value)
	                    <tr>
	                        <td class="text-center">{{ $key+1 }}</td>
	                        <td class="font-w600">{{ $value['no_order'] }}</td>
	                        <td class="font-w600">{{ date('d F Y H:i', strtotime($value['tanggal'])) }}</td>
	                        <td class="font-w600">{{ $value['divisi']['nama'] }}</td>
	                        <td class="font-w600">{{ $value['kategori']['nama'] ?? $value['created_user']['nama'] }}</td>
	                        <td class="d-none d-sm-table-cell">
	                        	@if (isset($value['approved_by']))
	                            	<span class="badge badge-success">DITERIMA</span>
	                            @else
	                            	<span class="badge badge-warning">MENUNGGU</span>
	                            @endif
	                        </td>
                            <td class="font-w600">{{ $value['ringkasan'] }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" id="btnGroupDrop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        @if (in_array(13, $fitur))
                                        <a class="dropdown-item" href="{{ url('order/detail', $value['id']) }}?status={{ $req }}">
                                            <i class="fa fa-fw fa-bell mr-5"></i>Detail
                                        </a>
                                        @endif

                                        @if (in_array(15, $fitur))
                                            @if (Auth::user()->id == $value['created_by'])
                                                @if (!isset($value['approved_by']))
                                                    <a class="dropdown-item" href="{{ url('order/edit', $value['id']) }}?status={{ $req }}">
                                                        <i class="fa fa-fw fa-pencil mr-5"></i>Edit
                                                    </a>
                                                @else
                                                    <a class="dropdown-item" href="#" onclick="pesan('Order telah di terima oleh admin, tidak dapat diedit kembali')">
                                                        <i class="fa fa-fw fa-pencil mr-5"></i>Edit
                                                    </a>
                                                @endif
                                            @else
                                                <a class="dropdown-item" href="#" onclick="pesan('Anda tidak diperbolehkan meng-edit data order ini, silahkan hubungi {{ ucwords(strtolower($value['created_user']['nama'])) }}')">
                                                    <i class="fa fa-fw fa-pencil mr-5"></i>Edit
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </td>
	                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
	<script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ url('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>jQuery(function(){ Codebase.helpers(['select2']); });</script>

    <script src="{{ url('assets/js/custom.js?') }}"></script>
    <script>jQuery(function(){ Codebase.helpers('table-tools'); });</script>

    <script type="text/javascript">
    	$(document).on('click', '.btn-delete', function() {
			var id = $(this).data('id');
			Swal.fire({
				title: 'Are you sure?',
				text: "Barang masuk akan dihapus",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then((result) => {
  				if (result.value) {
  					var url = "{{ url('barangmasuk/delete') }}/"+id;
  					window.location.href = url;
  				}
			})
		});

        $(document).on('click', '.btn-sub', function() {
            var bulan = $('.select_bulan').val();
            var tahun = $('.select_tahun').val();

            var url = "{{ url('order') }}?bulan="+bulan+"&tahun="+tahun;
            window.location.href = url;
        });

        function pesan(msg) {
            alert(msg);
        }
    </script>
@endsection