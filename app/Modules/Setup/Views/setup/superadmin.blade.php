@extends('setup.master')

@section('content')
    <section class="app-content-header">
        <h1>@lang('bt.account_setup')</h1>
        <h3>@lang('acl.setup1')</h3>
        <h3>@lang('acl.setup2')</h3>
        <h3>@lang('acl.setup3')</h3>
        <h3>@lang('acl.setup4')</h3>
        <h3>@lang('acl.setup5')</h3>
        <h3>@lang('acl.setup6')</h3>
    </section>
    <section class="content">
        {!! Form::open(['route' => 'setup.postAccount', 'class' => 'form-install', 'autocomplete' => 'off']) !!}
        <div class="row">
            <div class="col-md-12">
                <div class=" card card-light">
                    <div class="card-body">
                        @include('layouts._alerts')
                        <h4>@lang('bt.user_account')</h4>
                        <table class="table table-responsive table-striped table-bordered mb-5">
                            <thead class="table-light">
                            <tr>
                                <th scope="col">@lang('acl.id')</th>
                                <th scope="col">@lang('acl.name')</th>
                                <th scope="col">@lang('acl.email')</th>
                                <th scope="col">@lang('acl.superadmin')</th>
                                <th scope="col">@lang('acl.admin')</th>
                                <th scope="col">@lang('acl.user')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <th scope="row">{{$user->id}}</th>
                                    <th>{{$user->name}}</th>
                                    <th>{{$user->email}}</th>
                                    <th><input class="form-check-input" type="radio" name="role1" value="{{$user->id}}"
                                               id="super{{$user->id}}" @if($loop->first) checked @endif></th>
                                    <th><input class="form-check-input" type="radio" name="role2[{{$user->id}}]"
                                               value="admin_{{$user->id}}" id="admin{{$user->id}}"
                                               @if($loop->first) disabled @endif></th>
                                    <th><input class="form-check-input" type="radio" name="role2[{{$user->id}}]"
                                               value="user_{{$user->id}}" id="user{{$user->id}}"
                                               @if($loop->first) checked
                                               disabled @endif @if(!$loop->first) checked @endif></th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <script>
                            // get superadmin radios
                            var superrb = document.querySelectorAll('input[name="role1"]');
                            superrb.forEach((rb) => {
                                rb.onclick = function () {
                                    // which superadmin radio checked?
                                    var superadmin = this.checked; // true or false
                                    // get list of radio buttons with name  starts with 'role2['
                                    var radios = document.querySelectorAll('input[name^="role2["]');
                                    // loop through list of radio buttons
                                    for (var i = 0, len = radios.length; i < len; i++) {
                                        var r = radios[i]; // current radio button
                                        // superadmin checked and id = role2 id (remove admin_, user_ to get id)
                                        if (superadmin && this.value === r.value.substring(r.value.indexOf("_") + 1)) {
                                            r.disabled = true
                                        } else { // superadmin not checked
                                            r.disabled = false; // no radios disabled
                                        }
                                    }
                                }
                            })
                        </script>
                        <button class="btn btn-primary" type="submit">@lang('bt.continue')</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
@stop
