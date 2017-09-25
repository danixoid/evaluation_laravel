<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestCreateRequest;
use App\Http\Requests\QuestUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class QuestController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager',['except' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = request('count') ?: 10;
        $org_id = request('org_id');
        $func_id = request('func_id');
        $position_id = request('position_id');
        $text = request('text');
        $trashed = request('trashed');


        $query = \App\Quest::whereNotNull('created_at');

        if (!Auth::user()->hasRole('admin') && Auth::user()->hasRole('manager'))
        {
            $query = $query->whereAuthorId(Auth::user()->id);
        }

        if($trashed &&  $trashed > 0)
        {
            $query = $query->onlyTrashed();
        }

        if($org_id &&  $org_id > 0)
        {
            $query = $query->whereHas('orgs',function($q) use ($org_id) {
                return $q->whereOrgId($org_id);
            });
        }

        if($func_id &&  $func_id > 0)
        {
            $query = $query->whereHas('funcs',function($q) use ($func_id) {
                return $q->whereFuncId($func_id);
            });
        }

        if($position_id &&  $position_id > 0)
        {
            $query = $query->whereHas('positions',function($q) use ($position_id) {
                return $q->wherePositionId($position_id);
            });
        }

        if($text) {
            $query = $query
                ->where(function($q) {
                    return $q
//                        ->where('source', 'LIKE', '%' . \request('text') . '%')
                        ->orWhere('task', 'LIKE', '%' . \request('text') . '%');
                });
        }

        $quests = $query->paginate($count);

        if(request()->ajax()) {
            return response()->json(["quests" => $quests]);
        }


        return view('quest.index',['quests' => $quests->appends(Input::except('page'))]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('quest.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestCreateRequest $request)
    {
        $data = $request->all();
        $data['author_id'] = auth()->user()->id;

//        dd($data);

        if(request()->hasFile('word_file')) {
//
//            $path = storage_path(request()
//                ->file('word_file')
//                ->store('word_files'));

            Storage::disk('word')->delete('word.html');

            $file = request()->file('word_file');
            $file = $file->move(storage_path('app/word_files'),"word."
                . $file->getClientOriginalExtension());
            $path = $file->getRealPath();

            $output = mberegi_replace("docx?$","html",$path);

            putenv('PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:'
                . '/bin:/usr/games:/usr/local/games:/opt/node/bin');
            putenv('HOME=' . sys_get_temp_dir());
            $shell = shell_exec(env('OFFICE_CMD','libreoffice'). " --headless --convert-to  html:\"HTML (StarWriter)\" "
                . $path . " --outdir " . storage_path('app/word_files'));
//            $shell = shell_exec("sudo /usr/bin/unoconv -f  html " . $path);

//            dd($shell);

            $content = file_get_contents($output);

            $content = preg_replace_callback("/(&#[0-9]+;)/", function($m) {
                return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
                },
                $content
            );

            $files = File::allFiles(storage_path('app/word_files/'));

            foreach ($files as $file)
            {
                if(is_file($file))
                {
                    if(mb_ereg_match("^word_html_",$file->getFilename())) {
                        File::move($file->getRealPath(),storage_path('app/'.$file->getFilename()) );

                        $content = mb_ereg_replace($file->getFilename(),
                            route('uploaded.image',$file->getFilename()), $content);
                    }
                }
            }

//            $content = mb_ereg_replace("\n","", $content);
            $content = mberegi_replace("<!DOCTYPE.+<body[^>]+>","", $content);
            $content = mberegi_replace("<\/body>.+$","", $content);
//            $content = mberegi_replace("\s?style=\"font-family:\s+[\w\,\'\s]+;\"","", $content);
            $content = mberegi_replace("\s?face=\"[\w\,\'\s]+\""," face=\"Times New Roman\"", $content);
//            return $content;
//            $content = mberegi_replace("<p((?!</p>).)+Решение\.?((?!<p).)+</p>","",$content);
//            $content = mb_ereg_replace("<p((?!</p>).)+[^а-яА-Я]+((?!<p).)+</p>","",$content);


//            return $content;//тест

            $arr = mb_split("<p((?!(</p>|[а-яА-Я]+)).)+Ситуация((?!<p).)+</p>",$content);

            //первый элемент без данных, удалить
            unset($arr[0]);

//            dd($arr);
            $int = 0;
            foreach ($arr as $str) {
                $str = mberegi_replace("<p((?!(</p>|[а-яА-Я]+)).)+Решение\.?.+$","",$str);
                $data['task'] = $str;
                $quest = \App\Quest::create($data);


                if(isset($data['struct']) && is_array($data['struct'])) {
                    $struct = array_unique($data['struct']);

                    foreach ($struct as $items) {
                        $quest->self()->attach($quest,$items);
                    }
                }

                if(isset($data['positions']) && is_array($data['positions'])) {

                    foreach ($data['positions'] as $position_id)
                        $quest
                            ->positions()
                            ->attach(\App\Position::find($position_id));

                }

                $int++;
            }

            return redirect()
                ->route('quest.index')
                ->with('message',trans('interface.imported_file',['count' => $int]));
        }


        $quest = \App\Quest::create($data);

        if(!$quest) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_quest')
                ]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('warning',trans('interface.failure_create_quest'));
        }

        if(isset($data['struct']) && is_array($data['struct'])) {
            $struct = array_unique($data['struct'],SORT_REGULAR);

            foreach ($struct as $items) {
                $quest->self()->attach($quest,$items);
            }
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_quest'),
                'quest' => $quest,
            ]);
        }

        return redirect()
            ->route('quest.show',$quest->id)
            ->with('message',trans('interface.success_create_quest'));

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
                ->json(\App\Quest::find($id));
        }

        $quest = \App\Quest::withTrashed()->find($id);

        if(request()->has('type') && \request('type') == "minimum"){
            return view('layouts.clear',['content' => $quest->task]);
        }

        return view('quest.show',['quest' => $quest]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quest = \App\Quest::withTrashed()->find($id);
        return view('quest.edit',['quest' => $quest]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestUpdateRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestUpdateRequest $request, $id)
    {
        $data = $request->all();

        if($id == 0 && isset($data['timer'])) {
            \App\Quest::whereNotNull('created_at')->update(['timer'=>$data['timer']]);
            return redirect()
                ->back()
                ->withInput()
//                ->with('message',trans('interface.success_save_quest'))
            ;
        }



        $data['author_id'] = auth()->user()->id;

        $quest = \App\Quest::updateOrCreate(['id' => $id], $data);

        if(!$quest) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_quest')]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('warning',trans('interface.failure_save_quest'));
        }


        if(isset($data['struct']) && is_array($data['struct'])) {
            $quest
                ->positions()
                ->detach();

            $struct = array_unique($data['struct'],SORT_REGULAR);

            foreach ($struct as $items) {
                $quest->self()->attach($quest,$items);
            }
        }


        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_quest'),
                'quest' => $quest,
            ]);
        }

        return redirect()
            ->route('quest.show',$id)
            ->with('message',trans('interface.success_save_quest'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quest = \App\Quest::withTrashed()->find($id);

        if($quest->trashed())
        {
            $quest->restore();

            return redirect()
                ->back()
                ->with('message',trans('interface.success_restored_quest'));
        }

        if(!$quest) {

            if(request()->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_deleted_quest')]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('warning',trans('interface.failure_deleted_quest'));
        }

//        if(count($quest->tickets) == 0) {
            $quest->delete();
//        }

        return redirect()
            ->back()
            ->with('message',trans('interface.success_deleted_quest'));

    }
}
