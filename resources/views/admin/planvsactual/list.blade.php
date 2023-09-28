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
		<button class="btn btn-admin btn-round ml-auto plan-exports">
			<i class="fa fa-download"></i>
			Export
		</button>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
					<!-- <div class="card-header">
					<div class="d-flex align-items-center">
						<h4 class="card-title">{{ $title }}</h4>
						<button class="btn btn-admin btn-round ml-auto" id="addModalOpen">
							<i class="fa fa-plus"></i>
							Add Schedule
						</button>
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
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Choose Month</label>
								<input type="text" class="form-control bg-white" id="month-filter" readonly>
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
									<th>Product</th>
									<th>Code</th>
									<th>Line</th>
									<th>Station</th>									
									<th>Plan Date</th>
									<th>Actual Date</th>
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
@push("css")
<style>
.export:hover 
{
    background-color: #1572E8 !important;
    color: #FFF!important;
}

</style>
@endpush

@push("js")
	<script type="text/javascript">
		var delete_url = "{{ route('private.schedule.destroy') }}";
		var table;
		var startDate = moment().startOf('year').format('YYYY-MM-DD');
		var endDate = moment().format('YYYY-MM-DD');

		$("#month-filter").datepicker({
			format: 'mm-yyyy',
			minViewMode: 'months',
			autoclose: true,
		});


		var current_month = moment().format('MM-YYYY');
		$("#month-filter").val(current_month);

		$("#month-filter").on('change', function(){
			table.fnDraw();
		});

		$(document).ready(function() {			

			$('#addForm #title-input').hide();
			$('#editForm #title-input').hide();

	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "Plan vs Actual"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.actual.list") }}",
	                "type": "POST",
	                data: function (d) { 
						d.status 		= $("#status-filter").val()         
						/*d.module 		= $("#module-filter").val()         
						d.manager 		= $("#manager-filter").val()*/    
						d.month = $('#month-filter').val()    
						/*d.start_date 	= startDate
						d.end_date 		= endDate*/
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

	                    { "data": "schedule_date",

							"render": function ( data, type, row ) {
								return moment(row.schedule_date).format('YYYY/MM/DD');
							},
							"name": "schedule_date" 
						},
						{ "data": "actual_scheduledate.date",

							"render": function ( data, type, row ) {
								if(row.actual_scheduledate != null){
									return moment(row.actual_scheduledate.date).format('YYYY/MM/DD');
								}else{
									return '<span class="badge badge-pill badge-danger">No Date Available</span>';
								}
							},
							"name": "actual_scheduledate.date" 
						},
	                    { "data": "active",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge status_edit"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    } 
						},
	                    {   "mRender": function ( data, type, row ) 
	                        {
	                        	
	                        	var btn = `<div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div>`;
	                            return btn;       
	                         }
	                    }
	                ],

	               createdRow: function( row, data, dataIndex ) {
	                            
	                              $(row).find( '.status_edit' ).editable({
	                                    url: "{{ route('private.schedule.status') }}",
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

			$("#addModalOpen").click(function(){
				$("#addModal").find("#addForm")[0].reset();
				$("#addModal").find("#addName").focus();
				$("#addModal").modal();
			});

			//DateRange Picker
			/*$("#date-filter").daterangepicker({
				opens: 'left',
				startDate: moment().startOf('year'),
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
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'This Year': [moment().startOf('year'), moment().endOf('year')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				//autoUpdateInput: false,
			}, function(start, end, label) {
				startDate = start.format('YYYY-MM-DD');
				endDate =  end.format('YYYY-MM-DD');
				table.fnDraw();

			});*/
			

		    $('.plan-exports').on("click",function(){
		    	var exportsStatus = $('#status-filter').val();
		    	var getMonth = $('#month-filter').val() 
                $.ajax({
                    type: "POST",
                    url: "{{ route('private.actual.plan-exports') }}",
                    data: {getMonth:getMonth},
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