<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 19.10.17
 * Time: 12:18
 */?><!DOCTYPE html>
<html>
<header>
{{--<link href="{{ asset('css/_reports.css') }}" rel="stylesheet">--}}
</header>
<body>
<table>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="4">
            <h3>{{ trans('interface.report_individual') }}</h3>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <h4>{{ trans('interface.user') }}: @if(request('user_id')){{ \App\User::find(request('user_id'))->name }}@else {{ trans('interface.all') }}@endif</h4>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <h4>{{ trans('interface.period') }}: @if(request('begin_at')){{ date('d.m.Y',strtotime(request('begin_at'))) }} @endif &mdash;
                @if(request('end_at')){{ date('d.m.Y',strtotime(request('end_at'))) }} @endif</h4>
        </td>
    </tr>
</table>
<table id="report" class="table table-bordered">
    <?php $i = 0; $typeId = 0;?>
    @foreach($competences as $competence)
        @if($typeId != $competence->competence_type_id)
            <tr>
                <th colspan="3">{{ $competence->type->name }}</th>
                <?php
                $typeId = $competence->competence_type_id;
                $i = 0;
                ?>
            </tr>
            <tr>
                <th>№</th>
                <th>{{ trans('interface.competences') }}</th>
                <th>{{ trans('interface.level') }}</th>
            </tr>
        @endif
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $competence->name }}</td>
            <?php
            $average = \App\EvalProcess::
                whereIn('indicator_id',$competence->indicators()->pluck('id'))
                ->whereIn('evaluater_id',$evaluation->evaluaters()->pluck('id'))
                ->leftJoin('eval_levels','eval_level_id','eval_levels.id')
                ->avg('eval_levels.level');
            ?>
            <td align="center">
                @if ($average > 0)
                    <?php
                    $level = \App\EvalLevel::where('min','<',$average)
                        ->where('max','>=',$average)
                        ->first();
                    ?>

                    {{ $level ? $level->level : "" }}
                    ({{ round($average,2) }})
                @endif
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>
