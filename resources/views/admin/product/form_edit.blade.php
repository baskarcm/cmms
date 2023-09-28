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
				<a href="{{ route('private.form') }}">Check List</a>
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
					</div>
				</div>
				<div class="card-body">
					<form id="addForm">
						<input type="hidden" name="data" id="key" value="{{ $product_key }}">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group form-group-default">
									<label>Equipment Name</label>
									<select class="select-filter form-control product" name="product" data-placeholder="Select a type" id="product">
										<option value="">Select a type</option>
										@foreach($products as $product)
											<option value="{{ $product->id }}">{{ $product->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6">
									<div class="form-group form-group-default">
										<label>Serial Number</label>
										<input id="code" type="text"  class="form-control" placeholder="Equipment Serial Number" name="code">
									</div>
								</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group form-group-default">
									<label>Line</label>
									<select class="select-filter form-control line" name="line" data-placeholder="Select a type" id="line">
										<option value="">Select a line</option>
											<option value="0">0</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">S/ASSY MECH</option>
											<option value="5">RECTIFICATION</option>
											<option value="6">FINISH/FINAL</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
									<div class="form-group form-group-default">
										<label>Station</label>
										<input id="station" type="text"  class="form-control" placeholder="Equipment Station" name="station">
									</div>
								</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group form-group-default">
                                        <label>Position</label>
                                        <input id="position" type="text"  class="form-control" placeholder="Equipment Position" name="position">
                                    </div>
                                </div>
                        </div>
						<div class="row check_box">
							<div class="col-md-6">
								<h3 class="text-center">Inspection Point</h3>
								<div class="inspection_point scrollbar">

								</div>
							</div>
							<div class="col-md-6">
								<h3 class="text-center">Inspection Items</h3>
								<div class="inspection_items scrollbar">

								</div>
							</div>
						</div>
						<div class="row justify-content-center mt-4">
							<button type="submit" id="addRowButton" class="btn btn-admin" data-loading-text="Update..." data-loading="" data-text="">Update</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@push("js")

	<script type="text/javascript">

		var delete_url = "{{ route('private.insPoint.destroy') }}";
		var table;
		var first = 0;
		$(document).ready(function() {


			var key = $('#key').val();
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.form.edit') }}",
	                    data: {data:key},
	                    dataType: "json",
	                    success: function(record) {
							$('#code').val(record.type.code);
							$('.line').val(record.type.location);
							$('#station').val(record.type.station);
							$('#position').val(record.type.position);
							$('.product').val(record.type.product_id);
							$('.select-filter').trigger("change");
	                    }
	                });

				$('#addForm').on('change','.product',function(){
				var data = $('.product').val();
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.form.getPoint') }}",
	                    data: "data="+data,
	                    dataType: "json",
	                    success: function(record) {
							$('.inspection_point .form-group').remove();
	                    	$.each(record.data, function(i, item) {
    							$('.inspection_point').append("<div class='form-group pb-0'><input type='checkbox' class='check point_"+item.id+"' id='point_"+item.id+"' data-id='"+item.id+"' name='point[]' value='"+item.id+"'><label for='checkbox' class='m-0 label_point' data-id='"+item.id+"' >"+item.name+"</label></div>");
							});
	                    }
	                });
			});



			$('.inspection_point').on('click','.check',function(){
				var dataId = $(this).data("id");
				if($(this).is(":checked")){
	                $.ajax({
	                    type: "POST",
	                    url: "{{ route('private.form.getItems') }}",
	                    data: "data="+dataId,
	                    dataType: "json",
	                    success: function(record) {
							//$('.inspection_items .form-group').remove();
	                    	$.each(record.data, function(i, item) {
    							$('.inspection_items').append("<div class='form-group pb-0 group_"+dataId+"'><input type='checkbox' class='check_item items_"+item.id+"' id='items_"+item.id+"' data-parent='"+dataId+"' data-id='"+item.id+"'  name='items[]' value='"+item.id+"'><label for='checkbox' class='m-0'>"+item.name+"</label></div>");

								first= dataId;
							});
							$('.inspection_items .group_'+dataId).append("<hr>");
							$('.inspection_items .form-group').hide();
							//console.log(first);
							$('.inspection_items .group_'+first).show();
	                    }
					});
				}else if($(this).is(":not(:checked)")){
					$('.check_item[data-parent="'+dataId+'"]').parent('.form-group').remove();
            	}
			});

			$("#addForm").on("change",".product", function(){
				console.log('test');
						//alert('testing');
						$.ajax({
							type: "POST",
							url: "{{ route('private.form.editPoint') }}",
							data: {data:key},
							dataType: "json",
							success: function(record) {
								$.each(record.point, function(i, item) {
									$(".point_"+item.inspec_point).trigger('click');
								});
							}
	                	});
					});



					$("#addForm").on("click",".check", function(){
						var dataId = $(this).data("id");
						$.ajax({
							type: "POST",
							url: "{{ route('private.form.edit') }}",
							data: {data:key},
							dataType: "json",
							success: function(record) {
								$.each(record.items, function(i, item) {

									if($(".items_"+item.inspec_iteam).is(":not(:checked)"))
									{
										$(".items_"+item.inspec_iteam).trigger('click');
									}

								});
							}
	                	});
					});

			$('.inspection_point').on('click','.label_point',function(){
				var dataId = $(this).data("id");
				$('.inspection_items .form-group').hide();
				$('.inspection_items .group_'+dataId).show();
			});

			//Initialize Select 2
			$(".select-filter").select2({
				theme: "bootstrap"
			});

			/**Create Club User Form Validation**/
	        $("#addForm").validate({
	            rules: {

					product:{
			                    required: true,
							},
					code:  {
	                        required: true,
	                        minlength: {{ limit("inspection.min") }},
	                        maxlength: {{ limit("inspection.max") }}
							},
					line:  {
	                        required: true,
	                        minlength: 1,
	                        maxlength: {{ limit("inspection.max") }}
							},
					station:  {
	                        required: true,
	                        minlength: 1,
	                        maxlength: {{ limit("inspection.max") }}
					},
                    position:  {
	                        required: true,
	                        minlength: 1,
	                        maxlength: {{ limit("inspection.max") }}
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
	                    url: "{{ route('private.form.update') }}",
	                    data: data,
	                    dataType: "json",
	                    success: function(data) {
	                        loadButton("#addRowButton");
	                        if(data.success == 1){
	                            notifySuccess(data.message);
								window.location.href = "{{ route('private.form') }}"
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



		});
	</script>
@endpush
