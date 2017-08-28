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
                            <label class="col-md-10">{{ $evaluation->evaluated->name }} | {{ $evaluation->type->name }}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('evaluation.show',['id'=>$evaluation->id]) !!}" >{!! trans('interface.prev') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <form id="form_create_evaluation" class="form-horizontal" action="{!! route('evaluater.store') !!}" method="POST">
                            {!! csrf_field() !!}
                            {{--                            {!! method_field("PUT")  !!}--}}
    {{--


                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-9 form-control-static">
                                    <h3>{!! trans('interface.evaluaters') !!}</h3></div>
                            </div>
    --}}

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.org') !!}</label>
                                <div class="col-md-9 form-control-static">
                                    {{ $evaluation->org->name }}
                                </div>
                            </div>

                            @if($evaluation->func)
                                <div class="form-group">
                                    <label class="col-md-3 control-label">{!! trans('interface.func') !!}</label>
                                    <div class="col-md-9 form-control-static">
                                        {{ $evaluation->func->name }}
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9 form-control-static">
                                    {{ $evaluation->position->name }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-9 table-responsive">
                                    <table id="struct_table" class="table table-condensed">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('interface.user') }}</th>
                                            <th>{{ trans('interface.role') }}</th>
                                            <th>{{ trans('interface.destroy') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($evaluation->evaluaters()->orderBy('eval_role_id')->get() as $evaluater)
                                            <tr>
                                                <td>{{ $evaluater->user->name }}</td>
                                                <td>{{ $evaluater->role->name }}</td>
                                                <td>
                                                    @if($evaluater->role->code != 'self')
                                                        <a class="remove" href="#struct_table"
                                                           onclick="$('#form_delete_evaluater{{ $evaluater->id }}').submit();">
                                                            {{ trans('interface.destroy') }}</a>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <div class="form-group role_form_group">
                                <label class="col-md-3 control-label">{!! trans('interface.role') !!}</label>
                                <div class="col-md-4">
                                    <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">
                                    <select class="form-control select2-single" name="eval_role_id" id="role">
                                        @foreach($evaluation->type->roles as $role)
                                            @if( $role->pivot->max -
                                                $evaluation->evaluaters()->whereEvalRoleId($role->id)->count())
                                                <option value="{{ $role->id }}">{{ $role->name }}
                                                    ({{ $role->pivot->max -
                                        $evaluation->evaluaters()->whereEvalRoleId($role->id)->count() }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-1 control-label">{!! trans('interface.user') !!}</label>
                                <div class="col-md-4">
                                    <select class="form-control select2-single" name="user_id" id="user">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group role_form_group">
                                <div class="col-md-offset-3 col-md-4">
                                    <button class="btn btn-block btn-danger" >{!! trans('interface.add') !!}</button>
                                </div>

                            </div>
                        </form>




                        @foreach($evaluation->evaluaters as $evaluater)
                            <form id="form_delete_evaluater{{ $evaluater->id }}" action="{!! route('evaluater.destroy',['id' => $evaluater->id]) !!}" method="POST">
                                {!! csrf_field() !!}
                                {!! method_field("DELETE") !!}
                            </form>
                        @endforeach

                        <form id="form_start_evaluation" action="{!! route('evaluation.update',['id' => $evaluation->id]) !!}" method="POST">
                            {!! csrf_field() !!}
                            {!! method_field("PUT") !!}

                            @foreach (\App\CompetenceType::all() as $type)
                                <div class="form-group">
                                    <label class="col-md-3 control-label">{!! $type->note !!}</label>
                                    <div class="col-md-9">
                                        @foreach ($competences as $competence)

                                            @if($competence->type->id == $type->id)
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="competences[]" checked="checked" value="{{ $competence->id }}">
                                                        <strong>{{ $competence->name }}</strong> {{ $competence->note }}</label>
                                                    {{--@foreach($competence->positions as $position)
                                                        <span>{{ $position->pivot->position_id }}</span>
                                                        <span>{{ $position->pivot->org_id }}</span>
                                                    @endforeach--}}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <hr>
                            @endforeach

                            @if($evaluation->enough)
                                <div class="form-group">
                                    <div class="col-md-offset-3 col-md-4">
                                        <a href="#" class="btn btn-block btn-primary" onclick="$('#form_start_evaluation').submit();">{!! trans('interface.start') !!}</a>
                                    </div>
                                </div>
                            @endif

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
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>

        var cols = 0;

        $(function () {

            /**
             *  SELECT2
             */
            $("#role").each(function(){
                var id = $(this).attr('id');

                $(this).select2({
                    theme: "bootstrap",
                    placeholder: '{!! trans('interface.select_position') !!}',
//                    allowClear: false,
                    language: '{!! config()->get('app.locale') !!}',
//                    minimumInputLength: 2,
                });
            });

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


            if($("#role option").length < 1) {
                $('.role_form_group').hide();
            }


        });

    </script>

@endsection

