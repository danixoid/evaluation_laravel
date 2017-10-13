<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')

    <?php $user_id = request('user_id') ?: '0'; ?>
    <?php $chief_id = request('chief_id') ?: '0'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <label class="col-md-8">{!! trans('interface.evaluation_personal') !!}: {!! trans('interface.create') !!}</label>
                            <div class="col-md-4 text-right">
                                <a href="#" onclick="$('#form_create_evaluation').submit();" >{!! trans('interface.create') !!}</a> |
                                <a href="{!! route('evaluation.index') !!}">{!! trans('interface.prev') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <form id="form_create_evaluation" class="form-horizontal" action="{!! route('evaluation.store') !!}" method="POST">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.evaluated') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="user_id" id="user">
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.evaluation_type') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="eval_type_id">
                                        {{--<option value="{!! old('eval_type_id') ?: 0 !!}">{!! (old('eval_type_id'))--}}
                                        {{--? \App\EvalType::find(old('eval_type_id'))->name--}}
                                        {{--: trans('interface.no_value') !!}</option>--}}
                                        @foreach(\App\EvalType::all() as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.org') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="org_id">
                                        <option value="{!! old('org_id') ?: 0 !!}">{!! (old('org_id'))
                                        ? \App\Org::find(old('org_id'))->name
                                        : trans('interface.no_value') !!}</option>
                                        @foreach(\App\Org::all() as $org)
                                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.func') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="func_id">
                                        <option value="{!! old('func_id') ?: 0 !!}">{!! (old('func_id'))
                                        ? \App\Func::find(old('func_id'))->name
                                        : trans('interface.no_value') !!}</option>
                                        @foreach(\App\Func::all() as $func)
                                            <option value="{{ $func->id }}">{{ $func->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" name="position_id">
                                        <option value="{!! old('position_id') ?: 0 !!}">{!! (old('position_id'))
                                        ? \App\Position::find(old('position_id'))->name
                                        : trans('interface.no_value') !!}</option>
                                        @foreach(\App\Position::all() as $position)
                                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                        </form>
                    </div>

                    <div class="panel-footer">
                        <div class="text-right">
                            <a href="#" onclick="$('#form_create_evaluation').submit();" >{!! trans('interface.create') !!}</a> |
                            <a href="{!! route('evaluation.index') !!}">{!! trans('interface.prev') !!}</a>
                        </div>
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
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>

        var cols = 0;

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

            $("select").each(function(){
                var id = $(this).attr('id');

                $(this).select2({
                    theme: "bootstrap",
                    placeholder: '{!! trans('interface.select_position') !!}',
//                    allowClear: false,
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
                    allowClear: false,
                    language: '{!! config()->get('app.locale') !!}',
                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                });
            });
        });

    </script>

@endsection

