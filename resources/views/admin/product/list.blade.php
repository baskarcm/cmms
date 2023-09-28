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
							Add Equipment
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
							Add Product
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
					</div>
					<div class="table-responsive">
						<table id="record-table" class="display table table-striped table-hover w-100 table-head-bg-primary" >
							<thead>
								<tr>
									<th>Equipment Name</th>
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
					New Equipment Name
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="addForm" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Equipment Name</label>
								<input id="name" type="text" maxlength="{{ limit("inspection.max")}}" class="form-control" placeholder="Equipment Name" name="name">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Equipment Image</label>
								<input id="image" type="file"  name="image[]" multiple>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer no-bd">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" id="addRowButton" class="btn btn-admin" data-loading-text="Adding Product..." data-loading="" data-text="">Add</button>
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
					Edit Equipment Name
				</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editForm" method="POST" enctype="multipart/form-data">
				@csrf
				<input id="editData" type="hidden"  name="data">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Equipment Name</label>
								<input id="editName" type="text" maxlength="{{ limit("inspection.max")}}" class="form-control" placeholder="Equipment Type" name="name">
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label>Equipment Image</label>
								<input id="editImage" type="file"  name="image[]" multiple>
							</div>
						</div>
						<div class="col-sm-12" id="viewImage">
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

<!-- image Model -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content" style="width:512px;margin: 0 auto;margin-top:150px;"> 
				<div class="modal-body">
				<div id="demo" class="carousel slide" data-ride="carousel">
				<ul class="carousel-indicators">
					<div class="indicator"></div>
				</ul>
				<div class="carousel-inner">
					<div class="carousel-image"></div>
				</div>
				<a class="carousel-control-prev" href="#demo" data-slide="prev">
					<span class="carousel-control-prev-icon"></span>
				</a>
				<a class="carousel-control-next" href="#demo" data-slide="next">
					<span class="carousel-control-next-icon"></span>
				</a>
				</div>
				</div>
		</div>
	</div>
</div>


@endsection

@push("js")
	
	<script type="text/javascript">
		var delete_url = "{{ route('private.product.destroy') }}";
		var table;
		
		$(document).ready(function() {
				
	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "product"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.product.list") }}",
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
	                        					<a href="#" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg image-data" data-id="${row.id}" data-original-title="Image"> <i class="fas fa-file-image"></i> </a>
		                        				<a href="javascript:void(0);" data-toggle="tooltip" title="Edit Type" class="btn btn-link btn-primary btn-lg edit-data" data-id="${row.id}"  data-original-title="Edit Type"> <i class="fa fa-edit"></i> </a>
		                        				<button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> <i class="fa fa-times"></i> </button>
	                        				 </div>`; 
	                            return btn;       
	                         }
	                    }
	                ],

	               createdRow: function( row, data, dataIndex ) {
	                            
	                              $(row).find( '.status_edit' ).editable({
	                                    url: "{{ route('private.product.status') }}",
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
	                            minlength: {{ limit("inspection.min") }},
	                            maxlength: {{ limit("inspection.max") }}
	                        },
					"image[]": {
                     			extension: "jpg|jpeg|png", 
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
					var data = new FormData(form);
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.product.create') }}",
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
						},
					   cache: false,
                       contentType: false,
                       processData: false
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
				"image[]": {
                     extension: "jpg|jpeg|png", 
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
					var data = new FormData(form);
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.product.update') }}",
	                    data: data,
						dataType: "json",
						cache: false,
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
						},
						cache: false,
                       	contentType: false,
                       	processData: false
	                }); 
	            }
	            
	           
	        });
	        

	         /**view image**/
			 $("table").on("click", ".image-data", function(){
					var dataId = $(this).data("id");
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.product.image') }}",
						data: {data:dataId},
	                    dataType: "json",
	                    success: function(response) {
							var record = response.data;
							$(".carousel-indicators").empty();
							$(".carousel-inner").empty();
							$.each(JSON.parse(record.image), function (key, val) {
								
								if(key==0)
								{
									$('.carousel-indicators').append('<li data-target="#demo" data-slide-to="'+key+'" class="active"></li>');
									$('.carousel-inner').append('<div class="carousel-item active"><img src="'+val+'" width="512" height="356"></div>');									
								}else
								{
									$('.carousel-indicators').append('<li data-target="#demo" data-slide-to="'+key+'"></li>');
									$('.carousel-inner').append('<div class="carousel-item"><img src="'+val+'" width="512" height="356"></div>');	
								}
    						});
	                    }
	                }); 
	            });
	            
         	//EDIT USER GET DATA
            $("table").on("click", ".edit-data", function(){
                var dataId = $(this).data("id");
                $.ajax({
                    type: "POST",
                    url: "{{route('private.product.edit')}}",
                    data: {data:dataId},
                    dataType: "json",
                    success: function(response) {
                    	if(response.success == 1){
                    		var record = response.data;
	                        $('#editModal #editData').val(record.id);
							$('#editModal #editName').val(record.name);
							$('#viewImage').empty();
	                        $.each(JSON.parse(record.image), function (key,val) {							
	                       	 $('#viewImage').append('<span class="slide-img" style="margin-left:10px"><img src='+val+' width="50" height="50"></span>');	
    						});
	                        $('#editModal').modal();
	                    }
	                    else
	                    {
	                    	notifyWarning(response.message);
                    	}
                	}
                }); 
            });

         	//Image model DATA
            $("table").on("click", ".image-data", function(){
	            $('#imageModal').modal();
            });

			$(document).on("click", '[data-toggle="lightbox"]', function(event) {
				event.preventDefault();
				$(this).ekkoLightbox();
			});
            
	    });
	</script>
@endpush