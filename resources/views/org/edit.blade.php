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
                    <div class="panel-heading">{!! trans('interface.orgs') !!} | {!! trans('interface.edit') !!}</div>

                    <div class="panel-body">

                        <form id="form_create_org" class="form-horizontal" action="{!! route('org.update',['id' => $org->id]) !!}" method="POST">
                            {!! csrf_field() !!}
                            {!! method_field("PUT")  !!}

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.title') !!}</label>
                                <div class="col-md-9">
                                    <input class="form-control" name="name" value="{!! $org->name !!}" />
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
@endsection
