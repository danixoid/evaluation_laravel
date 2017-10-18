<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['welcome']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        if(Auth::check())
        {
            return $this->index();
        }

        return view('welcome');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $evaluaters = \App\Evaluater::whereHas('evaluation',function($q) {
                return $q->whereNotNull('started_at');
            })
            ->where(function($q) {
                if(Auth::user()->hasAnyRole(['admin','manager'])) {
                    return $q;
                }
                return $q->whereUserId(Auth::user()->id);
            })
            ->paginate(10);


        return view('home',[
            'evaluaters' => $evaluaters->appends(Input::except('page'))
        ]);
    }

    public function imageUpload(Request $request) {

        $file = $request->file('imagefile');

        $extension = $file->getClientOriginalExtension();
        $preview = $file->getFilename();
        $filename = $preview . '.' . $extension;
        Storage::disk('local')->put($filename, File::get($file));

        return view('upload._image-upload', compact('filename'));
    }

    public function getImage($filename) {
        return file_get_contents(storage_path('app/' . $filename));
    }


    public function getImages() {
        $files = File::allFiles(storage_path('app/'));

        $arr = [];

        foreach ($files as $file)
        {
            if(is_file($file))
            {
                array_push($arr, [
                    'title' => 'Изображение ' . $file->getFilename(),
                    'value' => route('uploaded.image',$file->getFilename())
                ]);
            }
        }

        return $arr;
    }

    public function help() {
        return view('help');
    }

    public function __test() {
        $exam = \App\Exam::find(1);
        return view('pdf.exam',['exam' => $exam]);
    }
}
