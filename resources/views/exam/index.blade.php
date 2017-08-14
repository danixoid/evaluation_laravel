<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')

    <?php $org_id = request('org_id') ?: '0'; ?>
    <?php $func_id = request('func_id') ?: '0'; ?>
    <?php $position_id = request('position_id') ?: '0'; ?>
    <?php $user_id = request('user_id') ?: '0'; ?>
    <?php $chief_id = request('chief_id') ?: '0'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <label class="col-md-10">{!! trans('interface.exams') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('exam.create') !!}" >{!! trans('interface.create') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <form class="form-horizontal" id="form_quest_search" action="{!! route("exam.index") !!}">

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.org') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" id="org" name="org_id">
                                        <option value="0">{{ trans('interface.no_value') }}</option>
                                        @foreach(\App\Org::all() as $org)
                                            <option value="{{ $org->id }}"
                                            @if(request('org_id') == $org->id) selected @endif>{{ $org->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.func') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" id="func" name="func_id">
                                        <option value="0">{{ trans('interface.no_value') }}</option>
                                        @foreach(\App\Func::all() as $func)
                                            <option value="{{ $func->id }}"
                                            @if(request('func_id') == $func->id) selected @endif>{{ $func->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" id="position" name="position_id">
                                        <option value="0">{{ trans('interface.no_value') }}</option>
                                        @foreach(\App\Position::all() as $position)
                                            <option value="{{ $position->id }}"
                                            @if(request('position_id') == $position->id) selected @endif>{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.user') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="user_id" id="user">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.chief') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="chief_id" id="chief">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.started_date') !!}</label>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" value="{!! request('date_started') !!}"
                                           name="date_started" id="date_started" />
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="date" class="form-control" value="{!! request('date_finished') !!}"
                                            name="date_finished" id="date_finished" />
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-info btn-block">{!! trans("interface.search") !!}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>{!! trans('interface.position') !!}</th>
                                    <th>{!! trans('interface.user') !!}</th>
                                    <th>{!! trans('interface.chief') !!}</th>
{{--                                    <th>{!! trans('interface.quest_count') !!}</th>--}}
                                    <th>{!! trans('interface.started_date') !!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exams as $exam)
                                    <tr>
                                        <td>{!! $exam->id !!}</td>
                                        <td>
                                            <a href="{!! route('exam.index',['position_id' => $exam->position_id]) !!}">
                                                <span class="text-info" title="{!! $exam->position->orgPath !!}">{!! $exam->position->name !!}</span>

                                            </a>
                                        </td>
                                        <td>{{ $exam->user->name }}</td>
                                        <td>{{ $exam->chief->name }}</td>
{{--                                        <td>{{ $exam->count }}</td>--}}
                                        <td>
                                            <a href="{!! route('exam.show',['id'=>$exam->id]) !!}">
                                                <span class="text-{!! $exam->color !!}">
                                                    @if($exam->started){{ date(' d M, Y H:i',strtotime($exam->startedDate)) }}<br />@endif
                                                    @if($exam->finished){{ date(' d M, Y H:i',strtotime($exam->finishedDate)) }}<br />@endif
                                                    {!! trans('interface.' . $exam->status) !!}
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $exams->links() }}
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

            /**
             *  SELECT2
             */

            var data = {


                user : {
                    id: '{!! $user_id !!}',
                    text: '{!! ($user_id > 0)
                                ? \App\User::find($user_id)->name
                                : trans('interface.no_value') !!}',
                },
                chief : {
                    id: '{!! $chief_id !!}',
                    text: '{!! ($chief_id > 0)
                                ? \App\User::find($chief_id)->name
                                : trans('interface.no_value') !!}',
                }
            };

            $("#org,#func,#position").each(function(){
                var id = $(this).attr('id');

                $(this).select2({
                    theme: "bootstrap",
                    placeholder: '{!! trans('interface.select_position') !!}',
                    allowClear: true,
                    language: '{!! config()->get('app.locale') !!}',
//                    minimumInputLength: 2,
                });
            });



            $("#user,#chief").each(function(){
                var id = $(this).attr('id');

                $(this).select2({
                    data: [
                        data[id]
                    ],
                    ajax: {
                        url: "{!! url('/" + (id === "chief" ? "user" : id) + "') !!}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                count: params.page
                            };
                        },
                        processResults: function (data, params) {
                            // parse the results into the format expected by Select2
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data, except to indicate that infinite
                            // scrolling can be used
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
                    placeholder: '{!! trans('interface.select_position') !!}',
                    allowClear: true,
                    language: '{!! config()->get('app.locale') !!}',
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                });
            });

            $("#org,#func,#position,#user,#chief").each(function() {
                $(this).on("select2:select", function(e) {
                    $("#form_quest_search").submit();
                });

                $(this).on("select2:unselect", function(e) {
                    $(this).val("0");
                    $("#form_quest_search").submit();
                });
            });


        });




    </script>

@endsection