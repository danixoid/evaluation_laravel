@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{!! trans('interface.my_exams') !!}</div>

                <div class="panel-body">

                    @if(count($exams) > 0)
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>{{ trans('interface.org') }} / {{ trans('interface.func') }}</th>
                                <th>{{ trans('interface.position') }}</th>
                                <th>{{ trans('interface.user') }}</th>
{{--                                <th>{{ trans('interface.quests') }}</th>--}}
                                <th>{{ trans('interface.exam_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $num = 1; ?>
                            @foreach($exams as $exam)
                                <tr>
                                    <td>{!! $num++ !!}</td>
                                    <td>
                                        <span class="text-info">{!! $exam->org->name !!}</span><span
                                                class="text-primary">{{ $exam->func ? "/".$exam->func->name : "" }}</span>
                                    </td>
                                    <td>
                                        <span class="text-info">{!! $exam->position->name !!}</span>
                                    </td>
                                    <td>{!! $exam->user->name !!}</td>
{{--                                    <td>{!! $exam->count !!}</td>--}}
                                    <td><a href="{!! route('exam.show',['id' => $exam->id]) !!}">
                                        <span class="text-{!! $exam->color !!}">
                                        {!! trans('interface.'.$exam->status) !!}</span></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $exams->links() !!}
                    @else
                        <h3>{!! trans('interface.not_found') !!}</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
