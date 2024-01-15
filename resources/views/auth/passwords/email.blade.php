@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger border-danger text-white text-center"><font size=4>ลืมรหัสผ่าน</font></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{action('EmailsController@lostpass')}}">
                        @csrf
                        
                        <div class="form-group row">
                            
                            {{-- <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label> --}}

                            
                            <div class="col-md-14 mx-auto">
                                <b>Password จะถูกส่งไปยัง Email ของคุณ</b>
                            </div>
                        </div>

                        <div class="form-group row">
                            
                            {{-- <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label> --}}

                            
                            <div class="col-md-8 mx-auto">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" name="email" placeholder="E-mail" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-8 mx-auto">
                                <button type="submit" class="btn btn-danger ">
                                        <i class="fas fa-exclamation-circle"></i>
                                    ยืนยัน
                                </button>
                            
                                <a class="btn btn-outline-primary float-right" href="/home">
                                        กลับหน้าหลัก
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
</script>
@endsection
