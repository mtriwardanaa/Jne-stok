<!DOCTYPE html>
<html>
<head>
	<title>Report Pengeluaran Divisi</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<div style="padding-left: 25px;padding-right: 25px;padding-top: 10px">
		<style type="text/css">
			table tr td,
			table tr th{
				font-size: 9pt;
			}

			.download {
				color: #125a96;
			    background-color: #c8e2f8;
			    border-color: #c8e2f8;
			    font-family: "Nunito Sans",-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
    			font-weight: 600;
			}
		</style><br>
		<center>
			<h6>{{ strtoupper("Report Pengeluaran Divisi") }}</h6>
		</center>

		<table>
			<tr>
				<td>Periode</td>
				<td>:</td>
				<td>{{ $post['periode_mulai'] }} s/d {{ $post['periode_selesai'] }}</td>
			</tr>
			<tr>
				<td>Divisi</td>
				<td>:</td>
				<td>{{ $post['title'] }}</td>
			</tr>
			<tr>
				<td>Barang</td>
				<td>:</td>
				<td>{{ $post['title_barang'] }}</td>
			</tr>
			<tr><td> <br/> </td></tr>
			<tr>
				<td><button class="download">DOWNLOAD EXCEL</button></td>
			</tr>
		</table><br>
	 	<form action="{{ url('report/print') }}" id="target" method="post" accept-charset="utf-8">
	 		@csrf
	 		<input type="hidden" name="tanggal_mulai" value="{{ $post['tanggal_mulai'] }}">
	 		<input type="hidden" name="tanggal_selesai" value="{{ $post['tanggal_selesai'] }}">
	 		<input type="hidden" name="id_divisi" value="{{ $post['id_divisi'] }}">
	 		<input type="hidden" name="id_kategori" value="{{ $post['id_kategori'] }}">
	 		<input type="hidden" name="id_agen" value="{{ $post['id_agen'] }}">
	 		<input type="hidden" name="id_barang" value="{{ $post['id_barang'] }}">
	 		<input type="hidden" name="view" value="PRINT">
	 	</form>
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th>TANGGAL ORDER</th>
					<th>TANGGAL KELUAR</th>
					<th>NO TRX</th>
					<th>REQUEST BY</th>
					<th>DIVISI</th>
					<th>PROSES BY</th>
					<th>KODE BARANG</th>
					<th>NAMA BARANG</th>
					<th>JUMLAH BARANG</th>
					<th>HARGA SATUAN</th>
					<th>HARGA TOTAL</th>
					<th>SUKSES</th>
					<th>ALASAN</th>
				</tr>
				@if (isset($data))
					@foreach($data as $key => $value)
						<tr>
							<td>{{ $value['order_tanggal'] }}</td>
							<td>{{ $value['tanggal'] }}</td>
							<td>{{ $value['no_transaksi'] }}</td>
							<td>{{ $value['request_by'] }}</td>
							<td>{{ $value['divisi'] }}</td>
							<td>{{ $value['proses_by'] }}</td>
							<td>{{ $value['kode_barang'] }}</td>
							<td>{{ $value['nama_barang'] }}</td>
							<td>{{ $value['jumlah_barang'] }}</td>
							@if ($key == ($post['total'] - 1))
								<td>{{ $value['harga_satuan'] }}</td>
							@else
								<td>Rp. {{ number_format((float) $value['harga_satuan']) }}</td>
							@endif
							<td>Rp. {{ number_format($value['harga_total']) }}</td>
							<td>{{ $value['success'] }}</td>
							<td>{{ $value['alasan'] }}</td>
						</tr>
					@endforeach
				@else
				@endif
			</thead>
			<tbody>
			</tbody>
		</table>
 	</div>
 	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
 	<script type="text/javascript">
 		$(document).on('click', '.download', function() {
 			$( "#target" ).submit();
 		});
 	</script>
</body>
</html>