<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluationCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

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
        $begin_at = \request('begin_at');
        $end_at = \request('end_at');

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

        if($begin_at) {
            $query = $query->where('started_at','>',$begin_at);
        }

        if($end_at) {
            $query = $query->where('started_at','<',$end_at);
        }

        $evaluations = $query->paginate(15);

        if(request()->ajax()) {
            return response()->json(['evaluations' => $evaluations]);
        }

        return view('evaluation.index',['evaluations' => $evaluations->appends(Input::except('page'))]);
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
        set_time_limit(60);

        if($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();

//            dd($data);
            if(!empty($data)) {

                $i = 0;
                foreach ($data->toArray() as $key => $value) {
                    if (!empty($value)) {
                        foreach ($value as $v) {

                            $org = $v['strukturnoe_podrazdelenie']
                                ? \App\Org::where('name','LIKE',$v['strukturnoe_podrazdelenie'])->first()
                                : null;
                            $func = $v['funtsionalnoe_napravlenie']
                                ? \App\Func::where('name','LIKE',$v['funtsionalnoe_napravlenie'])->first()
                                : null;
                            $position = $v['dolzhnost']
                                ? \App\Position::where('name','LIKE',$v['dolzhnost'])->first()
                                : null;

                            $evalType = \App\EvalType::where('name','LIKE', $v['vid_otsenki'].'%' ?: '180%')
                                ->first();

//                            dd([$org,$position]);

                            $role = \App\Role::whereName('employee')->firstOrFail();


                            $user = \App\User::whereIin($v['iin'])->first();
                            if(!$user) {
                                $user = new \App\User();
                                $user->iin = $v['iin'];
                                $user->email = $v['el.adres'];
                                $user->password = bcrypt('12345');
                            }
                            $user->name = $v['fio'];
                            $user->save();

                            $user->roles()->detach();
                            $user->roles()->attach($role);

                            if( !$user || !$org || !$position)
                            {
                                continue;
                            }

                            $arr = [
                                'org_id' => $org->id,
                                'position_id' => $position->id
                            ];

                            if ($func) {
                                $arr['func_id'] = $func->id;
                            }

                            $evaluation = new \App\Evaluation();
                            $evaluation->user_id = $user->id;
                            $evaluation->org_id = $org->id;
                            $evaluation->position_id = $position->id;
                            $evaluation->eval_type_id = $evalType->id;

                            if ($func)
                            {
                                $evaluation->func = $org;
                            }

                            $evaluation->save();

                            $i++;
                        }
                    }
                }

                return redirect()
                    ->route('evaluation.index')
                    ->with('success',trans('interface.imported_file',['num' => $i]));

            }

            return redirect()
                ->route('evaluation.index')
                ->with('warning',trans('interface.failure_create_evaluation'));

        }

        $data = $request->all();

        if($data['func_id'] == 0 || !$data['func_id']) {
            unset($data['func_id']);
        }

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

        if(\request()->has('type') && \request('type') == 'pdf')
        {
            $pdf = \PDF::loadView('pdf.evaluation',['evaluation' => $evaluation]);
            return $pdf->stream('evaluation_'. $id . 'pdf');
        }

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

        $competences = \App\Competence::whereDoesntHave('positions')
            ->orWhereHas('positions',function($q) use ($evaluation)
            {
                return $q
                    ->where('position_id',$evaluation->position_id)
                    ->where('org_id',$evaluation->org_id)
                    ->where(function($q) use ($evaluation) {
                        return $q
                            ->where('func_id',$evaluation->func_id)
                            ->orWhereNull('func_id');
                    });
            })
            ->get();

        return view('evaluation.edit',['evaluation' => $evaluation,'competences' => $competences]);
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

        $comp_arr = $request->get('competences');

        if(is_array($comp_arr))
        {

            foreach ($evaluation->evaluaters as $evaluater)
            {
                foreach (\App\Indicator::whereIn('competence_id',$comp_arr)
                             ->orderBy('competence_id')
                             ->get() as $ind) {
                    $process = \App\EvalProcess::create([
                        'evaluater_id' => $evaluater->id,
                        'indicator_id' => $ind->id,
                    ]);
                }
            }

            $evaluation->started_at = \Carbon\Carbon::now();
            $evaluation->save();
        }

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
