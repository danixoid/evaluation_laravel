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
    <div class="container-fluid" xmlns:height="http://www.w3.org/1999/xhtml">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <label class="col-md-8">{!! trans('interface.reports') !!} | {!! trans('interface.report_plan') !!}</label>
                    <div class="col-md-4 text-right">
                        {{--<a href="#" onclick="$('#form_import_evaluation').submit();" >{!! trans('interface.import') !!}</a> |--}}
                        @if(isset($evaluation) && $evaluation)
                            <a href="{!! route('reports.compare', array_merge(request()->all(),
                            ['export'=>'xls'])) !!}">{!! trans('interface.export_xls') !!}</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="panel-body">

                <form class="form-horizontal" id="form_reports_plan" action="{!! route("reports.plan") !!}">


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
                                <input type="text" class="form-control" value="{!! request('begin_at') ?: \Carbon\Carbon::today() !!}"
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
                @if(isset($evaluation))
                    @include('reports.plan_export')
                @endif

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

            $("#user").each(function() {
                $(this).on("select2:select", function(e) {
                    $("#form_reports_plan").submit();
                });

                $(this).on("select2:unselect", function(e) {
                    $(this).val("0");
                    $("#form_reports_plan").submit();
                });
            });

        });

    </script>

@endsection