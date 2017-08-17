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
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <label class="col-md-10">{!! trans('interface.competences') !!} : {{ $type->name }}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('competence.create',['type' => $type->id]) !!}" >{!! trans('interface.add') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <form class="form-horizontal" id="form_competence_search" action="{!! route("competence.index") !!}">

                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-9">
                                    {{ $type->note }}
                                    <input type="hidden" name="type" value="{{ $type->id }}"/>
                                </div>
                            </div>

                            @if($type->prof)
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
                            @endif


                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.by_text') !!}</label>
                                <div class="col-md-9">

                                    <div class="input-group">
                                        <input type="search" name="text" class="form-control" value="{!! request('text') !!}"
                                               placeholder="{!! trans('interface.search') !!}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-default">Найти</button>
                                        </span>
                                    </div><!-- /input-group -->
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <div class="checkbox">
                                        <label><input type="checkbox" @if(request('trashed')) checked @endif
                                            onchange="$('#form_competence_search').submit()" name="trashed" value="1"> {{ trans('interface.search_in_archive') }}</label>
                                    </div>
                                </div>
                            </div>

                        </form>

                        @if(count($competences) > 0)
                        <div class="container-fluid">
                        @foreach($competences as $competence)
                            <p>{{--{{ $competence->id }}. --}}<strong>{{ $competence->name }}</strong></p>
                            <p>{{ $competence->note }}</p>
                            <ul class="list-group list-inline">
                                @foreach($competence->indicators as $indicator)
                                    <li>
                                        <strong>{{ $indicator->evalLevel->name }}</strong>
                                        {{ $indicator->name }}
                                    </li>
                                @endforeach
                            </ul>

                            @if($competence->positions()->count() > 0)
                            <div class="table-responsive">
                                <table id="struct_table" class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('interface.org') }}</th>
                                        <th>{{ trans('interface.func') }}</th>
                                        <th>{{ trans('interface.position') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0?>
                                    @foreach($competence->positions as $struct)

                                        <tr>
                                            <td>{{ \App\Org::find($struct->pivot->org_id)->name }}</td>
                                            <td>{{ \App\Func::find($struct->pivot->func_id)
                                    ? \App\Func::find($struct->pivot->func_id)->name
                                    : "" }}</td>
                                            <td>{{ \App\Position::find($struct->pivot->position_id)->name  }}</td>
                                        </tr>
                                        <?php $i++ ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            <p>
                                <a href="{!! route('competence.show',['id'=>$competence->id]) !!}">{!!
                                    trans('interface.show') !!}</a> |
                                <a href="{!! route('competence.edit',['id'=>$competence->id]) !!}">{!!
                                    trans('interface.edit') !!}</a> |
                                <a href="#form_delete_competence{{ $competence->id }}"
                                   onclick="$('#form_delete_competence{{ $competence->id }}').submit();">{!!
                                                $competence->trashed() ? trans('interface.restore')
                                                : trans('interface.to_archive') !!}</a>
                            </p>
                            <form id="form_delete_competence{{ $competence->id }}" action="{!! route('competence.destroy',['id' => $competence->id]) !!}" method="POST">
                                {!! csrf_field() !!}
                                {!! method_field("DELETE") !!}
                            </form>
                            <hr />
                        @endforeach
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
    @if($type->prof)
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap.min.css') }}" rel="stylesheet">
    @endif
@endsection

@section('javascript')
    @if($type->prof)
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/i18n/ru.js') }}"></script>
    <script>


        $(function () {

            /**
             *  SELECT2
             */

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

            $("#org,#func,#position").each(function() {
                $(this).on("select2:select", function(e) {
                    $("#form_competence_search").submit();
                });

                $(this).on("select2:unselect", function(e) {
                    $(this).val("0");
                    $("#form_competence_search").submit();
                });
            });

        });

    </script>
    @endif

@endsection