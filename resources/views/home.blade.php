@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{!! trans('interface.evaluation_personal') !!}</div>

                <div class="panel-body">

                    @if(count($evaluaters) > 0)
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>№</th>
                                {{--<th>{{ trans('interface.org') }} / {{ trans('interface.func') }}</th>--}}
                                {{--<th>{{ trans('interface.position') }}</th>--}}
                                <th>{{ trans('interface.evaluated') }}</th>
                                <th>{{ trans('interface.evaluater') }}</th>
                                <th>{{ trans('interface.exam_status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $num = 1; ?>
                            @foreach($evaluaters as $evaluater)
                                <tr>
                                    <td>{!! $num++ !!}</td>
                                    {{--<td>--}}
                                        {{--<span class="text-info">{!! $evaluater->evaluation->org->name !!}</span><span--}}
                                                {{--class="text-primary">{{ $evaluater->evaluation->func ? "/".$evaluater->evaluation->func->name : "" }}</span>--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--<span class="text-info">{!! $evaluater->evaluation->position->name !!}</span>--}}
                                    {{--</td>--}}
                                    <td>{!! $evaluater->evaluation->evaluated->name !!}</td>
                                    <td>{!! $evaluater->user->name !!}</td>

                                    <td><a href="{!! route('evaluater.show',['id' => $evaluater->id]) !!}">
                                        <span class="text-{{ $evaluater->finished ? 'success' : 'danger'}}">
                                        {{ $evaluater->role->name }}
                                            [{{ $evaluater->finished
                                            ? trans('interface.finished')
                                            : trans('interface.not_finished') }}]
                                        </span>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $evaluaters->links() !!}
                    @else
                        <h3>{!! trans('interface.not_found') !!}</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
