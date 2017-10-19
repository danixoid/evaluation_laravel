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
        <td></td>
        <td></td>
        <td colspan="4">
            <h3>{{ trans('interface.report_results') }}</h3>
        </td>
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
            <td align="center">
                <?php
                $results = DB::select("
                    SELECT   # СРЕДНЯЯ ПО КОМПЕТЕНЦИИ ВСЕХ ОЦЕНЩИКОВ
                      t.id,
                      t.name,
                      t.level as average,els.level
                      FROM
                        (SELECT
                          t.id,
                          t.name,
                          avg(els.level) as level,
                          t.level as average
                          FROM
                          ( SELECT
                              c.id,
                              c.name,
                              avg(el.level) AS level
                            FROM evaluations evn
                              LEFT JOIN evaluaters evr ON evn.id = evr.evaluation_id
                              LEFT JOIN eval_processes ep ON evr.id = ep.evaluater_id
                              LEFT JOIN eval_levels el ON el.id = ep.eval_level_id
                              LEFT JOIN indicators i ON i.id = ep.indicator_id
                              LEFT JOIN competences c ON c.id = i.competence_id
                              LEFT JOIN competence_types ct ON ct.id = c.competence_type_id
                            WHERE evn.id = $evaluation->id AND competence_id = $competence->id
                            GROUP BY evr.id ) t, eval_levels els
                        WHERE (t.level >= els.min AND t.level < els.max)
                         OR (t.level = 5 AND els.max = t.level)
                        ) t, eval_levels els
                      WHERE (t.level >= els.min AND t.level < els.max)
                      OR (t.level = 5 AND els.max = t.level);");
                ?>
                @foreach($results as $result)
                        <?php
                        array_push($totalAverage,$result->level);

                        ?>
                    {{ $result->level }}
                    ({{ round($result->average,2) }})
                @endforeach
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
