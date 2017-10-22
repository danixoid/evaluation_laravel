<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
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

    public function results()
    {
        $org_id = request('org_id');
        $func_id = request('func_id');
        $position_id = request('position_id');
        $user_id = request('user_id');
        $begin_at = \request()->has('begin_at') ? \request('begin_at') . " 00:00:00"
            : \Carbon\Carbon::today()->month(\Carbon\Carbon::today()->month - 1);
        $end_at = \request()->has('end_at') ? \request('end_at') . " 23:59:59"
            : mb_ereg_replace('00:00:00','23:59:59',\Carbon\Carbon::today());


        $query = \App\Evaluation::whereNotNull('started_at')
            ->where(function($q){
                return $q
                    ->where('finished_at', '<',\Carbon\Carbon::now())
                    ->orWhereHas('evaluaters', function($q) {
                        return $q->whereHas('processes',function ($q) {
                            return $q->whereNull('eval_level_id');
                        });
                    },'=',0);
            });


        if (!Auth::user()->hasAnyRole(['admin','manager']))
        {
            $query = $query->whereHas('evaluaters',function($q) {
                return $q->whereUserId(Auth::user()->id);
            });
        }

        if($user_id &&  $user_id > 0)
        {
            $query = $query->whereUserId($user_id);
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

        $evaluations_list = array_unique($query->pluck('id')->toArray());

        $evaluations= $query
            ->orderBy('finished_at','asc')
            ->get();

        $competences = \App\Competence::whereHas('indicators',function($q) use ($evaluations_list) {
                return $q->whereHas('processes',function ($q) use ($evaluations_list){
                    return $q->whereHas('evaluater',function ($q) use ($evaluations_list){
                            return $q->whereIn('evaluation_id',$evaluations_list);
                        });
                });
            })
            ->get();

        if(request()->has('export') && request('export') == "xls")
        {
            return Excel::create('New file', function ($excel) use ($competences, $evaluations) {

                $excel->sheet('New sheet', function ($sheet) use ($competences, $evaluations) {

                    $sheet->loadView('reports.results_export', ['competences' => $competences, 'evaluations' => $evaluations]);

                });

            })->download('xlsx');
        }

        return view('reports.results',['competences' => $competences,'evaluations' => $evaluations]);
    }


    public function individual() {


        $query = \App\Evaluation::whereNotNull('started_at')
            ->where(function($q){
                return $q
                    ->where('finished_at', '<',\Carbon\Carbon::now())
                    ->orWhereHas('evaluaters', function($q) {
                        return $q->whereHas('processes',function ($q) {
                            return $q->whereNull('eval_level_id');
                        });
                    },'=',0);
            });

        if(request('user_id')) {

            $user_id = request('user_id');
            $begin_at = \request()->has('begin_at') ? \request('begin_at') . " 00:00:00" : null;
            $end_at = \request()->has('end_at') ? \request('end_at') . " 23:59:59" : null;

            if ($user_id && $user_id > 0) {
                $query = $query->whereUserId($user_id);
            }


            if ($begin_at) {
                $query = $query->where('started_at', '>', $begin_at);
            }

            if ($end_at) {
                $query = $query->where('started_at', '<', $end_at);
            }

            $evaluation = $query
                ->orderBy('finished_at','desc')
                ->first();

            if ($evaluation) {

                if(request()->has('export') && request('export') == "xls")
                {
                    return Excel::create('New file', function ($excel) use ( $evaluation) {

                        $excel->sheet('New sheet', function ($sheet) use ($evaluation) {

                            $sheet->loadView('reports.individual_export', ['evaluation' => $evaluation]);

                        });

                    })->download('xlsx');
                }

                return view('reports.individual', ['evaluation' => $evaluation]);
            }
        }

        return view('reports.individual');
    }

    public function compare() {

        return view('reports.compare');
    }

    public function compare_diagram() {


        $query = \App\Evaluation::whereNotNull('started_at')
            ->where(function($q){
                return $q
                    ->where('finished_at', '<',\Carbon\Carbon::now())
                    ->orWhereHas('evaluaters', function($q) {
                        return $q->whereHas('processes',function ($q) {
                            return $q->whereNull('eval_level_id');
                        });
                    },'=',0);
            });

        if(request('user_id')) {

            $user_id = request('user_id');
            $begin_at = \request()->has('begin_at') ? \request('begin_at') . " 00:00:00" : null;
            $end_at = \request()->has('end_at') ? \request('end_at') . " 23:59:59" : null;

            if ($user_id && $user_id > 0) {
                $query = $query->whereUserId($user_id);
            }


            if ($begin_at) {
                $query = $query->where('started_at', '>', $begin_at);
            }

            if ($end_at) {
                $query = $query->where('started_at', '<', $end_at);
            }

            $evaluation = $query
                ->orderBy('finished_at','desc')
                ->first();

            if ($evaluation) {
                $competences = \App\Competence::whereHas('indicators', function ($q) use ($evaluation) {
                    return $q->whereHas('processes', function ($q) use ($evaluation) {
                        return $q->whereHas('evaluater', function ($q) use ($evaluation) {
                            return $q->where('evaluation_id', $evaluation->id);
                        });
                    });
                })
                    ->orderBy('competence_type_id')
                    ->orderBy('id')
                    ->get();

                if(request()->has('export') && request('export') == "xls")
                {
                    return Excel::create('New file', function ($excel) use ($competences, $evaluation) {

                        $excel->sheet('New sheet', function ($sheet) use ($competences, $evaluation) {

                            $sheet->loadView('reports.compare_export', ['competences' => $competences, 'evaluation' => $evaluations]);

                        });

                    })->download('xlsx');
                }

                return view('reports.compare_diagram', ['competences' => $competences, 'evaluation' => $evaluation]);
            }
        }

        return null;
    }

    public function plan()
    {
        $query = \App\Evaluation::whereNotNull('started_at')
            ->where(function($q){
                return $q
                    ->where('finished_at', '<',\Carbon\Carbon::now())
                    ->orWhereHas('evaluaters', function($q) {
                        return $q->whereHas('processes',function ($q) {
                            return $q->whereNull('eval_level_id');
                        });
                    },'=',0);
            });

        if(request('user_id')) {

            $user_id = request('user_id');
            $begin_at = \request()->has('begin_at') ? \request('begin_at') . " 00:00:00" : null;
            $end_at = \request()->has('end_at') ? \request('end_at') . " 23:59:59" : null;

            if ($user_id && $user_id > 0) {
                $query = $query->whereUserId($user_id);
            }

            if ($begin_at) {
                $query = $query->where('started_at', '>', $begin_at);
            }

            if ($end_at) {
                $query = $query->where('started_at', '<', $end_at);
            }

            $evaluation = $query
                ->orderBy('finished_at','desc')
                ->first();

            if ($evaluation) {

                if(request()->has('export') && request('export') == "xls")
                {
                    return Excel::create('New file', function ($excel) use ( $evaluation) {

                        $excel->sheet('New sheet', function ($sheet) use ($evaluation) {

                            $sheet->loadView('reports.individual_export', ['evaluation' => $evaluation]);

                        });

                    })->download('xlsx');
                }

                return view('reports.plan', ['evaluation' => $evaluation]);
            }
        }

        return view("reports.plan");
    }

}
