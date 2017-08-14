<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrgCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class OrgController extends Controller
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
        $orgs = \App\Org::paginate(15);

        if(request()->has('q')) {
            $orgs = \App\Org::where('name','LIKE', '%' . request('q'). '%')
                ->orderBy('created_at','desc')
                ->paginate(15);

            if(request()->ajax()) {
                return $orgs->toJson();
            }

        }

        return view('org.index',['orgs' => $orgs->appends(Input::except('page'))]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('org.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Controllers\Request|OrgCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrgCreateRequest $request)
    {

        $data = $request->all();

        $org = \App\Org::create($data);

        if(!$org) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_org')
                ]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_create_org'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_org'),
                'org' => $org,
            ]);
        }

        return redirect()
            ->route('org.show',$org->id)
            ->with('message',trans('interface.success_create_org'));

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
                ->json(\App\Org::with('parent','children')
                    ->find($id));
        }

        $org = \App\Org::find($id);
        return view('org.index',['org' => $org]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $org = \App\Org::find($id);

        return view('org.edit',['org' => $org]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Controllers\Request|OrgCreateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function update(OrgCreateRequest $request, $id)
    {
        $data = $request->all();

        $org = \App\Org::updateOrCreate(['id' => $id], $data);

        if(!$org) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_org')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_save_org'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_org'),
                'org' => $org,
            ]);
        }

        return redirect()->route('org.index')->with('message',trans('interface.success_save_org'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $org = \App\Org::find($id);

        if(!$org || count($org->children) > 0) {

            if(request()->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_deleted_org')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_deleted_org'));
        }

        foreach($org->positions as $position): $position->delete(); endforeach;

        $org->delete();

        return redirect()
            ->route('org.index')
            ->with('message',trans('interface.success_deleted_org'));
    }
}
