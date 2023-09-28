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
			
			<div class="card card-with-nav">
				<div class="card-header">
					<div class="row row-nav-line">
						<ul class="nav nav-tabs nav-line nav-color-secondary" role="tablist">
							
							<li class="nav-item "> <a class="nav-link active show" data-toggle="tab" href="#profile-tab" role="tab" aria-selected="false">Profile</a> </li>
							<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#password-tab" role="tab" aria-selected="false">Change Password</a> </li>
						</ul>
					</div>
				</div>
				<div class="card-body ">
					<div class="tab-content">
						<div class="tab-pane fade active show" id="profile-tab" role="tabpanel" aria-labelledby="pills-home-tab-nobd">
							<form class="row" id="profileForm" enctype="multipart/form-data">
								<div class="col-md-5 offset-md-2">
									<div class="row mt-3">
										<div class="col-md-12">
											<div class="form-group form-group-default">
												<label>Name</label>
												<input type="text" maxlength="{{ limit('name.max') }}" class="form-control alpha-space" name="name" placeholder="Name" value="{{ $profile->name }}">
											</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col-md-6">
											<div class="form-group form-group-default">
												<label>Email</label>
												<input type="email" maxlength="{{ limit('email.max') }}" class="form-control" name="email" placeholder="Name" value="{{ $profile->email }}">
												</div>
											</div>
										<div class="col-md-6">
											<div class="form-group form-group-default">
												<label>Phone</label>
												<input type="text" maxlength="{{ limit('phone.max') }}" class="form-control digit-only" value="{{ $profile->phone }}" name="phone" placeholder="Phone">
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4 ">
									<div class="row mt-3">
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<div class="fileinput-new img-thumbnail" style="width: 166px; height: auto;">
												<img src="{{ empty($profile->profile_pic) ? avatar($profile->name) : asset($profile->profile_pic) }}" data-src="{{ empty($profile->profile_pic) ? avatar($profile->name) : asset($profile->profile_pic) }}" data-trigger="fileinput"  alt="Profile Pic">
											</div>
											<div class="fileinput-preview fileinput-exists img-thumbnail" data-trigger="fileinput" style="max-width: 166px; max-height: auto;"></div>
											<div>
												<span class="btn btn-outline-secondary btn-file d-none">
													{{-- <span class="fileinput-new">Select image</span>
													<span class="fileinput-exists">Change</span> --}}
													<input type="file" name="profile_pic" accept="image/jpeg,image/png,image/webp">
												</span>
												{{-- <a href="#" class="btn btn-outline-secondary fileinput-exists" data-dismiss="fileinput">Remove</a> --}}
											</div>
										</div>
									</div>
									
								</div>
								<div class="col-md-3 offset-md-4">
									<div class="text-right mt-3 mb-3">
										<button class="btn btn-success btn-block" id="updateProfileBtn" data-loading-text="Updating.." data-loading="" data-text="" type="submit">Update</button>
									</div>
								</div>
							</form>
										
						</div>
						<div class="tab-pane fade" id="password-tab" role="tabpanel" aria-labelledby="pills-home-tab-nobd">
							<div class="row">
								<form class="col-md-4 offset-md-4" id="confirmPasswordForm">
									<div class="row mt-3">
										<div class="col-md-12">
											<div class="form-group form-group-default">
												<label>Current Password</label>
											<input type="password" maxlength="{{ limit('password.max') }}" class="form-control inline-control" name="current_password" placeholder="Fill Current Password">
											</div>
										</div>
									
										<div class="col-md-12">
											<div class="form-group form-group-default">
												<label>New Password</label>
												<input type="password" maxlength="{{ limit('password.max') }}" class="form-control inline-control" id="password" name="password" placeholder="Fill New Password">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group form-group-default">
												<label>Confirm Password</label>
												<input type="password" maxlength="{{ limit('password.max') }}" class="form-control inline-control" name="password_confirmation" placeholder="Fill Confirm Password">
											</div>
										</div>
									
										<div class="text-right mt-3 mb-3 col-md-12">
											<button class="btn btn-success btn-block" id="updatepasswordBtn" data-loading-text="Updating.." data-loading="" data-text="" type="submit">Update Password</button>
										</div>
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



@endsection


@push('js')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){

    	$('.nav-item submenu').on('change',function(){
    		alert();
    	});
        /**Reset Password Form Validation**/
        $("#confirmPasswordForm").validate({
            rules: {
                current_password:  {
                            required: true,
                            minlength: {{ limit("password.min") }},
							maxlength: {{ limit("password.max") }}
                        },
                password:{
                            required: true,
                            validPassword: true,
							minlength: {{ limit("password.min") }},
							maxlength: {{ limit("password.max") }}
                        },
                password_confirmation:{
                            required: true,
                            equalTo: "#password"
                        }
            },
            messages: {
            	password_confirmation: {
            		equalTo: "{{ __("validation.confirmed", ["attribute" => "password"]) }}"
            	}
            },
            errorPlacement: function(error, element) {
                if(element.hasClass("select2-hidden-accessible")){
                	error.insertAfter(element.siblings('span.select2'));
                }else if(element.hasClass("floating-input")){
                	element.closest('.form-floating-label').addClass("error-cont").append(error);
                	//error.insertAfter();
                }else if(element.hasClass("inline-control")){
                	element.closest('.form-group').append(error);
                	//error.insertAfter();
                }else{
                	error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                //form.submit();
                loadButton('#updatepasswordBtn');
				var data = $(form).serialize();
				$.ajax({
					type: "POST",
					url: "{{ route('private.passwordUpdate') }}",
					data: data,
					dataType: "json",
					success: function(data) {
						loadButton('#updatepasswordBtn');
						if(data.success == 1){
							form.reset();
							notifySuccess(data.message);
						}
						else
						{
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

		/**Reset Password Form Validation**/
        $("#profileForm").validate({
            rules: {
                name:  {
                            required: true,
                            minlength: {{ limit("name.min") }},
							maxlength: {{ limit("name.max") }},
							validName: true
                        },
                email:{
                            required: true,
							minlength: {{ limit("email.min") }},
							maxlength: {{ limit("email.max") }}
                        },
                phone:{
                            required: true,
							digits: true,
                            minlength: {{ limit("phone.min") }},
							maxlength: {{ limit("phone.max") }}
                        },
				profile_pic:{
						accept: "image/*"
				}
            },
            messages: {
            	password_confirmation: {
            		equalTo: "{{ __("validation.confirmed", ["attribute" => "password"]) }}"
            	}
            },
            errorPlacement: function(error, element) {
                if(element.hasClass("select2-hidden-accessible")){
                	error.insertAfter(element.siblings('span.select2'));
                }else if(element.hasClass("floating-input")){
                	element.closest('.form-floating-label').addClass("error-cont").append(error);
                	//error.insertAfter();
                }else if(element.hasClass("inline-control")){
                	element.closest('.form-group').addClass("error-cont").append(error);
                	//error.insertAfter();
                }else{
                	error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                //form.submit();
                loadButton('#updateProfileBtn');
				var data = new FormData(form);
				$.ajax({
					type: "POST",
					url: "{{ route('private.profileUpdate') }}",
					data: data,
					processData: false,
                    contentType: false,
					dataType: "json",
					success: function(data) {
						loadButton('#updateProfileBtn');
						if(data.success == 1){
							form.reset();
							notifySuccess(data.message);
							setTimeout(function(){
								window.location.reload();
							}, 1000);
						}
						else
						{
							notifyWarning(data.message);
							var errors = data.errors;
                            if(_.size(errors) > 0){
                                $.each(errors, function(index, error){
									if(index == "profile_pic"){
										
										$(form).find( ".fileinput-preview" ).after( "<label class='error'>"+error+"</label>" ); 
									}else{
										
										$(form).find( "[name='"+index+"']" ).addClass("error").after( "<label class='error'>"+error+"</label>" ); 
									}
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