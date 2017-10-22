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
            <h3>{{ trans('interface.report_individual') }}</h3>
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
</table><?php
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
        <th>{{ trans('interface.total_avg') }}</th>
    </tr>
    <?php

    $evalId = $evaluation->id;

    $types = DB::select("
        SELECT   # СРЕДНЯЯ ПО КОМПЕТЕНЦИЯМ ВСЕХ ОЦЕНЩИКОВ
          t.id,
          t.note,
          t.level as average,
          els.level
        FROM
          (SELECT
             t.id,
             t.note,
             avg(els.level) as level,
             t.level as average
           FROM
             ( SELECT
                 ct.id,
                 ct.note,
                 avg(el.level) AS level
               FROM evaluations evn
                 LEFT JOIN evaluaters evr ON evn.id = evr.evaluation_id
                 LEFT JOIN eval_processes ep ON evr.id = ep.evaluater_id
                 LEFT JOIN eval_levels el ON el.id = ep.eval_level_id
                 LEFT JOIN indicators i ON i.id = ep.indicator_id
                 LEFT JOIN competences c ON c.id = i.competence_id
                 LEFT JOIN competence_types ct ON ct.id = c.competence_type_id
               WHERE evn.id = $evaluation->id
               GROUP BY evr.id,competence_id
               ORDER BY competence_type_id, competence_id)
             t, eval_levels els
           WHERE (t.level >= els.min AND t.level < els.max)
                 OR (t.level = 5 AND els.max = t.level)
           GROUP BY t.id
          ) t, eval_levels els
        WHERE (t.level >= els.min AND t.level < els.max)
              OR (t.level = 5 AND els.max = t.level)");

    $typeId = 0;
    ?>

    @foreach($types as $type)

        <tr>
            <th></th>
            <th>{{ $type->note }}</th>
            <td align="center">
                {{ $type->level }}
                ({{ round($type->average,2) }})
            </td>
        </tr>

        <?php
        $comps = DB::select("
            SELECT
             t.id,
             t.name,
             els.level,
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
               WHERE evn.id = $evaluation->id
               AND competence_type_id = $type->id
               GROUP BY evr.id,competence_id
               ORDER BY competence_type_id, competence_id
             ) t, eval_levels els
           WHERE (t.level >= els.min AND t.level < els.max)
                 OR (t.level = 5 AND els.max = t.level)
           GROUP BY t.id
        ");
        ?>

        @foreach($comps as $competence)

            <?php
            $type = \App\Competence::find($competence->id)->type;
            ?>
            @if($type->id != $typeId)
                <?php
                $i = 0;
                $typeId = $type->id;
                $typeLevel = 0;
                ?>

            @endif

            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $competence->name }}</td>

                <td align="center">
                    {{ $competence->level }}
                    ({{ round($competence->average,2) }})
                </td>
            </tr>
        @endforeach
    @endforeach
    <tr>
        <th></th>
        <th>{{ trans('interface.total_role') }}</th>
        <td align="center">
            <?php
            $results = DB::select("
                SELECT   # СРЕДНЯЯ ПО КОМПЕТЕНЦИЯМ ВСЕХ ОЦЕНЩИКОВ
                  t.level as average,
                  els.level
                FROM
                  (SELECT
                     avg(els.level) as level,
                     t.level as average
                   FROM
                     ( SELECT
                         avg(el.level) AS level
                       FROM evaluations evn
                         LEFT JOIN evaluaters evr ON evn.id = evr.evaluation_id
                         LEFT JOIN eval_processes ep ON evr.id = ep.evaluater_id
                         LEFT JOIN eval_levels el ON el.id = ep.eval_level_id
                         LEFT JOIN indicators i ON i.id = ep.indicator_id
                         LEFT JOIN competences c ON c.id = i.competence_id
                         LEFT JOIN competence_types ct ON ct.id = c.competence_type_id
                       WHERE evn.id = $evaluation->id #AND competence_type_id = 1
                       GROUP BY evr.id,competence_id
                       ORDER BY competence_type_id, competence_id)
                     t, eval_levels els
                   WHERE (t.level >= els.min AND t.level < els.max)
                         OR (t.level = 5 AND els.max = t.level)
                   #GROUP BY t.id
                  ) t, eval_levels els
                WHERE (t.level >= els.min AND t.level < els.max)
                      OR (t.level = 5 AND els.max = t.level)");
            ?>
            @foreach($results as $result)
                {{ $result->level }}
                ({{ round($result->average,2) }})
            @endforeach
        </td>
    </tr>
</table>
</body>
</html>
