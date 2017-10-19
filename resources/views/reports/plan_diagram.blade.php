<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 19.10.17
 * Time: 15:10
 */?>

        <!doctype html>
<html>

<head>
    <title>{{ trans('interface.report_plan') }}</title>
    <script type="text/javascript" src="{!! url('/js/Chart.bundle.min.js') !!}"></script>
    {{--<script src="../../utils.js"></script>--}}
    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>
</head>

<body>
<div id="container" style="width: 90%;">
    <canvas id="canvas"></canvas>
</div>
<script>
    var barChartData = {
        labels: [
            <?php $i=0; ?>
            @foreach($competences as $competence)
                "{{ $competence->name }}" @if(++$i < count($competences)),@endif
            @endforeach
        ],
        datasets: [{
            label: '{{ trans('interface.total_role') }}',
            backgroundColor: '#00FF00',
            borderColor: '#000000',
            borderWidth: 1,
            data: [

                <?php $i = 0; ?>
                @foreach($competences as $competence)
                    <?php
                    $i++;
                    $average = \App\EvalProcess::
                        whereIn('indicator_id',$competence->indicators()->pluck('id'))
                        ->whereIn('evaluater_id',$evaluation->evaluaters()->pluck('id'))
                        ->leftJoin('eval_levels','eval_level_id','eval_levels.id')
                        ->avg('eval_levels.level');
                    ?>
                    @if ($average > 0)
                    <?php
                        $level = \App\EvalLevel::where('min','<',$average)
                            ->where('max','>=',$average)
                            ->first();
                        ?>
                    {{ $level ? $level->level : 0 }} @if($i < count($competences)),@endif
                    @else "0"@if($i < count($competences)),@endif
                    @endif
                @endforeach
            ]
        }, {
            label: '{{ trans('interface.competence_profile') }}',
            backgroundColor: '#DDDDFF',
            borderColor: "#000000",
            borderWidth: 1,
            data: [
                <?php $i = 0; ?>
                @foreach($competences as $competence)
                    <?php
                    $i++;
                    $query = \App\CompetenceProfile::whereCompetenceId($competence->id)
                        ->whereOrgId($evaluation->org_id)
                        ->wherePositionId($evaluation->position_id);
                    if($evaluation->func_id) {
                        $query = \App\CompetenceProfile::whereFuncId($evaluation->func_id);
                    }
                    $total = $query->first();
                    ?>
                    {{ $total ? $total->level->level : '0' }}@if($i < count($competences)),@endif
                @endforeach
            ]
        }]

    };

    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                responsive: true,
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: '{{ trans('interface.report_plan') }}'
                },
                scales: {
                    yAxes: [{
                        display: true,
                        stepSize: 1,
                        ticks: {
                            stacked: true,
                            min: 0,    // minimum will be 0, unless there is a lower value.
                            max: 5,    // minimum will be 0, unless there is a lower value.
                            // OR //
                            beginAtZero: true   // minimum value will be 0.
                        }
                    }]
                }
            }
        });

    };
    function getRandomArbitrary(min, max) {
        return Math.round(Math.random() * (max - min) + min);
    }
</script>
</body>

</html>
