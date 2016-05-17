@extends("cms::admin.layouts.default")

@section("title", "Register")

@section("content")
    
    {{ Form::open(["method" => "POST", "url" => cmsaction('Auth\AuthController@register')]) }}
        {!! csrf_field() !!}
        
        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Firstame</label>
            <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}">
            @if ($errors->has('firstname'))
                {{ $errors->first('firstname') }}
            @endif
        </div>
        
        <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Surname</label>
            <input type="text" class="form-control" name="surname" value="{{ old('surname') }}">
            @if ($errors->has('surname'))
                {{ $errors->first('surname') }}
            @endif
        </div>
        
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">E-Mail Address</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
            @if ($errors->has('email'))
                {{ $errors->first('email') }}
            @endif
        </div>
        
        <div class="form-group{{ $errors->has('email_confirmation') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Confirm E-Mail Address</label>
            <input type="email" class="form-control" name="email_confirmation" value="{{ old('email_confirmation') }}">
            @if ($errors->has('email_confirmation'))
                {{ $errors->first('email_confirmation') }}
            @endif
        </div>
        
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Password</label>
            <input type="password" class="form-control" name="password">
            @if ($errors->has('password'))
                {{ $errors->first('password') }}
            @endif
        </div>
        
        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">Confirm Password</label>
            <input type="password" class="form-control" name="password_confirmation">
            @if ($errors->has('password_confirmation'))
                {{ $errors->first('password_confirmation') }}
            @endif
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-user"></i> Register</button>
        </div>
        
    {{ Form::close() }}
    
@endsection