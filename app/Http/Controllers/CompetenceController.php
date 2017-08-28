<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompetenceCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class CompetenceController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager'/*,['except' => ['index','show']]*/);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!\request()->has('type'))
        {
            return abort(404);
        }
        $org_id = request('org_id');
        $func_id = request('func_id');
        $position_id = request('position_id');
        $text = request('text');
        $trashed = request('trashed');


        $type = \App\CompetenceType::find(request('type'));
        $query = $type->competences();

        if($trashed &&  $trashed > 0)
        {
            $query = $query->onlyTrashed();
        }

        if($type->prof)
        {
            if($org_id &&  $org_id > 0)
            {
                $query = $query->whereHas('positions',function($q) use ($org_id) {
                    return $q->whereOrgId($org_id);
                });
            }

            if($func_id &&  $func_id > 0)
            {
                $query = $query->whereHas('positions',function($q) use ($func_id) {
                    return $q->whereFuncId($func_id);
                });
            }

            if($position_id &&  $position_id > 0)
            {
                $query = $query->whereHas('positions',function($q) use ($position_id) {
                    return $q->wherePositionId($position_id);
                });
            }
        }

        if($text) {
            $query = $query
                ->where(function($q) {
                    return $q
                        ->where('name', 'LIKE', '%' . \request('text') . '%')
                        ->orWhere('note', 'LIKE', '%' . \request('text') . '%');
                });
        }

        $competence = $query->whereCompetenceTypeId($type->id)->paginate(20);

        if(request()->ajax()) {
            return response()->json(['competences' => $competence]);
        }

        return view('competence.index',['competences' => $competence->appends(Input::except('page')),'type' => $type]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!\request()->has('type'))
        {
            return abort(404);
        }

        $type = \App\CompetenceType::find(\request('type'));
        return view('competence.create',['type' => $type]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CompetenceCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompetenceCreateRequest $request)
    {
        $data = $request->all();
        $data['author_id'] = auth()->user()->id;
//        dd($request->all());
        $competence = \App\Competence::create($data);

        if(!$competence) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_competence')
                ]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('warning',trans('interface.failure_create_competence'));
        }

        if(isset($data['indicator']) && is_array($data['indicator'])) {
            $indicators = $data['indicator'];

            foreach ($indicators as $key => $val) {
                $indicator = \App\Indicator::firstOrNew([
                    'competence_id' => $competence->id,
                    'eval_level_id' => $key,
                ]);
                $indicator->name = $val;
                $indicator->save();
            }
        }

        if(isset($data['struct']) && is_array($data['struct'])) {
            $struct = array_unique($data['struct'],SORT_REGULAR);

            foreach ($struct as $items) {
                $competence->positions()->attach($competence,$items);
            }
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_competence'),
                'competence' => $competence,
            ]);
        }

        return redirect()
            ->route('competence.show',$competence->id)
            ->with('message',trans('interface.success_create_competence'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $competence = \App\Competence::withTrashed()->find($id);

        if(request()->ajax()) {
            return response()->json($competence);
        }

        return view('competence.show',['competence' => $competence]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $competence = \App\Competence::withTrashed()->find($id);
        return view('competence.edit',['competence' => $competence]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CompetenceCreateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompetenceCreateRequest $request, $id)
    {
//        dd($request->all());
        $data = $request->all();

        $competence = \App\Competence::updateOrCreate(['id' => $id], $data);

        if(!$competence) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_competence')]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('warning',trans('interface.failure_save_competence'));
        }

        if(isset($data['indicator']) && is_array($data['indicator'])) {
            $indicators = $data['indicator'];

            foreach ($indicators as $key => $val) {
                $indicator = \App\Indicator::updateOrCreate(['id' => $key], ['name' => $val]);
            }
        }

        if(isset($data['struct']) && is_array($data['struct'])) {
            $competence
                ->positions()
                ->detach();

            $struct = array_unique($data['struct'],SORT_REGULAR);

            foreach ($struct as $items) {
                $competence->positions()->attach($competence,$items);
            }
        }


        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_competence'),
                'competence' => $competence,
            ]);
        }

        return redirect()
            ->route('competence.show',$id)
            ->with('message',trans('interface.success_save_competence'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $competence = \App\Competence::withTrashed()->find($id);

        if($competence->trashed())
        {
            $competence->restore();

            return redirect()
                ->back()
                ->with('message',trans('interface.success_restored_competence'));
        }

        if(!$competence) {

            if(request()->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_deleted_competence')]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('warning',trans('interface.failure_deleted_competence'));
        }

//        if(count($competence->tickets) == 0) {
        $competence->delete();
//        }

        return redirect()
            ->back()
            ->with('message',trans('interface.success_deleted_competence'));

    }
}
