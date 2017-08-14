<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * TicketController constructor.
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager',['except' => ['index','show','update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!request()->has('exam_id')) {
            return redirect()->back()->with('warning',trans('interface.error'));
        }

        $tickets = \App\Ticket::whereExamId(request('exam_id'))->get();

        foreach ($tickets as $ticket) :

            if(!$ticket->finished_at && (!$ticket->started_at || $ticket->quest->timer * 60 >
                (strtotime(\Carbon\Carbon::now()->toTimeString()) - strtotime($ticket->started_at)))) {

                return redirect()->route('ticket.show',$ticket->id);
                break;
            }
        endforeach;

        return redirect()
            ->to('/')
            ->with('warning',trans('interface.exam_finished'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = \App\Ticket::find($id);

        if(\request()->has('type') && \request('type') == 'minimum')
        {
            return view('layouts.clear',['content' =>
                "<h4>" . trans('interface.quest') . ":</h4> " .
                $ticket->quest->task . "<hr />" .
                    "<strong>" . trans("interface.answer") .
                    ":</strong> " . ($ticket->answer ?: trans("interface.not_found"))
            ]);
        }

        if($ticket->exam->user_id != Auth::user()->id || $ticket->finished)
        {
            return view('ticket.show',['ticket' => $ticket]);
//            abort(403);
        }

        if(!$ticket->started_at) {
            $ticket->started_at = \Carbon\Carbon::now();
            $ticket->save();
        }

        if($ticket->quest->timer * 60 <
                (strtotime(\Carbon\Carbon::now()->toTimeString())
                    - strtotime($ticket->started_at))) {

            if(!$ticket->finished_at) {
                $ticket->finished_at = $ticket->updated_at;
                $ticket->save();
            }

            return redirect()
                ->to('/')
                ->with('warning',trans('interface.time_is_up'));
        }

        return view('ticket.index',['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TicketUpdateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(TicketUpdateRequest $request, $id)
    {
        $data = $request->all();

//        dd($data);
        $ticket = \App\Ticket::find($id);

        if(Auth::user()->id == $ticket->exam->user_id) {

            if ($ticket->started_at && $ticket->quest->timer * 60 <
                (\Carbon\Carbon::now()->toTimeString() - strtotime($ticket->started_at))
            ) {

                $ticket->finished_at = $ticket->updated_at;
                $ticket->save();

                return redirect()
                    ->to('/')
                    ->with('warning', trans('interface.time_is_up'));
            }

            if (isset($data['finished_at'])) {
                $data['finished_at'] = \Carbon\Carbon::now();
            }

            if (isset($data['started_at'])) {
                $data['finished_at'] = \Carbon\Carbon::now();
            }
        }

        $ticket = \App\Ticket::updateOrCreate(['id' => $id], $data);

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_ticket'),
                'ticket' => $ticket,
            ]);
        }

        if(Auth::user()->id != $ticket->exam->user_id) {
            return redirect()
                ->route('exam.show',$ticket->exam_id)
                ->with('message',trans('interface.success_save_ticket'));
        }

        return redirect()
            ->route('ticket.index',['exam_id' => $ticket->exam_id])
//            ->with('message',trans('interface.success_save_ticket'))
            ;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
