<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluationCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $org_id = request('org_id');
        $func_id = request('func_id');
        $position_id = request('position_id');

        $query = \App\Evaluation::whereNotNull('created_at');

        if (!Auth::user()->hasAnyRole(['admin','manager']))
        {
            $query = $query->whereHas('evaluaters',function($q) {
                return $q->whereUserId(Auth::user()->id);
            });
        }


        if($org_id &&  $org_id > 0)
        {
            $query = $query->whereOrgId($org_id);
        }

        if($func_id &&  $func_id > 0)
        {
            $query = $query->whereFuncId($func_id);
        }

        if($position_id &&  $position_id > 0) {
            $query = $query->wherePositionId($position_id);
        }

        $evaluations = $query->paginate(15);

        if(request()->ajax()) {
            return response()->json(['evaluations' => $evaluations]);
        }

        return view('evaluation.index',['evaluations' => $evaluations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('evaluation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EvaluationCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluationCreateRequest $request)
    {
        $data = $request->all();

        $evaluation = \App\Evaluation::create($data);

        if(!$evaluation) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_evaluation')
                ]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('warning',trans('interface.failure_create_evaluation'));
        }


        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_evaluation')
            ]);
        }

        return redirect()
            ->route('evaluation.edit',$evaluation->id)
            ->with('message',trans('interface.success_create_evaluation'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evaluation = \App\Evaluation::find($id);
        return view('evaluation.show',['evaluation' => $evaluation]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $evaluation = \App\Evaluation::find($id);

        if($evaluation->started)
        {
            abort(404);
        }

        return view('evaluation.edit',['evaluation' => $evaluation]);
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
        $evaluation = \App\Evaluation::find($id);

        if($evaluation->started)
        {
            abort(404);
        }

        if(!$evaluation || !$evaluation->enough) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_evaluation')]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('warning',trans('interface.failure_save_evaluation'));
        }

        $evaluation->started_at = \Carbon\Carbon::now();
        $evaluation->save();

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_evaluation'),
                'evaluation' => $evaluation,
            ]);
        }

        return redirect()
            ->route('evaluation.show',$id)
            ->with('message',trans('interface.success_save_evaluation'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evaluation = \App\Evaluation::find($id);

        if(!$evaluation) {

            if(request()->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_deleted_evaluation')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_deleted_evaluation'));
        }

        $evaluation->delete();

        return redirect()
            ->route('evaluation.index')
            ->with('message',trans('interface.success_deleted_evaluation'));

    }
}
