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
        $totalRole = \App\EvalRole::whereCode('total')->firstOrFail();
        $totalProcess = $evaluation->evaluaters()
            ->where('eval_role_id',$totalRole->id)
            ->first();
        ?>
        @foreach($totalProcess->processes()
                ->whereIn('competence_id',
                    \App\Competence::where('competence_type_id',
                    $type->id)->pluck('id'))->get() as $process)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $process->competence->name }}</td>
                @foreach($evaluation->evaluaters()
                    ->orderBy('eval_role_id')
                    ->get() as $evaluater)
                    @foreach($evaluater
                        ->processes()
                        ->whereCompetenceId($process->competence_id)
                        ->get() as $thisProcess)
                        <td align="center">{{ $thisProcess->level ? $thisProcess->level->level : ""}}</td>
                    @endforeach
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
@endforeach
