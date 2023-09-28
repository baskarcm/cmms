@extends('admin.layouts.guest')

@section('content')

<div class="wrapper wrapper-login wrapper-login-full p-0">
    <div class="login-aside w-50 d-flex flex-column align-items-center justify-content-center text-center bg-white">
        <div class="logo-admin">
            <img src="http://aumediasystems.com/darashmaju/wp-content/uploads/2019/05/dm-logo03.png">
        </div>
        </div>
    <div class="login-aside w-50 d-flex align-items-center justify-content-center login-right-admin">
        <div class="container container-login container-transparent animated fadeIn">
            <div class="login-form">
        <h3 class="text-center">Reset Your Password</h3>
       <form role="form" method="POST" action="{{ route('password.update') }}">
                            <h3>Reset Password</h3>

                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                @endif
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{ $email }}" >
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                                @endif
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password"/>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                @if ($errors->has('password_confirmation'))
                                <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                                @endif
                                <input type="password_confirmation" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"/>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-default submit">Reset Password</button>
                            </div>

                            <div class="clearfix"></div>

                            <div class="separator">

                                <div class="clearfix"></div>
                                <br />

                                <div>
                                    <h1><i class="fa fa-paw"></i> Larashop Admin Panel</h1>
                                    <p>©2017 All Rights Reserved.</p>
                                </div>
                            </div>
                        </form>
    </div>
    </div>
    

   <!-- <div class="container container-forgot container-transparent animated fadeIn d-none">
        <h3 class="text-center">Forgot Your Password?</h3>
        <p class="mb-4">We get it, stuff happens. Just enter your email address below and we'll send you a link to reset your password!</p>
        <form class="login-form form" id="forgotPasswordForm" >
            <div class="alert alert-warning alert-dismissible fade show text-center d-none" role="alert">
              <strong></strong>
            </div>
            <div class="form-group">
                <label for="email" class="placeholder">Email</label>
                <input  id="forgotEmail" name="email" type="email" class="form-control pl-1 pr-1" placeholder="Enter Email Address..." required maxlength="{{ limit("email.max")}}">
                
            </div>
            <div class="form-action">
                <a href="#" id="show-signin" class="btn btn-danger btn-link btn-login mr-3">< Back</a>
                <button type="submit" class="btn btn-primary btn-login" data-loading-text="Checking.." data-loading="" data-text="" id="forgotButton">Send Reset Link</button>
            </div>
           <div class="login-account mt-5">
                <span class="msg">&copy; {{ date("Y") }} DEMS</span>
                
            </div>
        </form>
    </div>-->
</div>
</div>

@endsection


@push('js')

<script>
    $(document).ready(function(){

        $.ajaxSetup({
            headers : { "X-CSRF-TOKEN" :jQuery(`meta[name="csrf-token"]`). attr("content")}
        });
        /**Login Form Validation**/
        $("#loginForm").validate({
            rules: {
                email:  {
                            required: true,
                            email   : true
                        },
                password:{
                            required: true,
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
                form.submit();
                loadButton('#signin');
            }
            
           
        }); 


        /**forgot password Form Validation**/
        $("#forgotPasswordForm").validate({
            rules: {
                email:  {
                            required: true,
                            email   : true
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
                loadButton('#forgotButton');
                $(form).find(".alert").addClass("d-none");
                var data = $(form).serialize();
                $.ajax({
                    type: "POST",
                    url: "",
                    data: data,
                    dataType: "json",
                    success: function(data) {
                        loadButton("#forgotButton");
                        $(form).find(".alert").removeClass("d-none").find("strong").html(data.message);
                        if(data.success == 1){
                            notifySuccess(data.message);
                            $(form).find(".alert").removeClass("alert-warning").addClass("alert-success");
                            form.reset();
                        }else{
                            notifyWarning(data.message);
                            $(form).find(".alert").removeClass("alert-success").addClass("alert-warning");
                        }
                    }
                }); 
            }
            
           
        }); 


    });
</script>

<style type="text/css">
    
    .login-right-admin {
    color: #fff;
    background-color: #1a164e !important;
}
input#loginEmail {
    background: #fff !important;
}

input#password{
    background: #fff !important;
}
.login-form label.placeholder {
    color: #fff !important;
}

.btn-primary {
    background: #f26621 !important;
    border-color: #fff !important;
}.form-control{
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
 transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
 font-size: 14px;
    border-color: #ebedf2;
    /* padding: .6rem 1rem; */
    height: inherit !important;
}

.login .login-aside {
    padding: 25px;
    -moz-box-shadow: inset 0 0 10px #000000;
    -webkit-box-shadow: inset 0 0 10px #000000;
    box-shadow: inset 0 0 10px #000000;
}
.logo-admin img {
    width: 70%;
}
</style>
@endpush