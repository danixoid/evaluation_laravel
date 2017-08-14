<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 01.07.17
 * Time: 21:42
 */?>
@extends('layouts.pdf')

@section('meta')
    <link rel="stylesheet" href="{!! asset('css/rating.css') !!}" type="text/css" media="screen" title="Rating CSS">
@endsection

@section('content')
    <style>
        body {
            /*font-family: sans-serif;*/
            font-size: 10pt;
        }
    </style>
    <div>
        <h3 style="clear:both; text-align: center;">{{ trans('interface.exam') }} № {{ $exam->id }}</h3>
        <div>
            <div style="float:right;">
                <p><strong>{{ trans('interface.started_date') }}</strong>:</p>
                <p>{{ date('d.m.Y',strtotime($exam->finishedDate)) }}г.</p>
            </div>
            <div>
                <p><strong>{{ trans('interface.org') }}</strong>: {{ $exam->org->name }}</p>
                <p><strong>{{ trans('interface.func') }}</strong>: {{ $exam->func ? $exam->func->name : "-"}}</p>
                <p><strong>{{ trans('interface.position') }}</strong>: {{ $exam->position->name }}</p>
                <p><strong>{{ trans('interface.user') }}</strong>: {{ $exam->user->name }}</p>
                <p><strong>{{ trans('interface.chief') }}</strong>: {{ $exam->chief->name }}</p>
            </div>

            <div style="clear:both"></div>
        </div>

        <?php $inc = 1; ?>
        @foreach($exam->tickets as $ticket)

            <fieldset>
            <p><strong>{!! trans('interface.quest_number',['num' => $inc ]) !!}</strong></p>
            <p>{!!  preg_replace('/\s?(face|style|lang)="[^\"]+"/','',$ticket->quest->task) !!}</p>
{{--            <p>{!! $ticket->quest->task !!}</p>--}}
            <p><strong>{{ trans('interface.answer') }}</strong></p>
            <p>{{ $ticket->answer }}</p>
            <p><strong>{{ trans('interface.mark') }}</strong>:
                {{--@for($i = 0; $i < 3; $i++)
                <img src="@if($ticket->mark > $i){!! asset('images/star.png') !!}@else{!! asset('images/star-empty.png') !!}@endif"></a>
                @endfor--}}
                <ul>
                    <li>@if($ticket->mark == 1)<strong>{{ trans('interface.bad') }}</strong>@else{{ trans('interface.bad') }}@endif</li>
                    <li>@if($ticket->mark == 2)<strong>{{ trans('interface.satisfy') }}</strong>@else{{ trans('interface.satisfy') }}@endif</li>
                    <li>@if($ticket->mark == 3)<strong>{{ trans('interface.good') }}</strong>@else{{ trans('interface.good') }}@endif</li>

                </ul>
            </p>
            <p><strong>{{ trans('interface.note') }}</strong>: {{ $ticket->note }}</p>
            </fieldset>

            <?php $inc++; ?>
        @endforeach
        <br />
        @if(count($exam->signs) > 0)
            <div><strong>{!! trans('interface.signers') !!}</strong>:</div>
            <br />
            <div>
                @foreach($exam->signs as $sign)
                    <?php
                    $root = simplexml_load_string($sign->xml);
                    $errors = libxml_get_errors();
                    $root->registerXPathNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
                    $pub = ("-----BEGIN CERTIFICATE-----".
                        $root->xpath('//ds:Signature/ds:KeyInfo/ds:X509Data/ds:X509Certificate')[0]->__toString()
                        ."-----END CERTIFICATE-----");
                    $pub_key = openssl_x509_parse(openssl_x509_read($pub));
                    ?>
                    <div style="border: 1px solid #555555; margin-right: 20px; padding:10px 20px;float: left;">
                        <strong>
                            @if($sign->signer_id == $exam->chief_id)
                                {!! trans('interface.chief') !!}
                            @else
                                {!! trans('interface.user') !!}
                            @endif
                        </strong>
                        <p>{!! $sign->signer->name !!}</p>
                        <p>{!! trans('interface.signer') !!}: {!! $pub_key['subject']['CN'] !!}</p>
                        <p>{!! trans('interface.iin') !!}:
                            {!! mb_ereg_replace("^(I|B)IN","",$pub_key['subject']['serialNumber']) !!}</p>
                        <p>{!! trans('interface.sign') !!}:</p>
                        <img src="data:image/png;base64,
                            {!! base64_encode(\QrCode::format('png')
                                ->size(150)
                                ->generate($root->xpath('//ds:Signature/ds:SignatureValue')[0]))  !!}"/>
                    </div>
                @endforeach
{{--

                <div style="clear:both;">

                    <img src="data:image/png;base64,
                            {!! base64_encode(\QrCode::format('png')
                                ->size(150)
                                ->generate(route('exam.show',$root->xpath('//ds:Signature/ds:SignatureValue')[0])))  !!}"/>
                </div>
--}}

            </div>
        @endif
    </div>
@endsection