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
                            <label class="col-md-10">{!! trans('interface.funcs') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('func.create') !!}" >{!! trans('interface.create') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <form class="form" id="form_func_search" action="{!! route("func.index") !!}">

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="search" name="q" class="form-control" value="{!! request('q') !!}"
                                           placeholder="{!! trans('interface.search') !!}">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-default">{{ trans('interface.search') }}</button>
                                    </span>
                                </div><!-- /input-group -->
                            </div>

                        </form>

                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>№</th>
                                <th>{!! trans('interface.func') !!}</th>
                                <th>{!! trans('interface.edit') !!}</th>
                                <th>{!! trans('interface.destroy') !!}</th>
                                {{--<th>{!! trans('interface.edit') !!}</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($funcs as $func)
                                <tr>
                                    <td>{!! $func->id !!}</td>
                                    <td>
{{--                                        <a href="{!! route('func.show',['id' => $func->id]) !!}">--}}
                                            {!! $func->name !!}
                                        {{--</a>--}}
                                    </td>
                                    <td>
                                        <a href="{!! route('func.edit',['id'=>$func->id]) !!}">{!! trans('interface.edit') !!}</a>
                                    </td>
                                    <td>
                                        <a href="#" id="deleteFunc" class="delete">{!! trans('interface.destroy') !!}</a>
                                    </td>
                                    {{--                                        <td><a href="{!! route('func.edit',['id'=>$func->id]) !!}">{!! trans('interface.edit') !!}</a></td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $funcs->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="form_delete_func" action="" method="POST">
        {!! csrf_field() !!}
        {!! method_field("DELETE") !!}
    </form>
@endsection


@section('javascript')
    <script>
        $(function(){
            $(".delete").each(function() {
                $(this).click(function() {
                    if(confirm('{{ trans('interface.destroy') }}?')) {
                        $('#form_delete_func').attr('action', $(this).attr('action'));
                        $('#form_delete_func').submit();
                    }
                });
            });


        })
    </script>
@endsection


