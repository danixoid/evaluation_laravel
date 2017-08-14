<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    /**
     *  constructor.
     */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['index','show']]);
        $this->middleware('role:admin',['except' => ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = request('count') ?: 10;

        if(request('q')) {
            $users = \App\User::where('name','LIKE', '%' . request('q'). '%')
                ->orWhere('email','LIKE', '%' . request('q'). '%')
                ->orWhere('iin','LIKE', '%' . request('q'). '%')
                ->orderBy('created_at','desc')
//                ->take(request('page'))
                ->paginate($count);

            if(request()->ajax()) {
                return $users->toJson();
            }

        } else {
            $users = \App\User::paginate($count);
        }

        return view('user.index',['users' => $users->appends(Input::except('page'))]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        $data = $request->all();

        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user = \App\User::create($data);

        if(!$user) {

            if($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('interface.failure_create_user')
                ]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_create_user'));
        }

        $user
            ->roles()
            ->attach(\App\Role::where('name','employee')->first());

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_create_user'),
                'user' => $user,
            ]);
        }

        return redirect()
            ->route('user.show',$user->id)
            ->with('message',trans('interface.success_create_user'));

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
                ->json(\App\User::find($id));
        }

        $user = \App\User::find($id);
        return view('user.show',['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = \App\User::find($id);

        return view('user.edit',['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserEditRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserEditRequest $request, $id)
    {
        $data = $request->all();

        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
//        dd($data);

        $user = \App\User::updateOrCreate(['id' => $id], $data);

        if(isset($data['role_id']) && is_array($data['role_id'])) {
            $user
                ->roles()
                ->detach();

            foreach ($data['role_id'] as $role_id)
                $user
                    ->roles()
                    ->attach(\App\Role::find($role_id));

        }

        if(!$user) {

            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_save_user')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_save_user'));
        }

        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => trans('interface.success_save_user'),
                'user' => $user,
            ]);
        }

        return redirect()->route('user.show',$id)->with('message',trans('interface.success_save_user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = \App\User::find($id);

        if(!$user) {

            if(request()->ajax()) {
                return response()->json(['success' => false, 'message' => trans('interface.failure_deleted_user')]);
            }

            return redirect()->back()->with('warning',trans('interface.failure_deleted_user'));
        }

        $user->delete();

        return redirect()
            ->route('user.index')
            ->with('message',trans('interface.success_deleted_user'));
    }
}
