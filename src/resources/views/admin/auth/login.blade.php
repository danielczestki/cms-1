@extends("cms::admin.layouts.default")

@section("title", "Login")

@section("content")

    {{ Form::open(["method" => "POST", "url" => cmsaction('Auth\AuthController@login')]) }}
        {!! csrf_field() !!}
        
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">E-Mail Address</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
            @if ($errors->has('email'))
                {{ $errors->first('email') }}
            @endif
        </div>
        
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Password</label>
            <input type="password" class="form-control" name="password">
            @if ($errors->has('password'))
                {{ $errors->first('password') }}
            @endif
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-sign-in"></i> Login</button>
            <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
        </div>
        
    {{ Form::close() }}
    
@endsection