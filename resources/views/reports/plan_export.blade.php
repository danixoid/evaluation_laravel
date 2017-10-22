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

    </tr>
    <tr>
        <td></td>
        <td colspan="4">
            <h3>{{ trans('interface.report_plan') }}</h3>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <h4>{{ trans('interface.user') }}: {{ $evaluation->evaluated->name }}</h4>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <h4>{{ trans('interface.period') }}: {{ date('d.m.Y',strtotime($evaluation->started_at)) }} &mdash;
                {{ date('d.m.Y',strtotime($evaluation->finished_at)) }}</h4>
        </td>
    </tr>
</table>

<table id="report" class="table table-bordered">

    <tr>
        <th>№</th>
        <th>{{ trans('interface.competence') }}</th>
        <th>{{ trans('interface.subject_to_learn') }}</th>
    </tr>
    <?php

    $evalId = $evaluation->id;
    $comps = \App\Competence::whereHas('indicators',function ($q) use ($evalId) {
            return $q->whereHas('processes',function ($q) use ($evalId)  {
                return $q->whereHas('evaluater',function ($q) use ($evalId)  {
                    return $q->whereEvaluationId($evalId);
                });
            });
        })
        ->orderBy('competence_type_id')
        ->orderBy('id')
        ->get();

    $typeId = 0;
    ?>

    @foreach($comps as $competence)


        @if($competence->competence_type_id != $typeId)
            <?php
            $i = 0;
            $typeId = $competence->competence_type_id;
            $typeLevel = 0;
            ?>
            <tr>
                <th></th>
                <th>{{ $competence->type->note }}</th>
                <td align="center">

                </td>
            </tr>
        @endif
            <?php

            $profile = \App\CompetenceProfile::whereOrgId($evaluation->org_id)
                ->wherePositionId($evaluation->position_id)
                ->whereFuncId($evaluation->func_id)
                ->whereCompetenceId($competence->id)
                ->first();

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
                @if($result && $profile && $result->level < $profile->level->level)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $competence->name }}</td>

                        <td align="center">
{{--                            {{ $result->level }} {{ $profile->level->level }}--}}
                        </td>
                    </tr>
                @endif
            @endforeach
    @endforeach

</table>
</body>
</html>
