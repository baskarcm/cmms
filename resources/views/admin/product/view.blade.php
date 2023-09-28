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
				<a href="{{ route('private.form') }}"> Check List</a>
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
						<h4 class="card-title">{{ $product_type->product->name}}</h4>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Equipment Name</label>
								<p class="text-center">{{ $product_type->product->name}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Sl.no</label>
								<p class="text-center">{{ $product_type->code}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Location</label>
								<p class="text-center">{{ $product_type->location}}</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Station</label>
								<p class="text-center">{{ $product_type->station}}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Doc No</label>
								<p class="text-center">{{ $product_type->doc_no}}</p>
							</div>
						</div>
					</div>
					<hr>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Inspection Point</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary text-center">Inspection Item</label>
							</div>
						</div>
					</div>
					<div class="row">
					@foreach($form as $forms)
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group no-bd form-group-default">
										<p class="text-center">{{$forms->item->point->name}}</p>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group no-bd form-group-default">
										<p class="text-center">{{$forms->item->name}}</p>
									</div>
								</div>
							</div>
						</div>
						<hr>
					@endforeach
					</div>
			</div>
		</div>


	</div>
</div>
@endsection
