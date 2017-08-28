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
        <div class="alert alert-success">
            <p>{{ $evaluation->evaluated->name }} | {{ $evaluation->type->name }}</p>
            <p>{{ $evaluation->position->name }} | {{ $evaluation->org->name }} |
            {{ $evaluation->func ? $evaluation->func->name : ""}}</p>
            <p>{{ $me->role->name }} | {{ $me->user->name }}</p>
        </div>


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
                    <td>&gt; {{ $level->min }}</td>
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
                    </th>
                </tr>
                <?php
                    $competences = \App\Competence::whereCompetenceTypeId($type->id)
                        ->whereHas('processes',function($q) use ($me)
                        {
                            return $q->whereEvaluaterId($me->id);
                        })
//                        ->groupBy('competences.id')
                        ->get();
                ?>

                    @foreach($competences as $competence)
                        <tr style="background-color: #2aabd2;color:white;">
                            <th>{{ trans('interface.level') }}</th>
                            <th>{{ $competence->name }}</th>

                            @foreach($evaluation->evaluaters as $evaluater)
                                @if($me->user->hasAnyRole(['admin']) ||
                                    $evaluater->id == $me->id ||
                                    ($me->role->code == 'total'))
                                <td align="center">
                                    {{ $evaluater->role->name }}
                                    @if(\Auth::user()->hasAnyRole(['admin']))
                                        {{ $evaluater->user->name }}
                                    @endif
                                </td>
                                @endif
                            @endforeach

                        </tr>
                            @foreach($competence->indicators as $indicator)
                            <tr id="tr_{{ $competence->id }}_{{$indicator->level->id}}" onclick="chxInd({{ $competence->id }},{{$indicator->level->id}})">
                                <td align="right">{{ $indicator->level->level }}</td>
                                <td>{{ $indicator->name }}</td>
                                @foreach($evaluation->evaluaters as $evaluater)
                                    @if($me->user->hasAnyRole(['admin']) ||
                                        $evaluater->id == $me->id ||
                                        ($me->role->code == 'total'))
                                    <?php
                                        $process = $evaluater->processes()
                                            ->whereCompetenceId($competence->id)
                                            ->first()
                                        ?>
                                    @if($process->level)
                                        @if($process->level->id == $indicator->level->id)
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
                                        @if($evaluater->id == $me->id &&
                                            ($evaluater->role->code != 'total' || $evaluation->is_total))
                                        <td align="center">
                                            <input type="hidden" name="process[{{$process->id}}][evaluater_id]" value="{{ $evaluater->id }}">
                                            <input type="hidden" name="process[{{$process->id}}][competence_id]" value="{{ $competence->id }}">
                                            <input type="radio" name="process[{{$process->id}}][eval_level_id]" required
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

            @if($evaluation->started && !$me->finished)
                <div class="form-group">
                    <div class="col-md-3 pull-right">
                        <button class="btn btn-block btn-success" >{!! trans('interface.next') !!}</button>
                    </div>
                </div>
            @endif

        </form>
    </div>
@endsection

@section('javascript')
    <script>

        @if(!$me->finished)
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
