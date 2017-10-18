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
                            <label class="col-md-10">{!! trans('interface.competence_level') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="#" onclick="$('#form_level_update').submit();" >{!! trans('interface.update') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">

                        <form class="form" method="POST" id="form_level_update" action="{!! route("level.store") !!}">

                            {!! csrf_field() !!}

                            <table class="table table-condensed">
                                <thead>
                                <tr>
                                    <th>{!! trans('interface.level') !!}</th>
                                    <th>{!! trans('interface.minimum') !!}</th>
                                    <th>{!! trans('interface.maximum') !!}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($levels as $level)
                                    <tr>
                                        <td><input type="text" class="form-control"  value="{!! $level->level!!} - {!! $level->name !!}" disabled/></td>
                                        <td><input type="number" class="form-control" name="level[{{ $level->id }}][min]" value="{!! $level->min !!}" /></td>
                                        <td><input type="number" class="form-control" name="level[{{ $level->id }}][max]" value="{!! $level->max !!}" /></td>


                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="form_delete_level" action="" method="POST">
        {!! csrf_field() !!}
        {!! method_field("DELETE") !!}
    </form>
@endsection


@section('javascript')
    <script>
        $(leveltion(){
            $(".delete").each(leveltion() {
                $(this).click(leveltion() {
                    if(confirm('{{ trans('interface.destroy') }}?')) {
                        $('#form_delete_level').attr('action', $(this).attr('action'));
                        $('#form_delete_level').submit();
                    }
                });
            });


        })
    </script>
@endsection


