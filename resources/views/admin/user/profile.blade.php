@extends("admin.layouts.app")

@section("content")
<div class="page-inner">
	{{-- <h4 class="page-title">User Profile</h4> --}}
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
				<a href="{{ route('private.users') }}">User List</a>
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
		<div class="col-md-8">
			<div class="card card-with-nav">
				<div class="card-header">
					<div class="row row-nav-line">
						<ul class="nav nav-tabs nav-line nav-color-secondary" role="tablist">
							{{-- <li class="nav-item"> <a class="nav-link active show" data-toggle="tab" href="#home" role="tab" aria-selected="true">Timeline</a> </li> --}}
							<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab" aria-selected="false">Profile</a> </li>
							{{-- <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab" aria-selected="false">Settings</a> </li> --}}
						</ul>
					</div>
				</div>
				<div class="card-body">
					<div class="row mt-3">
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary">Name</label>
								<p>{{ $profile->name }} </p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary">Email</label>
								<p>{{ $profile->email }}</p>
							</div>
						</div>
					</div>
					<div class="row mt-1">
						
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary">Gender</label>
								<p>{{ $profile->gen->name }}</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary">Phone</label>
								<p>{{ $profile->phone }}</p>
							</div>
						</div>
					</div>
					<div class="row mt-1">
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary">User Type</label>
								<p>{{ $profile->type->name }}</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group no-bd form-group-default">
								<label class="text-primary">Created On</label>
								<p>@date($profile->created_at)</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card card-profile">
				<div class="card-header" style="background-image: url('{{ asset('private/assets/img/blogpost.jpg') }}')">
					<div class="profile-picture">
						<div class="avatar avatar-xl">
							<img src="{{ $profile->profile_pic }}" alt="..." class="avatar-img rounded-circle">
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="user-profile text-center">
										<div class="name">{{ $profile->name }} {{ $profile->lastname }}</div>
										<div class="job">{{ $profile->email }}</div>
										<div class="desc"> {{ $profile->phone }}</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


@endsection

@push("js")
	<script type="text/javascript">
		var delete_url = "{{ route('private.user.destroy') }}";
		var table;
		
		$(document).ready(function() {
			$(".select-filter").select2();
	    });
	</script>
@endpush