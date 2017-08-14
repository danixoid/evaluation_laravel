<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{!! trans('interface.tickets') !!} | {!! trans('interface.edit') !!}</div>

                    <div class="panel-body">

                        <form id="form_create_exam" class="form-horizontal" action="{!! route('exam.update',$exam->id) !!}" method="POST">
                            {!! csrf_field() !!}
                            {!! method_field("PUT") !!}

                            <div class="form-group">
                                <label class="control-label col-md-3">{!! trans('interface.user') !!}</label>
                                <div class="col-md-9 form-control-static">{!! $exam->user->name !!}</div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">{!! trans('interface.org') !!}</label>
                                <div class="col-md-9 form-control-static">
                                    <label class="text-info">{!! $exam->org->name !!}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">{!! trans('interface.func') !!}</label>
                                <div class="col-md-9 form-control-static">
                                    <label class="text-info">{!! $exam->func ? $exam->func->name : "" !!}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9 form-control-static">
                                    <label class="text-info">{!! $exam->position->name !!}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.chief') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="chief_id" id="chief">
                                    </select>
                                </div>
                            </div>

                            {{--<div class="form-group">--}}
                                {{--<label class="control-label col-md-3">{!! trans('interface.chief') !!}</label>--}}
                                {{--<div class="col-md-9 form-control-static">{!! $exam->chief->name !!}</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group">--}}
                                {{--<label class="control-label col-md-3">{!! trans('interface.ticket_count') !!}</label>--}}
                                {{--<div class="col-md-9 form-control-static">--}}
                                    {{--{!! $exam->count !!}--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-3 control-label">{!! trans('interface.quests') !!}</label>--}}
                                {{--<div class="col-md-9">--}}
                                    {{--@foreach(\App\Quest::where('position_id',$exam->position_id)->get() as $quest)--}}
                                    {{--<div class="checkbox">--}}
                                        {{--<label><input type="checkbox" value="{!! $quest->id !!}" name="quest_id[]">--}}
                                            {{--<p class="text-info">{!! $quest->shortSource !!}...</p>--}}
                                            {{--<p class="text-primary">{!! $quest->shortTask !!}...</p>--}}
                                        {{--</label>--}}
                                    {{--</div>--}}
                                    {{--@endforeach--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-3">
                                    <button type="submit" class="btn btn-block btn-danger" >{!! trans('interface.update') !!}</button>
                                </div>
                                <div class="col-md-3">
                                    <a href="{!! route('exam.index') !!}" class="btn btn-block btn-info" >{!! trans('interface.cancel') !!}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('meta')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('javascript')
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/i18n/ru.js') }}"></script>
    <script>

        $(function () {

            $("#chief").select2({
                data: [
                    {
                        id: '{!! $exam->chief->id !!}',
                        name: '{!! $exam->chief->name !!}',
                    }
                ],
                ajax: {
                    url: "{!! url('/user') !!}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 30) < data.length
                            }
                        };
                    },
                    cache: true
                },
                theme: "bootstrap",
                placeholder: '{!! trans('interface.select_user') !!}',
                language: '{!! config()->get('app.locale') !!}',
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            });

        });

    </script>

@endsection

