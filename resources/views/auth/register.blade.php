@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
@include('layouts.headers.guest')

<div class="container mt--8 pb-5">
    <!-- Table -->
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card bg-secondary shadow border-0">
                {{-- <div class="card-header bg-transparent pb-5">--}}
                {{-- <div class="text-muted text-center mt-2 mb-4"><small>{{ __('Sign up with') }}</small></div>--}}
            {{-- <div class="text-center">--}}
            {{-- <a href="#" class="btn btn-neutral btn-icon mr-4">--}}
            {{-- <span class="btn-inner--icon"><img src="{{ asset('argon') }}/img/icons/common/github.svg"></span>--}}
            {{-- <span class="btn-inner--text">{{ __('Github') }}</span>--}}
            {{-- </a>--}}
            {{-- <a href="#" class="btn btn-neutral btn-icon">--}}
            {{-- <span class="btn-inner--icon"><img src="{{ asset('argon') }}/img/icons/common/google.svg"></span>--}}
            {{-- <span class="btn-inner--text">{{ __('Google') }}</span>--}}
            {{-- </a>--}}
            {{-- </div>--}}
            {{-- </div>--}}
            <div class="card-body px-lg-5 py-lg-5">
                <div class="text-center text-muted mb-4">
                    <small>{{ __('Sign up with credentials') }}</small>
                </div>
                <form role="form" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                        <div class="input-group input-group-alternative mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                            </div>
                            <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" type="text" name="name" value="{{ old('name', $name ?? '') }}" required autofocus>
                        </div>
                        @if ($errors->has('name'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                        <div class="input-group input-group-alternative mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                            </div>
                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email', $email ?? '') }}" required>
                        </div>
                        @if ($errors->has('email'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('organization') ? ' has-danger' : '' }}">
                        <div class="input-group input-group-alternative mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-world-2"></i></span>
                            </div>
                            <input id="org-input" class="form-control{{ $errors->has('organization') ? ' is-invalid' : '' }}" placeholder="Organization name" type="text" name="organization" value="{{ old('organization', $organization ?? '') }}">
                        </div>
                        @if ($errors->has('organization'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('organization') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                            <label class="custom-control-label" for="customCheck1">I do not have an organization</label>
                        </div>
                    </div>
                    <!-- <div style="display: none;" class="form-group{{ $errors->has('user_type') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-badge"></i></span>
                                    </div>
{{--                                    <select class="form-control{{ $errors->has('user_type') ? ' is-invalid' : '' }}" id="user_type" name="user_type">--}}
{{--                                        <option value="" selected hidden>{{ __('User Type') }}</option>--}}
{{--                                        <option value="1" @if (old('user_type') == 1) selected @endif>{{ __('Admin') }}</option>--}}
{{--                                        <option value="2" @if (old('user_type') == 2) selected @endif>{{ __('Creator') }}</option>--}}
{{--                                        <option value="3" @if (old('user_type') == 3) selected @endif>{{ __('Member') }}</option>--}}
{{--                                    </select>--}}
                                    <select class="form-control{{ $errors->has('user_type') ? ' is-invalid' : '' }}" id="user_type" name="user_type">
                                        <option value="3" selected hidden>{{ __('User Type') }}</option>
{{--                                        <option value="1" @if (old('user_type') == 1) selected @endif>{{ __('Admin') }}</option>--}}
{{--                                        <option value="2" @if (old('user_type') == 2) selected @endif>{{ __('Creator') }}</option>--}}
{{--                                        <option value="3" @if (old('user_type') == 3) selected @endif>{{ __('Member') }}</option>--}}
                                    </select>
                                </div>
                                @if ($errors->has('user_type'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('user_type') }}</strong>
                                    </span>
                                @endif
                            </div> -->
                    <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                            </div>
                            <input id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" type="password" name="password" required>
                        </div>
                        @if ($errors->has('password'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                            </div>
                            <input class="form-control" placeholder="{{ __('Confirm Password') }}" type="password" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="text-muted font-italic">
                      <small>{{ __('password strength') }}: <span id="password-strength-status"></span></small>
                    </div>
                    <div class="row my-4">
                        <div class="col-12">
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" id="customCheckRegister" type="checkbox" required>
                                <label class="custom-control-label" for="customCheckRegister">
                                    <span class="text-muted">{{ __('I agree with the') }} <a href="{{ route('page.termsandconditions') }}" target=\\"_blank\\">{{ __('Terms of Use') }}</a></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-4">{{ __('Create account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('js')
<script>
    $("#customCheck1.custom-control-input").change(function() {
        if(this.checked){
            var input = $("#org-input");
            input.attr('disabled', 'disabled');
            input.removeAttr('value');
        }
        else {
            $("#org-input").removeAttr('disabled');
        }
    })
</script>
<script>
    $("#password").on('keyup', function(){
        var number = /([0-9])/;
        var alphabets = /([a-zA-Z])/;
        var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
        var status = $("#password-strength-status")
        if ($(this).val().length < 6) {
            status.removeClass();
            status.addClass('text-warning font-weight-700 weak-password');
            status.html("Weak (should be at least 6 characters.)");
        } else {
            if ($(this).val().match(number) && $(this).val().match(alphabets) && $(this).val().match(special_characters)) {
                status.removeClass();
                status.addClass('text-success font-weight-700 strong-password');
                status.html("Strong");
            } else {
                status.removeClass();
                status.addClass('text-primary font-weight-700 medium-password');
                status.html("Medium (numbers and special characters help improve strength.)");
            }
        }
    })
</script>
@endpush
