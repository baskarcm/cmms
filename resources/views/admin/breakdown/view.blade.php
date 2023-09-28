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
				<a href="{{ route('private.breakdown.report') }}">Breakdown Module</a>
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
		<div class="col-md-8 mx-auto">
			<div class="card">
					<div class="card-header">
					<div class="d-flex align-items-center">
						<h4 class="card-title">{{$breakdown->equipment->product->name}}</h4>
						<div class="ml-auto">
							@if($breakdown->schedule->engineer_status == 1)	
								<span class="badge badge-pill badge-warning">Pending</span>									
							@elseif($breakdown->schedule->engineer_status == 2 )
								<span class="badge badge-pill badge-primary">Completed</span>
							@endif
						</div>
					</div>
				</div>	
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Equipment Name</label>
								<p class="text-center">{{$breakdown->equipment->product->name}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Sl.no</label>
								<p class="text-center">{{$breakdown->equipment->code}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Line</label>
								<p class="text-center">{{$breakdown->equipment->location}}</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Station</label>
								<p class="text-center">{{$breakdown->equipment->station}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Ref.no</label>
								<p class="text-center">{{$breakdown->schedule->ref_no}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Doc.no</label>
								<p class="text-center">{{$breakdown->equipment->doc_no}}</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Technician</label>
								<p class="text-center">{{$breakdown->users->name}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Engineer</label>
								<p class="text-center">{{$breakdown->schedule->engineer->name}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Manager</label>
								<p class="text-center">{{$breakdown->schedule->manager->name}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Root Cause</label>
								<p class="text-center">{{$breakdown->root_cause}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Engineer Comment</label>
								<p class="text-center">{{$breakdown->schedule->engineer_comment}}</p>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-4">
						</div>
						<div class="col-md-4">
								<p class="text-primary text-center">Date</p>
						</div>
						<div class="col-md-4">
							<p class="text-primary text-center">Time</p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<p class="text-primary">Failure :</p>
						</div>
						<div class="col-md-4">
							<p class="text-center">{{$breakdown->failure_date}}</p>
						</div>
						<div class="col-md-4">
							<p class="text-center">{{$breakdown->failure_time}}</p>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<p class="text-primary">Reporting :</p>
						</div>
						<div class="col-md-4">
							<p class="text-center">{{$breakdown->report_date}}</p>
						</div>
						<div class="col-md-4">
							<p class="text-center">{{$breakdown->report_time}}</p>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<p class="text-primary">Maintenance Start :</p>
						</div>
						<div class="col-md-4">
							<p class="text-center">{{$breakdown->start_date}}</p>
						</div>
						<div class="col-md-4">
							<p class="text-center">{{$breakdown->start_time}}</p>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<p class="text-primary">Maintenance End :</p>
						</div>
						<div class="col-md-4">
							<p class="text-center">{{$breakdown->end_date}}</p>
						</div>
						<div class="col-md-4">
							<p class="text-center">{{$breakdown->end_time}}</p>
						</div>
					</div>
					<hr>

					<div class="row">
						<div class="col-md-6">
							<p class="text-primary">Request Period :</p>
						</div>
						<div class="col-md-6">
							<p class="text-center">{{$breakdown->request_period}}</p>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<p class="text-primary">Waiting Period :</p>
						</div>
						<div class="col-md-6">
							<p class="text-center">{{$breakdown->waiting_period}}</p>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<p class="text-primary">Maintenance Period :</p>
						</div>
						<div class="col-md-6">
							<p class="text-center">{{$breakdown->maintenance_period}}</p>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<p class="text-primary">Total Downtime :</p>
						</div>
						<div class="col-md-6">
							<p class="text-center">{{$breakdown->total_downtime}}</p>
						</div>
					</div>
					
					<hr>
					<div class="row">
						<div class="col-md-6  mx-auto">
							<p class="text-primary text-center">Problem</p>
						</div>
					</div>
					
					@foreach($problem as $problems)
						@if(!empty($problems->problem))
							<div class="row">
								<p class="text-center">{{$problems->problem}}</p>
							</div>
							@if(!empty($problems->problem_image))
								<div class="row image-gallery">
									@foreach(json_decode($problems->problem_image, true) as $problem_images)
										<a href="{{$problem_images}}"><img src="{{$problem_images}}" width="100" height="75" style="margin-left:10px" class="img-fluid"></a>
									@endforeach
								</div>
							@endif
						@endif
					@endforeach
					
					<hr>
					<div class="row">
						<div class="col-md-6  mx-auto">
							<p class="text-primary text-center">Action</p>
						</div>
					</div>
					
					@foreach($action as $actions)
						@if(!empty($actions->action))
							<div class="row">
								<p class="text-center">{{$actions->action}}</p>
							</div>
							@if(!empty($actions->action_image))
								<div class="row image-gallery">
									@foreach(json_decode($actions->action_image, true) as $action_images)
									<a href="{{$problem_images}}"><img src="{{$action_images}}" width="100" height="75" style="margin-left:10px" class="img-fluid"></a>
									@endforeach
								</div>
							@endif
						@endif
					@endforeach	

					<hr>
					<div class="row">
						<div class="col-md-6  mx-auto">
							<p class="text-primary text-center">Prevention</p>
						</div>
					</div>
					
					@foreach($prevention as $preventions)
					   @if(!empty($preventions->prevention))
							<div class="row">
								<p class="text-center">{{$preventions->prevention}}</p>
							</div>
							@if(!empty($preventions->prevention_image))
							<div class="row image-gallery">
								@foreach(json_decode($preventions->prevention_image, true) as $prevention_images)
								<a href="{{$problem_images}}"><img src="{{$prevention_images}}" width="100" height="75" style="margin-left:10px" class="img-fluid"></a>
								@endforeach
							</div>
							@endif
						@endif
					@endforeach
					
					@if(!empty($spare->title))
						<hr>
						<div class="row">
							<div class="col-md-6 mx-auto">
								<p class="text-primary text-center">Spare</p>
							</div>
						</div>
					
						<div class="row">
							<div class="col-md-6">
								<div class="form-group no-bd form-group-default">
									<label class="text-primary text-center">Title</label>
									<p class="text-center">{{$spare->title}}</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group no-bd form-group-default">
									<label class="text-primary text-center">Name</label>
									<p class="text-center">{{$spare->name}}</p>
								</div>
							</div>
						</div>
						
						@if(!empty($spare->file))
							<div class="row image-gallery">
								@foreach(json_decode($spare->file, true) as $file)
								<a href="{{$file}}" download><img src="{{$file}}" width="100" height="75" style="margin-left:10px" class="img-fluid"></a>
								@endforeach
							</div>
						@endif
					@endif
				</div>
			</div>
		</div>


	</div>
</div>
@endsection
