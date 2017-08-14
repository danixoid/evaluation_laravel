<?php
/**
 * Created by PhpStorm.
 * User: danixoid
 * Date: 22.06.17
 * Time: 15:42
 */?>
@extends('layouts.app')

@section('content')
    <style>
        @media screen and (min-width: 768px) {
            .wide {
                width: 70%; /* either % (e.g. 60%) or px (400px) */
            }
        }

        /*.bmd-modalButton {*/
        /*display: block;*/
        /*margin: 15px auto;*/
        /*padding: 5px 15px;*/
        /*}*/

        .close-button {
            overflow: hidden;
        }

        .bmd-modalContent {
            box-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .bmd-modalContent .close {
            font-size: 30px;
            line-height: 30px;
            padding: 7px 4px 7px 13px;
            text-shadow: none;
            opacity: .7;
            color:#fff;
        }

        .bmd-modalContent .close span {
            display: block;
        }

        .bmd-modalContent .close:hover,
        .bmd-modalContent .close:focus {
            opacity: 1;
            outline: none;
        }

        .bmd-modalContent iframe {
            display: block;
            margin: 0 auto;
        }
    </style>

    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <label class="col-md-10">
                        <strong>{!! $exam->user->name !!}</strong>,
                        <a href="{!! route('exam.index',['position_id' => $exam->position_id]) !!}">
                            <span class="text-primary">{!! $exam->position->name !!}</span>
                        </a>
                    </label>
                    <div class="col-md-2 text-right">
                        <a target="_blank" href="{!! route('exam.show',['id'=>$exam->id,'type' => "pdf"]) !!}">
                            {{ trans('interface.print_to_pdf') }}
                        </a>
                    </div>
                </div>

            </div>

            <div class="panel-body">

                <div class="form-group">
                    <h3 class="text-{!! $exam->color !!}">
                        {!! trans('interface.'.$exam->status) !!}</h3>
                </div>

                @if(!$exam->isUser || $exam->finished)

                    <?php $inc_tic = 1 ?>
                    @foreach($exam->tickets as $ticket)
                    <div class="form-group">
                        <label class="text-primary">{!! trans('interface.quest') !!}
                            @if(\Auth::user()->hasAnyRole(['manager','admin']))
                                №{!! $ticket->quest->id !!}
                            @else
                                {{ $inc_tic++ }}
                            @endif</label>
                        <iframe id="iframe" src="{!! route('ticket.show', ['id'=>$ticket->id,'type'=>'minimum']) !!}"
                            onload="resizeIframe(this)" style="width:100%; background: #FFFFFF;"></iframe>
                    </div>


                    @if($exam->finished && !$exam->amISigner && $exam->isChief)

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary bmd-modalButton" data-toggle="modal"
                                data-target="#chief_form{{ $ticket->id }}">{{ trans('interface.mark') }}</button>
                    </div>
                    <div class="modal fade" id="chief_form{{ $ticket->id }}">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-body">
                                    <div class="close-button">
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>

                                    <div class="embed-responsive embed-responsive-16by9">
                                        <h2 class="form-signin-heading">{!! trans('interface.mark') !!}</h2>

                                        <form class="form" id="form_chief_update{{ $ticket->id }}"
                                              action="{!! route('ticket.update',$ticket->id) !!}" method="POST">
                                            {!! csrf_field() !!}
                                            {!! method_field('PUT') !!}

                                            <div class="form-group">
                                                <div class="my_mark">
                                                        <label>
                                                            <input type="radio" name="mark" class="rating" value="1"
                                                               @if($ticket->mark == 1) checked @endif
                                                               title="{{ trans('interface.bad') }}" />
                                                            {{ trans('interface.bad') }}</label>
                                                        <label>
                                                            <input type="radio" name="mark" class="rating" value="2"
                                                               @if($ticket->mark == 2) checked @endif
                                                               title="{{ trans('interface.satisfy') }}" />
                                                            {{ trans('interface.satisfy') }}</label>
                                                        <label>
                                                            <input type="radio" name="mark" class="rating" value="3"
                                                               @if($ticket->mark == 3) checked @endif
                                                               title="{{ trans('interface.good') }}" />
                                                            {{ trans('interface.good') }}</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-primary">{!! trans('interface.note') !!}</label>
                                                <textarea rows="5" class="form-control" name="note"
                                                          placeholder="{{ trans('interface.type_text') }}">{!! $ticket->note !!}</textarea>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">{{ trans('interface.save') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    @elseif(!$exam->isUser && $ticket->mark)
                        <div class="form-group">
                            <label class="text-primary">{!! trans('interface.mark') !!}</label>
                            <div>
                                @if($ticket->mark == 1){{ trans('interface.bad') }}@endif
                                @if($ticket->mark == 2){{ trans('interface.satisfy') }}@endif
                                @if($ticket->mark == 3){{ trans('interface.good') }}@endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="text-primary">{!! trans('interface.note') !!}</label>
                            <div>{{ $ticket->note }}</div>
                        </div>
                    @endif
                    @endforeach
                @endif

                <div class="form-group">
                    <div class="row">
                        @if(\Auth::user()->hasAnyRole(['manager','admin']))
                        <div class="col-md-2">
                            <a href="{!! route('exam.edit',['id'=>$exam->id]) !!}" class="btn btn-block btn-info">{!! trans('interface.edit') !!}</a>
                        </div>

                            @if(!$exam->started)
                            <div class="col-md-2">
                                <a href="#" id="deleteExam" class="delete btn btn-block btn-danger">{!! trans('interface.destroy') !!}</a>
                            </div>
                            @endif
                        @endif

                        @if(!$exam->finished && $exam->isUser)
                        <div class="col-md-2">
                            <a href="{!! route('ticket.index',['exam_id' => $exam->id]) !!}" class="delete btn btn-block btn-warning">{!! trans('interface.start') !!}</a>
                        </div>
                        @elseif(
                            $exam->finished && !$exam->amISigner &&
                            (($exam->isChief && !$exam->chiefHasNoteMark) || $exam->isUser)
                        )

                        <div class="col-md-2">
                            <a href="#signing_modal" class="btn btn-info btn-lg bmd-modalButton" data-toggle="modal"
                               data-bmdSrc="{!! route('signing.data',$exam->id) !!}"
                               data-bmdWidth="600" data-bmdHeight="550" data-target="#signing_modal">{!! trans('interface.eds_signing') !!}</a>
                            {{--<a href="{!! route('signing.data',$exam->id) !!}" id="signing" class="btn btn-primary"></a>--}}
                        </div>
                        @endif
                    </div>
                </div>

                @if(count($exam->signs) > 0)
                <div class="form-group">
                    <label class="text-primary">{!! trans('interface.signers') !!}</label>
                    <div class="form-control-static row">
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
                            <div class="col-md-6">
                                <div class="well">
                                    <strong>
                                    @if($sign->signer_id == $exam->chief_id)
                                        {!! trans('interface.chief') !!}
                                    @else
                                        {!! trans('interface.employee') !!}
                                    @endif
                                    </strong>
                                    <p>{!! $sign->signer->name !!}</p>
                                    <p>{!! trans('interface.signer') !!}: {!! $pub_key['subject']['CN'] !!}</p>
                                    <p>{!! trans('interface.iin') !!}: {!! mb_ereg_replace("^(I|B)IN","",$pub_key['subject']['serialNumber']) !!}</p>
                                    <p>
                                        <a download="signature.p7b" href=
                                           "data:application/octet-stream;charset=utf-8;base64,{!!
                                           $root->xpath('//ds:Signature/ds:SignatureValue')[0]
                                           !!}">{!! trans('interface.sign') !!}</a>
                                    </p>
                                    <p>
                                        <a href="{!! route('signed.xml',[
                                            'id' => $sign->id,'type' => 'pdf'
                                         ]) !!}" target="_blank">{!! trans('interface.print_to_pdf') !!}</a>
                                    </p>
                                    <p>
                                        <a download="public_key.cer" href=
                                           "data:application/octet-stream;charset=utf-8;base64,{!!
                                           $root->xpath('//ds:Signature/ds:KeyInfo/ds:X509Data/ds:X509Certificate')[0]
                                           !!}">{!! trans('interface.public_key') !!}</a>
                                    </p>
                                    <p>
                                        <a download="signature.xml" href="{!! route('signed.xml',$sign->id) !!}">{!! trans('interface.xml_file') !!}</a>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>


    </div>

    <form id="form_delete_exam" action="{!! route('exam.destroy',['id' => $exam->id]) !!}" method="POST">
        {!! csrf_field() !!}
        {!! method_field("DELETE") !!}
    </form>


    <div class="modal fade" id="signing_modal">
        <div class="modal-dialog wide">
            <div class="modal-content bmd-modalContent">

                <div class="modal-body">

                    <div class="close-button">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="embed-responsive embed-responsive-16by9">
                        <h2 class="form-signin-heading">{!! trans('interface.xml_signing') !!}</h2>
                        <iframe class="embed-responsive-item" frameborder="0"></iframe>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('meta')
{{--    <link rel="stylesheet" href="{!! asset('css/rating.css') !!}" type="text/css" media="screen" title="Rating CSS">--}}
@endsection


@section('javascript')
    {{--<script type="text/javascript" src="{!! asset('js/rating.js') !!}"></script>--}}
    <script>

        function resizeIframe(obj) {
            obj.style.height = (obj.contentWindow.document.body.scrollHeight + 20) + 'px';
        }

        $(function(){
            $("#deleteExam").click(function() {
                $('#form_delete_exam').submit();
            });

//            $('.my_mark').rating();

            @foreach($exam->tickets as $ticket)
            $('#chief_form{{ $ticket->id }} .form').submit(function (ev) {
                ev.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{!! route('ticket.update',$ticket->id) !!}",
                    data: $(this).serializeArray(),
                    dataType: "json",
                    success: function(msg){
                        $("#chief_form{{ $ticket->id }}").modal('hide');
//                        window.location.href = "";
//                        alert(msg.message);
                        console.log(msg.message);
                    },
                    error: function(msg){
                        alert("Все поля обязательны для заполнения");
                    },
                });
                return false;
            });
            @endforeach
        });

        (function($) {

            $.fn.bmdIframe = function( options ) {
                var self = this;
                var settings = $.extend({
                    classBtn: '.bmd-modalButton',
                    defaultW: 640,
                    defaultH: 360
                }, options );

                $(settings.classBtn).on('click', function(e) {
                    var allowFullscreen = $(this).attr('data-bmdVideoFullscreen') || false;

                    var dataVideo = {
                        'src': $(this).attr('data-bmdSrc'),
                        'height': $(this).attr('data-bmdHeight') || settings.defaultH,
                        'width': $(this).attr('data-bmdWidth') || settings.defaultW
                    };

                    if ( allowFullscreen ) dataVideo.allowfullscreen = "";

                    // stampiamo i nostri dati nell'iframe
                    $(self).find("iframe").attr(dataVideo);
                });

                // se si chiude la modale resettiamo i dati dell'iframe per impedire ad un video di continuare a riprodursi anche quando la modale è chiusa
                this.on('hidden.bs.modal', function(){
                    $(this).find('iframe').html("").attr("src", "");
                });

                return this;
            };

        })(jQuery);




        jQuery(document).ready(function(){
            jQuery("#signing_modal").bmdIframe();
        });
    </script>
@endsection
