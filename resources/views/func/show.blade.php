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
                    <div class="panel-heading">{!! trans('interface.func') !!}</div>


                    <div class="panel-body form-horizontal">

                        <div class="form-group">
                            <label class="control-label col-md-2">{!! trans('interface.title') !!}</label>
                            <div class="col-md-10 form-control-static">{!! $func->name !!}</div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3">
                                <a href="{!! route('func.edit',['id'=>$func->id]) !!}" class="btn btn-block btn-info">{!! trans('interface.edit') !!}</a>
                            </div>
                            <div class="col-md-3">
                                <a href="#" id="deleteFunc" class="delete btn btn-block btn-danger">{!! trans('interface.destroy') !!}</a>
                            </div>
                        </div>


                    </div>


                </div>
            </div>
        </div>
    </div>

    <form id="form_delete_func" action="{!! route('func.destroy',['id' => $func->id]) !!}" method="POST">
        {!! csrf_field() !!}
        {!! method_field("DELETE") !!}
    </form>
@endsection


@section('javascript')
    <script>
        $(function(){
            $("#deleteFunc").click(function() {
                $('#form_delete_func').submit();
            });


        })
    </script>
@endsection

