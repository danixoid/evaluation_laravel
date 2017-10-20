<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvalProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

//        dd($data);
        $process = null;
//        try {

            foreach ($data['process'] as $id => $_data)
            {
                $evaluation = \App\EvalProcess::find($id)->evaluater->evaluation;
                if($evaluation->finished_at > \Carbon\Carbon::now() &&
                        $_data['eval_level_id'] > 0) {
                    $process = \App\EvalProcess::updateOrCreate(['id' => $id], $_data);
                }

            }/*
        } catch (\Exception $e) {
            $evalProcesses = null;
        }*/

        if(!$process) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_eval_process')
                ]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('warning',trans('interface.failure_create_eval_process'));
        }


        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_eval_process')
            ]);
        }

        if($process->evaluater->finished) {
            return redirect()
                ->route('index')
                ->with('message', trans('interface.success_create_eval_process'));
        }

        return redirect()
            ->back()
            ->with('message', trans('interface.success_create_eval_process'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
