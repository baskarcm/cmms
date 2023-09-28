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
			<i class="fas fa-file-export"></i>
			Export
		</button>
		<button class="btn btn-admin btn-round ml-2" id="addModalOpen">
			<i class="fas fa-file-import"></i>
			Import
		</button>
		<button class="btn btn-admin btn-round ml-2 stock-exports">
			<i class="fas fa-file-download"></i>
			Download
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
		                              	<option value="1">Available</option>
		                              	<option value="0">Not vailable</option>
								</select>
							</div>

						</div>
					</div>
					<div class="table-responsive">
						<table id="record-table" class="display table table-striped table-hover w-100 table-head-bg-primary" >
							<thead>
								<tr>
									<th>Equipment</th>
									<th>Title</th>
									<th>Spare Name</th>
									<th>Sl.no</th>
									<th>Line</th>
									<th>Station</th>
									<th>Technician</th>
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


<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title">
					Import Inventory
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
							<div class="form-group">
								<label for="file-import">Import File</label>
								<input type="file" class="form-control-file" id="inventoryImport" name="importFile">
							</div>
							<div id="do-sample" style="color: #f25961;padding: 0px 10px;cursor:pointer;font-size:12px">Download Sample Inventory Stock</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					<button type="submit" id="addRowButton" class="btn btn-admin" data-loading-text="Import..." data-loading="" data-text="">Import</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push("js")



	<script type="text/javascript">
		var delete_url = "{{ route('private.inventory.destroy') }}";
		var table;

		$(document).ready(function() {
		    
		    $('#do-sample').click(function(){
    			window.open("{{ asset('common/img/sample-inventory-stock.csv') }}");
    		});

	    	table = $('#record-table').dataTable({
				"oLanguage": {
			        "sEmptyTable": "{{ __("site.no_data", ["attr" => "inventory "]) }}"
			    },
	            "processing": true,
	            "serverSide": true,
	            "ajax": {
	                "url": "{{ route("private.inventory.list") }}",
	                "type": "POST",
	                data: function (d) {
	                	d.user_type = $("#type-filter").val()
	                	d.status = $("#status-filter").val()
	                },
	            },

	            "columns": [
					{ "data": "product_type",
							"render": function ( data, type, row ) {
								return row.equipment.product.name;
							},
							"name": "product_type"
						},
						{ "data": "title" },
						{ "data": "name" },
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
								return row.users.name;
							},
							"name": "user_id"
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
	                        	var btn = `<div class="form-button-action">
	                        		<button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger delete-data"  data-id="${row.id}" data-original-title="Remove"> <i class="fa fa-trash"></i> </button> </div>`;
	                            return btn;
	                         }
	                    }
	                ],

	               createdRow: function( row, data, dataIndex ) {

	                              $(row).find( '.status_edit' ).editable({
	                                    url: "{{ route('private.inventory.status') }}",
	                                    success: function(response, newValue) {
	                                        if(response.success == 0) return response.message; //msg will be shown in editable form
	                                    },

	                                    inputclass: 'form-control',
	                                    source: [{
	                                        value: 1,
	                                        text: 'Available'
	                                    }, {
	                                        value: 0,
	                                        text: 'Not Available'
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


			$('.data-exports').on("click",function(){
	            var exportsStatus = $('#status-filter').val();
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.inventory.data-exports') }}",
	                    data: {exportsStatus:exportsStatus},
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


			/**Create Import File Form Validation**/
	        $("#addForm").validate({
	            rules: {
	                importFile: {
	                	required: true,
	                	extension: "xls|csv"
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
	                    url: "{{ route('private.inventory.stock-imports') }}",
	                    data: data,
	                    dataType: "json",
	                    success: function(data) {
	                        loadButton("#addRowButton");

	                        if(data.success == 1){
	                        	form.reset();
	                        	$("#addModal").modal("toggle");
	                            notifySuccess(data.message);
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

		    $('.stock-exports').on("click",function(){
                $.ajax({
                    type: "POST",
                    url: "{{ route('private.inventory.stock-exports') }}",
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
