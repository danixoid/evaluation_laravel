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

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <label class="col-md-10">{!! trans('interface.quests') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('quest.create') !!}" >{!! trans('interface.create') !!}</a>
                                |
                                <a href="#modal_timer" data-toggle="modal" data-target="#modal_timer">{!! trans('interface.edit') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal_timer">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-body">
                                    <div class="close-button">
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <h2 class="form-signin-heading">{!! trans('interface.timer') !!} | {!! trans('interface.edit') !!}</h2>

                                        <form class="form"  action="{!! route('quest.update',0) !!}" method="POST">
                                        {!! csrf_field() !!}
                                        {!! method_field('PUT') !!}
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="timer" value="15">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-primary">{{ trans('interface.save') }}</button>
                                                    </span>
                                                </div><!-- /input-group -->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="panel-body">
                        <form class="form-horizontal" id="form_quest_search" action="{!! route("quest.index") !!}">


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
                                            onchange="$('#form_quest_search').submit()" name="trashed" value="1"> {{ trans('interface.search_in_archive') }}</label>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    {{--<th>{!! trans('interface.source') !!}</th>--}}
                                    <th>{!! trans('interface.task') !!}</th>
{{--                                    <th>{!! trans('interface.position') !!}</th>--}}
                                    <th>{!! trans('interface.timer') !!}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quests as $quest)
                                    <tr>
                                        <td>{!! $quest->id !!}</td>
{{--                                        <td>{{ $quest->shortSource }}...</td>--}}
                                        <td>{{ $quest->shortTask }}...</td>
                                        {{--<td>
                                            @foreach($quest->positions as $position)
                                                <a href="{!! route('quest.index',['position_id' => $position->id]) !!}">
                                                    <span title="{!! $position->orgPath !!}" class="text-info">{!! $position->name !!}</span>
                                                </a>,
                                            @endforeach
                                        </td>--}}
                                        <td><span class="text-warning">{{ $quest->timer }} {{ trans('interface.minutes') }}</span></td>
                                        <td><a href="{!! route('quest.show',['id'=>$quest->id]) !!}">{!! trans('interface.show') !!}</a></td>
                                        <td>

                                            <form id="form_delete_quest{{ $quest->id }}" action="{!! route('quest.destroy',['id' => $quest->id]) !!}" method="POST">
                                                {!! csrf_field() !!}
                                                {!! method_field("DELETE") !!}
                                            </form>
                                            <a href="#form_delete_quest{{ $quest->id }}"
                                               onclick="$('#form_delete_quest{{ $quest->id }}').submit();">{!!
                                                $quest->trashed() ? trans('interface.restore') : trans('interface.to_archive')
                                                !!}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $quests->links() }}
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