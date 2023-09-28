@extends("admin.layouts.app")

@section('content')
<div class="page-inner">
	{{-- <div class="page-header">
		<h4 class="page-title">{{""}}</h4>
		<ul class="breadcrumbs">
			<li class="nav-home">
				<a href="#">
					<i class="flaticon-home"></i>
				</a>
			</li>
			<li class="separator">
				<i class="flaticon-right-arrow"></i>
			</li>
			<li class="nav-item">
				<a href="{{ route('private.dashboard') }}">Dashboard</a>
			</li>
			<li class="separator">
				<i class="flaticon-right-arrow"></i>
			</li>
			<li class="nav-item">
				<a href="#">{{ "" }}</a>
			</li>
		</ul>
	</div> --}}
	<div class="row">
		<div class="col-md-12">
			<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
				<div>
					<h2 class=" pb-2 fw-bold">Dashboard</h2>
					{{-- <h5 class=" op-7 mb-2">Premium Bootstrap 4 Admin Dashboard</h5> --}}
				</div>
			</div>
			<div class="page-inner mt--5">
				<div class="row mt--2">
					<div class="col-md-12">
						<div class="card full-height">
						
						
							<div class="card-body over-all">
								<div class="card-title">Overall statistics</div>
									<div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
										<div class="px-2 pb-2 pb-md-0 text-center">
											<i class="fas fa-user-cog icon-blue"></i>
												<h6 class="fw-bold mt-3 mb-0">Admins</h6>
													<h4 class="card-title">{{$admin_count}}</h4>
										</div>
										<div class="px-2 pb-2 pb-md-0 text-center">
											<i class="fas fa-users icon-blue"></i>
												<h6 class="fw-bold mt-3 mb-0">Technicians</h6>
													<h4 class="card-title">{{$tech_count}}</h4>
										</div>
										<div class="px-2 pb-2 pb-md-0 text-center">
											<i class="fas fa-user-tie icon-blue"></i>
												<h6 class="fw-bold mt-3 mb-0">Engineers</h6>
													<h4 class="card-title">{{$engineer_count}}</h4>
										</div>
										<div class="px-2 pb-2 pb-md-0 text-center">
											<i class="fas fa-user-tie icon-blue"></i>
												<h6 class="fw-bold mt-3 mb-0">Managers</h6>
													<h4 class="card-title">{{$manager_count}}</h4>
										</div>
										<div class="px-2 pb-2 pb-md-0 text-center">
											<i class="far fa-calendar-alt icon-blue"></i>
												<h6 class="fw-bold mt-3 mb-0">Schedules</h6>
													<h4 class="card-title">{{$schedule_count}}</h4>
										</div>
										<div class="px-2 pb-2 pb-md-0 text-center">
											<i class="fab fa-wpforms icon-blue"></i>
												<h6 class="fw-bold mt-3 mb-0">Forms</h6>
													<h4 class="card-title">{{$form_count}}</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
			<div class="col-md-12">
				<div class="card full-height">
					<div class="card-body pie-chart">
						<div class="card-title">Total PM & Breakdown statistics
						<input type='button' id='btn' class="btn btn-primary btn-sm float-right print" value='Print' onclick='printDiv1();'>
						</div>
							<div class="row p-2 filter-cont mb-4">
							     <div class="col-md-3 pl-0">
    								<div class="form-group pt-0">
    									<label>Created Date</label>
    									<input type="text" class="form-control bg-white" readonly id="date-filters">
    								</div>
    							</div>
    						</div>
    						<div class="row">
								<div class="col-md-6">
									<div class="chart-container"><div class="chartjs-size-monitor" ><div class="chartjs-size-monitor-expand" ><div></div></div><div class="chartjs-size-monitor-shrink"><div></div></div></div>
									<div id="doughnut-chart">
									    <canvas id="doughnutChart"  width="463" height="300" class="chartjs-render-monitor"></canvas>
									</div>
								</div>
										<p class="text-center"><b>PM Schedule</b></p>
								</div>
								<div class="col-md-6">
									<div class="chart-container"><div class="chartjs-size-monitor" ><div class="chartjs-size-monitor-expand" ><div></div></div><div class="chartjs-size-monitor-shrink"><div></div></div></div>
										<div id="doughnut-chart2">
										    <canvas id="doughnutChart2"  width="463" height="300" class="chartjs-render-monitor"></canvas>
										</div>
									</div>
										<p class="text-center"><b>Breakdown Schedule</b></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="card full-height">
					<div class="card-body bar-chart">
						<div class="card-title">Preventive Maintenance & Breakdown  Achievement</div>
						<div class="row p-2 filter-cont mb-4">
							<div class="col-md-3 pl-0">
								<div class="form-group pt-0">
									<label>Status</label>
									<select class="select-filter form-control" data-placeholder="Select a Status" id="status-filter">
										<option value="0">PM Module</option>
										<option value="1">Breakdown Module</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 pl-0">
								<div class="form-group pt-0">
									<label>Created Date</label>
									<input type="text" class="form-control bg-white" readonly id="date-filter">
								</div>
							</div>
							<div class="col-md-3 pl-0 ml-auto">
								<div class="form-group pt-10 float-right ">
								<input type='button' id='btn'  class="btn btn-primary btn-sm print" value='Print' onclick='printDiv();'>
								</div>
							</div>
						</div>
						<div class="row" >
								<div class="col-md-3">
									<h6 class="fw-bold text-uppercase text-success op-8 text-center">Total</h6>
									<h3 class="fw-bold total text-center"></h3>
								</div>
								<div class="col-md-3">
									<h6 class="fw-bold text-uppercase text-danger op-8 text-center">Today</h6>
									<h3 class="fw-bold today text-center"></h3>
								</div>
								<div class="col-md-3">
									<h6 class="fw-bold text-uppercase text-danger op-8 text-center">This Month</h6>
									<h3 class="fw-bold month text-center"></h3>
								</div>
								<div class="col-md-3">
									<h6 class="fw-bold text-uppercase text-danger op-8 text-center">This Year</h6>
									<h3 class="fw-bold year text-center"></h3>
								</div>
						</div>
						<hr>
						<div class="module-print" id="DivIdToPrint">
							<div class="col-md-12">
								<div id="chart-container">
									<canvas id="totalIncomeChart" ></canvas>
								</div>
								<p class="text-center"><b class="chart-year"></b></p>
								<div class="row">
									<div class="mx-auto table_cls">
										<table>
											<thead>
												<tr class="heading">
													<th class="rename">ASSEMBLY SHOP MBM</th>
												<tr>
											</thead>
											<tbody>
												<tr class="target">
													<td>Target</td>
												</tr>
												<tr class="acctual">
													<td>Actual</td>
												</tr>
												<tr class="percentage">
													<td>Percentage</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="card full-height">
					<div class="card-body line-chart">
						<div class="card-title">Overall Equipment Downtime MBM Assembly Shop & Cumulative Downtime (mins)</div>
						<div class="row p-2 filter-cont mb-4">
							<div class="col-md-3 pl-0">
								<div class="form-group pt-0">
									<label>Line</label>
									<select class="select-filter form-control" data-placeholder="Select a Status" id="month-status">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">S/ASSY MECH</option>
											<option value="5">RECTIFICATION</option>
											<option value="6">FINISH/FINAL</option>
											<option value="0">0</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 pl-0">
								<div class="form-group pt-0">
									<label>Choose Type</label>
									<select class="select-filter form-control" data-placeholder="Select a Status" id="type-status">
											<option value="1">Month</option>
											<option value="2">Year</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 pl-0" id="month">
								<div class="form-group pt-0" >
									<label>Choose Month</label>
									<input type="text" class="form-control bg-white value" readonly id="month-filter">
								</div>
							</div>
							<div class="col-md-3 pl-0" id="year-status">
								<div class="form-group pt-0" >
									<label>Choose Year</label>
									<input type="text" class="form-control bg-white value" readonly id="year-filter">
								</div>
							</div>
							<div class="col-md-3 pl-0 ">
								<div class="form-group pt-10 float-right">
								<input type='button' id='btn' class="btn btn-primary btn-sm print" value='Print' onclick='printDiv2();'>
								</div>
							</div>
						</div>
						<hr>
						<div class="module-print">
							<div class="col-md-12">
								<div id="line-chart-container">
									<canvas id="totalLineChart" ></canvas>
								</div>
								<p class="text-center"><b class="line-chart-year"></b></p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-12 performance-chart">
				<div class="card full-height">
					<div class="card-body">
						<div class="card-title">Monthly Machine Performance</div>
						<div class="row p-2 filter-cont mb-4">
							<div class="col-md-3 pl-0" id="month">
								<div class="form-group pt-0" >
									<label>Choose Month</label>
									<input type="text" class="form-control bg-white value" readonly id="performance-month-filter">
								</div>
							</div>
							<div class="col-md-3 pl-0 ml-auto float-right">
								<div class="form-group pt-10">
								<input type='button' id='btn' class="btn btn-primary btn-sm print" value='Print' onclick='printDiv3();'>
								</div>
							</div>
						</div>
						<hr>
						<div class="module-print">
							<div class="col-md-12">
								<div id="performance-chart-container">
									<canvas id="PerformanceChart" ></canvas>
								</div>
								<div class="row justify-content-center align-items-center mt-5">
									<div class="perforamance_table">
									    <table>
                                            <tr>
                                                <th colspan="3">MBM ASSEMBLY SHOP MAINTENANCE DOWNTIME SUMMARY</th>
                                            </tr>
                                            <tr>
                                                <td>TOTAL ACTUAL
                                                    MACHINE
                                                    BREAKDOWN
                                                    (MIN)<br><spen id="performance_bk" style="color:#ff4c4c;font-weight:bold;font-size:22px"></spen>
                                                </td>
                                                <td>
                                                    <table id="past_data">
                                                    <thead>
                                                        <tr>
                                                            <th>Line</th>
                                                            <th>Production Hour (min)</th>
                                                            <th>Actual Machine Breakdown (min)</th>
                                                            <th>Actual Prod/ Downtime (min)</th>
                                                            <th>Machine Uptime %</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </td>
                                                <td>
                                                    Machine
                                                    uptime<br><spen id="performance_ut" style="color:#ff4c4c;font-weight:bold;font-size:22px"></spen>
                                                </td>
                                            </tr> 
                                        </table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>


@endsection
@push("css")
<style>
.card-stats a{
text-decoration: none;
}
.icon-blue {
    font-size: 75px;
    color: #030048;
}
.navbar-toggler .navbar-toggler-icon {
    color: #030048  !important;
}
.navbar .navbar-nav .notification{
	background-color: #1a164e !important;
}
.card-stats a{
text-decoration: none;
}
table {
  border-collapse: collapse;
}
/* .table_cls
{
	overflow-x: scroll;
} */
#totalIncomeChart
{
	width:965px !important;
	height: 250px !important;
}
table, th, td {
  border: 1px solid black;
  padding: 2px;
}
tr.heading{
	background-color:#f1f1f1;
	font-weight: bold;
}
#month-filter > div { display: inherit; }
.perforamance_table th,td{
	padding: 5px !important;
	text-align: center !important;
}
.btn-primary {
    background-color:#030048 !important;
}
.navbar-header[data-background-color="blue2"] {
    background: #030048 !important;
}


</style>
@endpush
@push("js")

<script type="text/javascript">

function print1(){
  window.print();
  window.location.href = "{{asset('private/dashboard')}}";
}

function printDiv1() 
{
    $(".card-body").css({"width": "60%"});
	$(".sidebar").remove();
	$(".main-header").remove();
	$(".over-all").hide();
	$(".pie-chart").show();
	$(".main-panel").css({"width":"calc(100% - -10px)"});
	$(".bar-chart").hide();
	$(".line-chart").hide();
	$(".performance-chart").hide();
    $(".print").remove();
    setTimeout(print1, 1000);
	
}

function printDiv()
{
    $(".card-body").css({"width": "60%"});
	$(".sidebar").remove();
	$(".main-header").remove();
	$(".over-all").hide();
	$(".pie-chart").hide();
	$(".mx-auto").removeClass('table_cls');
	$('.rename').html("MBM");
	$(".main-panel").css({"width":"calc(100% - -10px)"});
	$(".bar-chart").show();
	$(".line-chart").hide();
	$(".performance-chart").hide();
    $(".print").remove();
    setTimeout(print1, 1000);

}
function printDiv2()
{
    $(".card-body").css({"width": "60%"});
	$(".sidebar").remove();
	$(".main-header").remove();
	$(".over-all").hide();
	$(".pie-chart").hide();
	$(".main-panel").css({"width":"calc(100% - -10px)"});
	$(".bar-chart").hide();
	$(".line-chart").show();
	$(".performance-chart").hide();
    $(".print").remove();
    setTimeout(print1, 1000);

}

function printDiv3()
{
    $(".card-body").css({"width": "60%"});
	$(".sidebar").remove();
	$(".main-header").remove();
	$(".over-all").hide();
	$(".pie-chart").hide();
	$(".main-panel").css({"width":"calc(100% - -10px)"});
	$(".bar-chart").hide();
	$(".line-chart").hide();
	$(".performance-chart").show();
    $(".print").remove();
    setTimeout(print1, 1000);

}

    var start_date   = moment().startOf('month').format('YYYY-MM-DD');
	var end_date     = moment().format('YYYY-MM-DD');
		

	$(document).ready(function(){
	    
	    
	     var start_date   = moment().startOf('month').format('YYYY-MM-DD');
	var end_date     = moment().format('YYYY-MM-DD');
		
    function piechartPm(){
		   
        $.ajax({
            
			url: "{{ route('private.piechart.pm') }}",
			type: "POST",
			data: {startdate: start_date,enddate :end_date },
			dataType: "json",
			success: function(record){
			    
			    $("canvas#doughnutChart").remove();
                $("div#doughnut-chart").append('<canvas id="doughnutChart"  width="463" height="300" class="chartjs-render-monitor"></canvas>');

				var myDoughnutChart = new Chart(doughnutChart, {
					type: 'doughnut',
					data: {
						datasets: [{
							data: record.data,
							//backgroundColor: ['#f3545d','#fdaf4b','#1d7af3']
							backgroundColor: ['#030048','#921df3','#1d7af3']
						}],
						labels: [
						'Schedule ('+record.schedule+')',
						'complete ('+record.module+')',
						'pending ('+record.pending+')'
						]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						legend : {
							position: 'bottom'
						},
						layout: {
							padding: {
								left: 20,
								right: 20,
								top: 20,
								bottom: 20
							}
						},
					}
				});
			}
		});
    }
    
    function piechartBk(){
		
		$.ajax({
			url: "{{ route('private.piechart.bk') }}",
			type: "POST",
			data: {startdate: start_date,enddate :end_date },
			dataType: "json",
			success: function(record){
			    
			    $("canvas#doughnutChart2").remove();
                $("div#doughnut-chart2").append(' <canvas id="doughnutChart2"  width="463" height="300" class="chartjs-render-monitor"></canvas>');
                
				var myDoughnutChart = new Chart(doughnutChart2, {
					type: 'doughnut',
					data: {
						datasets: [{
							data: record.data,
							backgroundColor: ['#030048','#921df3','#1d7af3']
						}],
						labels: [
						'Schedule ('+record.schedule+')',
						'complete ('+record.module+')',
						'pending ('+record.pending+')'
						]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						legend : {
							position: 'bottom'
						},
						layout: {
							padding: {
								left: 20,
								right: 20,
								top: 20,
								bottom: 20
							}
						}
					}
				});
			}
		});
    }

		$('#year-status').hide();
		//Initialize Select 2
		$(".select-filter").select2({
			theme: "bootstrap"
		});

		/*--------------- PM AND BREAKDOWN ACHIEVEMENT CHART ----------------------*/

		//PM AND BREAKDOWN ACHIEVEMENT CHART
		var startDate = moment().startOf('month').format('YYYY-MM-DD');
		var endDate = moment().format('YYYY-MM-DD');
		var status = $("#status-filter").val();
		$('#status-filter').on('change',function(){
			status = $("#status-filter").val();
			chart();
		});
		function chart($this){
			$.ajax({
					url: "{{ route('private.dashboard.chart') }}",
					type: "POST",
					data: {startDate: startDate,endDate :endDate,Status :status },
					dataType: "json",
					success: function(data){
						$('.total').html(data.total_count+ "%" );
						$('.month').html(data.one_month+ "%" );
						$('.today').html(data.today+ "%");
						$('.chart-year').html(data.chart_year);
						$('.year').html(data.one_year+ "%");

						$('.heading_th').remove();
						$('.target_td').remove();
						$('.acctual_td').remove();
						$('.percentage_td').remove();

						$.each(data.days, function(key,value) {
  							$('.heading').append("<th class='heading_th'>"+value+"</th>");
						});

						$.each(data.target, function(key,value) {
  							$('.target').append("<td class='target_td'>"+value+"</td>");
						});

						$.each(data.acctual, function(key,value) {
  							$('.acctual').append("<td class='acctual_td'>"+value+"</td>");
						});

						$.each(data.day_count, function(key,value) {
  							$('.percentage').append("<td class='percentage_td'>"+value+"%</td>");
						});

						$("canvas#totalIncomeChart").remove();
						$("div#chart-container").append('<canvas id="totalIncomeChart"></canvas>');

						var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');
						var mytotalIncomeChart = new Chart(totalIncomeChart, {
						type: 'bar',
						data: {
							labels: data.days,
							datasets : [{
								backgroundColor: '#119ddc',
								borderColor: 'rgb(23, 125, 255)',
								data: data.day_count,
								label: "%",
								//data: [500,400],
							}],
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							},
						},
					});
	   			},

			});
		}


		var per_month = moment().format('MM-YYYY');
		$("#performance-month-filter").val(per_month);
		$("#performance-month-filter").on('change',function(){
			performance();
		});
		function performance(){
			var month = $("#performance-month-filter").val();
			$.ajax({
					url: "{{ route('private.performance.chart') }}",
					type: "POST",
					data: {month:month },
					dataType: "json",
					success: function(data){

						$('.perforamance_table #past_data tbody').empty();
						$('.perforamance_table #performance_bk').empty();
						$('.perforamance_table #performance_ut').empty();
						$.each(data.records, function(key,value) {
							$('.perforamance_table #past_data tbody').append('<tr><td>'+value.line+'</td><td>'+value.pro+'</td><td>'+value.dt+'</td><td>'+value.pro_down+'</td><td>'+value.ut+'</td></tr>');
						});
                        
                        $('.perforamance_table #performance_bk').append(data.breakdown_total+"MINUTES");
						$('.perforamance_table #performance_ut').append(data.uptime_total+"%");

						$("canvas#PerformanceChart").remove();
						$("div#performance-chart-container").append('<canvas id="PerformanceChart"></canvas>');

						var PerformanceCharte = document.getElementById('PerformanceChart').getContext('2d');
						var mytotalIncomeChart = new Chart(PerformanceChart, {
						type: 'bar',
						data: {
							labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', "Jul", 'Aug', 'Sep', 'Oct', 'Nov', 'Dec','Avg'],
							datasets : [{
								backgroundColor: '#119ddc',
								borderColor: 'rgb(23, 125, 255)',
								data: data.performance,
								label: "%",
							}],
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero:true
									}
								}]
							},
						},
					});
	   			},

			});
		}
		//DateRange Picker
		$("#date-filter").daterangepicker({
			opens: 'left',
			startDate: moment().startOf('month'),
			endDate: moment(),
			locale: {
				format: '{{ config("site.date_format.front") }}'
			},
			maxDate:moment(),
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				//'This Month': [moment().startOf('month'), moment().endOf('month')],
				'This Year': [moment().startOf('year'), moment().endOf('year')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			//autoUpdateInput: false,
		}, function(start, end, label) {
			startDate = start.format('YYYY-MM-DD');
			endDate =  end.format('YYYY-MM-DD');
			chart();
		});
		
		//DateRange Picker
		$("#date-filters").daterangepicker({
			opens: 'right',
			startDate: moment().startOf('month'),
			endDate: moment(),
			locale: {
				format: '{{ config("site.date_format.front") }}'
			},
			maxDate:moment(),
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				//'This Month': [moment().startOf('month'), moment().endOf('month')],
				'This Year': [moment().startOf('year'), moment().endOf('year')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			//autoUpdateInput: false,
		}, function(start, end, label) {
			start_date = start.format('YYYY-MM-DD');
			end_date =  end.format('YYYY-MM-DD');
			piechartPm();
			piechartBk();
		});

		//PM AND BREAKDOWN ACHIEVE CHART
		chart();
		/*--------------- END PM AND BREAKDOWN ACHIEVEMENT CHART ----------------------*/

		/*------------------- LINE CHART ------------------- */
		//BREAKDOWNLINE CHART
		var current_month = moment().format('MM-YYYY');
		var current_year = moment().format('YYYY');
		$("#month-filter").val(current_month);
		$("#year-filter").val(current_year);
		$("#month-filter").on('change',function(){
			lineChart();
		});
		$("#year-filter").on('change',function(){
			lineChart();
		});

		$('#month-status').on('change',function(){
			lineChart();
		});

		function lineChart($this){
			var month = $('#month-filter').val();
			var year = $('#year-filter').val();
			var line = $('#month-status').val();
			var type = $('#type-status').val();
			$.ajax({
					url: "{{ route('private.linechart') }}",
					type: "POST",
					data: {month:month,year:year,line:line,type:type},
					dataType: "json",
					success: function(data){
						var option = {
							scales: {
								xAxes: [{
								ticks: {
									// callback: function(value) {
									// if (value.length > 4) {
									// 	return value.substr(0, 4) + '...'; //truncate
									// } else {
									// 	return value
									// }
									// },
                                    autoSkip: false,
								}
								}],
								yAxes: [{}]
							},
							tooltips: {
								enabled: true,
								mode: 'label',
								callbacks: {
								title: function(tooltipItems, data) {
									var idx = tooltipItems[0].index;
									return 'Title:' + data.labels[idx]; //do something with title
								},
								label: function(tooltipItems, data) {
									//var idx = tooltipItems.index;
									//return data.labels[idx] + ' €';
									return tooltipItems.xLabel;
								}
								}
							},
							};
						$("canvas#totalLineChart").remove();
						$("div#line-chart-container").append('<canvas id="totalLineChart"></canvas>');
						var ctx = document.getElementById('totalLineChart').getContext('2d');
						var mytotalIncomeChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: data.name,
							datasets : [{
								backgroundColor: '#119ddc',
								borderColor: 'rgb(23, 125, 255)',
								data: data.total,
								label: "Minute",
								//data: [500,400],
								borderWidth: 1
							}],
						},
						options: option
					});
	   			},
			});

			Chart.plugins.register({
				afterDatasetsDraw: function(chart, easing) {
					// To only draw at the end of animation, check for easing === 1
					var ctx = chart.ctx;
					chart.data.datasets.forEach(function (dataset, i) {
						var meta = chart.getDatasetMeta(i);
						if (!meta.hidden) {
							meta.data.forEach(function(element, index) {
								// Draw the text in black, with the specified font
								ctx.fillStyle = 'rgb(0, 0, 0)';
								var fontSize = 16;
								var fontStyle = 'normal';
								var fontFamily = 'Helvetica Neue';
								ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
								// Just naively convert to string for now
								var dataString = dataset.data[index].toString();
								// Make sure alignment settings are correct
								ctx.textAlign = 'center';
								ctx.textBaseline = 'middle';
								var padding = 5;
								var position = element.tooltipPosition();
								ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
							});
						}
					});
				}
        	});
		}
		$("#month-filter").datepicker({
			format: 'mm-yyyy',
			minViewMode: 'months',
			autoclose: true,
		});

		$("#performance-month-filter").datepicker({
			format: 'mm-yyyy',
			minViewMode: 'months',
			autoclose: true,
		});

		$('#type-status').change(function(){
			var data = $('#type-status').val();
			if(data == 1)
			{
				$('#month').show();
				$('#year-status').hide();
			}else
			{
				$('#month').hide();
				$('#year-status').show();
			}
		});

		$( "#year-filter" ).datepicker({
			format: 'yyyy',
			minViewMode: 'years',
			autoclose: true,
			});
		// BREAKDOWN line CHART
		lineChart();

		// PERFORMANCE CHART
		performance();
        
        piechartPm();
        
        piechartBk();
		/*------------- END LINE CHART -----------------*/
	});
</script>
@endpush
