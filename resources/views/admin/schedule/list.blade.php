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
		<button class="btn btn-admin btn-round ml-2" id="addModalOpen">
			<i class="fa fa-plus"></i> Add Schedule
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
								<label>Maintenance Type</label>
								<select class="select-filter form-control" data-placeholder="Select a Status" id="module-filter">
	                              	<option value="">All</option>
	                              	@foreach($moduleType as $type)
			                            <option value="{{ $type->id }}">{{ $type->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Engineer</label>
								<select class="select-filter form-control" data-placeholder="Select a Status" id="manager-filter">
	                              	<option value="">All</option>
	                              	@foreach($engineer as $engineers)
			                            <option value="{{ $engineers->id }}">{{ $engineers->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-3 pl-0">
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
									<th>Technician</th>
									<th>Engineer</th>
									<th>Manager</th>
									<th>Schedule status</th>
									<th>Maintenance Type</th>
									<th>Schedule Date</th>
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

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header no-bd">
				<h2 class="modal-title">
					New schedule
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
				<form id="addForm"  method="POST" autocomplete="off">
			<div class="modal-body">
					<div class="detail"></div>
					<div  id="bk-input">
						<div class="row" >
							<div class="col-md-6">
								<div class="form-group form-group-default">
									<label>Breakdown Title</label>
									<input id="title" type="text"  class="form-control" placeholder="Breakdown  Title" name="title">
								</div>
							</div>
							<div class="col-md-6" >
								<div class="form-group form-group-default">
									<label>Reference Number</label>
									<input id="ref_no" type="text"  class="form-control" placeholder="Breakdown  Ref.no" name="ref_no">
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-6">
								<div class="form-group form-group-default">
									<label>Failure</label>
									<input id="failure" type="text"  class="form-control select_time" placeholder="Breakdown  Failure" name="failure">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group form-group-default">
									<label>Reporting</label>
									<input id="reporting" type="text"  class="form-control select_time" placeholder="Breakdown  Reporting" name="reporting">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>Equipment Name</label>
								<select class="select-filters form-control product" name="product" data-placeholder="Select a Equipment" id="product">
		                          	<option value="">Select a Equipment</option>
		                          	@foreach($products as $product)
			                            <option value="{{ $product->id }}">{{ isset($product->equipment) ? $product->equipment->name : $product->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>Schedule Date</label>
								<input type="text" class="form-control bg-white select_date" readonly id="date" name="date">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-group-default form-group-password">
								<label>Assign Technician </label>
								<select class="select-filters form-control" name="user" data-placeholder="Select a Technician" id="user">
		                          	<option value="">Select a Technician</option>
		                          	@foreach($user as $users)
			                            <option value="{{ $users->id }}">{{ $users->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group form-group-default form-group-password">
								<label>Assign Engineer</label>
								<select class="select-filters form-control" name="engineer" data-placeholder="Select a Engineer" id="engineer">
		                          	<option value="">Select a Engineer</option>
		                          	@foreach($engineer as $engineers)
			                            <option value="{{ $engineers->id }}">{{ $engineers->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 ">
							<div class="form-group form-group-default form-group-password">
								<label>Maintenance Type</label>
								<select class="select-filters form-control" name="module_type" data-placeholder="Select a type" id="moduleType">
		                          	<option value="">Select a type</option>
		                          	@foreach($moduleType as $type)
			                            <option value="{{ $type->id }}">{{ $type->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group form-group-default form-group-password">
								<label>Assign Manager</label>
								<select class="select-filters form-control" name="manager" data-placeholder="Select a Manager" id="manager">
		                          	<option value="">Select a Manager</option>
		                          	@foreach($manager as $managers)
			                            <option value="{{ $managers->id }}">{{ $managers->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer no-bd">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				<button type="submit" id="addRowButton" class="btn btn-admin"  data-loading="" data-text=""  data-loading-text="Please wait...">Add</button>
			</div>
				</form>
		</div>
	</div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header no-bd">
				<h2 class="modal-title">
					Edit Schedule
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editForm">
				@csrf
				<input type="hidden" id="editId" name="data">
				<div class="modal-body">
				<div class="detail"></div>
				<div  id="bk-input">
						<div class="row" >
							<div class="col-md-6">
								<div class="form-group form-group-default">
									<label>Breakdown Title</label>
									<input id="editTitle" type="text"  class="form-control" placeholder="Breakdown  Title" name="title">
								</div>
							</div>
							<div class="col-md-6" >
								<div class="form-group form-group-default">
									<label>Reference Number</label>
									<input id="editRef_no" type="text"  class="form-control" placeholder="Breakdown  Ref.no" name="ref_no">
								</div>
							</div>
						</div>
						<div class="row" >
							<div class="col-md-6">
								<div class="form-group form-group-default">
									<label>Failure</label>
									<input id="editFailure" type="text"  class="form-control select_time" placeholder="Breakdown  Failure" name="failure">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group form-group-default">
									<label>Reporting</label>
									<input id="editReporting" type="text"  class="form-control select_time" placeholder="Breakdown  Reporting" name="reporting">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>Equipment Name</label>
								<select class="select-filter2 form-control editProduct" name="product" data-placeholder="Select a Equipment" id="editProduct">
		                          	<option value="">Select a Equipment</option>
		                          	@foreach($products as $product)
			                            <option value="{{ $product->id }}">{{ isset($product->equipment) ? $product->equipment->name : $product->name}}</option>
		                            @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>Schedule Date</label>
								<input type="text" class="form-control bg-white select_date" readonly id="editDate" name="date">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-group-default form-group-password">
								<label>Assign User</label>
								<select class="select-filter2 form-control" name="user" data-placeholder="Select a User" id="editUser">
		                          	<option value="">Select a User</option>
		                          	@foreach($user as $users)
			                            <option value="{{ $users->id }}">{{ $users->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group form-group-default form-group-password">
								<label>Assign Engineer</label>
								<select class="select-filter2 form-control" name="engineer" data-placeholder="Select a Engineer" id="editEngineer">
		                          	<option value="">Select a Engineer</option>
		                          	@foreach($engineer as $engineers)
			                            <option value="{{ $engineers->id }}">{{ $engineers->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-6 ">
							<div class="form-group form-group-default form-group-password">
								<label>Maintenance Type</label>
								<select class="select-filter2 form-control edit_module" name="module_type" data-placeholder="Select a type" id="editModuleType">
		                          	<option value="">Select a type</option>
		                          	@foreach($moduleType as $type)
			                            <option value="{{ $type->id }}">{{ $type->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group form-group-default form-group-password">
								<label>Assign Manager</label>
								<select class="select-filter2 form-control" name="manager" data-placeholder="Select a Manager" id="editManager">
		                          	<option value="">Select a Manager</option>
		                          	@foreach($manager as $managers)
			                            <option value="{{ $managers->id }}">{{ $managers->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div>
					</div>	
			</div>
				<div class="modal-footer no-bd">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" id="editRowButton" class="btn btn-admin" data-loading-text="Update..." data-loading="" data-text="">Update</button>
				</div>
			</form>
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
		
		$(document).ready(function() {

			$('#addForm #bk-input').hide();
			$('#editForm #bk-input').hide();

			$('#editForm').on('change','.editProduct',function(){
				var data = $('.editProduct').val();
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.schedule.productType') }}",
	                    data: {data:data},
	                    dataType: "json",
	                    success: function(record) {
							$('#editForm .detail p').remove();
    						$('#editForm .detail').append("<p style='background-color:#ffcb01;padding:3px;'><b><span style='margin-left:15px;'>Code:  "+record.data.code+"</span><span style='margin-left:15px;'>Line:  "+record.data.location+"</span><span style='margin-left:15px;'>Station:  "+record.data.station+"</span></b>");
	                    }
	                });
			});

			$('#addForm').on('change','.product',function(){
				var data = $('.product').val();
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.schedule.productType') }}",
	                    data: {data:data},
	                    dataType: "json",
	                    success: function(record) {
							$('#addForm .detail p').remove();
    						$('#addForm .detail').append("<p style='background-color:#ffcb01;padding:3px;'><b><span style='margin-left:15px;'>Code:  "+record.data.code+"</span><span style='margin-left:15px;'>Line:  "+record.data.location+"</span><span style='margin-left:15px;'>Station:  "+record.data.station+"</span></b>");
	                    }
	                });
			});


			$('#addForm').on('change','#moduleType',function(){
				var data = $('#moduleType').val();
				if( data == 2)
				{
					$('#addForm #bk-input').show();
				}else
				{
					$('#addForm #bk-input').hide();
				}
				
			});

			$('#editForm').on('change','.edit_module',function(){
				var data = $('.edit_module').val();
				if( data == 2)
				{
					$('#editForm #bk-input').show();
				}else
				{
					$('#editForm #bk-input').hide();
				}
				
			});

			$('.select_date').daterangepicker({
				singleDatePicker: true,
				minDate:new Date(),
				locale: {
      				format: 'YYYY-MM-DD'
    			}
				
			});

			$('.select_time').daterangepicker({
				singleDatePicker: true,
				timePicker: true,
				minDate:new Date(),
				locale: {
      				format: 'YYYY-MM-DD hh:mm A'
    			}
				
			});

			$.ajax({
  					url: "{{ route('private.user.count') }}",
  					type: "POST",
  					dataType: "json",
  					success: function(response){
            		 $('.user-count').html(response);
           				},
				});
				
	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "schedule"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.schedule.list") }}",
	                "type": "POST",
	                data: function (d) { 
						d.status 		= $("#status-filter").val()         
						d.module 		= $("#module-filter").val()         
						d.engineer 		= $("#manager-filter").val()         
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
						{"data": "user_id",
							"render": function ( data, type, row ) {
								if(row.users == null)
								{
									return "Not Defind";
								}else
								{
								 	return row.users.name;	
								}
							},
							"name": "user_id" 
						},
						{"data": "engineer_id",
							"render": function ( data, type, row ) {
								if(row.engineer == null)
								{
									return "Not Defind";
								}else
								{
								 	return row.engineer.name;	
								}
							},
							"name": "engineer_id" 
						},
						{"data": "manager_id",
							"render": function ( data, type, row ) {
								if(row.manager == null)
								{
									return "Not Defind";
								}else
								{
								 return row.manager.name;	
								}
							},
							"name": "manager_id" 
						},
						{ "data": "schedule_status",
							"render": function ( data, type, row ) {
								if(row.schedule_status == 0)
								{
									return '<span class="badge badge-pill badge-warning">Schedule</span>';
																		
								}else if(row.schedule_status == 1 )
								{
									return '<span class="badge badge-pill badge-danger">Pending</span>';
								}else if(row.schedule_status == 2 )
								{
									return '<span class="badge badge-pill badge-primary">Completed</span>';
								}
							},
							"name": "schedule_status"
						},
						{"data": "module_type",
							"render": function ( data, type, row ) {
								return row.module_type.name;
							},
							"name": "module_type" 
						},

	                    { "data": "schedule_date",

							"render": function ( data, type, row ) {
								return moment(row.schedule_date).format('YYYY/MM/DD');
							},
							"name": "schedule_date" 
						},
	                    { "data": "active",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge status_edit"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    } 
						},
	                    {   "mRender": function ( data, type, row ) 
	                        {
	                        	
	                        	var btn = `<div class="form-button-action"><a href="#" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg edit-data" data-id="${row.id}" data-original-title="Edit Profile" target="_blank"> <i class="fa fa-edit"></i> </a> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> <i class="fa fa-trash"></i> </button> </div>`;
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
					[9, "desc"]
				] 
	        });

			//Table Filter
			$(".filter-cont .select-filter").change(function(){
				table.fnDraw();
			});

			//Initialize Select 2
			$(".select-filter").select2({
				theme: "bootstrap",
				 
			});
			
			$(".select-filters").select2({
				 dropdownParent: $("#addModal"),
				 theme: "bootstrap",
			});
			
			$(".select-filter2").select2({
				 dropdownParent: $("#editModal"),
				 theme: "bootstrap",
			});

			$("#addModalOpen").click(function(){
			    
			    $("#addModal").find("#addForm")[0].reset();
			    $("#addForm .detail").empty();
			    $("#addForm #product").val('').trigger('change.select2');
			    $("#addForm #user").val('').trigger('change.select2');
			    $("#addForm #engineer").val('').trigger('change.select2');
			    $("#addForm #manager").val('').trigger('change.select2');
			    $("#addForm #moduleType").val('').trigger('change.select2');
                $("#addModal").modal();
			
			});

			//DateRange Picker
			$("#date-filter").daterangepicker({
				opens: 'left',
				startDate: moment().startOf('year'),
				endDate: moment().endOf('month'),
				locale: {
					format: '{{ config("site.date_format.front") }}'
				},
				maxDate:moment().endOf('month'),
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

			});


			/**Create User Form Validation**/
			$("#addForm").validate({
			    rules: {
			    	product:  {
	                            required: true,
	                           
	                        },
	                date:{
	                            required: true,
	                            
	                        },
	                module_type:{
	                            required: true,
	                            
	                        },
	                title:{
			                    required: true,
			                },
					ref_no:{
			                    required: true,
			                },
	                failure:{
			                    required: true,
			                },
	                reporting:{
			                    required: true,
			                }
			    },
			    errorPlacement: function(error, element) {
			        if(element.hasClass("select2-hidden-accessible")){
			        	error.insertAfter(element.siblings('span.select2'));
			        }else if(element.hasClass("floating-input")){
			        	element.closest('.form-floating-label').addClass("error-cont").append(error);
			        	//error.insertAfter();
			        }else{
			        	error.insertAfter(element);
			        }
			    },
			    submitHandler: function(form) {
			        loadButton('#addRowButton');
	                $(form).find(".alert").addClass("d-none");
	                var data = $(form).serialize();
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.schedule.create') }}",
	                    data: data,
	                    dataType: "json",
	                    success: function(data) {
	                        loadButton("#addRowButton");
	                        
	                        if(data.success == 1){
	                        	//form.reset();
	                        	$("#addModal").find("#addForm")[0].reset();
	                        	$("#addModal").modal("toggle");
	                            notifySuccess(data.message);
	                           	table.fnDraw();
	                        }else{
	                            notifyWarning(data.message);
	                            var errors = data.errors;
	                            console.log(errors);
	                            if(_.size(errors) > 0){
	                                $.each(errors, function(index, error){
	                                    $(form).find( "[name='"+index+"']" ).addClass("error").after( "<label class='error'>"+error+"</label>" );
	                                });
	                            }

	                        }
	                    }
	                });
			    }
			});

			$("#editForm").validate({
			    rules: {

			        product:  {
	                            required: true,
	                           
	                        },
	                date:{
	                            required: true,
	                            
	                        },
	                module_type:{
	                            required: true,
	                            
	                        },
	                title:{
			                    required: true,
			                },
	                ref_no:{
			                    required: true,
			                },
	                failure:{
			                    required: true,
			                },
	                reporting:{
			                    required: true,
			                }
			    },
			    errorPlacement: function(error, element) {
			        if(element.hasClass("select2-hidden-accessible")){
			            error.insertAfter(element.siblings('span.select2'));
			        }else if(element.hasClass("floating-input")){
			            element.closest('.form-floating-label').addClass("error-cont").append(error);
			            //error.insertAfter();
			        }else{
			            error.insertAfter(element);
			        }
			    },
			    submitHandler: function(form) {
			        loadButton('#editRowButton');
			        $(form).find(".alert").addClass("d-none");
			        var data = $(form).serialize();
			        $.ajax({
			            type: "POST",
			            url: "{{ route('private.schedule.update') }}",
			            data: data,
			            dataType: "json",
			            success: function(data) {
			                loadButton("#editRowButton");
			                if(data.success == 1){
			                	form.reset();
			                	$("#editModal").modal("toggle");
			                    notifySuccess(data.message);
			                   	table.fnDraw();
			                }else{
			                    notifyWarning(data.message);
			                    var errors = data.errors;
			                    console.log(errors);
			                    if(_.size(errors) > 0){
			                        $.each(errors, function(index, error){
			                            $(form).find( "[name='"+index+"']" ).addClass("error").after( "<label class='error'>"+error+"</label>" );
			                        });
			                    }

			                }
			            }
			        }); 
			    }
			}); 

			$("table").on("click", ".edit-data", function(e){
		    	e.preventDefault();
		        var dataId = $(this).data("id");
		        $.ajax({
                    type: "POST",
                    url: "{{route('private.schedule.edit')}}",
                    data: {data:dataId},
                    dataType: "json",
                    success: function(response) {
                    	if(response.success == 1){
                    		var record = response.data;
	                        $('#editModal #editId').val(record.id);
	                        $('#editModal #editProduct').val(record.product_type).trigger('change');
	                        $('#editModal #editUser').val(record.user_id).trigger('change');
	                        $('#editModal #editManager').val(record.manager_id).trigger('change');
	                        $('#editModal #editEngineer').val(record.engineer_id).trigger('change');
							var date = moment(record.schedule_date).format('YYYY-MM-DD');
	                        $('#editModal #editDate').val(date);
	                        $('#editModal #editModuleType').val(record.module_type).trigger('change');
	                        $('#editModal #editTitle').val(record.title);
	                        $('#editModal #editRef_no').val(record.ref_no);
	                        $('#editModal #editFailure').val(record.failure);
	                        $('#editModal #editReporting').val(record.reporting);
	                        $('#editModal .select-filter').trigger("change");
	                        $('#editModal').modal();
	                    }
	                    else
	                    {
	                    	notifyWarning(response.message);
                    	}
	                }
		        }); 
		    });

		    $('.data-exports').on("click",function(){
	            var exportsStatus = $('#status-filter').val();
	            var exportsModule = $('#module-filter').val();
	            var exportsManager = $("#manager-filter").val()
	            var exportsStartDate = startDate;
	            var exportsEndDate = endDate;
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.schedule.data-exports') }}",
	                    data: {exportsStatus:exportsStatus,exportsModule:exportsModule,exportsManager:exportsManager,exportsStartDate:exportsStartDate,exportsEndDate:exportsEndDate},
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