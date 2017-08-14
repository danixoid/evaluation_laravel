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
                            <label class="col-md-10">{!! trans('interface.users') !!}</label>
                            <div class="col-md-2 text-right">
                                <a href="{!! route('user.create') !!}" >{!! trans('interface.create') !!}</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        
                        <form class="form" id="form_user_search" action="{!! route("user.index") !!}">

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="search" name="q" class="form-control" value="{!! request('q') !!}"
                                           placeholder="{!! trans('interface.search') !!}">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-default">Найти</button>
                                    </span>
                                </div><!-- /input-group -->
                            </div>

                        </form>
                        
                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>№</th>
                                <th>{!! trans('interface.name') !!}</th>
                                <th>{!! trans('interface.email_address') !!}</th>
                                <th>{!! trans('interface.iin') !!}</th>
                                <th>{!! trans('interface.roles') !!}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{!! $user->id !!}</td>
                                    <td><a href="{!! route('user.show',['id' => $user->id]) !!}">{!! $user->name !!}</a></td>
                                    <td><span class="text-info">{!! $user->email !!}</span></td>
                                    <td><span class="text-warning">{!! $user->iin !!}</span></td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="text-info">{!!
                                                trans('interface.' . $role->name) !!}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

