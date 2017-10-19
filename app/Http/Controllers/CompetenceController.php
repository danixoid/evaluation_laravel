<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompetenceCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

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

        if(request()->hasFile('word_file')) {
//
//            $path = storage_path(request()
//                ->file('word_file')
//                ->store('word_files'));

            Storage::disk('word')->delete('word.txt');

            $file = request()->file('word_file');
            $file = $file->move(storage_path('app/word_files'),"word."
                . $file->getClientOriginalExtension());
            $path = $file->getRealPath();

            $output = mberegi_replace("docx?$","txt",$path);

            putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:'
                . '/bin:/usr/games:/usr/local/games:/opt/node/bin');
            putenv('HOME=' . sys_get_temp_dir());
            $shell = shell_exec(env('OFFICE_CMD','libreoffice'). " --headless --convert-to "
                . "\"txt:Text (encoded):UTF8\" "
                . $path . " --outdir " . storage_path('app/word_files'));
//            $shell = shell_exec("sudo /usr/bin/unoconv -f  html " . $path);

//            dd($shell);

            $content = file_get_contents($output);





            $arr = mb_split("Компетенция \d+(\s+)?",$content);

            //первый элемент без данных, удалить
            unset($arr[0]);

            $int = 0;
            foreach ($arr as $str) {

                $_data = mb_split("\n",$str);

                $data['name'] = $_data[0];
                unset($_data[0]);

                $data['note'] = "";
                $data['indicator'] = [];
                $_isNote = true;
                foreach($_data as $s) {
                    if(preg_match("/\d+\.\s+/",$s)) {
                        $_isNote = false;
                        $_s = mberegi_replace("\d+\.\s+","",$s);
                        array_push($data['indicator'],['name'=>$_s]);
                    }
                    if($_isNote) {
                        $data['note'] .= "\n" . $s;
                    }
                }

                $competence = \App\Competence::create($data);

                if(isset($data['indicator']) && is_array($data['indicator'])) {
                    $indicators = $data['indicator'];

                    for ($i=0;$i<count($indicators);$i++) {
                        $indicators[$i]['competence_id'] = $competence->id;
                        \App\Indicator::create($indicators[$i]);
                    }
                }

                if(isset($data['struct']) && is_array($data['struct'])) {
                    $struct = array_unique($data['struct'],SORT_REGULAR);

                    foreach ($struct as $items) {
                        $competence->positions()->attach($competence,$items);
                    }
                }

            }

            return redirect()
                ->route('competence.index',['type' => $data['competence_type_id']])
                ->with('message',trans('interface.imported_file',['count' => $int]));
        }

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

            for ($i=0;$i<count($indicators);$i++) {
                $indicators[$i]['competence_id'] = $competence->id;
                \App\Indicator::create($indicators[$i]);
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

            $ids = [];
//            dd($indicators);

            \App\Indicator::whereCompetenceId($id)->delete();

            foreach($indicators as $indicator) {

                if(array_key_exists('id',$indicator)) {
                    \App\Indicator::withTrashed()->find($indicator['id'])->restore();
                } else {
                    $ind = \App\Indicator::create([
                        'competence_id' => $id,
                        'name' => $indicator['name']
                    ]);
//                    \App\Indicator::find($ind->id)->restore();
                }
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
