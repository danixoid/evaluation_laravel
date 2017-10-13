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
                            <label class="col-md-8">{{ $competence->type->note }} | {!! trans('interface.edit') !!}</label>
                            <div class="col-md-4 text-right">
                                <a href="#" onclick="$('#form_create_competence').submit();" >{!! trans('interface.update') !!}</a> |
                                <a href="{!! route('competence.show',['id'=>$competence->id]) !!}">{!! trans('interface.prev') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <form id="form_create_competence" class="form-horizontal" action="{!! route('competence.update',['id' => $competence->id]) !!}" method="POST">
                            {!! csrf_field() !!}
                            {!! method_field("PUT")  !!}


                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.competence') !!}</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="name" value="{!! $competence->name !!}" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.notes') !!}</label>
                                <div class="col-md-9">
                                    <textarea id="note" rows="6" class="form-control" name="note" required>{!! $competence->note !!}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.indicators') !!}</label>
                                <div class="col-md-9 form-control-static" id="indicators">
                                    <ol>
                                        @foreach($competence->indicators as $indicator)
                                            <li>
                                                <input type="hidden" name="indicator[{!! $indicator->id !!}][name]" value="{!! $indicator->name !!}"/>
                                                <input type="hidden" name="indicator[{!! $indicator->id !!}][id]" value="{!! $indicator->id !!}"/>
                                                {{ $indicator->name }}
                                                <a href="#" class="removeIndicator">{{ trans('interface.destroy') }}</a>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.deleted') !!}</label>
                                <div class="col-md-9 form-control-static" id="indicatorsDeleted">
                                    <ol>
                                        @foreach($competence->indicators()->onlyTrashed()->get() as $indicator)
                                            <li>
                                                <input type="hidden" disabled="disabled" name="indicator[{!! $indicator->id !!}][name]" value="{!! $indicator->name !!}"/>
                                                <input type="hidden" disabled="disabled" name="indicator[{!! $indicator->id !!}][id]" value="{!! $indicator->id !!}"/>
                                                <i class="text-danger">{{ $indicator->name }}</i>
                                                <a href="#" class="restoreIndicator">{{ trans('interface.restore') }}</a>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.new_indicator') !!}</label>
                                <div class="col-md-9">
                                    <textarea placeholder="{!! trans('interface.new_indicator') !!}"
                                              rows="3" class="form-control" id="indicator_name"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-2">
                                    <button type="button" id="addIndicator" class="btn btn-block btn-info" >{!! trans('interface.add') !!}</button>
                                </div>
                            </div>

                            @if($competence->type->prof)
                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-9 table-responsive">
                                    <table id="struct_table" class="table table-condensed">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('interface.org') }}</th>
                                            <th>{{ trans('interface.func') }}</th>
                                            <th>{{ trans('interface.position') }}</th>
                                            <th>{{ trans('interface.destroy') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 0?>
                                        @if($competence->positions()->count() > 0)
                                            @foreach($competence->positions as $struct)
                                                <?php
                                                $org = \App\Org::find($struct->pivot->org_id);
                                                $func = \App\Func::find($struct->pivot->func_id);
                                                $position = \App\Position::find($struct->pivot->position_id);
                                                ?>
                                                <tr>
                                                    <td>{{ $org->name ?: "" }}</td>
                                                    <td>{{ $func ? $func->name : "" }}</td>
                                                    <td>{{ $position->name ?: "" }}</td>
                                                    <td>
                                                        <a class="remove" href="#struct_table">{{ trans('interface.destroy') }}</a>
                                                        <input type="hidden" name="struct[{{ $i }}][org_id]" value="{{ $org->id }}" />
                                                        @if($func)<input type="hidden" name="struct[{{ $i }}][func_id]" value="{{ $func->id }}" />@endif
                                                        <input type="hidden" name="struct[{{ $i }}][position_id]" value="{{ $position->id }}" />
                                                    </td>
                                                </tr>
                                                <?php $i++ ?>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.org') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" id="org">
                                        <option value="0">{!! trans('interface.no_value') !!}</option>
                                        @foreach(\App\Org::all() as $org)
                                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.func') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" id="func">
                                        <option value="0">{!! trans('interface.no_value') !!}</option>
                                        @foreach(\App\Func::all() as $func)
                                            <option value="{{ $func->id }}">{{ $func->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.position') !!}</label>
                                <div class="col-md-9">
                                    <select class="form-control select2-single" id="position">
                                        <option value="0">{!! trans('interface.no_value') !!}</option>
                                        @foreach(\App\Position::all() as $position)
                                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-2">
                                    <button type="button" id="addStruct" class="btn btn-block btn-info" >{!! trans('interface.add') !!}</button>
                                </div>
                            </div>
                            @endif


                        </form>
                    </div>
                    <div class="panel-footer">
                        <div class="text-right">
                            <a href="#" onclick="$('#form_create_competence').submit();" >{!! trans('interface.update') !!}</a> |
                            <a href="{!! route('competence.show',['id'=>$competence->id]) !!}">{!! trans('interface.prev') !!}</a>
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

            $("#org,#func,#position").each(function(){
                var id = $(this).attr('id');

                $(this).select2({
                    theme: "bootstrap",
                    placeholder: '{!! trans('interface.select_position') !!}',
//                    allowClear: false,
                    language: '{!! config()->get('app.locale') !!}',
//                    minimumInputLength: 2,
                });
            });

            $('#addStruct').on('click',function(ev) {


                var func_val = $('#func').select2('data')[0].id;
                var org_val = $('#org').select2('data')[0].id;
                var position_val = $('#position').select2('data')[0].id;

                if(org_val > 0 && position_val > 0) {
                    cols++;
                    $('#struct_table tbody').append(
                        '<tr>' +
                        '<td>' + $('#org').select2('data')[0].text + '</td>' +
                        '<td>' + $('#func').select2('data')[0].text + '</td>' +
                        '<td>' + $('#position').select2('data')[0].text + '</td>' +
                        '<td>' +
                        '<a class="remove" href="#struct_table">{{ trans('interface.destroy') }}</a>' +
                        (func_val > 0 ? '<input type="hidden" name="struct[' + cols + '][func_id]" ' +
                            'value="' + func_val + '">' : "") +
                        '<input type="hidden" name="struct[' + cols + '][org_id]" value="' +
                        org_val + '">' +
                        '<input type="hidden" name="struct[' + cols + '][position_id]" value="' +
                        position_val + '">' +
                        '</td>' +
                        '</tr>'
                    );
                    $('#func').select2("val", 0);
                    $('#position').select2('val',0);
                    $('#org').select2('val',0);

                } else {
                    alert('Выберите должность и структуру подразделения.');
                }
            });

            $(document).on('click', 'a.remove', function(ev) {
                cols--;
                $(this).parent().parent().remove();
            });

            $('#org').on("select2:select", function(e) {
                $("#func").select2('open');
            });

            $('#func').on("select2:select", function(e) {
                $("#position").select2('open');
            });

            $('#position').on("select2:select", function(e) {
                $('#addStruct').trigger('click');
//                $("#org").select2('open');
            });

        });


        $('#addIndicator').on('click',function(ev) {
            ev.preventDefault();
            $('#indicators ol').append('<li><input type="hidden" name="indicator[][name]" value="' +
                $('#indicator_name').val() + '" />' + $('#indicator_name').val() +
                ' <a href="#" class="removeIndicator">{{ trans('interface.destroy') }}</a>' +
                '</li>');
            $('#indicator_name').val('');
        });

        $(document).on('click', '.removeIndicator', function(ev) {
            ev.preventDefault();
            if($(this).parent().find('[name$="[id]"]').length > 0) {
                $(this).parent().find('a').removeClass('removeIndicator');
                $(this).parent().find('a').addClass('restoreIndicator');
                $(this).parent().find('a').text('{{ trans('interface.restore') }}');
                $(this).parent().appendTo("#indicatorsDeleted ol");
                $(this).parent().find('[name^="indicator"]').attr('disabled',true);
            } else {
                $(this).parent().remove();
            }
        });


        $(document).on('click', '.restoreIndicator', function(ev) {
            ev.preventDefault();
            $(this).parent().find('a').removeClass('restoreIndicator');
            $(this).parent().find('a').addClass('removeIndicator');
            $(this).parent().find('a').text('{{ trans('interface.destroy') }}');
            $(this).parent().appendTo("#indicators ol");
            $(this).parent().find('[name^="indicator"]').removeAttr('disabled');
        });

    </script>

@endsection

