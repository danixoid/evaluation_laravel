<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompetenceProfileController extends Controller
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
        $competences = \App\Competence::where(function($q){
                return $q->whereHas('type',function($q) {
                        return $q->whereProf(false);
                    });
            })
            ->orWhere(function($q) {

                return $q->whereHas('type', function ($q) {
                        return $q->whereProf(true);
                    })
                    ->whereHas('positions', function ($q) {

                        $org_id = request('org_id') ?: null;
                        $func_id = request('func_id') ?: null;
                        $position_id = request('position_id') ?: null;

                        return $q
                            ->whereOrgId($org_id)
                            ->whereFuncId($func_id)
                            ->wherePositionId($position_id);

                    });

            })
            ->orderBy('competence_type_id')
            ->orderBy('id')
            ->get();

        return view('profile.index',['competences' => $competences] );
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
        $data = $request->all();

//        dd($data);

        if(isset($data['profile']) && is_array($data['profile'])){
            foreach($data['profile'] as $id => $profile) {
                if($profile['eval_level_id'] > 0
                    && $profile['competence_id'] > 0
                    && $profile['org_id'] > 0
                    && $profile['position_id'] > 0) {
                    \App\CompetenceProfile::whereOrgId($profile['org_id'])
                        ->wherePositionId($profile['position_id'])
                        ->whereFuncId($profile['func_id'])
                        ->whereCompetenceId($profile['competence_id'])
                        ->delete();
                    \App\CompetenceProfile::create($profile);
                }
            }
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_competence_profile')
            ]);
        }

        return redirect()
            ->back()
            ->with('message', trans('interface.success_create_competence_profile'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }
}
