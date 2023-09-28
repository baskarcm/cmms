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
				<a href="{{ route('private.pm.report') }}">PM Module</a>
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
						<h4 class="card-title">{{$pm->equipment->product->name}}</h4>
						<div class="ml-auto">
							@if($pm->schedule->engineer_status == 1)	
								<span class="badge badge-pill badge-warning">Pending</span>									
							@elseif($pm->schedule->engineer_status == 2 )
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
								<p class="text-center">{{$pm->equipment->product->name}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Sl.no</label>
								<p class="text-center">{{$pm->equipment->code}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Line</label>
								<p class="text-center">{{$pm->equipment->location}}</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Station</label>
								<p class="text-center">{{$pm->equipment->station}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Doc.no</label>
								<p class="text-center">{{$pm->equipment->doc_no}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Root Cause</label>
								<p class="text-center">{{$pm->root_cause}}</p>
							</div>
						</div>
					</div>
					<div class="row">
					<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Technician</label>
								<p class="text-center">{{$pm->users->name}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Engineer</label>
								<p class="text-center">{{$pm->schedule->engineer->name}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Manager</label>
								<p class="text-center">{{$pm->schedule->manager->name}}</p>
							</div>
						</div>
					</div>
					
					@foreach($detail as $details)
					    <hr>
						<div class="row">
							<div class="col-md-3">
								<p class="text-primary">Inspection Point :</p>
							</div>
							<div class="col-md-9">
								<p class="text-left">{{$details->point->name}}</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<p class="text-primary">Inspection Item :</p>
							</div>
							<div class="col-md-9">
								<p class="text-left">{{$details->items->name}}</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<p class="text-primary">Product Judge :</p>
							</div>
							<div class="col-md-9">
								<p class="text-left">{{$details->judge->name}}</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<p class="text-primary">Cycle Of Period :</p>
							</div>
							<div class="col-md-9">
								<p class="text-left">Monthly</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<p class="text-primary">Status :</p>
							</div>
							<div class="col-md-9">
								<p class="text-left">{{$details->status}}</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6  mx-auto">
								<p class="text-primary text-center">Action</p>
							</div>
						</div>
						<div class="row p-2">
								<p class="text-center">{{$details->action}}</p>
							</div>
							<div class="row p-2 image-gallery">
								@if(!empty($details->action_image))
									@foreach(json_decode($details->action_image, true) as $key => $action_images)
										<a href="{{$action_images}}" image="{{$key}}" class="col-6 col-md-3 mb-4" ><img src="{{$action_images}}" width="100" height="75" style="margin-left:10px" class="img-fluid"></a>
									@endforeach
								@endif
						</div>

						<div class="row">
							<div class="col-md-6  mx-auto">
								<p class="text-primary text-center">Defected</p>
							</div>
						</div>
						<div class="row p-2">
								<p class="text-center">{{$details->defect_item}}</p>
							</div>
							<div class="row p-2 image-gallery">
								@if(!empty($details->defect_image))
									@foreach(json_decode($details->defect_image, true) as $defect_images)
										<a href="{{$defect_images}}" class="col-6 col-md-3 mb-4" ><img src="{{$defect_images}}" width="100" height="75" style="margin-left:10px" class="img-fluid"></a>
									@endforeach
								@endif
						</div>
					@endforeach
				</div>
			</div>
		</div>


	</div>
</div>
@endsection
