@extends('layouts.app')

@section('content')
<style>
    .footer {
        
        position: absolute;
        width: 100%;
        bottom:0px;
        
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-secondary">
                <div class="card-header bg-dark text-white border-secondary"><center><font size=5>ใบเตือนออนไลน์(TEST FOR JEAMJIT)</font></center></div>

                <div class="card-body ">
                    <form method="POST" action="{{ route('login') }}" onsubmit="return submitResult();">
                        @csrf

                        <div class="form-group row">
                            {{-- <label for="userid" class="col-sm-4 col-form-label text-md-right">Username</label> --}}

                            <div class="col-md-8 mx-auto">
                                <input id="userid" placeholder="Username" type="text" class="form-control{{ $errors->has('userid') ? ' is-invalid' : '' }}" name="userid" value="{{ old('userid') }}" autofocus>

                                @if ($errors->has('userid'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('userid') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        
                        <div class="form-group row">
                            {{-- <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label> --}}

                            <div class="col-md-8 mx-auto">
                                <input id="password" placeholder="Password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" >

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group row mb-0">
                            <div class="col-md-8 mx-auto">
                                <button type="submit" class="btn btn-dark btn-block btn-lg ">
                                    <i class="fas fa-sign-in-alt"></i>
                                    เข้าสู่ระบบ
                                </button>                                
                            </div>  
                        </div>  
                        <div class="form-group row mb-0">      
                                <div class="col-md-8 mx-auto ">
                                        <br> <center>
                                    <a  href="/register">
                                        สมัครใช้งาน
                                    </a> | 
                                <a href="{{ route('password.request') }}">
                                    ลืมรหัสผ่าน ?
                                </a></center>
                                </div>
                        </div>   
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    @include('inc.footer')
</footer> 

<script type="text/javascript">

function submitResult() {
   if ( $('#userid').val() == '' ) {
      alert('กรุณาใส่รหัสผู้ใช้งาน !');
      $('#password').val('');
      $('#userid').val('');
      $('#userid').focus();
 
      return false;
   }else if ( $('#password').val() == '' ) {
    alert('กรุณาใส่รหัสผ่าน !');
      $('#password').val('');
      $('#userid').val('');
      $('#userid').focus();

      return false;
   }else {
      return true ;
   }
}

</script>
@endsection
