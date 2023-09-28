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
							Add AdminUser
						</button>
					</div>
				</div>
				<div class="card-body">
					<div class="row p-2 filter-cont mb-4">
						
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>User Type</label>
								<select class="select-filter form-control" data-placeholder="Select a Status" id="type-filter">
	                              	<option value="">All</option>
	                              	@foreach($userTypes as $userType)
			                            <option value="{{ $userType->id }}">{{ $userType->name }}</option>
		                            @endforeach

								</select>
							</div>
						</div>
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
									<th>Email</th>
									<th>Mobile</th>
									<th>Type</th>
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
					New AdminUser
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
				<form id="addForm"  method="POST" autocomplete="off">
			<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Name</label>
								<input id="addName" type="text" maxlength="{{ limit("name.max")}}" class="form-control" placeholder="Name" name="name">
							</div>
						</div>
						
					</div>	
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>Email</label>
								<input id="addEmail" type="email" maxlength="{{ limit("email.max")}}" class="form-control" placeholder="Email Address" name="email">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>Phone</label>
								<input id="addPhone" type="text" maxlength="{{ limit("phone.max")}}" class="form-control digit-only" placeholder="Phone Number" name="phone">
							</div>
						</div>
						<!-- <div class="col-md-6">
							<div class="form-group form-group-default">
								<label>Gender</label>
								<select class="select-filter form-control" name="gender" data-placeholder="Select a type" id="gender">
		                          	<option value="">Select a type</option>
		                          	@foreach($genders as $gender)
			                            <option value="{{ $gender->id }}">{{ $gender->name }}</option>
		                            @endforeach
								</select>
							</div>
						</div> -->
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-group-default form-group-password">
								<label>Password</label>
								<input id="addPassword" type="password" maxlength="{{ limit("password.max")}}" class="form-control" placeholder="Password" name="password">
								<div class="show-password">
									<i class="far fa-eye-slash"></i>
								</div>
							</div>
						</div>


						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>User Type</label>
								<select class="select-filter form-control" name="user_type" data-placeholder="Select a type" id="addType">
		                          	<option value="">Select a type</option>
		                          	@foreach($userTypes as $userType)
			                            <option value="{{ $userType->id }}">{{ $userType->name }}</option>
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
					Edit User
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editForm" method="POST">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Name</label>
								<input id="editName" type="text" maxlength="{{ limit("name.max")}}" class="form-control" placeholder="Name" name="name">
								<input type="hidden" id="editId" name="data">
							</div>
						</div>
						

					</div>	
					<div class="row">
						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>Email</label>
								<input id="editEmail" type="email" maxlength="{{ limit("email.max")}}" class="form-control" placeholder="Email Address" name="email">
							</div>
						</div>
					
						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>Phone</label>
								<input id="editPhone" type="text" maxlength="{{ limit("phone.max")}}" class="form-control digit-only" placeholder="Phone Number" name="phone">
							</div>
						</div>
					</div>
					<div class="row">	
						<div class="col-md-6">
							<div class="form-group form-group-default form-group-password">
								<label>Password</label>
								<input id="editPassword" type="password" maxlength="{{ limit("password.max")}}" class="form-control" placeholder="Password" name="password">
								<div class="show-password">
									<i class="far fa-eye-slash"></i>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group form-group-default">
								<label>User Type</label>
								<select class="select-filter form-control" name="user_type" data-placeholder="Select a type" id="editType">
		                          	<option value="">Select a type</option>
		                          	@foreach($userTypes as $userType)
			                            <option value="{{ $userType->id }}">{{ $userType->name }}</option>
		                            @endforeach

								</select>
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

@endsection

@push("js")

	

	<script type="text/javascript">
		var delete_url = "{{ route('private.adminuser.destroy') }}";
		var table;
		
		$(document).ready(function() {
				
	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "Admin User"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.adminuser.list") }}",
	                "type": "POST",
	                data: function (d) { 
	                	d.user_type = $("#type-filter").val()     
	                	d.status = $("#status-filter").val()     
	                },
	            },
	            
	            "columns": [
	                    { "data": "name" },
	                    { "data": "email" },
	                    { mRender:  function ( data, type, row ){ return row.phone} },
	                     { mRender:  function ( data, type, row ){ return row.user_type.name} },
	                    { "data": "active",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge status_edit"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    } 
						},
	                    {   "mRender": function ( data, type, row ) 
	                        {
	                        	var btn = `<div class="form-button-action"> 
	                        		<a href="#" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg edit-data" data-id="${row.id}" data-original-title="Edit Profile" target="_blank"> <i class="fa fa-edit"></i> </a> 
	                        		<button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> <i class="fa fa-trash"></i> </button> </div>`; 
	                            return btn;       
	                         }
	                    }
	                ],

	               createdRow: function( row, data, dataIndex ) {
	                            
	                              $(row).find( '.status_edit' ).editable({
	                                    url: "{{ route('private.adminuser.status') }}",
	                                    success: function(response, newValue) {
	                                        if(response.success == 0) return response.message; //msg will be shown in editable form
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


			/**Create User Form Validation**/
			$("#addForm").validate({
			    rules: {
			    	name:  {
	                            required: true,
	                            minlength: {{ limit("name.min") }},
	                            maxlength: {{ limit("name.max") }},
	                            validName: true
	                        },
	                email:{
	                            required: true,
	                            minlength: {{ limit("email.min") }},
	                            maxlength: {{ limit("email.max") }}
	                        },
	                phone:{
	                            required: true,
	                            digits: true,
	                            minlength: {{ limit("phone.min") }},
	                            maxlength: {{ limit("phone.max") }}
	                        },
	                password:{
	                            required: true,
	                            validPassword: true,
	                            minlength: {{ limit("password.min") }},
	                            maxlength: {{ limit("password.max") }}
	                        },
	                user_type:{
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
	                    url: "{{ route('private.adminuser.create') }}",
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
			$("#editForm").validate({
			    rules: {

			        name:  {
			                    required: true,
			                    minlength: {{ limit("name.min") }},
			                    maxlength: {{ limit("name.max") }},
			                    validName: true
			                },
			        email:{
			                    required: true,
			                    minlength: {{ limit("email.min") }},
			                    maxlength: {{ limit("email.max") }}
			                },
			        phone:{
			                    required: true,
			                    digits: true,
			                    minlength: {{ limit("phone.min") }},
			                    maxlength: {{ limit("phone.max") }}
			                },
			        password:{
			                    validPassword: true,
			                    minlength: {{ limit("password.min") }},
			                    maxlength: {{ limit("password.max") }}
			                },
			        user_type:{
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
			            url: "{{ route('private.adminuser.update') }}",
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

		 	//EDIT USER GET DATA
		    $("table").on("click", ".edit-data", function(e){
		    	e.preventDefault();
		        var dataId = $(this).data("id");
		        $.ajax({
                    type: "POST",
                    url: "{{route('private.adminuser.edit')}}",
                    data: {data:dataId},
                    dataType: "json",
                    success: function(response) {
                    	if(response.success == 1){
                    		var record = response.data;
	                        $('#editModal #editId').val(record.id);
	                        $('#editModal #editName').val(record.name);
	                        $('#editModal #editEmail').val(record.email);
	                        $('#editModal #editPhone').val(record.phone);
	                        $('#editModal #editPassword').val('');
	                        $('#editModal #editType').val(record.user_type.id);
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
	    });
	</script>
@endpush