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

        @foreach($evaluation->evaluaters as $evaluater)
            <th>{{ $evaluater->role->name }}</th>
        @endforeach

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
            @foreach($evaluation->evaluaters as $evaluater)
                <td align="center"><?php
                    $evr = DB::select("
                    SELECT
                      els.min,
                      els.max,
                      els.level,
                      avg(t.level) AS average
                      FROM (
                        SELECT
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
                        WHERE competence_type_id = $type->id
                        AND evr.id = $evaluater->id
                        GROUP BY evr.id, c.id, ct.id, els.id
                        HAVING (average >= els.min AND average < els.max)
                               OR (average = 5 AND els.max = average)
                           ) t, eval_levels els
                      GROUP BY t.type_id, els.id
                      HAVING (average >= els.min AND average < els.max)
                             OR (average = 5 AND els.max = average)
                    ");
                    ?>
                    @foreach($evr as $ev)
                        {{ $ev->level }}
                        ({{ round($ev->average,2) }})
                    @endforeach
                </td>
            @endforeach
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
                @foreach($evaluation->evaluaters as $evaluater)
                    <td align="center">
                        <?php
                            $evr = DB::select("
                                SELECT
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
                                WHERE c.id = ?
                                AND evr.id = ?
                                GROUP BY c.id, ct.id, els.id
                                HAVING (average >= els.min AND average < els.max)
                                       OR (average = 5 AND els.max = average)
                                ",[$competence->id,$evaluater->id]);
                        ?>
                        @foreach($evr as $ev)
                            {{ $ev->level }}
                            ({{ round($ev->average,2) }})
                        @endforeach
                    </td>
                @endforeach

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
        @foreach($evaluation->evaluaters as $evaluater)
            <td align="center"><?php
                $evr = DB::select("
                    SELECT
                      els.min,
                      els.max,
                      els.level,
                      avg(t.level) AS average
                      FROM (
                        SELECT
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
                        WHERE evr.id = $evaluater->id
                        GROUP BY evr.id, c.id, ct.id, els.id
                        HAVING (average >= els.min AND average < els.max)
                               OR (average = 5 AND els.max = average)
                           ) t, eval_levels els
                      HAVING (average >= els.min AND average < els.max)
                             OR (average = 5 AND els.max = average)
                    ");
                ?>
                @foreach($evr as $ev)
                    {{ $ev->level }}
                    ({{ round($ev->average,2) }})
                @endforeach
            </td>
        @endforeach

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