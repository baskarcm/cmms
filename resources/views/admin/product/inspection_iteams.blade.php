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
		<button class="btn btn-admin btn-round ml-auto" id="addModalOpen">
							<i class="fa fa-plus"></i>
							Inspection Items
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
							Inspection Items
						</button>
					</div>
				</div> -->
				<div class="card-body">
					<div class="row p-2 filter-cont mb-4">
						
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Equipment Name</label>
								<select class="select-filter form-control" data-placeholder="Select a Status" id="product-filter">
	                              	<option value="">All</option>
	                              	@foreach($products as $product)
			                            <option value="{{ $product->id }}">{{ $product->name }}</option>
		                            @endforeach

								</select>
							</div>
						</div>
						<div class="col-md-3 pl-0">
							<div class="form-group pt-0">
								<label>Inspection Point</label>
								<select class="select-filter form-control" data-placeholder="Select a Status" id="point-filter">
	                              	<option value="">All</option>
	                              	@foreach($inspec_point as $point)
			                            <option value="{{ $point->id }}">{{ $point->name }}</option>
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
									<th>Created_at</th>
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
					New Inspection Point
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
								<label>Equipment Name</label>
								<select class="select-filters form-control product" data-placeholder="Select a Status" id="product" name="product">
	                              	<option value="">All</option>
	                              	@foreach($products as $product)
			                            <option value="{{ $product->id }}">{{ $product->name }}</option>
		                            @endforeach
								</select>
							</div>
					    </div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Inspection Point</label>
								<select class="select-filters form-control" data-placeholder="Select a Status" id="inspec_point" name="inspec_point">
	                              	<option value="">All</option>
								</select>
							</div>
					    </div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Inspection Items</label>
								<input id="inspection_iteams" type="text" class="form-control" placeholder="Inspection Items" name="name">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer no-bd">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" id="addRowButton" class="btn btn-admin" data-loading-text="Register..." data-loading="" data-text="">Add</button>
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
					Edit Inspection Point
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editForm" method="POST">
				@csrf
				<input id="edit_data" type="hidden"  name="data">
				<div class="modal-body">
				<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Equipment Name</label>
								<select class="select-filter2 form-control" data-placeholder="Select a Status" id="edit_product" name="product">
	                              	<option value="">All</option>
	                              	@foreach($products as $product)
			                            <option value="{{ $product->id }}">{{ $product->name }}</option>
		                            @endforeach
								</select>
							</div>
					    </div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Inspection Point</label>
								<select class="select-filter2 form-control" data-placeholder="Select a Status" id="edit_inspec_point" name="inspec_point">
	                              	<option value="">All</option>
								</select>
							</div>
					    </div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Inspection Items</label>
								<input id="edit_name" type="text" class="form-control" placeholder="Inspection Items" name="name">
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

@push("js")
	
	<script type="text/javascript">
		var delete_url = "{{ route('private.insIteam.destroy') }}";
		var table;
		
		$(document).ready(function() { 
			
			$('#addForm').on('change','.product',function(){
				var data = $('.product').val();
				console.log(data);
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.insIteam.getPoint') }}",
	                    data: "data="+data,
	                    dataType: "json",
	                    success: function(record) {
	                    	$('#inspec_point option').remove();
							$('#inspec_point').append("<option value=''>All</option>");
	                    	$.each(record.data, function(i, item) {

    							$('#inspec_point').append($('<option>', { value: item.id,text: item.name }));
							});
	                    }
	                }); 
			});

				
	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "inspection items"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.insIteam.list") }}",
	                "type": "POST",
	                data: function (d) {     
						d.status = $("#status-filter").val()  
						d.product = $("#product-filter").val() 
						d.point = $("#point-filter").val()  
	                },
	            },
	            
	            "columns": [
	                    { "data": "name" },
	                    { "data": "created_at",
							"render": function ( data, type, row ) {
								return moment(row.created_at).format('YYYY/MM/DD');
							},
							"name": "created_at" 
						},
	                    { "data": "active",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge status_edit"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    } 
						},
						
	                    {   "mRender": function ( data, type, row ) 
	                        {
	                        	var btn = `<div class="form-button-action"> 
		                        				<a href="javascript:void(0);" data-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-lg edit-data" data-id="${row.id}" data-point="${row.product_id}" data-original-title="Edit"> <i class="fa fa-edit"></i> </a>
		                        				<button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> <i class="fa fa-trash"></i> </button>
	                        				 </div>`; 
	                            return btn;       
	                         }
	                    }
	                ],

	               createdRow: function( row, data, dataIndex ) {
	                            
	                              $(row).find( '.status_edit' ).editable({
	                                    url: "{{ route('private.insIteam.status') }}",
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
					[1, "desc"]
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
			
			$(".select-filters").select2({
				 dropdownParent: $("#addModal"),
				 theme: "bootstrap",
			});
			
			$(".select-filter2").select2({
				 dropdownParent: $("#editModal"),
				 theme: "bootstrap",
			});

			$("#addModalOpen").on('click',function(){
				$("#addModal").find("#addForm")[0].reset();
				$("#addModal").find("#addName").focus();
				$("#addModal").modal();
			});

			/**Create items Form Validation**/
	        $("#addForm").validate({
	            rules: {

	                name:  {
	                            required: true,
	                            minlength: {{ limit("inspection.min") }},
	                            maxlength: {{ limit("inspection.max") }}
	                        },
					product: {
								required: true,
							},
					inspec_point: {
								required: true,
					},
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
	                    url: "{{ route('private.insIteam.create') }}",
	                    data: data,
	                    dataType: "json",
	                    success: function(data) {
	                        loadButton("#addRowButton");
	                        
	                        if(data.success == 1){
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
                            minlength: {{ limit("inspection.min") }},
                            maxlength: {{ limit("inspection.max") }}
                        },
					product: {
								required: true,
							},
					inspec_point: {
								required: true,
					},
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
	                    url: "{{ route('private.insIteam.update') }}",
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
			
			$('#edit_product').on('change',function(){
				var data = $('#edit_product').val();
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.insIteam.getPoint') }}",
	                    data: "data="+data,
	                    dataType: "json",
	                    success: function(record) {
	                    	$('#edit_inspec_point option').remove();
							$('#edit_inspec_point').append("<option value=''>All</option>");
	                    	$.each(record.data, function(i, item) {

    							$('#edit_inspec_point').append($('<option>', { value: item.id,text: item.name }));
							});
	                    }
	                }); 
			});

         	//EDIT USER GET DATA
            $("table").on("click", ".edit-data", function(){
                var dataId = $(this).data("id");
                $.ajax({
                    type: "POST",
                    url: "{{route('private.insIteam.edit')}}",
                    data: {data:dataId},
                    dataType: "json",
                    success: function(response) {
                    	if(response.success == 1){
                    		var record = response.data;
	                        $('#editModal #edit_data').val(record.id);
	                        $('#editModal #edit_name').val(record.name);
							$('#editModal #edit_product').val(record.product_id);
							$('#editModal #edit_inspec_point').val(record.inspec_point);
							console.log(record.inspec_point);
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