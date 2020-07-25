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
            <a class="block block-link-pop text-right bg-primary" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-bar-chart fa-3x text-primary-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white" data-toggle="countTo" data-speed="1000" data-to="8900">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Sales</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-earth" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-trophy fa-3x text-earth-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white">$<span data-toggle="countTo" data-speed="1000" data-to="2600">0</span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Earnings</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-elegance" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-envelope-letter fa-3x text-elegance-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white" data-toggle="countTo" data-speed="1000" data-to="260">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Messages</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-link-pop text-right bg-corporate" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix border-black-op-b border-3x">
                    <div class="float-left mt-10 d-none d-sm-block">
                        <i class="si si-fire fa-3x text-corporate-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-white" data-toggle="countTo" data-speed="1000" data-to="4252">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-white-op">Online</div>
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
                    <h3 class="block-title">Lines</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Lines Chart Container -->
                    <div id="chartContainer" style="height: 230px; width: 100%;"></div>
                </div>
            </div>
            <!-- END Lines Chart -->
        </div>
        <div class="col-xl-6">
            <!-- Bars Chart -->
            <div class="block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Bars</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full text-center">
                    <!-- Bars Chart Container -->
                    <div id="chartContainer1" style="height: 230px; width: 100%;"></div>
                </div>
            </div>
            <!-- END Bars Chart -->
        </div>
    </div>
@endsection

@section('script')
	<script type="text/javascript">
		$(document).ready( function () {
			var chart = new CanvasJS.Chart("chartContainer", {
				animationEnabled: true,
				theme: "light2",
				title:{
					text: "Simple Line Chart"
				},
				axisY:{
					includeZero: false
				},
				data: [{        
					type: "line",
			      	indexLabelFontSize: 16,
					dataPoints: [
						{ y: 450 },
						{ y: 414},
						{ y: 520, indexLabel: "\u2191 highest",markerColor: "red", markerType: "triangle" },
						{ y: 460 },
						{ y: 450 },
						{ y: 500 },
						{ y: 480 },
						{ y: 480 },
						{ y: 410 , indexLabel: "\u2193 lowest",markerColor: "DarkSlateGrey", markerType: "cross" },
						{ y: 500 },
						{ y: 480 },
						{ y: 510 }
					]
				}]
			});
			chart.render();

			//////////////////////////////////////

			var chart = new CanvasJS.Chart("chartContainer1", {
				animationEnabled: true,
				exportEnabled: true,
				title:{
					text: "Gold Medals Won in Olympics"             
				}, 
				axisY:{
					title: "Number of Medals"
				},
				toolTip: {
					shared: true
				},
				legend:{
					cursor:"pointer",
					itemclick: toggleDataSeries
				},
				data: [{        
					type: "spline",  
					name: "US",        
					showInLegend: true,
					dataPoints: [
						{ label: "Atlanta 1996" , y: 44 },     
						{ label:"Sydney 2000", y: 37 },     
						{ label: "Athens 2004", y: 36 },     
						{ label: "Beijing 2008", y: 36 },     
						{ label: "London 2012", y: 46 },
						{ label: "Rio 2016", y: 46 }
					]
				}, 
				{        
					type: "spline",
					name: "China",        
					showInLegend: true,
					dataPoints: [
						{ label: "Atlanta 1996" , y: 16 },     
						{ label:"Sydney 2000", y: 28 },     
						{ label: "Athens 2004", y: 32 },     
						{ label: "Beijing 2008", y: 48 },     
						{ label: "London 2012", y: 38 },
						{ label: "Rio 2016", y: 26 }
					]
				},
				{        
					type: "spline",  
					name: "Britain",        
					showInLegend: true,
					dataPoints: [
						{ label: "Atlanta 1996" , y: 1 },     
						{ label:"Sydney 2000", y: 11 },     
						{ label: "Athens 2004", y: 9 },     
						{ label: "Beijing 2008", y: 19 },     
						{ label: "London 2012", y: 29 },
						{ label: "Rio 2016", y: 27 }
					]
				},
				{        
					type: "spline",  
					name: "Russia",        
					showInLegend: true,
					dataPoints: [
						{ label: "Atlanta 1996" , y: 26 },     
						{ label:"Sydney 2000", y: 32 },     
						{ label: "Athens 2004", y: 28 },     
						{ label: "Beijing 2008", y: 22 },     
						{ label: "London 2012", y: 20 },
						{ label: "Rio 2016", y: 19 }
					]
				},
				{        
					type: "spline",  
					name: "S Korea",        
					showInLegend: true,
					dataPoints: [
						{ label: "Atlanta 1996" , y: 7 },     
						{ label:"Sydney 2000", y: 8 },     
						{ label: "Athens 2004", y: 9 },     
						{ label: "Beijing 2008", y: 13 },     
						{ label: "London 2012", y: 13 },
						{ label: "Rio 2016", y: 9 }
					]
				},  
				{        
					type: "spline",  
					name: "Germany",        
					showInLegend: true,
					dataPoints: [
						{ label: "Atlanta 1996" , y: 20 },     
						{ label:"Sydney 2000", y: 13 },     
						{ label: "Athens 2004", y: 13 },     
						{ label: "Beijing 2008", y: 16 },     
						{ label: "London 2012", y: 11 },
						{ label: "Rio 2016", y: 17 }
					]
				}]
			});

			chart.render();

			function toggleDataSeries(e) {
				if(typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
					e.dataSeries.visible = false;
				}
				else {
					e.dataSeries.visible = true;            
				}
				chart.render();
			}
		});
	</script>
@endsection