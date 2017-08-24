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
                            <label class="col-md-10">{!! trans('interface.evaluation_list') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('evaluation.create') !!}" >{!! trans('interface.add') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <form class="form-horizontal" id="form_evaluation_search" action="{!! route("evaluation.index") !!}">

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

                        </form>

                        @if(count($evaluations) > 0)
                        <div class="container-fluid">
                            <div class="row">
                            @foreach($evaluations as $evaluation)
                                <div class="well col-md-4">
                                    <p>{{--{{ $evaluation->id }}. --}}<strong>{{ $evaluation->evaluated->name }}</strong>, {{ $evaluation->type->note }}</p>
                                    <ul class="list-group list-unstyled">
                                        @foreach($evaluation->evaluaters as $evaluater)
                                            <li>
                                                <strong>{{ $evaluater->role->name }}</strong>
                                                {{ $evaluater->user->name }}
                                            </li>
                                        @endforeach
                                    </ul>

                                    <p>
                                        <a href="{!! route('evaluation.show',['id'=>$evaluation->id]) !!}">{!!
                                            trans('interface.show') !!}</a>
                                        @if(!$evaluation->started) |
                                            <a href="{!! route('evaluation.edit',['id'=>$evaluation->id]) !!}">{!!
                                            trans('interface.edit') !!}</a> |
                                        <a href="#form_delete_evaluation{{ $evaluation->id }}"
                                           onclick="$('#form_delete_evaluation{{ $evaluation->id }}').submit();">{!!
                                                        trans('interface.destroy') !!}</a>
                                        @else

                                        @endif
                                    </p>

                                    @if(!$evaluation->started)
                                    <form id="form_delete_evaluation{{ $evaluation->id }}" action="{!! route('evaluation.destroy',['id' => $evaluation->id]) !!}" method="POST">
                                        {!! csrf_field() !!}
                                        {!! method_field("DELETE") !!}
                                    </form>
                                    @endif
                                </div>
                            @endforeach
                            </div>
                        </div>

                        @else
                            <h3>{{ trans('interface.not_found') }}</h3>
                        @endif
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
                    $("#form_evaluation_search").submit();
                });

                $(this).on("select2:unselect", function(e) {
                    $(this).val("0");
                    $("#form_evaluation_search").submit();
                });
            });

        });

    </script>

@endsection