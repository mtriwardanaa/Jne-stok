@extends('master')

@section('title', 'Barang')
@section('barang', 'open')
@section('barang-list', 'active')

@section('head-title', 'Barang')
@section('head-sub-title', 'List')

@section('css')
	<link rel="stylesheet" href="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
	@include('partial.notification')
	<div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Barang <small>History</small></h3>
        </div>
        <div class="block-content block-content-full">
	       	<table class="js-table-sections table table-hover">
                <thead>
                    <tr>
                        <th style="width: 30px;"></th>
                        <th>User</th>
                        <th>Event</th>
                        <th>IP Address</th>
                        <th class="d-none d-sm-table-cell">Date</th>
                    </tr>
                </thead>
                @if (!empty($audits))
                	@foreach ($audits as $key => $value)
                		<tbody class="js-table-sections-header">
		                    <tr style="background-color: #f6f6f6;">
		                        <td class="text-center">
		                            <i class="fa fa-angle-right"></i>
		                        </td>
		                        <td class="font-w600">{{ $value['user']['nama'] }}</td>
		                        <td class="font-w600"><span class="badge badge-info">{{ strtoupper($value['event']) }}</span></td>
		                        <td>
		                            {{ $value['ip_address'] }}
		                        </td>
		                        <td class="d-none d-sm-table-cell">
		                            <em class="text-muted">{{ date('d F Y H:i:s', strtotime($value['created_at'])) }}</em>
		                        </td>
		                    </tr>
		                </tbody>
		                <tbody>
		                    <tr>
		                        <td class="text-center"></td>
		                        <td class="font-w600 text-success">Data Lama</td>
		                        <td class="font-w600 text-warning">Data Baru</td>
		                    </tr>
		                    @for ($i = 0; $i < $value['total']; $i++)
							    <tr>
			                        <td class="text-center"></td>
			                        @php
			                        	$old_keys   = array_keys( $value['old_values'] );
			                        	$new_keys   = array_keys( $value['new_values'] );
			                        @endphp
			                        <td class="font-w600">@if (isset($old_keys[$i])) {{ str_replace('_', ' ', $old_keys[$i]) }} : {{ $value['old_values'][$old_keys[$i]] }} @else - @endif</td>
			                        <td class="font-w600">@if (isset($new_keys[$i])) {{ str_replace('_', ' ', $new_keys[$i]) }} : {{ $value['new_values'][$new_keys[$i]] }} @else - @endif</td>
			                    </tr>
							@endfor
		                </tbody>
                	@endforeach
                @endif
            </table>
        </div>
    </div>
@endsection

@section('script')
	<script src="{{ url('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ url('assets/js/pages/be_tables_datatables.min.js') }}"></script>
    <script>jQuery(function(){ Codebase.helpers('table-tools'); });</script>
@endsection