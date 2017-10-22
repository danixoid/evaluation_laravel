<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 19.10.17
 * Time: 10:50
 */?>
@extends('layouts.app')

@section('content')

    <?php $user_id = request('user_id') ?: '0'; ?>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <label class="col-md-8">{!! trans('interface.reports') !!} | {!! trans('interface.report_results') !!}</label>
                    <div class="col-md-4 text-right">
                        {{--<a href="#" onclick="$('#form_import_evaluation').submit();" >{!! trans('interface.import') !!}</a> |--}}
                        <a href="{!! route('reports.results', array_merge(request()->all(),
                            ['export'=>'xls'])) !!}">{!! trans('interface.export_xls') !!}</a>
                    </div>
                </div>
            </div>

            <div class="panel-body">

                <form class="form-horizontal" id="form_reports_results" action="{!! route("reports.results") !!}">

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
                        <label class="col-md-3 control-label">{!! trans('interface.evaluated') !!}</label>
                        <div class="col-md-9">
                            <select class="form-control select2-single" name="user_id" id="user">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">{!! trans('interface.started_date') !!}</label>

                        <div class="col-md-4">

                            <div class='input-group date'>
                                <input type="text" class="form-control" value="{!! request('begin_at')
                                    ?: \Carbon\Carbon::today()->month(\Carbon\Carbon::today()->month - 1) !!}"
                                       name="begin_at" id="begin_at" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>

                        </div>
                        <div class="col-md-5">

                            <div class='input-group date'>
                                <input type="text" class="form-control" value="{!! request('end_at') ?: \Carbon\Carbon::today() !!}"
                                       name="end_at" id="end_at" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-info btn-block">{!! trans("interface.search") !!}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

                @include('reports.results_export')

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
             * DateTimePicker
             * */
            $(function () {
                $('.date').datetimepicker({
                    locale: 'ru',
                    format: 'YYYY-MM-DD'
                });
            });

            /**
             *  SELECT2
             */
            var data = {


                user : {
                    id: '{!! $user_id !!}',
                    text: '{!! ($user_id > 0)
                                ? \App\User::find($user_id)->name
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

            $("#user").each(function(){
                var id = $(this).attr('id');

                $(this).select2({
                    data: [
                        data[id]
                    ],
                    ajax: {
                        url: "{!! url('/user') !!}",
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

            $("#org,#func,#position,#user").each(function() {
                $(this).on("select2:select", function(e) {
                    $("#form_reports_results").submit();
                });

                $(this).on("select2:unselect", function(e) {
                    $(this).val("0");
                    $("#form_reports_results").submit();
                });
            });

//            $('[name="begin_at"],[name="end_at"]').on('change',function (ev) {
//                $("#form_evaluation_search").submit();
//            });

        });

    </script>

@endsection