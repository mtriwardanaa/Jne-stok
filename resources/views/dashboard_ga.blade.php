@extends('master')

@section('title', 'Dashboard')
@section('dashboard', 'open')
@section('dashboard-menu', 'active')

@section('head-title', 'Dashboard')
@section('head-sub-title', 'Application')

@section('content')
	@include('partial.notification')
	<div class="row invisible" data-toggle="appear">
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-primary" href="{{ url('order') }}">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-bar-chart fa-3x text-primary-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white" data-toggle="countTo" data-speed="1000" data-to="{{ $order_pending }}"></div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Order Pending</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-earth" href="{{ url('barangmasuk') }}">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-trophy fa-3x text-earth-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white"><span data-toggle="countTo" data-speed="1000" data-to="{{ $total_barang_masuk }}"></span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Barang Masuk</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-elegance" href="{{ url('barangkeluar') }}">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-envelope-letter fa-3x text-elegance-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white" data-toggle="countTo" data-speed="1000" data-to="{{ $total_barang_keluar }}"></div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Barang Keluar</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-corporate" href="{{ url('barang') }}?status=warning">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-fire fa-3x text-corporate-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white" data-toggle="countTo" data-speed="1000" data-to="{{ $barang }}"></div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Stok Warning</div>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
    </div>

    <div class="row">
        <div class="col-xl-6">
            <!-- Lines Chart -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Total Rupiah Barang Masuk dan Barang Keluar</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Lines Chart Container -->
                    <br>
                    <div id="chartContainer" style="height: 250px; width: 100%;"></div>
                </div>
            </div>
            <!-- END Lines Chart -->
        </div>
        <div class="col-xl-6">
            <!-- Bars Chart -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Summary Barang Keluar Divisi</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Bars Chart Container -->
                    <br>
                    <div id="chartContainer1" style="height: 250px; width: 100%;"></div>
                </div>
            </div>
            <!-- END Bars Chart -->
        </div>
        <div class="col-xl-6">
            <!-- Bars Chart -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Summary Barang Keluar Sub Agen / Cabang</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Bars Chart Container -->
                    <br>
                    <div id="chartContainer3" style="height: 250px; width: 100%;"></div>
                </div>
            </div>
            <!-- END Bars Chart -->
        </div>
        <div class="col-xl-6">
            <!-- Bars Chart -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Summary Barang Keluar Agen Hybrid</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Bars Chart Container -->
                    <br>
                    <div id="chartContainer2" style="height: 250px; width: 100%;"></div>
                </div>
            </div>
            <!-- END Bars Chart -->
        </div>
    </div>

    <input type="hidden" class="chart_masuk" value="{{ json_encode($masuk) }}">
	<input type="hidden" class="chart_keluar" value="{{ json_encode($keluar) }}">
	<input type="hidden" class="chart_laporan_divisi" value="{{ json_encode($laporan_divisi) }}">
	<input type="hidden" class="chart_laporan_hybrid" value="{{ json_encode($laporan_hybrid) }}">
	<input type="hidden" class="chart_laporan_sub" value="{{ json_encode($laporan_sub) }}">
@endsection

@section('script')
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script type="text/javascript">
		$(document).ready( function () {
			var masuk = JSON.parse($('.chart_masuk').val());
			var keluar = JSON.parse($('.chart_keluar').val());

			var chart2 = new CanvasJS.Chart("chartContainer", {
				theme:"light2",
				animationEnabled: true,
				axisY:{
					title: "Jumlah Rupiah"
				},
				legend:{
					cursor:"pointer",
					itemclick : toggleDataSeries
				},
				toolTip: {
					shared: "true"
				},
				data: [{
					type: "spline",
					showInLegend: true,
					yValueFormatString: "Rp #,##0.##",
					name: "Barang Masuk",
					dataPoints: masuk
				},
				{
					type: "spline",
					showInLegend: true,
					yValueFormatString: "Rp #,##0.##",
					name: "Barang Keluar",
					dataPoints: keluar
				}]
			});

			chart2.render();

			function toggleDataSeries(e) {
				if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
					e.dataSeries.visible = false;
				} else {
					e.dataSeries.visible = true;
				}
				chart.render();
			}

			//////////////////////////////////////

			var laporan_divisi = JSON.parse($('.chart_laporan_divisi').val());
			console.log(laporan_divisi);
			var chart = new CanvasJS.Chart("chartContainer1", {
				theme: "light1", // "light1", "light2", "dark1", "dark2"
				animationEnabled: true,
				data: [{
					type: "pie",
					startAngle: 240,
					yValueFormatString: "##0.00\"%\"",
					indexLabel: "{label} {y}%",
					dataPoints: laporan_divisi
				}]
			});
			chart.render();

			//////////////////////////////////////

			var laporan_hybrid = JSON.parse($('.chart_laporan_hybrid').val());
			console.log(laporan_hybrid);
			var chart = new CanvasJS.Chart("chartContainer2", {
				theme: "light1", // "light1", "light2", "dark1", "dark2"
				animationEnabled: true,
				data: [{
					type: "pie",
					startAngle: 240,
					yValueFormatString: "##0.00\"%\"",
					indexLabel: "{label} {y}%",
					dataPoints: laporan_hybrid
				}]
			});
			chart.render();

			//////////////////////////////////////

			var chart_laporan_sub = JSON.parse($('.chart_laporan_sub').val());
			console.log(chart_laporan_sub);
			var chart = new CanvasJS.Chart("chartContainer3", {
				theme: "light1", // "light1", "light2", "dark1", "dark2"
				animationEnabled: true,
				data: [{
					type: "pie",
					startAngle: 240,
					yValueFormatString: "##0.00\"%\"",
					indexLabel: "{label} {y}%",
					dataPoints: chart_laporan_sub
				}]
			});
			chart.render();
		});
	</script>
@endsection