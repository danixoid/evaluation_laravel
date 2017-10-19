<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 28.08.17
 * Time: 19:04
 */?>

<table id="report" class="table table-bordered">

    <tr>
        <th>№</th>
        <th>{{ trans('interface.competence') }}</th>
        @foreach($evaluation->evaluaters()->orderBy('eval_role_id')->get() as $evaluater)
            <th>
                {{ $evaluater->role->name }}
            </th>
        @endforeach
        <th>{{ trans('interface.total_avg') }}</th>
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
            @foreach($evaluation->evaluaters()->orderBy('eval_role_id')->get() as $evaluater)
                <td align="center">
                    <?php
                    $results = DB::select("
                        SELECT # СРЕДНЯЯ ПО ТИПУ КОМПЕТЕНЦИИ ОДНОГО ОЦЕНЩИКА
                          t.id,
                          t.name,
                          els.level,
                          t.level as average
                        FROM
                          ( SELECT
                              t.id,
                              t.name,
                              avg(els.level) as level
                            FROM
                              (
                                SELECT
                                  ct.id,
                                  ct.name,
                                  avg(el.level) AS level
                                FROM evaluations evn
                                  LEFT JOIN evaluaters evr ON evn.id = evr.evaluation_id
                                  LEFT JOIN eval_processes ep ON evr.id = ep.evaluater_id
                                  LEFT JOIN eval_levels el ON el.id = ep.eval_level_id
                                  LEFT JOIN indicators i ON i.id = ep.indicator_id
                                  LEFT JOIN competences c ON c.id = i.competence_id
                                  LEFT JOIN competence_types ct ON ct.id = c.competence_type_id
                                WHERE evn.id = $evaluation->id
                                  AND evr.id = $evaluater->id
                                  AND competence_type_id = $competence->competence_type_id
                                GROUP BY competence_id
                              ) t, eval_levels els
                            WHERE (t.level >= els.min AND t.level < els.max)
                                  OR (t.level = 5 AND els.max = t.level)
                            GROUP BY t.id) t, eval_levels els
                        WHERE (t.level >= els.min AND t.level < els.max)
                              OR (t.level = 5 AND els.max = t.level)");
                    ?>
                    @foreach($results as $result)
                        <?php $typeLevel += $result->level; ?>
                        {{ $result->level }}
                        ({{ round($result->average,2) }})
                    @endforeach
                </td>
            @endforeach
            <td align="center">
                <?php
                    $average = $typeLevel / $evaluation->evaluaters()->count();
                    $level = \App\EvalLevel::where(function($q) use ($average) {
                            return $q
                                ->where('min','<=',$average)
                                ->where('max','>',$average);
                            })
                        ->orWhere(function($q) use ($average) {
                            return $q->whereRaw($average . '= 5')
                                ->where('max','=',$average);
                            })
                        ->first();
                ?>
                {{ $level ? $level->level : "" }}
                ({{ $average }})
            </td>
        </tr>
        @endif

        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $competence->name }}</td>
            @foreach($evaluation->evaluaters()->orderBy('eval_role_id')->get() as $evaluater)
                <td align="center">
                    <?php
                    $results = DB::select("
                        SELECT # СРЕДНЯЯ ПО КОМПЕТЕНЦИИ ОДНОГО ОЦЕНЩИКА
                          t.id,
                          t.name,
                          els.level,
                          t.level as average
                        FROM
                          (
                            SELECT
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
                             WHERE evn.id = $evaluation->id
                                AND competence_id = $competence->id
                                AND evr.id = $evaluater->id
                        #     GROUP BY evr.id
                          ) t, eval_levels els
                        WHERE (t.level >= els.min AND t.level < els.max)
                         OR (t.level = 5 AND els.max = t.level)");
                    ?>
                    @foreach($results as $result)
                        {{ $result->level }}
                        ({{ round($result->average,2) }})
                    @endforeach
                </td>
            @endforeach

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
                    {{ $result->level }}
                    ({{ round($result->average,2) }})
                @endforeach
            </td>
        </tr>
    @endforeach

    {{--<tr>--}}
        {{--<th></th>--}}
        {{--<th>{{ trans('interface.total_role') }}</th>--}}
        {{--@foreach($evaluation->evaluaters()->orderBy('eval_role_id')->get() as $evaluater)--}}
            {{--<td align="center">--}}
                {{--TODO:--}}
            {{--</td>--}}
        {{--@endforeach--}}
        {{--<td align="center">--}}
            {{--TODO:--}}
        {{--</td>--}}
    {{--</tr>--}}
</table>