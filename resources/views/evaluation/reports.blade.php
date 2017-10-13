<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 28.08.17
 * Time: 19:04
 */?>

@foreach(\App\CompetenceType::all() as $type)
    <h3>{{ $type->note }}</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>№</th>
            <th>{{ trans('interface.competences') }}</th>
            @foreach($evaluation->evaluaters()
                ->orderBy('eval_role_id')
                ->get() as $evaluater)
                <th>{{ $evaluater->role->name }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        $totalRole = \App\EvalRole::whereCode('total')->first();
        $totalEvaluater = $evaluation->evaluaters()
            ->where('eval_role_id',$totalRole->id)
            ->first();
        $compList = \App\Indicator::whereIn('id',$totalEvaluater->processes()->pluck('indicator_id'))
            ->pluck('competence_id');
        $competences = \App\Competence::whereCompetenceTypeId($type->id)->whereIn('id',$compList)->get();
        ?>
        @foreach($competences as $competence)
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
            </tr>
        @endforeach
        </tbody>
    </table>
@endforeach
