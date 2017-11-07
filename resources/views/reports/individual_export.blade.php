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
</table>

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

        <th>{{ trans('interface.total_avg') }}</th>
    </tr>
    <?php

    $evalId = $evaluation->id;

    $types = DB::select("
        SELECT
          t.id,
          t.note,
          els.min,
          els.max,
          els.level,
          avg(t.level) AS average
        FROM (
           SELECT
             ct.id,
             ct.note,
             els.min,
             els.max,
             els.level,
             avg(el.level) AS average
           FROM evaluations evn
             LEFT JOIN evaluaters evr ON evn.id = evr.evaluation_id
             LEFT JOIN eval_processes ep ON evr.id = ep.evaluater_id
             LEFT JOIN eval_levels el ON el.id = ep.eval_level_id
             LEFT JOIN indicators i ON i.id = ep.indicator_id
             LEFT JOIN competences c ON c.id = i.competence_id
             LEFT JOIN competence_types ct ON ct.id = c.competence_type_id
             , eval_levels els
           WHERE evn.id = $evaluation->id
           GROUP BY c.id, ct.id, els.id
           HAVING (average >= els.min AND average < els.max)
                  OR (average = 5 AND els.max = average)
         ) t, eval_levels els
            GROUP BY t.id,els.id
            HAVING (average >= els.min AND average < els.max)
               OR (average = 5 AND els.max = average)
           ");

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
                  c.id,
                  c.name,
                  ct.id AS type_id,
                  els.min,
                  els.max,
                  els.level,
                  avg(el.level) AS average
                FROM evaluations evn
                  LEFT JOIN evaluaters evr ON evn.id = evr.evaluation_id
                  LEFT JOIN eval_processes ep ON evr.id = ep.evaluater_id
                  LEFT JOIN eval_levels el ON el.id = ep.eval_level_id
                  LEFT JOIN indicators i ON i.id = ep.indicator_id
                  LEFT JOIN competences c ON c.id = i.competence_id
                  LEFT JOIN competence_types ct ON ct.id = c.competence_type_id
                  , eval_levels els
                WHERE evn.id = $evaluation->id AND ct.id = $type->id
                GROUP BY c.id, ct.id, els.id
                HAVING (average >= els.min AND average < els.max)
                       OR (average = 5 AND els.max = average)
        ");
        $i = 0;
        ?>

        @foreach($comps as $competence)

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
                SELECT
                  els.min,
                  els.max,
                  els.level,
                  avg(t.level) AS average
                FROM
                  (SELECT
                     c.id,
                     c.name,
                     ct.id AS type_id,
                     els.min,
                     els.max,
                     els.level,
                     avg(el.level) AS average
                   FROM evaluations evn
                     LEFT JOIN evaluaters evr ON evn.id = evr.evaluation_id
                     LEFT JOIN eval_processes ep ON evr.id = ep.evaluater_id
                     LEFT JOIN eval_levels el ON el.id = ep.eval_level_id
                     LEFT JOIN indicators i ON i.id = ep.indicator_id
                     LEFT JOIN competences c ON c.id = i.competence_id
                     LEFT JOIN competence_types ct ON ct.id = c.competence_type_id
                     , eval_levels els
                   WHERE evn.id = $evaluation->id
                   GROUP BY c.id, ct.id, els.id
                   HAVING (average >= els.min AND average < els.max)
                          OR (average = 5 AND els.max = average)
                  ) t, eval_levels els
                  HAVING (average >= els.min AND average < els.max)
                         OR (average = 5 AND els.max = average)");
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
