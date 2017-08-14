<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{!! trans('interface.users') !!} | {!! trans('interface.edit') !!}</div>

                    <div class="panel-body">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
                                    {!! trans('interface.profile') !!}
                                </a></li>
                            <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
                                    {!! trans('interface.change_password') !!}
                                </a></li>
                            <li role="presentation"><a href="#iin" aria-controls="iin" role="tab" data-toggle="tab">
                                    {!! trans('interface.iin') !!}
                                </a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="profile">
                                <br />
                                <form id="form_create_user" class="form-horizontal" action="{!!
                            route('user.update',['id' => $user->id]) !!}" method="POST">
                                    {!! csrf_field() !!}
                                    {!! method_field("PUT")  !!}

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.role') !!}</label>
                                        <div class="col-md-9">
                                            <select class="form-control select2-multiple" multiple name="role_id[]" id="role">
                                                @foreach(\App\Role::all() as $role)
                                                    <option value="{!! $role->id !!}"
                                                        {!! ($user->hasRole($role->name)) ? "selected='selected'" : ""!!}>{!! trans('interface.' . $role->name) !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.email_address') !!}</label>
                                        <div class="col-md-9">
                                            <input type="email" class="form-control" name="email" value="{!! $user->email !!}" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.name') !!}</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="name" value="{!! $user->name !!}" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-3">
                                            <button class="btn btn-block btn-danger" >{!! trans('interface.update') !!}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="settings">
                                <br />
                                <form id="form_create_user" class="form-horizontal" action="{!!
                                        route('user.update',['id' => $user->id]) !!}" method="POST">
                                    {!! csrf_field() !!}
                                    {!! method_field("PUT")  !!}

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.password') !!}</label>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.confirm_password') !!}</label>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" name="password_confirmation" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-3">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="gen_pswd" > {!! trans('interface.generate_password') !!}
                                                </label>
                                                <span class="text-primary" id="pswd"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-3">
                                            <button class="btn btn-block btn-danger" >{!! trans('interface.update') !!}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="iin">
                                <br />
                                <form id="form_create_user" class="form-horizontal" action="{!!
                                        route('user.update',['id' => $user->id]) !!}" method="POST">
                                    {!! csrf_field() !!}
                                    {!! method_field("PUT")  !!}

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.iin') !!}</label>
                                        <div class="col-md-9">
                                            <input type="text" pattern="\d{12}" class="form-control" name="iin" value="{!! $user->iin !!}" required/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-3">
                                            <button class="btn btn-block btn-danger" >{!! trans('interface.update') !!}</button>
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
@endsection

@section('meta')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('javascript')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/i18n/ru.js') }}"></script>
    <script>

        $(function () {

            /**
             *  SELECT2
             */
            $("#role").select2({
                theme: "bootstrap",
                placeholder: '{!! trans('interface.select_role') !!}',
                language: '{!! config()->get('app.locale') !!}',
            });

            $('#gen_pswd').change(function (ev) {
                if(this.checked) {
                    var pswd = generatePassword();
                    $("#pswd").text('[' + pswd + ']');
                    $("input[type='password']").val(pswd);
                } else {
                    $("#pswd").text('');
                    $("input[type='password']").val('');
                }
            });

            $("input[type='password']").on('keyup',function(ev) {
                $("#pswd").text('');
            });
        });

        function generatePassword() {
            var length = 6,
                charset = "1234567890",
                retVal = "";
            for (var i = 0, n = charset.length; i < length; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }
            return retVal;
        }


    </script>

@endsection

