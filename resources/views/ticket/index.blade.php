@extends('layouts.app')

@section('content')
<div class="container">


    <div class="row form-group">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <?php $step = 0 ?>
                @foreach($ticket->exam->tickets as $tick)
                    <li class="{!! $ticket->id == $tick->id ? "active" : "disabled" !!}">
                        <a href="#step-{!! $step !!}">
                        <h4 class="list-group-item-heading">{!! trans('interface.quest_number',['num' => $step + 1]) !!}</h4>
                        <p class="list-group-item-text">{!! trans('interface.timer') !!}:
                            {!! $tick->quest->timer !!}
                            {!! trans('interface.minutes') !!} </p>
                        </a>
                    </li>
                    <?php $step++ ?>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-12 well setup-content " id="step-{!! $step !!}">

                <form id="form_update_ticket" class="form-horizontal" action="{!! route('ticket.update',['id' => $ticket->id]) !!}" method="POST">
                    {!! csrf_field() !!}
                    {!! method_field('PUT') !!}

                    <input type="hidden" name="finished_at" value="true" />

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-9">
                            <h3>{!! trans('interface.remaining_time',['time' => "<span id=\"timer\">" .
                                date('i:s',$ticket->quest->timer*60) ."</span>"]) !!}</h3>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">{!! trans('interface.task') !!}</label>
                        <div class="col-md-9 form-control-static">
                            <iframe id="iframe" src="{!! route('quest.show',
                                    ['id'=>$ticket->quest->id,'type'=>'minimum']) !!}" onload="resizeIframe(this)"
                                    style="width:100%; background: #FFFFFF;"></iframe>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-2 control-label">{!! trans('interface.answer') !!}</label>
                        <div class="col-md-9">
                            <textarea id="answer" placeholder="{!! trans('interface.type_text') !!}" rows="6" class="form-control" name="answer">{!! $ticket->answer !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-3 form-control-static">
                            <button id="activate-next-step" class="btn btn-primary btn-lg">{!! trans('interface.next') !!}</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
@endsection


@section('javascript')
    <script>
        function resizeIframe(obj) {
            obj.style.height = (obj.contentWindow.document.body.scrollHeight + 20) + 'px';
        }
        $(document).ready(function() {

            var times = {{strtotime($ticket->started_at) * 1000 }} +
                    {{ $ticket->quest->timer * 60 * 1000  }};
            var timerId = setInterval(function () {
                if(times === 0) {
                    clearInterval(timerId);
                }
                $curr_time = new Date(times - new Date());
                $("#timer").text($curr_time.getMinutes() + ":" +
                    ($curr_time.getSeconds() < 10 ? "0" : "") +
                    $curr_time.getSeconds())
            },100);

            var timeOut = setTimeout(function() {
                clearInterval(timerId);
                clearTimeout(timeOut);
                window.location.href = "{!! route('ticket.index',['exam_id' => $ticket->exam_id]) !!}";
            },{{strtotime($ticket->started_at) + $ticket->quest->timer * 60 }} * 1000 - new Date().getTime());

            $("#answer").on('keyup',function () {
                $.ajax({
                    type: "POST",
                    url: "{!! route('ticket.update',$ticket->id) !!}",
                    data: $("#form_update_ticket").serializeArray(),
                    dataType: "json",
                    success: function(msg){
                        console.log(msg.message);
                    }
                });
            });


        });


    </script>
@endsection