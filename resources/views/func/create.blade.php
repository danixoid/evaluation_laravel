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
                    <div class="panel-heading">{!! trans('interface.funcs') !!} | {!! trans('interface.create') !!}</div>

                    <div class="panel-body">

                        <form id="form_create_func" class="form-horizontal" action="{!! route('func.store') !!}" method="POST">
                            {!! csrf_field() !!}


                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.title') !!}</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="name" value="{!! old('name') !!}">
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