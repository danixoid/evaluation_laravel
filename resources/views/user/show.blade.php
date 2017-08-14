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
                    <div class="panel-heading">{!! trans('interface.user') !!}</div>


                    <div class="panel-body form-horizontal">

                        <div class="form-group">
                            <label class="control-label col-md-2">{!! trans('interface.roles') !!}</label>
                            <div class="col-md-10 form-control-static">
                                @foreach($user->roles as $role)<span class="text-info">{!!
                                trans('interface.' . $role->name) !!}</span> @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">{!! trans('interface.name') !!}</label>
                            <div class="col-md-10 form-control-static">{!! $user->name !!}</div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">{!! trans('interface.iin') !!}</label>
                            <div class="col-md-10 form-control-static">{!! $user->iin !!}</div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">{!! trans('interface.email_address') !!}</label>
                            <div class="col-md-10 form-control-static">{!! $user->email !!}</div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-3">
                                <a href="{!! route('user.edit',['id'=>$user->id]) !!}" class="btn btn-block btn-info">{!! trans('interface.edit') !!}</a>
                            </div>
                            <div class="col-md-3">
                                <a href="#" id="deleteOrg" class="delete btn btn-block btn-danger">{!! trans('interface.destroy') !!}</a>
                            </div>
                        </div>


                    </div>


                </div>
            </div>
        </div>
    </div>

    <form id="form_delete_user" action="{!! route('user.destroy',['id' => $user->id]) !!}" method="POST">
        {!! csrf_field() !!}
        {!! method_field("DELETE") !!}
    </form>
@endsection


@section('javascript')
    <script>
        $(function(){
            $("#deleteOrg").click(function() {
                $('#form_delete_user').submit();
            });


        })
    </script>
@endsection

