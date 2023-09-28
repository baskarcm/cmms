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
				<div class="card-body">
					<div class="row p-2 filter-cont mb-4">	
							<div class="card-body">
								<div class="row filter-cont mb-4">
									<div class="col-md-3 pl-0">
										<div class="form-group pt-0">
											<label>Choose Month</label>
											<input type="text" class="form-control bg-white" readonly id="month-filter">
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<form method="post" id="prodUptime">
										<table id="prod-uptime" class="display table table-striped table-hover w-100 table-head-bg-primary">
											<thead>
												<th>Month</th>
												<th>Jan</th>
												<th>Feb</th>
												<th>March</th>
												<th>April</th>
												<th>May</th>
												<th>June</th>
												<th>July</th>
												<th>Aug</th>
												<th>Sep</th>
												<th>Oct</th>
												<th>Nov</th>
												<th>Dec</th>
												<th>Year</th>
											</thead>
											<tbody class="tbody">

											</tbody>
								 		</table>
										<div class="modal-footer no-bd">
											<button type="submit" id="addRowButton" class="btn btn-primary" data-loading-text="Adding Customer..." data-loading="" data-text="">Submit</button>
										</div>
								 	</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@push("css")
<style>
table {
  border-collapse: collapse;
  margin-top:20px;
}

table, th, td {
  border: 1px solid black;
  padding: 10px;
}

tr.heading{
	background-color:#f1f1f1;
	font-weight: bold;
}
.btn-admin, a {
    color: #1572E8;
}
#prod-uptime .dynamic-month td{
	padding: 5px !important;
}
#prod-uptime .dynamic-month input{
	padding: 0px;
	border-radius: 0px;
	height: 40px !important;
}
.dynamic-month td{
	height: 40px;
}
</style>
@endpush

@push("js")
<script type="text/javascript">
 
	$(document).ready(function() {
		var count = 1;

		dynamic_field(count);

		function dynamic_field(number)
		{
		    html = '<tr class="dynamic-month">';
		        html += '<td><input type="text" name="name[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="jan[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="feb[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="march[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="april[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="may[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="june[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="july[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="aug[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="sep[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="oct[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="nov[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="dec[]" class="form-control" /></td>';
		        html += '<td><input type="text" name="year[]" class="form-control" /></td>';
		        if(number > 1)
		        {
		            /*html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove</button></td></tr>';*/
		            $('tbody').append(html);
		        }
		        else
		        {   
		            /*html += '<td><button type="button" name="add" id="add" class="btn btn-success">Add</button></td></tr>';*/
		            $('tbody').html(html);
		        }
		}

		$(document).on('click', '#add', function(){
			count++;
			dynamic_field(count);
		});

		$(document).on('click', '.remove', function(){
			count--;
			$(this).closest("tr").remove();
		});

		$('#prodUptime').on('submit', function(event){
	        event.preventDefault();
	        $.ajax({
	            url:'{{ route("private.production-uptime.list") }}',
	            method:'post',
	            data:$(this).serialize(),
	            dataType:'json',
	            beforeSend:function(){
	                $('#addRowButton').attr('disabled','disabled');
	            },
	            success:function(data)
	            {
	                if(data.error)
	                {
	                    var error_html = '';
	                    for(var count = 0; count < data.error.length; count++)
	                    {
	                        error_html += '<p>'+data.error[count]+'</p>';
	                    }
	                    $('#result').html('<div class="alert alert-danger">'+error_html+'</div>');
	                }
	                else
	                {
	                    dynamic_field(1);
	                    $('#result').html('<div class="alert alert-success">'+data.success+'</div>');
	                }
	                $('#addRowButton').attr('disabled', false);
	            }
	        })
		});

	});
</script>

@endpush