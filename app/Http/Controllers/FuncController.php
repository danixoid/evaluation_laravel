<?php

namespace App\Http\Controllers;

use App\Http\Requests\FuncCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class FuncController extends Controller
{
    /**
     *  constructor.
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
        $funcs = \App\Func::paginate(15);

        if(request()->has('q')) {
            $funcs = \App\Func::where('name','LIKE', '%' . request('q'). '%')
                ->orderBy('created_at','desc')
                ->paginate(15);

            if(request()->ajax()) {
                return $funcs->toJson();
            }

        }

        return view('func.index',['funcs' => $funcs->appends(Input::except('page'))]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('func.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Controllers\Request|FuncCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FuncCreateRequest $request)
    {

        $data = $request->all();

        $func = \App\Func::create($data);

        if(!$func) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_func')
                ]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_create_func'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_func'),
                'func' => $func,
            ]);
        }

        return redirect()
            ->route('func.show',$func->id)
            ->with('message',trans('interface.success_create_func'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(request()->ajax()) {
            return response()
                ->json(\App\Func::with('parent','children')
                    ->find($id));
        }

        $func = \App\Func::find($id);
        return view('func.index',['func' => $func]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $func = \App\Func::find($id);

        return view('func.edit',['func' => $func]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Controllers\Request|FuncCreateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function update(FuncCreateRequest $request, $id)
    {
        $data = $request->all();

        $func = \App\Func::updateOrCreate(['id' => $id], $data);

        if(!$func) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_func')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_save_func'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_func'),
                'func' => $func,
            ]);
        }

        return redirect()->route('func.index')->with('message',trans('interface.success_save_func'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $func = \App\Func::find($id);

        if(!$func || count($func->children) > 0) {

            if(request()->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_deleted_func')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_deleted_func'));
        }

        foreach($func->positions as $position): $position->delete(); endforeach;

        $func->delete();

        return redirect()
            ->route('func.index')
            ->with('message',trans('interface.success_deleted_func'));
    }
}
