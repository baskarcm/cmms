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
				<div class="card-header">
					<div class="d-flex align-items-center">
						<h4 class="card-title">{{ $title }}</h4>
						<button class="btn btn-admin btn-round ml-auto" id="addModalOpen">
							<i class="fa fa-plus"></i>
							Add User Type
						</button>
					</div>
				</div>
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
					</div>
					<div class="table-responsive">
						<table id="record-table" class="display table table-striped table-hover w-100 table-head-bg-primary" >
							<thead>
								<tr>
									<th>Name</th>
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
					New User Type
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="addForm" method="POST" autocomplete="off">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>User Type</label>
								<input id="addName" type="text" maxlength="{{ limit("user_type.max")}}" class="form-control" placeholder="User Type" name="name">
							</div>
						</div>
						
					</div>
				</div>
				<div class="modal-footer no-bd">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" id="addRowButton" class="btn btn-primary" data-loading-text="Club Register..." data-loading="" data-text="">Add</button>
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
					Edit User Type
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editForm" method="POST">
				@csrf
				<input id="editData" type="hidden"  name="data">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>User Type</label>
								<input id="editName" type="text" maxlength="{{ limit("user_type.max")}}" class="form-control" placeholder="User Type" name="name">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer no-bd">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" id="editRowButton" class="btn btn-primary" data-loading-text="Update..." data-loading="" data-text="">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>



<!-- Edit Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header no-bd">
				<h2 class="modal-title">
					Permissions
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
				<div class="modal-body">
			<form id="permissionForm" method="POST">
				@csrf
				<input id="permissionData" type="hidden"  name="user_type">
					<div class="row">
						<div class="col-sm-12  permission-content">

						</div>
					</div>
			</form>
				</div>
				<div class="modal-footer no-bd">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" id="permissionRowButton" class="btn btn-primary" data-loading-text="Updating..." data-loading="" data-text="">Update Permission</button>
				</div>
		</div>
	</div>
</div>

@endsection

@push("js")
	
	<script type="text/javascript">
		var delete_url = "{{ route('private.admintype.destroy') }}";
		var table;
		
		$(document).ready(function() {
				
	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "Type"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.admintype.list") }}",
	                "type": "POST",
	                data: function (d) {     
						d.status = $("#status-filter").val()  
	                },
	            },
	            
	            "columns": [
	                    { "data": "name" },
	                    
	                    { "data": "active",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge status_edit"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    } 
						},
						
	                    {   "mRender": function ( data, type, row ) 
	                        {
	                        	var btn = `<div class="form-button-action"> 
	                        					<a href="" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg set-permission" data-id="${row.id}" data-original-title="Set Permission" target="_blank" style="display:none"> <i class="fas fa-shield-alt"></i> </a>
		                        				<a href="javascript:void(0);" data-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-lg edit-data" data-id="${row.id}"  data-original-title="Edit"> <i class="fa fa-edit"></i> </a>
		                        				<button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> <i class="fa fa-trash"></i> </button>
	                        				 </div>`; 
	                            return btn;       
	                         }
	                    }
	                ],

	               createdRow: function( row, data, dataIndex ) {
	                            
	                              $(row).find( '.status_edit' ).editable({
	                                    url: "{{ route('private.admintype.status') }}",
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
					[0, "asc"]
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

			/**Create Club User Form Validation**/
	        $("#addForm").validate({
	            rules: {

	                name:  {
	                            required: true,
	                            minlength: {{ limit("user_type.min") }},
	                            maxlength: {{ limit("user_type.max") }}
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
	                    url: "{{ route('private.admintype.create') }}",
	                    data: data,
	                    dataType: "json",
	                    success: function(data) {
	                        loadButton("#addRowButton");
	                        
	                        if(data.success == 1){
	                        	form.reset();
	                        	$("#addModal").modal("toggle");
	                            notifySuccess(data.message);
	                           	table.fnDraw();
	                        }else{
	                            notifyWarning(data.message);
	                            var errors = data.errors;
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

	        /**Edit Club User Form Validation**/
	        $("#editForm").validate({
	            rules: {
	            	 name:  {
                            required: true,
                            minlength: {{ limit("user_type.min") }},
                            maxlength: {{ limit("user_type.max") }}
                        }
	            },
	            errorPlacement: function(error, element) {
	                if(element.hasClass("select2-hidden-accessible")){
	                    error.insertAfter(element.siblings('span.select2'));
	                }if(element.hasClass("floating-input")){
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
	                    url: "{{ route('private.admintype.update') }}",
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
	        

	         /**Edit Club User Form Validation**/
	        $("#permissionForm").validate({
	            rules: {
	            	
	            },
	            errorPlacement: function(error, element) {
	                if(element.hasClass("select2-hidden-accessible")){
	                    error.insertAfter(element.siblings('span.select2'));
	                }if(element.hasClass("floating-input")){
	                    element.closest('.form-floating-label').addClass("error-cont").append(error);
	                    //error.insertAfter();
	                }else{
	                    error.insertAfter(element);
	                }
	            },
	            submitHandler: function(form) {
	                loadButton('#permissionRowButton');
	                $(form).find(".alert").addClass("d-none");
	                var data = $(form).serialize();
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.permission.update') }}",
	                    data: data,
	                    dataType: "json",
	                    success: function(data) {
	                        loadButton("#permissionRowButton");
	                        if(data.success == 1){
	                        	form.reset();
	                        	$("#permissionModal").modal("toggle");
	                            notifySuccess(data.message);
	                           	table.fnDraw();
	                        }else{
	                            notifyWarning(data.message);
	                            var errors = data.errors;
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
         	//EDIT USER GET DATA
            $("table").on("click", ".edit-data", function(){
                var dataId = $(this).data("id");
                $.ajax({
                    type: "POST",
                    url: "{{route('private.admintype.edit')}}",
                    data: {data:dataId},
                    dataType: "json",
                    success: function(response) {
                    	if(response.success == 1){
                    		var record = response.data;
	                        $('#editModal #editData').val(record.id);
	                        $('#editModal #editName').val(record.name);
	                        $('#editModal').modal();
	                    }
	                    else
	                    {
	                    	notifyWarning(response.message);
                    	}
                	}
                }); 
            });

             //Permission get data
            $("table").on("click", ".set-permission", function(e){
            	e.preventDefault();
                var dataId = $(this).data("id");
                console.log(dataId);
                $.ajax({
                    type: "get",
                    url: "{{route('private.permission.list')}}",
                    data: {user_type:dataId},
                    dataType: "json",
                    success: function(response) {
                    	if(response.success == 1){
                    		$("#permissionModal #permissionData").val(dataId);
                    		var data = response.data;
                    		if(data.length > 0){
                    			var perHtml = "";
                    			$.each(data, function(key,permission){
                    				perHtml += `<div class="row permission-ind border m-1 mb-0 p-2"><div class="col-sm-9 ">
													<span>${permission.name}</span>
												</div>
												<div class="col-sm-3 ">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" id="permission${permission.id}" name="permission[]" class="custom-control-input" ${permission.assigned_permission ? "checked" : ""} value="${permission.id}">
														<label class="custom-control-label" for="permission${permission.id}"></label>
													</div>
												</div></div>`;
                    			});
                    			$('#permissionModal .permission-content').html(perHtml);
                    			$('#permissionModal').modal();
                    		}
	                    }
	                    else
	                    {
	                    	notifyWarning(response.message);
                    	}
                	}
                }); 
            });

            $("#permissionRowButton").click(function(){
            	$("#permissionForm").submit();
            });

            
	    });
	</script>
@endpush