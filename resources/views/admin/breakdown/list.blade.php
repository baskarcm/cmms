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
		<button class="btn btn-admin btn-round ml-auto data-exports">
			<i class="fa fa-download"></i> Export
		</button>
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
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Status</label>
								<select class="select-filter form-control" data-placeholder="Select a Status" id="status-filter">
	                              	<option value="">All</option>
	                              	@foreach(config("site.status") as $key => $status)
		                              	<option value="{{ $key }}">{{ $status }}</option>
	                              	@endforeach

								</select>
							</div>
						</div>
						<div class="col-md-3 pl-0" style="display:none">
							<div class="form-group pt-0">
								<label>Created Date</label>
								<input type="text" class="form-control bg-white" readonly id="date-filter">
							</div>
						</div>
						<div class="col-md-3 pl-0 " style="display: none;">
							<div class="form-group pt-10 float-right">
								<button class="btn btn-primary btn-border btn-round export">Exports</button>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table id="record-table" class="display table table-striped table-hover w-100 table-head-bg-primary" >
							<thead>
								<tr>
									<th>Equipment</th>
									<th>Sl.no</th>
									<th>Line</th>
									<th>Station</th>
									<th>Ref.no</th>
									<th>Technician</th>
									<th>Engineer</th>
									<th>Approval Status</th>
									<th>Date</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


@push("js")

	<script type="text/javascript">
		var delete_url = "{{ route('private.breakdown.destroy') }}";
		var table;
		var startDate = moment().format('YYYY-MM-DD');
		var endDate = moment().format('YYYY-MM-DD');

		$(document).ready(function() {

	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "breakdown"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.breakdown.list") }}",
	                "type": "POST",
	                data: function (d) {
						d.status 		= $("#status-filter").val()
						d.start_date 	= startDate
						d.end_date 		= endDate
	                },
	            },

	            "columns": [
						{ "data": "product_type",
							"render": function ( data, type, row ) {
								return row.equipment.product.name;
							},
							"name": "product_type"
						},
						{
							"render": function ( data, type, row ) {
								return row.equipment.code;
							},

						},
						{
							"render": function ( data, type, row ) {
								return row.equipment.location;
							},

						},
						{
							"render": function ( data, type, row ) {
								return row.equipment.station;
							},

						},
						{
							"render": function ( data, type, row ) {
								return row.schedule.ref_no;
							},

						},
						{"data": "user_id",
							"render": function ( data, type, row ) {
								return row.users.name;
							},
							"name": "user_id"
						},
						{
							"render": function ( data, type, row ) {
								return row.engineer.name;
							},
						},
						{ "data": "manager_id",
							"render": function ( data, type, row ) {
								if(row.schedule.engineer_status == 0)
								{
									return '<span class="badge badge-pill badge-warning">Pending</span>';

								}else if(row.schedule.engineer_status == 1 )
								{
									return '<span class="badge badge-pill badge-danger">Reject</span>';
								}else if(row.schedule.engineer_status == 2 )
								{
									return '<span class="badge badge-pill badge-primary">Completed</span>';
								}
							},
							"name": "manager_id"
						},
	                    { "data": "date",

							"render": function ( data, type, row ) {
								return moment(row.date).format('YYYY/MM/DD');
							},
							"name": "date"
						},
	                    { "data": "active",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge status_edit"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    }
						},
	                    {   "mRender": function ( data, type, row )
	                        {

	                        	var btn = `<div class="form-button-action"><a href="{{route('private.breakdown.view', ['key' => ''])}}/${row.key}" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-id="${row.id}" data-original-title="Breakdown view" target="_blank"> <i class="fa fa-eye"></i> </a> <a href="javascript:void(0);" data-toggle="tooltip" title="Export" class="btn btn-link btn-primary btn-lg getData p-2" data-id="${row.id}"> <i class="fa fa-download"></i> </a> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> <i class="fa fa-trash"></i> </button> </div>`;
	                            return btn;
	                         }
	                    }
	                ],

	               createdRow: function( row, data, dataIndex ) {

	                              $(row).find( '.status_edit' ).editable({
	                                    url: "{{ route('private.breakdown.status') }}",
	                                    success: function(response, newValue) {
	                                        if(response.success == 0) return response.message; //msg will be shown in editable form
											table.fnDraw();
	                                    },

	                                    inputclass: 'form-control',
	                                    source: [{
	                                        value: 1,
	                                        text: 'Active'
	                                    }, {
	                                        value: 0,
	                                        text: 'Inactive'
	                                    }],
	                                    display: function(value, sourceData) {
	                                        var cls = {
	                                                1: "badge-success",
	                                                0: "badge-danger"
	                                            },
	                                            rmcls = {
	                                                1: "badge-danger",
	                                                0: "badge-success"
	                                            },
	                                            elem = $.grep(sourceData, function(o) {
	                                                return o.value == value;
	                                            });

	                                        if (elem.length) {
	                                            $(this).text(elem[0].text).attr("data-value", value).removeClass( rmcls[value]);
	                                            $(this).addClass( cls[value]);
	                                        } else {
	                                            $(this).empty();
	                                        }
	                                    }
	                                });
	                        },
	            "columnDefs": [
		            {  // set default column settings
		                'orderable': false,
		                'targets': [ -1]
		            },
		            {
		                "searchable": false,
		                "targets": [ -1]
		            }
				],
				"order": [
					[4, "desc"]
				]
	        });

			//Table Filter
			$(".filter-cont .select-filter").change(function(){
				table.fnDraw();
			});

			//Initialize Select 2
			$(".select-filter").select2({
				theme: "bootstrap"
			});

			// //DateRange Picker
			// $("#date-filter").daterangepicker({
			// 	opens: 'left',
			// 	startDate: moment().startOf('year'),
			// 	endDate: moment(),
			// 	locale: {
			// 		format: '{{ config("site.date_format.front") }}'
			// 	},
			// 	maxDate:moment(),
			// 	ranges: {
			// 		'Today': [moment(), moment()],
			// 		'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			// 		'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			// 		'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			// 		'This Month': [moment().startOf('month'), moment().endOf('month')],
			// 		'This Year': [moment().startOf('year'), moment().endOf('year')],
			// 		'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			// 	},
			// 	//autoUpdateInput: false,
			// }, function(start, end, label) {
			// 	startDate = start.format('YYYY-MM-DD');
			// 	endDate =  end.format('YYYY-MM-DD');
			// 	table.fnDraw();

			// });

			$('.data-exports').on("click",function(){
	            var exportsStatus = $('#status-filter').val();
	            var exportsStartDate = startDate;
	            var exportsEndDate = endDate;
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.breakdown.data-exports') }}",
	                    data: {exportsStatus:exportsStatus,exportsStartDate:exportsStartDate,exportsEndDate:exportsEndDate},
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

		    $('table').on('click','.getData',function(){
		    	var individualId = $(this).attr('data-id');
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.breakdown.individualdata-exports') }}",
	                    data: {individualId:individualId},
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

	});
	</script>
@endpush
