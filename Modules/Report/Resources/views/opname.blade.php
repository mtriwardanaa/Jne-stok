<!DOCTYPE html>
<html>
<head>
	<title>Stok OPNAME GA</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<style type="text/css">
	@page {
	  size: A4;
	}
</style>
<body>
	<div style="border: solid black">
		<div style="padding-left: 50px;padding-right: 50px;padding-top: 10px">
			<style type="text/css">
				table tr td,
				table tr th{
					font-size: 9pt;
				}
			</style>
			<div style="float: right;">{{ $opname_no }}</div><br>
			<center>
				<h6>DATA STOK OPNAME INVENTORY</h6>
			</center>

			<table>
				<tr>
					<td>Stok No#</td>
					<td>:</td>
					<td>{{ $stock_no }}</td>
				</tr>
				<tr>
					<td>Group</td>
					<td>:</td>
					<td>{{ $group }}</td>
				</tr>
				<tr>
					<td>Periode</td>
					<td>:</td>
					<td>{{ $text_periode_akhir }}</td>
				</tr>
			</table><br>
		 
			<table class='table table-bordered' style="line-height: 0.0">
				<thead>
					<tr>
						<th><center>No#</center></th>
						<th><center>Kode Barang</center></th>
						<th><center>Nama Barang</center></th>
						<th><center>Jumlah Stok</center></th>
						<th><center>Stok Opname</center></th>
					</tr>
				</thead>
				<tbody>
					@if (!empty($report))
						@foreach ($report as $key => $value)
						<tr>
							<td><center>{{ $key + 1 }}</center></td>
							<td><center>{{ $value['kode_barang'] }}</center></td>
							<td>{{ $value['nama_barang'] }}</td>
							<td @if ($value['stok'] == 0) style="color: red" @endif><center>{{ $value['stok'] }}</center></td>
							<td @if ($value['stok'] == 0) style="color: red" @endif><center>{{ $value['opname'] }}</center></td>
						</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>
	<div style="padding-left: 50px;padding-right: 50px;padding-top: 10px;width: 50%">
		<div style="float: left">
			Pelaksana <br><br><br> <b>{{ $pelaksana }}</b> <br><br><br> Audit Internal <br><br><br> <b>{{ $audit }}</b>
		</div>
		<div style="float: right;">
			Koord. Hc & GA <br><br><br> <b>{{ $koordinator }}</b>
		</div>
	</div>
</body>
</html>