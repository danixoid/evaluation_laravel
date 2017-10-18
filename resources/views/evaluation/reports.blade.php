<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 28.08.17
 * Time: 19:04
 */?>

<?php
    $i = 1;

    $_processes = array_unique(\App\EvalProcess::whereHas('evaluater',function($q) use ($evaluation) {
            return $q->whereEvaluationId($evaluation->id);
        })
        ->pluck('indicator_id')
        ->toArray());
    $compList = \App\Indicator::whereIn('id',$_processes)->pluck('competence_id');
    $competences = \App\Competence::whereIn('id',$compList)->orderBy('competence_type_id')->get();
    $typeId = 0;
    $col_count = $evaluation->evaluaters()->count() + 3;
?>

<table class="table table-bordered">

    <tr>
        <th>№</th>
        <th>{{ trans('interface.competences') }}</th>
        @foreach($evaluation->evaluaters()
            ->orderBy('eval_role_id')
            ->get() as $evaluater)
            <th>{{ $evaluater->role->name }}</th>
        @endforeach
        <th>{{ trans('interface.total_role') }}</th>
    </tr>
@foreach($competences as $competence)

    @if($typeId != $competence->type->id)
        <?php $typeId = $competence->type->id; ?>
        <tr>
            <th></th>
            <th><h4>{{ $competence->type->note }}</h4></th>
            @foreach($evaluation->evaluaters()
            ->orderBy('eval_role_id')
            ->get() as $evaluater)
                <?php
                $average = $evaluater
                    ->processes()
                    ->whereHas('indicator',function($q) use ($typeId) {
                        return $q->whereHas('competence',function($q)use ($typeId) {
                            return $q->whereCompetenceTypeId($typeId);
                        });
                    })
                    ->avg('eval_level_id');

                $level = null;

                if ($average > 0) {
                    $level = \App\EvalLevel::where('min','<',$average)
                        ->where('max','>=',$average)
                        ->first();
                }
                ?>
                <td align="center"><strong>{{ $level ? $level->level : 0 }}</strong> ({{ round($average,2) }})</td>
            @endforeach

            <?php

            $average = \App\EvalProcess::whereHas('indicator',function($q) use ($typeId) {
                    return $q->whereHas('competence',function($q)use ($typeId) {
                        return $q->whereCompetenceTypeId($typeId);
                    });
                })
                ->whereHas('evaluater',function($q) use ($evaluation) {
                    return $q->whereEvaluationId($evaluation->id);
                })
                ->avg('eval_level_id');

            $level = null;

            if ($average > 0) {
                $level = \App\EvalLevel::where('min','<',$average)
                    ->where('max','>=',$average)
                    ->first();
            }
            ?>
            <td align="center"><strong>{{ $level ? $level->level : 0 }}</strong> ({{ round($average,2) }})</td>
        </tr>
    @endif
    <tr>
        <td>{{ $i++ }}</td>
        <td>{{ $competence->name }}</td>
        @foreach($evaluation->evaluaters()
            ->orderBy('eval_role_id')
            ->get() as $evaluater)
            <?php
            $average = $evaluater
                ->processes()
                ->whereHas('indicator',function($q) use ($competence) {
                    return $q->whereCompetenceId($competence->id);
                })
                ->avg('eval_level_id');

            $level = null;

            if ($average > 0) {
                $level = \App\EvalLevel::where('min','<',$average)
                    ->where('max','>=',$average)
                    ->first();
            }
            ?>
            <td align="center"><strong>{{ $level ? $level->level : 0 }}</strong> ({{ round($average,2) }})</td>
        @endforeach

        <?php

        $average = \App\EvalProcess::whereHas('indicator',function($q) use ($competence) {
                return $q->whereCompetenceId($competence->id);
            })
            ->whereHas('evaluater',function($q) use ($evaluation) {
                return $q->whereEvaluationId($evaluation->id);
            })
            ->avg('eval_level_id');

        $level = null;

        if ($average > 0) {
            $level = \App\EvalLevel::where('min','<',$average)
                ->where('max','>=',$average)
                ->first();
        }
        ?>
        <td align="center"><strong>{{ $level ? $level->level : 0 }}</strong> ({{ round($average,2) }})</td>
    </tr>
@endforeach
</table>