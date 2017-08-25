<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluaterCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluaterController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager',['except' => ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param EvaluaterCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluaterCreateRequest $request)
    {
        $data = $request->all();

        try {
            $evaluater = \App\Evaluater::create($data);
        } catch (\Exception $e) {
            $evaluater = null;
        }

        if(!$evaluater) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_evaluater')
                ]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('warning',trans('interface.failure_create_evaluater'));
        }


        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_evaluater')
            ]);
        }

        return redirect()
            ->back()
            ->with('message',trans('interface.success_create_evaluater'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evaluater = \App\Evaluater::find($id);

        if($evaluater->user_id == Auth::user()->id ||
            Auth::user()->hasAnyRole(['admin','manager']))
        {
            return view('evaluater.show',['me' => $evaluater,
                'evaluation' => $evaluater->evaluation]);
        }

        abort(404);
//        return ;
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
        $evaluater = \App\Evaluater::find($id);

        if(!$evaluater) {

            if(request()->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_deleted_evaluater')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_deleted_evaluater'));
        }

        $evaluater->delete();

        return redirect()
            ->back()
            ->with('message',trans('interface.success_deleted_evaluater'));

    }
}
