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
                    <div class="panel-heading">{!! trans('interface.quest') !!}</div>

                    <div class="panel-body form-horizontal">


                        <div class="form-group">
                            <label class="control-label col-md-2">{!! trans('interface.task') !!}</label>
                            <div class="col-md-10 form-control-static">
                                <iframe id="iframe" src="{!! route('quest.show',
                                    ['id'=>$quest->id,'type'=>'minimum']) !!}" onload="resizeIframe(this)"
                                        style="width:100%; background: #FFFFFF;"></iframe>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">{!! trans('interface.timer') !!}</label>
                            <div class="col-md-10 form-control-static">{!! $quest->timer !!} {!! trans('interface.minutes') !!}</div>
                        </div>


                        @if($quest->self()->count() > 0)
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10 table-responsive">
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
                                        @foreach($quest->self as $struct)

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
                        </div>
                        @endif

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-3">
                                <a href="{!! route('quest.edit',['id'=>$quest->id]) !!}" class="btn btn-block btn-info">{!! trans('interface.edit') !!}</a>
                            </div>

{{--                            @if(count($quest->tickets) == 0)--}}
                            <div class="col-md-2">
                                <a href="#" id="deleteQuest" class="btn btn-block btn-danger">{!!
                                 $quest->trashed() ? trans('interface.restore') : trans('interface.destroy')
                                 !!}</a>
                            </div>
                            {{--@endif--}}
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-9">
                                <small>
                                    {!! ($quest->created_at == $quest->updated_at)
                                    ? trans('interface.author') : trans('interface.editor') !!}: {!! $quest->author->name !!}

                                    {!! ($quest->created_at != $quest->updated_at)
                                    ? "<span class=\"text-success\">" . trans('interface.edited') . " " . $quest->updated_at . "</span>"
                                    : ""!!}
                                </small>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <form id="form_delete_quest" action="{!! route('quest.destroy',['id' => $quest->id]) !!}" method="POST">
        {!! csrf_field() !!}
        {!! method_field("DELETE") !!}
    </form>
@endsection

@section('javascript')
    <script>
        function resizeIframe(obj) {
            obj.style.height = (obj.contentWindow.document.body.scrollHeight + 20) + 'px';
        }

        $(function(){
            $("#deleteQuest").click(function() {
                $('#form_delete_quest').submit();
            });

        });
    </script>
@endsection
