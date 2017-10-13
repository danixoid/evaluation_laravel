<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')

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
            <?php
                $processes = $me
                    ->processes()
                    ->select('eval_processes.*')
//                    ->with('level')
//                    ->with('indicator')
//                    ->with('indicator.competence')
//                    ->with('indicator.competence.type')
                    ->leftJoin('eval_levels','eval_levels.id','=','eval_level_id')
                    ->leftJoin('indicators','indicators.id','=','indicator_id')
                    ->leftJoin('competences','competences.id','=','competence_id')
                    ->leftJoin('competence_types','competence_types.id','=','competence_type_id')
                    ->orderBy('competence_type_id')
                    ->orderBy('competence_id')
                    ->orderBy('indicator_id')
                    ->get();
                $type_id = 0;
                $competence_id = 0;
                $levels = \App\EvalLevel::all();
                $evaluaters = $evaluation->evaluaters()->orderBy('eval_role_id')->get();
            ?>
                @foreach($processes as $process)
                    @if($process->indicator->competence->type->id !== $type_id)
                        <?php
                        $type_id = $process->indicator->competence->type->id;
                        ?>
                        <tr style="background-color: #1d5020;color:white;">
                            <th colspan="50">
                                {{ $process->indicator->competence->type->note }}
                            </th>
                        </tr>
                    @endif

                    @if($process->indicator->competence->id !== $competence_id)
                        <?php
                        $competence_id = $process->indicator->competence->id;
                        ?>
                        <tr style="background-color: #2aabd2;color:white;">
                            <th>{{ $process->indicator->competence->name }}</th>

                            @foreach($evaluaters as $evaluater)
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
                    @endif

                    <tr @if($process->level) style="background-color: #DDDDFF" @endif>
                        <td>{{ $process->indicator->name }}</td>
                        @foreach($evaluaters as $evaluater)
                            <?php
                            $_process = $evaluater
                                ->processes()
                                ->whereIndicatorId($process->indicator_id)
                                ->first()
                            ?>

                            @if($me->user->hasAnyRole(['admin']) ||
                                $evaluater->id == $me->id ||
                                ($me->role->code == 'total'))

                                @if($evaluater->finished)
                                    <td align="center" style="background-color: #2f8034;color:white;">
                                        {{--<span class="glyphicon glyphicon-check"></span>--}}
                                        {{ $_process->level->level }}
                                    </td>
                                @else
                                    @if($evaluater->id == $me->id &&
                                        ($evaluater->role->code != 'total' || $evaluation->is_total))
                                        <td align="center">
                                            <input type="hidden" name="process[{{ $process->id }}][evaluater_id]"
                                                   value="{{ $me->id }}"/>
                                            <input type="hidden" name="process[{{ $process->id }}][indicator_id]"
                                                   value="{{ $process->indicator->id }}"/>
                                            <select name="process[{{ $process->id }}][eval_level_id]"
                                                    class="form-control selectLevel">
                                                <option value="-1">X</option>
                                                @foreach($levels as $level)
                                                    <option @if($process->eval_level_id == $level->id) selected @endif
                                                        value="{{ $level->id }}">{{ $level->level }}</option>
                                                @endforeach
                                            </select>
                                        </td>
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
            </table>

            @if($evaluation->started && !$me->finished)
                <div class="form-group">
                    <div class="col-md-3 pull-right">
                        <button type="submit"
                                class="btn btn-block btn-success" >{!! trans('interface.next') !!}</button>
                    </div>
                </div>
            @endif

        </form>
    </div>
@endsection

@section('javascript')
    <script>
        $(function() {
            $('.selectLevel').on('change',function(ev) {
                if($(this).val() > 0) {
                    $(this).parent().parent().css('background-color','#DDDDFF');
                } else {
                    $(this).parent().parent().css('background-color','#FFFFFF');
                }
            });
        });

    </script>
@endsection
