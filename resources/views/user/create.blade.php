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
                    <div class="panel-heading">{!! trans('interface.users') !!} | {!! trans('interface.create') !!}</div>

                    <div class="panel-body">

                        <form id="form_create_user" class="form-horizontal"
                              action="{!! route('user.store') !!}" method="POST">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.name') !!}</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="name"
                                           value="{!! old('name') !!}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.iin') !!}</label>
                                <div class="col-md-9">
                                    <input type="text" pattern="\d{12}"  class="form-control"
                                           name="iin" value="{!! old('iin') !!}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.email_address') !!}</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="email"
                                           value="{!! old('email') !!}" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.password') !!}</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control pwsd" name="password" required>
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
                                    <button class="btn btn-block btn-danger" >{!! trans('interface.create') !!}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<script>

    $(function(){

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