@extends('master')

@section('title', 'Barang')
@section('barang', 'open')
@section('barang-list', 'active')

@section('head-title', 'Barang')
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
            <h3 class="block-title">Barang <small>Filter</small></h3>
        </div>
        <div class="block-content block-content-full">
            <div class="form-group row">
                <div class="col-md-3">
                    <div class="form-material">
                        <select class="js-select2 form-control" id="example2-select20" name="id_divisi"
                            data-placeholder="Pilih divisi" required>
                            <option value="all" @if ($status == 'all') selected @endif>Semua</option>
                            <option value="aman" @if ($status == 'aman') selected @endif>Aman</option>
                            <option value="warning" @if ($status == 'warning') selected @endif>Warning</option>
                        </select>
                        <label for="material-text">Status</label>
                    </div>
                </div>
            </div><br>
            <div class="form-group row">
                <div class="col-md-9">
                    <button type="button" class="btn btn-alt-primary btn-filter">Submit Filter</button>
                </div>
            </div>
        </div>
    </div>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Barang <small>List</small></h3>
            <div class="block-options">
                <a href="{{ url('barang/print', $status) }}" class="btn btn-sm btn-info">
                    Print Barang
                </a>
            </div>
        </div>
        <div class="block-content block-content-full">
            <!-- DataTables functionality is initialized with .js-dataTable-full-pagination class in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
                <thead>
                    <tr>
                        <th style="width: 2%;">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Barang</th>
                        <th>Harga Barang</th>
                        <th>Minimal Stok</th>
                        <th>Satuan</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($list))
                        @foreach ($list as $key => $value)
                            </tr>
                            <td>{{ $key + 1 }}</td>
                            <td><a href="{{ url('barang/history', $value['id']) }}" data-toggle="click-ripple"
                                    title="History"> {{ $value['kode_barang'] }}</a></td>
                            <td>{{ $value['nama_barang'] }}</td>
                            <td>{{ $value['qty_barang'] }}</td>
                            <td>Rp. {{ number_format($value['harga_barang']) ?? '0' }}</td>
                            <td>{{ $value['warning_stok'] }}</td>
                            <td>{{ $value['stok_barang_satuan']['nama_satuan'] }}</td>
                            @if ($value['qty_barang'] < $value['warning_stok'])
                                <td class="font-w600"><span class="badge badge-warning">Warning</span></td>
                            @else
                                <td class="font-w600"><span class="badge badge-success">Aman</span></td>
                            @endif
                            <td class="text-center">
                                @if (in_array(23, $fitur))
                                    <div class="btn-group">
                                        <a href="{{ url('barang/edit', $value['id']) }}" class="btn btn-sm btn-info"
                                            data-toggle="click-ripple" title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                                    </div>
                                @endif

                                @if (in_array(24, $fitur))
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-danger btn-delete"
                                            data-id="{{ $value['id'] }}" data-toggle="click-ripple" title="Delete">
                                            <i class="fa fa-times"></i> Hapus
                                        </button>
                                    </div>
                                @endif
                            </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        jQuery(function() {
            Codebase.helpers(['flatpickr', 'datepicker', 'select2']);
        });
    </script>

    <script src="{{ url('assets/js/pages/be_tables_datatables.min.js') }}"></script>

    <script type="text/javascript">
        $(document).on('click', '.btn-delete', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "Laporan akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.value) {
                    var url = "{{ url('barang/delete') }}/" + id;
                    window.location.href = url;
                }
            })
        });

        $(document).on('click', '.btn-filter', function() {
            var status = $('#example2-select20').val();
            console.log(status);

            var url = "{{ url('barang') }}?status=" + status;
            window.location.href = url;
        });
    </script>
@endsection
