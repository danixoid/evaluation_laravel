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
                            <th>
                                {{ $process->indicator->competence->type->note }}
                            </th>
                            <td colspan="5" align="center">
                                {{ trans('interface.level') }}
                            </td>
                        </tr>
                    @endif

                    @if($process->indicator->competence->id !== $competence_id)
                        <?php
                        $competence_id = $process->indicator->competence->id;
                        ?>
                        <tr style="background-color: #2aabd2;color:white;">
                            <th>{{ $process->indicator->competence->name }}</th>

                            @foreach(\App\EvalLevel::orderBy('level')->get() as $level)
                            <td align="center">
                                {{ $level->level }}
                            </td>
                            @endforeach
                        </tr>
                    @endif

                    <tr @if($process->level) style="background-color: #DDDDFF" @endif>
                        <td>
                            {{ $process->indicator->name }}
                        </td>

                        @foreach(\App\EvalLevel::orderBy('level')->get() as $level)

                                @if($me->finished)
                                    @if($process->eval_level_id == null)
                                        <td align="center" style="background-color: #000000;color:white;"></td>
                                    @elseif($process->eval_level_id == $level->id)
                                        <td align="center" style="background-color: #2f8034;color:white;">
                                        {{--<span class="glyphicon glyphicon-check"></span>--}}
                                            {{ $process->level->level }}
                                        </td>
                                    @else
                                        <td align="center"></td>
                                    @endif
                                @else
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" class="selectLevel"
                                                   @if($process->eval_level_id == $level->id) checked @endif
                                                   value="{{ $level->id }}"
                                                   name="process[{{ $process->id }}][eval_level_id]"/></label>
                                        </div>
                                    </td>
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
                var $this = $(this);

                var process_id = $(this).attr('name').replace(/process\[(\d+)\]\[.+\]/gi,"$1");
                if($this.val() > 0) {


                    $.ajax({
                        type: "POST",
                        url: "{!! route('evalprocess.store') !!}",
                        data: JSON.parse('{ ' +
                            '\"_token\" : ' +  '"{{ csrf_token() }}", ' +
                            '\"process\" : { \"' +
                                process_id + '\": {' +
                                    '\"eval_level_id\" : ' + $this.val() +
                            ' } } }'),
                        dataType: "json",
                        success: function(msg){
                            if(msg.success) {
                                $this.prop('checked','checked');
                                $this.parent().parent().css('background-color','#DDDDFF');
                                console.log(msg.message);
                            } else {
                                $this.removeProp('checked');
                                console.log(msg.message);
                            }
                        },
                        error: function(err) {
                            $this.removeProp('checked');
                            console.log(JSON.stringify(err));
                        }
                    });
                    console.log('1');
                } else {
                    $this.parent().parent().css('background-color','#FFFFFF');
                }
            });

        });

    </script>
@endsection
