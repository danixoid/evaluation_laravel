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
                    <div class="panel-heading">{!! trans('interface.quest') !!} | {!! trans('interface.edit') !!}</div>

                    <div class="panel-body">

                        <form id="form_create_quest" class="form-horizontal" action="{!! route('quest.update',['id' => $quest->id]) !!}" method="POST">
                            {!! csrf_field() !!}
                            {!! method_field("PUT")  !!}


                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.task') !!}</label>
                                <div class="col-md-9">
                                    <textarea id="task" rows="6" class="form-control" name="task">{!! $quest->task !!}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">{!! trans('interface.timer') !!}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="timer" value="{!! $quest->timer !!}"/>
                                </div>
                            </div>




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
                                        @if($quest->self()->count() > 0)
                                            @foreach($quest->self as $struct)
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
                                    <select class="form-control select2-single" id="func">
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
                                    <select class="form-control select2-single" id="position">
                                        <option value="{!! old('position_id') ?: 0 !!}">{!! (old('position_id'))
                                            ? \App\Position::find(old('position_id'))->name
                                            : trans('interface.no_value') !!}</option>
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

                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-3">
                                    <button class="btn btn-block btn-danger" >{!! trans('interface.update') !!}</button>
                                </div>
                                <div class=" col-md-3">
                                    <a href="{!! route('quest.show',['id'=>$quest->id]) !!}" class="btn btn-block btn-primary">{!! trans('interface.prev') !!}</a>
                                </div>

                            </div>


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


        tinymce.init({
            selector: 'textarea',  // change this value according to your HTML
            language: '{!! config('app.locale') == "kz" ? "kk" : config('app.locale')!!}',
            menubar : false,
            plugins: [
                "link image code fullscreen imageupload table"
            ],
            toolbar: "undo redo | bold italic | bullist numlist outdent indent | link image " +
                "| imageupload | code | fullscreen  | table",
            relative_urls: false,
            image_list: "{!! route('images.list') !!}",
            table_advtab: true,


        });
    </script>

@endsection

