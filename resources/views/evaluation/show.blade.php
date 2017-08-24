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
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <label class="col-md-10">{{ $evaluation->evaluated->name }} | {{ $evaluation->type->name }}</label>

                            @if(!$evaluation->started)
                            <div class="col-md-2 text-right">
                                <a href="#" onclick="$('#form_start_evaluation').submit();">{!! trans('interface.start') !!}</a>
                            </div>
                            @endif

                            @foreach($evaluation->evaluaters as $evaluater)
                                <form id="form_start_evaluation" action="{!! route('evaluation.update',['id' => $evaluation->id]) !!}" method="POST">
                                    {!! csrf_field() !!}
                                    {!! method_field("PUT") !!}
                                </form>
                            @endforeach
                        </div>
                    </div>

                    <div class="panel-body">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            @if (Auth::user()->hasAnyRole(['admin','manager']))
                            <li role="presentation" @if(!$evaluation->started) class="active" @endif><a href="#evaluaters" aria-controls="evaluaters" role="tab" data-toggle="tab">{{ trans('interface.evaluaters') }}</a></li>
                            @endif
                            <li role="presentation" @if($evaluation->started) class="active" @endif><a href="#competences" aria-controls="competences" role="tab" data-toggle="tab">{{ trans('interface.competences') }}</a></li>

                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            @if (Auth::user()->hasAnyRole(['admin','manager']))
                            <div role="tabpanel" class="tab-pane fade @if(!$evaluation->started) in active @endif" id="evaluaters">

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
                                                        <td>{{ $evaluater->role->name }}</td>
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
                                        <div class=" col-md-3">
                                            <a href="{!! route('evaluation.index') !!}" class="btn btn-block btn-primary">{!! trans('interface.prev') !!}</a>
                                        </div>

                                    </div>
                                    @endif


                                </div>
                            </div>

                            @endif

                            <div role="tabpanel" class="tab-pane fade @if($evaluation->started) in active @endif" id="competences">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('interface.level') }}</th>
                                        <th>{{ trans('interface.minimum') }}</th>
                                        <th>{{ trans('interface.maximum') }}</th>
                                        <th>{{ trans('interface.title') }}</th>
                                        <th>{{ trans('interface.notes') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(\App\EvalLevel::all() as $level)
                                    <tr>
                                        <td>{{ $level->level }}</td>
                                        <td>> {{ $level->min }}</td>
                                        <td>{{ $level->max }}</td>
                                        <td>{{ $level->name }}</td>
                                        <td>{{ $level->note }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>


                                <form id="form_add_competence" class="form-horizontal"
                                      action="{!! route('evalprocess.store') !!}" method="POST">
                                    {!! csrf_field() !!}
                                    <table class="table table-bordered">
                                        @foreach(\App\CompetenceType::all() as $type)
                                        <tr style="background-color: #1d5020;color:white;">
                                            <th colspan="10">
                                                {{ $type->note }}
                                                ({{ $evaluation->competences()->whereCompetenceTypeId($type->id)->count() }})
                                            </th>
                                        </tr>
                                            @foreach($evaluation->competences()->whereCompetenceTypeId($type->id)->get() as $competence)
                                                <tr style="background-color: #2aabd2;color:white;">
                                                    <th>{{ trans('interface.level') }}</th>
                                                    <th>{{ $competence->name }}</th>

                                                    @foreach($evaluation->evaluaters as $evaluater)
                                                        @if(\Auth::user()->hasAnyRole(['admin','manager']) ||
                                                            $evaluater->user_id == \Auth::user()->id ||
                                                            ($evaluation
                                                                ->evaluaters()
                                                                ->whereUserId(\Auth::user()->id)
                                                                ->whereEvalRoleId(\App\EvalRole::whereCode('total')->first()->id)
                                                                ->count() > 0))
                                                        <td align="center">
                                                            {{ $evaluater->role->name }}
                                                        </td>
                                                        @endif
                                                    @endforeach

                                                </tr>
                                                    @foreach($competence->indicators as $indicator)
                                                    <tr id="tr_{{ $competence->id }}_{{$indicator->level->id}}" onclick="chxInd({{ $competence->id }},{{$indicator->level->id}})">
                                                        <td align="right">{{ $indicator->level->level }}</td>
                                                        <td>{{ $indicator->name }}</td>
                                                        @foreach($evaluation->evaluaters as $evaluater)
                                                            @if(\Auth::user()->hasAnyRole(['admin','manager']) ||
                                                            $evaluater->user_id == \Auth::user()->id ||
                                                            ($evaluation
                                                                ->evaluaters()
                                                                ->whereUserId(\Auth::user()->id)
                                                                ->whereEvalRoleId(\App\EvalRole::whereCode('total')->first()->id)
                                                                ->count() > 0))
                                                            @if($evaluater->processes()->count() > 0)
                                                                @if($evaluater->processes()
                                                                    ->whereEvalLevelId($indicator->level->id)
                                                                    ->whereCompetenceId($competence->id)
                                                                    ->count() > 0)
                                                                    <td align="center" style="background-color: #2f8034;color:white;">
                                                                    {{--<span class="glyphicon glyphicon-check"></span>--}}
                                                                        {{$indicator->level->level}}
                                                                    </td>
                                                                @else
                                                                    <td align="center">
                                                                    {{--<span class="glyphicon glyphicon-remove"></span>--}}
                                                                    </td>
                                                                @endif
                                                            @else
                                                                @if($evaluater->user_id == \Auth::user()->id)
                                                                <td align="center">
                                                                    <input type="hidden" name="process[{{$competence->id}}][evaluater_id]" value="{{ $evaluater->id }}">
                                                                    <input type="hidden" name="process[{{$competence->id}}][competence_id]" value="{{ $competence->id }}">
                                                                    <input type="radio" name="process[{{$competence->id}}][eval_level_id]" required
                                                                           id="ind_{{$competence->id}}_{{$indicator->level->id}}" value="{{ $indicator->level->id }}" /></td>
                                                                @else
                                                                <td align="center" style="background-color: #000000;color:white;">
                                                                    {{--<span class="glyphicon glyphicon-adjust"></span>--}}
                                                                </td>
                                                                @endif
                                                            @endif
                                                            @endif

                                                        @endforeach
                                                    </tr>
                                                    @endforeach
                                            @endforeach
                                    @endforeach
                                    </table>

                                    @if($evaluation->started)
                                        <div class="form-group">
                                            <div class="col-md-offset-3 col-md-3">
                                                <button class="btn btn-block btn-danger" >{!! trans('interface.next') !!}</button>
                                            </div>
                                            <div class=" col-md-3">
                                                <a href="{!! route('evaluation.index') !!}" class="btn btn-block btn-primary">{!! trans('interface.prev') !!}</a>
                                            </div>

                                        </div>
                                    @endif

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>

        @if($evaluation->evaluaters()->whereUserId(\Auth::user()->id)->count() > 0)
        function chxInd(comp,ind)
        {
            $('[id^=tr_' + comp + ']').css('background-color','white');
            $('#tr_' + comp + '_' + ind).css('background-color','#888888');
            $('[id^=ind_' + comp + ']').removeAttr('checked');
            $('#ind_' + comp + '_' + ind).attr('checked','checked');
        }
        @endif

    </script>
@endsection
