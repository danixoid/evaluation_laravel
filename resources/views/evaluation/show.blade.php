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
                            <label class="col-md-8">{{ $evaluation->evaluated->name }} | {{ $evaluation->type->name }}</label>
                            <div class="col-md-4 text-right">
                                @if($evaluation->started)
                                    <a href="{!! route('evaluation.show',
                                        ['id'=>$evaluation->id,'type' => 'pdf']) !!}">{!! trans('interface.print_to_pdf') !!}</a> |
                                @endif
                                @if(!$evaluation->started)
                                    <a href="{!! route('evaluation.edit',['id'=>$evaluation->id]) !!}">{!! trans('interface.edit') !!}</a> |
                                @endif
                                <a href="{!! route('evaluation.index') !!}" >{!! trans('interface.prev') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation"  class="active"><a href="#evaluaters" aria-controls="evaluaters" role="tab" data-toggle="tab">{{ trans('interface.evaluaters') }}</a></li>
                            @if($evaluation->started)
                                <li role="presentation"><a href="#reports" aria-controls="reports" role="tab" data-toggle="tab">{{ trans('interface.reports') }}</a></li>
                            @endif
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

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.started_date') !!}</label>
                                        <div class="col-md-3 form-control-static">
                                            {{ date("d.m.Y H:i",strtotime($evaluation->started_at)) }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">{!! trans('interface.finished_date') !!}</label>
                                        <div class="col-md-3 form-control-static">
                                            {{ date("d.m.Y H:i",strtotime($evaluation->finished_at)) }}
                                            <a href="#finished_at" data-toggle="collapse">[{{ trans('interface.edit') }}]</a>
                                        </div>
                                    </div>

                                    <form action="{!! route('evaluation.update',$evaluation->id) !!}"
                                          method="POST" id="finished_at"  class="collapse">
                                        {!! csrf_field() !!}
                                        {!! method_field('PUT') !!}
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">{!! trans('interface.finished_date') !!}</label>
                                            <div class="col-md-4">
                                                <div class='input-group date'>
                                                    <input type="text" class="form-control" value="{!! $evaluation->finished_at !!}"
                                                           name="finished_at" id="finished_at" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-danger">{{ trans('interface.edit') }}</button>
                                            </div>
                                        </div>
                                    </form>


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
                                                    @if($evaluation->started)
                                                        <th>{{ trans('interface.status') }}</th>
                                                    @endif
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($evaluation->evaluaters()->orderBy('eval_role_id')->get() as $evaluater)
                                                    <tr>
                                                        <td>{{ $evaluater->user->name }}</td>
                                                        <td>{{ $evaluater->role->name }}</td>
                                                        @if($evaluation->started)
                                                            <td><a href="{!! route('evaluater.show',['id' => $evaluater->id]) !!}">
                                                                <span class="text-{{ $evaluater->finished ? 'success' : 'danger'}}">
                                                                {{ $evaluater->role->name }}
                                                                    [{{ $evaluater->finished
                                                                    ? trans('interface.finished')
                                                                    : trans('interface.not_finished') }}]
                                                                </span>
                                                                </a>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>



                                </div>
                            </div>

                            @if($evaluation->started)
                                <div role="tabpanel" class="tab-pane fade" id="reports">
                                        @include("evaluation.reports")
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {

            /**
             * DateTimePicker
             * */
            $(function () {
                $('.date').datetimepicker({
                    locale: 'ru',
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
            });

//            $('#finished_at').on('submit', function(ev) {
//                ev.preventDefault();
//
//                $('#finished_at').collapse('hide');
//            });
        });
    </script>
@endsection

