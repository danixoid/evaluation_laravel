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
                            <div class="col-md-8">{!! trans('interface.competence_profile') !!} | {!! trans('interface.add') !!}</div>
                            <div class="col-md-4 text-right">
                                <a href="{!! route('profile.index') !!}" >{!! trans('interface.prev') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <form id="form_search_profile" class="form-horizontal"
                              action="{!! route('profile.index') !!}" method="GET">
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

                        </form>


                        @if(request('org_id') && request('position_id'))
                        <form id="form_create_profile" class="form-horizontal"
                              action="{!! route('profile.store') !!}" method="POST">
                            {!! csrf_field() !!}

                            <?php
                                $type_id = 0;
                            ?>
                            @foreach($competences as $competence)

                                <input type="hidden" name="profile[{{ $competence->id }}][competence_id]"
                                       value="{{ $competence->id }}" />
                                <input type="hidden" name="profile[{{ $competence->id }}][org_id]"
                                       value="{{ request('org_id') ?: null }}" />

                                <input type="hidden" name="profile[{{ $competence->id }}][func_id]"
                                       value="{{ request('func_id') ?: null }}" />

                                <input type="hidden" name="profile[{{ $competence->id }}][position_id]"
                                       value="{{ request('position_id') ?: null }}" />

                                @if($type_id != $competence->competence_type_id)
                                <h4>{{ $competence->type->note }}</h4>
                                @endif
                                <?php
                                    $profiles = $competence->profiles()
                                        ->whereOrgId(request('org_id') ?: null)
                                        ->whereFuncId(request('func_id') ?: null)
                                        ->wherePositionId(request('position_id') ?: null)
                                        ->first();
                                    $type_id = $competence->competence_type_id;
                                ?>
                                <div class="form-group">
                                    <label class="col-xs-4 control-label @if(!$profiles) col-xs-offset-6 @endif">{!! $competence->name !!}</label>
                                    <div class="col-xs-2">
                                        <select class="form-control compSelect" name="profile[{{ $competence->id }}][eval_level_id]">
                                            <option>0</option>
                                            @foreach(\App\EvalLevel::all() as $level)
                                                <option value="{{ $level->id }}"
                                                @if($profiles && $profiles->eval_level_id == $level->id)
                                                    selected="selected"
                                                @endif>{{ $level->level }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach


                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-3">
                                    <button class="btn btn-block btn-danger" >{!! trans('interface.save') !!}</button>
                                </div>

                            </div>


                        </form>
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
                    $("#form_search_profile").submit();
                });

                $(this).on("select2:unselect", function(e) {
                    $(this).val("0");
                    $("#form_search_profile").submit();
                });
            });

        });

        $(".compSelect").on('change',function(ev) {
            var $this = $(this);
            if($this.val() > 0) {
                $this.parent().parent().find('.col-xs-4').removeClass('col-xs-offset-6');
            } else {
                $this.parent().parent().find('.col-xs-4').addClass('col-xs-offset-6');
            }
        });
    </script>

@endsection

