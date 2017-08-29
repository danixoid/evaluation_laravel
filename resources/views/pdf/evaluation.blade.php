<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 28.08.17
 * Time: 19:22
 */?>

@extends('layouts.pdf')

@section('meta')
{{--<link rel="stylesheet" href="{!! asset('css/rating.css') !!}" type="text/css" media="screen" title="Rating CSS">--}}
@endsection

@section('content')

    <style>
        body {
            /*font-family: sans-serif;*/
            font-size: 8pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            padding: 0px;
            border: 1px solid #999999;
            border-spacing: 10px;
            border-collapse: separate;

        }
    </style>
    <div>
        <h3 style="clear:both; text-align: center;">{{ trans('interface.evaluation_personal') }} {{ $evaluation->type->name }} № {{ $evaluation->id }}</h3>
        <div>
            <div style="float:right;">
                <p><strong>{{ trans('interface.started_date') }}</strong>:</p>
                <p>{{ date('d.m.Yг.',strtotime($evaluation->started_at)) }}</p>
            </div>
            <div>
                <p><strong>{{ trans('interface.org') }}</strong>: {{ $evaluation->org->name }}</p>
                @if($evaluation->func)
                    <p><strong>{{ trans('interface.func') }}</strong>: {{  $evaluation->func->name }}</p>
                @endif
                <p><strong>{{ trans('interface.position') }}</strong>: {{ $evaluation->position->name }}</p>
                <p><strong>{{ trans('interface.evaluated') }}</strong>: {{ $evaluation->evaluated->name }}</p>
            </div>

            <div style="clear:both"></div>
        </div>
    </div>

    @include('evaluation.reports')

@endsection