@extends("admin.layouts.app")

@section("content")
<div class="page-inner">
	<div class="page-header">
		<h4 class="page-title">{{$title}}</h4>
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
				<a href="#">{{ $title }}</a>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
					<!-- <div class="card-header">
						<div class="d-flex align-items-center">
							<h4 class="card-title">{{ $title }}</h4>
						</div>
					</div> -->

				<div class="card-body">
					<div class="row p-2 filter-cont mb-4">
					</div>
					<div class="card-body">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#machine" role="tab">Machine Downtime</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#problemDetails" role="tab">Problem Details</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#month" role="tab">Month</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#production" role="tab">Production hours | MTBF</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#downtime" role="tab">Downtime | MTTR (mins)</a>
							</li>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content">
							<div class="tab-pane active" id="machine" role="tabpanel">
								<div class="card-body">
									<div class="row">
										<div class="col-md-3 pl-0">
											<div class="form-group pt-0">
												<label>Line</label>
												<select class="select-filter form-control" data-placeholder="Select a Status" id="line-status">
														@foreach(config("site.line") as $key => $line)
															<option value="{{ $key }}">{{ $line }}</option>
														@endforeach
													</select>
											</div>
										</div>
										<div class="col-md-3 pl-0">
											<div class="form-group pt-0">
												<label>Created Date</label>
												<input type="text" class="form-control bg-white" readonly id="year-filter">
											</div>
										</div>
										<div class="col-md-3 pl-0">
											<div class="form-group pt-0">
												<label>Machine Downtime Report</label><br>
												<button class="btn btn-admin btn-round ml-auto machine-exports" style="color: #fff;">
													<i class="fa fa-download"></i>
													Export
												</button>
											</div>
										</div>
									</div>
									<table>
										<thead>
											<th>Station</th>
											<th>Equipment</th>
											<th>Jan</th>
											<th>Feb</th>
											<th>Mar</th>
											<th>Apr</th>
											<th>May</th>
											<th>Jun</th>
											<th>July</th>
											<th>Aug</th>
											<th>Sep</th>
											<th>Oct</th>
											<th>Nov</th>
											<th>Dec</th>
											<th>Sum</th>
										</thead>
										<tbody class="tbody">

										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane" id="problemDetails" role="tabpanel">
								<div class="card-body">
								<div class="row">
									<div class="col-md-3 pl-0">
										<div class="form-group pt-0">
											<label>Line</label>
											<select class="select-filter form-control" data-placeholder="Select a Status" id="problem-line-status">
													@foreach(config("site.line") as $key => $line)
														<option value="{{ $key }}">{{ $line }}</option>
													@endforeach
												</select>
										</div>
									</div>
									<div class="col-md-3 pl-0">
										<div class="form-group pt-0">
											<label>Choose Month</label>
											<input type="text" class="form-control bg-white" readonly id="month-filter">
										</div>
									</div>
									<div class="col-md-4 pl-0">
										<div class="form-group pt-0">
											<label>Problem Details Report</label><br>
											<button class="btn btn-admin btn-round text-color-white data-exports-problemdetails">
												<i class="fa fa-download"></i> Export
											</button>
										</div>
									</div>
								</div>
									<table>
										<thead>
											<th>Equipment</th>
											<th>Problems</th>
											<th>B/Down</th>
											<th>P/Down</th>
											<th>Root Cause (RC) and Action Plan (AP)</th>
											<th>MR Status</th>
											<th>Action</th>
										</thead>
										<tbody class="tbody">

										</tbody>
								 	</table>
								</div>
							</div>
							<div class="tab-pane" id="month" role="tabpanel">
								<div class="card-body">
								<form id="addForm"  autocomplete="off">
									<div class="row">
										<div class="col-md-3 pl-0">
											<div class="form-group pt-0">
												<label>Line</label>
												<select class="select-filter form-control" name="line" data-placeholder="Select a Status" id="year-line-status">
														@foreach(config("site.line") as $key => $line)
															<option value="{{ $key }}">{{ $line }}</option>
														@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3 pl-0">
											<div class="form-group pt-0">
												<label>Created Date</label>
												<input type="text" class="form-control bg-white" name="year" readonly id="month-years-filter">
											</div>
										</div>
										<div class="col-md-3 pl-0">
											<div class="form-group pt-0">
												<label>Month Downtime Report</label><br>
												<a class="btn btn-admin btn-round ml-auto monthdt-exports" style="color: #fff;">
													<i class="fa fa-download"></i>
													Export
												</a>
											</div>
										</div>
									</div>
									<div class="col-md-12">
											<table>
												<thead>
													<th>Sl.no</th>
													<th>Month</th>
													<th>Jan</th>
													<th>Feb</th>
													<th>Mar</th>
													<th>Apr</th>
													<th>May</th>
													<th>Jun</th>
													<th>July</th>
													<th>Aug</th>
													<th>Sep</th>
													<th>Oct</th>
													<th>Nov</th>
													<th>Dec</th>
												</thead>
												<tbody class="tbody">
												</tbody>
											</table>
											<div class="col-md-10 mt-4 p-0" style="text-align: right;">
												<button type="submit"  class="btn btn-admin text-white ml-auto"  data-loading="" data-text=""  data-loading-text="Please wait...">Add</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="production" role="tabpanel">
								<div class="card full-height">
									<div class="card-body line-chart">
										<div class="card-title">Production hours | MTBF Vs Month
										<input type='button' id='btn' class="btn btn-primary btn-sm float-right" value='Print' onclick='printDiv1();'>
										</div>
										<hr>
										<div class="module-print">
											<div class="col-md-12">
												<div id="production-chart-container">
												<canvas id="productionHours" ></canvas>
												</div>
												<p class="text-center"><b class="line-chart-year"></b></p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="downtime" role="tabpanel">
								<div class="card full-height">
									<div class="card-body line-chart">
										<div class="card-title">Downtime | MTTR (mins) Vs Month
											<input type='button' id='btn' class="btn btn-primary btn-sm float-right" value='Print' onclick='printDiv2();'>
											</div>
											<hr>
										<div class="module-print">
											<div class="col-md-12">
												<div id="downtime-chart-container">
												<canvas id="downtimeChart" ></canvas>
												</div>
												<p class="text-center"><b class="line-chart-year"></b></p>
											</div>
										</div>
									</div>
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
table {
  border-collapse: collapse;
  margin-top:20px;
}
table, th, td {
  border: 1px solid black;
  padding: 10px;
}
tr.heading{
	background-color:#f1f1f1;
	font-weight: bold;
}
.btn-admin, a {
    color: #1572E8;
}

td input{
	padding: 0px !important;
    width: 50px;
    border: 0;
}
#month td {
padding: 5px;
}
.text-color-white{
	color: white !important;
}
</style>
@endpush

@push("js")

	<script type="text/javascript">
		var table;
		//var startDate = moment().startOf('year').format('YYYY-MM-DD');
		//var endDate = moment().format('YYYY-MM-DD');
        function print1(){
          window.print();
          window.location.href = "{{ asset('private/month-view') }}";
        }
		function printDiv1()
		{
		    $(".card-body").css({"width": "85%"});
			$(".sidebar").remove();
			$(".main-header").remove();
			$(".over-all").hide();
			$(".main-panel").css({"width":"calc(100% - -10px)"});
			$('ul.nav-tabs').hide();
			setTimeout(print1, 1000);
		}

		function printDiv2()
		{
		    $(".card-body").css({"width": "85%"});
			$(".sidebar").remove();
			$(".main-header").remove();
			$(".over-all").hide();
			$(".main-panel").css({"width":"calc(100% - -10px)"});
			$('ul.nav-tabs').hide();
			setTimeout(print1, 1000);
		}

		$(document).ready(function() {

			$(".nav-pills a").click(function(){
     			$(this).tab('show');
 			});

			var current_year = moment().format('YYYY');
			$("#year-filter").val(current_year);
			$( "#year-filter" ).datepicker({
				format: 'yyyy',
				minViewMode: 'years',
				autoclose: true
			});

			var current_year = moment().format('YYYY');
			$("#month-years-filter").val(current_year);
			$( "#month-years-filter" ).datepicker({
				format: 'yyyy',
				minViewMode: 'years',
				autoclose: true
			});

			function getlist(){
				var line  = $('#line-status').val();
				var year  = $('#year-filter').val();
				$.ajax({
					url: "{{ route('private.report.month') }}",
					type: "POST",
					data :{line:line,year:year},
					dataType: "json",
					success: function(record){
						$('#machine .tbody').empty();
						$.each( record.data, function( key, value ) {
							$('#machine .tbody').append('<tr><td>'+value.station+'</td><td>'+value.name+'</td><td>'+value.Jan+'</td><td>'+value.Feb+'</td><td>'+value.Mar+'</td><td>'+value.Apr+'</td><td>'+value.May+'</td><td>'+value.Jun+'</td><td>'+value.July+'</td><td>'+value.Aug+'</td><td>'+value.Sep+'</td><td>'+value.Oct+'</td><td>'+value.Nov+'</td><td>'+value.Dec+'</td><td>'+value.sum+'</td></tr>');

						});
						$('#machine .tbody').append('<tr><td colspan="2">Total</td><td>'+record.total.jan+'</td><td>'+record.total.feb+'</td><td>'+record.total.mar+'</td><td>'+record.total.apr+'</td><td>'+record.total.may+'</td><td>'+record.total.jun+'</td><td>'+record.total.jul+'</td><td>'+record.total.aug+'</td><td>'+record.total.sep+'</td><td>'+record.total.oct+'</td><td>'+record.total.nov+'</td><td>'+record.total.dec+'</td><td>'+record.total.sum+'</td></tr>');
					}
				});
			}

			function monthYear(){
				var line  = $('#year-line-status').val();
				var year  = $('#month-years-filter').val();
				$.ajax({
					url: "{{ route('private.year.list') }}",
					type: "POST",
					data :{line:line,year:year},
					dataType: "json",
					success: function(record){
						var value = record.total;
						var count = record.count;
						var mtbf = record.mtbf;
						var mttr = record.mttr;
						var per = record.per;
						var production = record.production;
						var target = record.target;
						$('#month .tbody').empty();
						$('#month .tbody').append('<tr><td><input type="hidden" name="type[0]" value="1">1</td><td>Total Production(mins)</td><td><input type="text" name="jan[0]" value="'+production.jan+'" class="digit"></td><td><input type="text" name="feb[0]" value="'+production.feb+'" class="digit"></td><td><input type="text" name="mar[0]" value="'+production.mar+'" class="digit"></td><td><input type="text" name="apr[0]" value="'+production.apr+'" class="digit"></td><td><input type="text" name="may[0]" value="'+production.may+'" class="digit"></td><td><input type="text" name="jun[0]" value="'+production.jun+'" class="digit"></td><td><input type="text" name="jul[0]" value="'+production.jul+'" class="digit"></td><td><input type="text" name="aug[0]" value="'+production.aug+'" class="digit"></td><td><input type="text" name="sep[0]" value="'+production.sep+'" class="digit"></td><td><input type="text" name="oct[0]" value="'+production.oct+'" class="digit"></td><td><input type="text" name="nov[0]" value="'+production.nov+'" class="digit"></td><td><input type="text" name="dec[0]" value="'+production.dec+'" class="digit"></td></tr>');
						$('#month .tbody').append('<tr><td><input type="hidden" name="type[1]" value="2">2</td><td>Machine DT (mins)</td><td><input type="text" name="jan[1]" value="'+value.jan+'" readonly></td><td><input type="text" name="feb[1]" value="'+value.feb+'" readonly></td><td><input type="text" name="mar[1]" value="'+value.mar+'" readonly></td><td><input type="text" name="apr[1]" value="'+value.apr+'" readonly></td><td><input type="text" name="may[1]" value="'+value.may+'" readonly></td><td><input type="text" name="jun[1]" value="'+value.jun+'" readonly></td><td><input type="text" name="jul[1]" value="'+value.jul+'" readonly></td><td><input type="text" name="aug[1]" value="'+value.aug+'" readonly></td><td><input type="text" name="sep[1]" value="'+value.sep+'" readonly></td><td><input type="text" name="oct[1]" value="'+value.oct+'" readonly></td><td><input type="text" name="nov[1]" value="'+value.nov+'" readonly></td><td><input type="text" name="dec[1]" value="'+value.dec+'" readonly></td></tr>');
						$('#month .tbody').append('<tr><td><input type="hidden" name="type[2]" value="3">3</td><td>Breakdown Frequency</td><td><input type="text" name="jan[2]" value="'+count.jan_count+'" readonly></td><td><input type="text" name="feb[2]" value="'+count.feb_count+'" readonly></td><td><input type="text" name="mar[2]" value="'+count.mar_count+'" readonly></td><td><input type="text" name="apr[2]" value="'+count.apr_count+'" readonly></td><td><input type="text" name="may[2]" value="'+count.may_count+'" readonly></td><td><input type="text" name="jun[2]" value="'+count.jun_count+'" readonly></td><td><input type="text" name="jul[2]" value="'+count.jul_count+'" readonly></td><td><input type="text" name="aug[2]" value="'+count.aug_count+'" readonly></td><td><input type="text" name="sep[2]" value="'+count.sep_count+'" readonly></td><td><input type="text" name="oct[2]" value="'+count.oct_count+'" readonly></td><td><input type="text" name="nov[2]" value="'+count.nov_count+'" readonly></td><td><input type="text" name="dec[2]" value="'+count.dec_count+'" readonly></td></tr>');
						$('#month .tbody').append('<tr><td><input type="hidden" name="type[3]" value="4">4</td><td>MTBF</td><td><input type="text" name="jan[3]" value="'+mtbf.jan+'" readonly></td><td><input type="text" name="feb[3]" value="'+mtbf.feb+'" readonly></td><td><input type="text" name="mar[3]" value="'+mtbf.mar+'" readonly></td><td><input type="text" name="apr[3]" value="'+mtbf.apr+'" readonly></td><td><input type="text" name="may[3]" value="'+mtbf.may+'" readonly></td><td><input type="text" name="jun[3]" value="'+mtbf.jun+'" readonly></td><td><input type="text" name="jul[3]" value="'+mtbf.jul+'" readonly></td><td><input type="text" name="aug[3]" value="'+mtbf.aug+'" readonly></td><td><input type="text" name="sep[3]" value="'+mtbf.sep+'" readonly></td><td><input type="text" name="oct[3]" value="'+mtbf.oct+'" readonly></td><td><input type="text" name="nov[3]" value="'+mtbf.nov+'" readonly></td><td><input type="text" name="dec[3]" value="'+mtbf.dec+'" readonly></td></tr>');
						$('#month .tbody').append('<tr><td><input type="hidden" name="type[4]" value="5">5</td><td>MTTR</td><td><input type="text" name="jan[4]" value="'+mttr.jan+'" readonly></td><td><input type="text" name="feb[4]" value="'+mttr.feb+'" readonly></td><td><input type="text" name="mar[4]" value="'+mttr.mar+'" readonly></td><td><input type="text" name="apr[4]" value="'+mttr.apr+'" readonly></td><td><input type="text" name="may[4]" value="'+mttr.may+'" readonly></td><td><input type="text" name="jun[4]" value="'+mttr.jun+'" readonly></td><td><input type="text" name="jul[4]" value="'+mttr.jul+'" readonly></td><td><input type="text" name="aug[4]" value="'+mttr.aug+'" readonly></td><td><input type="text" name="sep[4]" value="'+mttr.sep+'" readonly></td><td><input type="text" name="oct[4]" value="'+mttr.oct+'" readonly></td><td><input type="text" name="nov[4]" value="'+mttr.nov+'" readonly></td><td><input type="text" name="dec[4]" value="'+mttr.dec+'" readonly></td></tr>');
						$('#month .tbody').append('<tr><td><input type="hidden" name="type[5]" value="6">6</td><td>MachineUptimein%</td><td><input type="text" name="jan[5]" value="'+per.jan+'" readonly></td><td><input type="text" name="feb[5]" value="'+per.feb+'" readonly></td><td><input type="text" name="mar[5]" value="'+per.mar+'" readonly></td><td><input type="text" name="apr[5]" value="'+per.apr+'" readonly></td><td><input type="text" name="may[5]" value="'+per.may+'" readonly></td><td><input type="text" name="jun[5]" value="'+per.jun+'" readonly></td><td><input type="text" name="jul[5]" value="'+per.jul+'" readonly></td><td><input type="text" name="aug[5]" value="'+per.aug+'" readonly></td><td><input type="text" name="sep[5]" value="'+per.sep+'" readonly></td><td><input type="text" name="oct[5]" value="'+per.oct+'" readonly></td><td><input type="text" name="nov[5]" value="'+per.nov+'" readonly></td><td><input type="text" name="dec[5]" value="'+per.dec+'" readonly></td></tr>');
						$('#month .tbody').append('<tr><td><input type="hidden" name="type[6]" value="7">7</td><td>Target</td><td><input type="text" name="jan[6]" value="'+target.jan+'"></td><td><input type="text" name="feb[6]" value="'+target.feb+'"></td><td><input type="text" name="mar[6]" value="'+target.mar+'"></td><td><input type="text" name="apr[6]" value="'+target.apr+'"></td><td><input type="text" name="may[6]" value="'+target.may+'"></td><td><input type="text" name="jun[6]" value="'+target.jun+'"></td><td><input type="text" name="jul[6]" value="'+target.jul+'"></td><td><input type="text" name="aug[6]" value="'+target.aug+'"></td><td><input type="text" name="sep[6]" value="'+target.sep+'"></td><td><input type="text" name="oct[6]" value="'+target.oct+'"></td><td><input type="text" name="nov[6]" value="'+target.nov+'"></td><td><input type="text" name="dec[6]" value="'+target.dec+'"></td></tr>');
					}
				});
			}

			var current_month = moment().format('MM-YYYY');
			$("#month-filter").val(current_month);

			function getProblemDetails(){
				var line  = $('#problem-line-status').val();
				var month  = $('#month-filter').val();
				$.ajax({
					url: "{{ route('private.breakdown-problem.report') }}",
					type: "POST",
					data :{line:line,month:month},
					dataType: "json",
					success: function(record){
						$('#problemDetails .tbody').empty();
						$.each( record.data, function( key, value ) {
							$('#problemDetails .tbody').append('<tr><td>'+value.name+'</td><td><input type="text" name="problem" class="problem_'+value.id+'" value="'+value.problem+'"> </td><td>'+value.total+'</td><td><input type="text" name="p_down" class="p_down_'+value.id+'" value="'+value.p_down+'"></td><td><input type="text" name="root_cause" class="root_cause_'+value.id+'" value="'+value.root_cause+'"></td><td><input type="text" name="status" class="status_'+value.id+'" value="'+value.status+'"></td><td><button class="add" data-id="'+value.id+'"><i class="fas fa-check"></i></button></div></td></tr></<tr>');
						});
						$('#problemDetails .tbody').append('<tr><td colspan="4"><b>TOTAL BREAKDOWN (MIN)</b></td><td colspan="3"><b>'+record.bdown+'</b></td></tr><tr><td colspan="4"><b>TOTAL DOWNTIME TO PRODUCTION (MIN)</b></td><td colspan="3"><b>'+record.pdown+'</b></td></tr>');
					}
				});
			}

			//Table Filter
			$("#line-status").change(function(){
				getlist();
			});

			$("#year-line-status").change(function(){
				monthYear();
				productionChart();
				downtimeChart();
			});

			$("#problem-line-status").change(function(){
				getProblemDetails();
			});

			$("#year-filter").on('change',function(){
				getlist();
			});

			$("#month-years-filter").on('change',function(){
				monthYear();
				productionChart();
				downtimeChart();
			});

			$("#month-filter").on('change',function(){
				getProblemDetails();
			});

			$("#month-filter").datepicker({
				format: 'mm-yyyy',
				minViewMode: 'months',
				autoclose: true
			});

			//Initialize Select 2
			$(".select-filter").select2({
				theme: "bootstrap"
			});

			//Machine Time
			getlist();
			//ProbleDetails
			getProblemDetails();

			//Month
			monthYear();

			//Production Chart
			productionChart();

			//DOWNTIME Chart
			downtimeChart();

			$('#problemDetails').on('click','.add',function(){
				var id = $(this).attr("data-id");
				var problem = $('.problem_'+id).val();
				var p_down = $('.p_down_'+id).val();
				var root_cause = $('.root_cause_'+id).val();
				var status = $('.status_'+id).val();
				$.ajax({
					url: "{{ route('private.problem.store') }}",
					type: "POST",
					data :{id:id,problem:problem,p_down:p_down,root_cause:root_cause,status:status},
					dataType: "json",
					success: function(data){
						if(data.success == 1){
							notifySuccess(data.message);
							getProblemDetails();
						}else{
							notifyWarning(data.message);
						}
					}
				});
			});

			$("#addForm").validate({
			    submitHandler: function(form) {
	                var data = $(form).serialize();
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.year.report') }}",
	                    data: data,
	                    dataType: "json",
	                    success: function(data) {
							if(data.success == 1){
							notifySuccess(data.message);
							}else{
								notifyWarning(data.message);
							}

	                    }
	                });
			    }
			});

		});

		//PRODUCTION CHART
		function productionChart(){
			var lines  = $('#year-line-status').val();
			var years  = $('#month-years-filter').val();
			$.ajax({
					url: "{{ route('private.production.chart') }}",
					type: "POST",
					data: {line:lines,year:years },
					dataType: "json",
					success: function(record){
						$("canvas#productionHours").remove();
						$("div#production-chart-container").append('<canvas id="productionHours"></canvas>');
							var productionHours = document.getElementById('productionHours').getContext('2d');
								var mytotalIncomeChart = new Chart(productionHours, {
								type: 'bar',
								data: {
									labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', "Jul", 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
									datasets: [{
										type: 'line',
										label: 'MTBF - Mean Time Before Failure (days)',
										borderWidth: 5,
										data: record.data.mtbf,
										lineTension: 0,
										fill: false,
										borderColor: '#ed7d31' ,
										yAxisID: 'y-axis-1'
									}, {
										label: 'Total Production (mins)',
										data: record.data.production,
										// this dataset is drawn below
										backgroundColor: '#69a3db',
										yAxisID: 'y-axis-2'
									}],
								},
								options: {
										scales: {
											yAxes: [{
												type: "linear",
												display: true,
												position: "left",
												id: "y-axis-2",
												gridLines:{
													display: true
												},
												labels: {
													show:true,
												},
												ticks: {
													min: 0,
												},
											}, {
												type: "linear",
												display: true,
												position: "right",
												id: "y-axis-1",
												gridLines:{
													display: false
												},
												labels: {
													show:true,
												},
												ticks: {
													max: 31,
													min: 0,
													stepSize: 5
												},
											}]
										}
									},
							});
					}
			});
		}

		//Downtime  CHART
		function downtimeChart(){
			var lines  = $('#year-line-status').val();
			var years  = $('#month-years-filter').val();
			$.ajax({
					url: "{{ route('private.downtime.chart') }}",
					type: "POST",
					data: {line:lines,year:years },
					dataType: "json",
					success: function(record){
						$("canvas#downtimeChart").remove();
						$("div#downtime-chart-container").append('<canvas id="downtimeChart"></canvas>');
						var downtimeChart = document.getElementById('downtimeChart').getContext('2d');
							var downtimeCharts = new Chart(downtimeChart, {
							type: 'bar',
							data: {
								labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', "Jul", 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
								datasets: [{
									type: 'line',
									label: 'Machine Uptime in % ',
									borderWidth: 5,
									data: record.data.uptime,
									lineTension: 0,
									fill: false,
									borderColor: '#a8a8a8' ,
									yAxisID: 'y-axis-1'
								},{
									type: 'line',
									label: 'MTTR - Mean Time To Repair (mins) ',
									borderWidth: 5,
									data: record.data.mttr,
									lineTension: 0,
									fill: false,
									borderColor: '#ed7d31' ,
								}, {
									type: 'line',
									label: 'Target (%)',
									borderWidth: 5,
									data: record.data.target,
									yAxisID: 'y-axis-1',
									lineTension: 0,
									fill: false,
									borderColor: '#ffc000' ,
								},{
									label: 'Machine DT (mins)',
									data: record.data.dt,
									backgroundColor: '#69a3db',
									yAxisID: 'y-axis-2'
								}],
							},
							options: {
									scales: {
										yAxes: [{
											type: "linear",
											display: true,
											position: "left",
											id: "y-axis-2",
											gridLines:{
												display: true
											},
											labels: {
												show:true,
											},
											ticks: {
												min: 0,
											},
										}, {
											type: "linear",
											display: true,
											position: "right",
											id: "y-axis-1",
											gridLines:{
												display: false
											},
											labels: {
												show:true,
											},
											ticks: {
												max: 100,
												min: 90,
												stepSize: 2
											},
										}]
									}
								},
						});
					}
			});
		}

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

		$("table").on("keypress",".digit",function(evt)
		{
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31  && (charCode < 48 || charCode > 57))
			return false;
			if (charCode === 46) {
				return false;
			}

		return true;
		});

		$('.data-exports-problemdetails').on("click",function(){
            var exportsLineStatus = $('#problem-line-status').val();
            var exportsMonth = $('#month-filter').val();
            $.ajax({
                type: "POST",
                url: "{{ route('private.month-report.problem-details-exports') }}",
                data: {exportsLineStatus:exportsLineStatus,exportsMonth:exportsMonth},
                dataType: "json",
                success: function(data) {
                	if(data.success == 1){
						var download = data.data;
                    	window.location = download;
                        notifySuccess(data.message);
                    }
                    else
                    {
                    	notifyWarning(data.message);
                    }
                }
            });
		});

		$('.machine-exports').on("click",function(){
			//console.log("test");
	    	var getLine  = $('#line-status').val();
	    	var getYear = $('#year-filter').val();
            $.ajax({
                type: "POST",
                url: "{{ route('private.machine.data-exports') }}",
                data: {getLine:getLine,getYear:getYear},
                dataType: "json",
                success: function(data) {
                	if(data.success == 1){
						var download = data.data;
                    	window.location = download;
                        notifySuccess(data.message);
                    }
                    else
                    {
                    	notifyWarning(data.message);
                    }
                }
            });
	    });

	    $('.monthdt-exports').on("click",function(){
			//alert("test");
	    	var getLine  = $('#year-line-status').val();
	    	var getYear = $('#month-years-filter').val();
            $.ajax({
                type: "POST",
                url: "{{ route('private.monthdt.data-exports') }}",
                data: {getLine:getLine,getYear:getYear},
                dataType: "json",
                success: function(data) {
                	if(data.success == 1){
						var download = data.data;
                    	window.location = download;
                        notifySuccess(data.message);
                    }
                    else
                    {
                    	notifyWarning(data.message);
                    }
                }
            });
	    });

	</script>
@endpush
