<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 19.10.17
 * Time: 12:18
 */?><!DOCTYPE html>
<html>
<header>
<link href="{{ asset('css/_reports.css') }}" rel="stylesheet">
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
        <td colspan="6">
            <h4>{{ trans('interface.org') }}: @if(request('org_id')){{ \App\Org::find(request('org_id'))->name }}@else {{ trans('interface.all') }}@endif</h4>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <h4>{{ trans('interface.func') }}: @if(request('func_id')){{ \App\Func::find(request('func_id'))->name }}@else {{ trans('interface.all') }}@endif</h4>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <h4>{{ trans('interface.position') }}: @if(request('position_id')){{ \App\Position::find(request('position_id'))->name }}@else {{ trans('interface.all') }}@endif</h4>
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
<table id="report" class="table">
    <tr>
        <th></th>
        <th></th>
        <td align="center" colspan="{{ count($competences) }}">
            <strong>{{ trans('interface.competence_level') }}</strong>
        </td>
        <th></th>
    </tr>
    <tr>
        <th>№</th>
        <th>{{ trans('interface.name') }}</th>
        @foreach($competences as $competence)
            <th>{{ $competence->name }}</th>
        @endforeach<th>{{ trans('interface.total_avg') }}</th>
    </tr>
    <?php $i = 0; ?>
    @foreach($evaluations as $evaluation)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $evaluation->evaluated->name }}</td>
        <?php $totalAverage = []; ?>
        @foreach($competences as $competence)
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
            array_push($totalAverage,$level->level);
            ?>

            {{ $level ? $level->level : "" }}
            ({{ round($average,2) }})
            @endif
        </td>
        @endforeach
        <td align="center">
            <?php
            $average = array_sum($totalAverage)/count($totalAverage);
            ?>
            @if ($average > 0)
            <?php
            $level = \App\EvalLevel::where('min','<',$average)
                ->where('max','>=',$average)
                ->first();
            array_push($totalAverage,$level);
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
