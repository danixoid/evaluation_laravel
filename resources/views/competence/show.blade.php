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
                    <div class="panel-heading">{!! $competence->type->note !!}: {{ $competence->name }}</div>

                    <div class="panel-body form-horizontal">

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10 form-control-static">{!! $competence->note !!}</div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10 form-control-static">
                                <ul class="list-group list-unstyled">
                                @foreach($competence->indicators as $indicator)
                                    <li>
                                        <strong>{{ $indicator->evalLevel->name }}</strong><br />
                                        {{ $indicator->name }}
                                    </li>
                                @endforeach
                                </ul>
                            </div>
                        </div>

                        @if($competence->positions()->count() > 0)
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
                            </div>
                        @endif

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-3">
                                <a href="{!! route('competence.edit',['id'=>$competence->id]) !!}" class="btn btn-block btn-info">{!! trans('interface.edit') !!}</a>
                            </div>

                            <div class="col-md-3">
                                <a href="#" id="deleteQuest" class="btn btn-block btn-danger">{!!
                                 $competence->trashed() ? trans('interface.restore') : trans('interface.to_archive')
                                 !!}</a>

                            </div>
                            <div class=" col-md-3">
                                <a href="{!! route('competence.index',['type'=>$competence->type->id]) !!}" class="btn btn-block btn-warning">{!! trans('interface.prev') !!}</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <form id="form_delete_competence" action="{!! route('competence.destroy',['id' => $competence->id]) !!}" method="POST">
        {!! csrf_field() !!}
        {!! method_field("DELETE") !!}
    </form>
@endsection
