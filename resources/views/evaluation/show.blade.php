<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')

    <?php $user_id = request('user_id') ?: '0'; ?>
    <?php $chief_id = request('chief_id') ?: '0'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <label class="col-md-10">{{ $evaluation->evaluated->name }} | {{ $evaluation->type->name }}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('evaluation.index') !!}" >{!! trans('interface.prev') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation"  class="active"><a href="#evaluaters" aria-controls="evaluaters" role="tab" data-toggle="tab">{{ trans('interface.evaluaters') }}</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="evaluaters">

                                <div class="form-horizontal" >
                                    {!! csrf_field() !!}
                                    {{--                            {!! method_field("PUT")  !!}--}}
                                    {{--


                                                                        <div class="form-group">
                                                                            <div class="col-md-offset-3 col-md-9 form-control-static">
                                                                                <h3>{!! trans('interface.evaluaters') !!}</h3></div>
                                                                        </div>
                                    --}}

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.org') !!}</label>
                                        <div class="col-md-9 form-control-static">
                                            {{ $evaluation->org->name }}
                                        </div>
                                    </div>

                                    @if($evaluation->func)
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">{!! trans('interface.func') !!}</label>
                                            <div class="col-md-9 form-control-static">
                                                {{ $evaluation->func->name }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                        <div class="col-md-9 form-control-static">
                                            {{ $evaluation->position->name }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-9 table-responsive">
                                            <table id="struct_table" class="table table-condensed">
                                                <thead>
                                                <tr>
                                                    <th>{{ trans('interface.user') }}</th>
                                                    <th>{{ trans('interface.role') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($evaluation->evaluaters()->orderBy('eval_role_id')->get() as $evaluater)
                                                    <tr>
                                                        <td>{{ $evaluater->user->name }}</td>
                                                        @if($evaluation->started)
                                                            <td><a href="{!! route('evaluater.show',$evaluater->id) !!}">{{ $evaluater->role->name }}</a></td>
                                                        @else
                                                        <td>{{ $evaluater->role->name }}</td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    @if(!$evaluation->started)
                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-3">
                                            <a href="{!! route('evaluation.edit',['id'=>$evaluation->id]) !!}" class="btn btn-block btn-danger" >{!! trans('interface.edit') !!}</a>
                                        </div>
                                    </div>
                                    @endif


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
