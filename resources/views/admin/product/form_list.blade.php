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
		<a href="{{ route('private.form.reg') }}" class="ml-auto">
							<button class="btn btn-admin btn-round" id="addModalOpen">
								<i class="fa fa-plus"></i>
								Add checklist Form
							</button>
						</a>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<!-- <div class="card-header">
					<div class="d-flex align-items-center">
						<h4 class="card-title">{{ $title }}</h4>
						<a href="{{ route('private.form.reg') }}" class="ml-auto">
							<button class="btn btn-admin btn-round" id="addModalOpen">
								<i class="fa fa-plus"></i>
								Add Product
							</button>
						</a>
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
									<label>Line</label>
									<select class="select-filter form-control" data-placeholder="Select a Status" id="line-status">
										<option value="">All</option>
										@foreach(config("site.line") as $key => $line)
											<option value="{{ $key }}">{{ $line }}</option>
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
									<th>Equipment</th>
									<th>Sl.no</th>
									<th>Line</th>
									<th>Station</th>
									<th>Doc.no</th>
									<th>Position</th>
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
		var delete_url = "{{ route('private.form.destroy') }}";
		var table;

		$(document).ready(function() {

	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "Equipment"]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.form.list") }}",
	                "type": "POST",
	                data: function (d) {
						d.status 	= $("#status-filter").val()
						d.product 	= $("#product-filter").val()
						d.line 		= $("#line-status").val()
	                },
	            },

	            "columns": [
						{ "data": "product_id",
							"render": function ( data, type, row ) {
								return row.equipment.name;
							},
						},
	                    { "data": "code" },
	                    { "data": "location" },
	                    { "data": "station" },
	                    { "data": "doc_no" },
						{ "data": "position" },
	                    { "data": "status",
	                       "render": function ( data, type, row ) {
	                                        return `<a href="javascript:;" class="badge status_edit"  data-type="select" data-pk="${row.id}" data-value="${row.active}" data-original-title="Select Status"> </a>`;
	                                    }
						},

	                    {   "mRender": function ( data, type, row )
	                        {
	                        	var btn = `<div class="form-button-action">
		                        				<a href="{{route('private.formEdit', ['key' => ''])}}/${row.key}" data-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-lg"   data-original-title="Edit"> <i class="fa fa-edit"></i> </a>
												<a href="{{route('private.formView', ['key' => ''])}}/${row.key}" data-toggle="tooltip" title="View" class="btn btn-link btn-primary btn-lg"   data-original-title="view"> <i class="fa fa-eye"></i> </a>
		                        				<a href="`+row.barcode_file+`" data-toggle="tooltip" title="Barcode" class="btn btn-link btn-primary btn-lg"   data-original-title="Edit Type" download> <i class="fa fa-download"></i> </a>
		                        				<button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> <i class="fa fa-trash"></i> </button>
	                        				 </div>`;
	                            return btn;
	                         }
	                    }
	                ],

	               createdRow: function( row, data, dataIndex ) {

	                              $(row).find( '.status_edit' ).editable({
	                                    url: "{{ route('private.form.status') }}",
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
					[5, "asc"]
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

			/**Create Club User Form Validation**/
	        $("#addForm").validate({
	            rules: {

	                name:  {
	                            required: true,
	                            minlength: {{ limit("inspection.min") }},
	                            maxlength: {{ limit("inspection.max") }}
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
	                    url: "{{ route('private.insPoint.create') }}",
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
                            minlength: {{ limit("inspection.min") }},
                            maxlength: {{ limit("inspection.max") }}
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
	                    url: "{{ route('private.insPoint.update') }}",
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
            $("table").on("click", ".edit-data", function(){
                var dataId = $(this).data("id");
                $.ajax({
                    type: "POST",
                    url: "{{route('private.insPoint.edit')}}",
                    data: {data:dataId},
                    dataType: "json",
                    success: function(response) {
                    	if(response.success == 1){
                    		var record = response.data;
	                        $('#editModal #edit_data').val(record.id);
	                        $('#editModal #edit_name').val(record.name);
	                        $('#editModal #edit_product').val(record.product_id);
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
