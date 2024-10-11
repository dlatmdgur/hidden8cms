<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
	private $permissions = ['permission'];


    private $perPage = 100000; // using data-tables paging
    private $permissionNames = [
        'member'       => '사용자조회',
        'game'         => '게임조회',
        'operation'    => '운영정보',
        'management'   => '운영관리',
        'user'         => '관리자',
        'log'          => '운영로그',
        'permission'   => '권한관리',
        'maintenance'  => '운영툴관리',
        'monitor'      => '모니터링',
        'statistics'   => '지표관리',
        'outer0'       => '외부(마스터)',
        'outer1'       => '외부(본사)',
        'outer2'       => '외부(부본)',
        'outer3'       => '외부(총판)',
        'outer4'       => '외부(대리점)',
        'outer5'       => '외부(매장)',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }


	private function permission()
	{
		$args = func_get_args();

		if (count($args) <= 0)
			$args = $this->permissions;

		$user = Auth::user();

		$status = 0;
		foreach ($args as $val)
		{
			if ($user->hasPermissionTo($val))
				$status++;
		}

		if ($status == 0)
		{
			header('Location: /blank');
			die();
		}

		return true;
	}


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
		$this->permission();

        $data = User::orderBy('id', 'DESC')->get();
        $permissions = Permission::pluck('name', 'name')->all();
        $names = $this->permissionNames;
        return view('users.index', compact('data', 'permissions', 'names'))
            ->with('i', ($request->input('page', 1) - 1) * $this->perPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
		$this->permission();

        // $roles = Role::pluck('name','name')->all();
        $permissions = Permission::pluck('name', 'name')->all();
        $names = $this->permissionNames;
        return view('users.create', compact('permissions', 'names'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
		$this->permission();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'phone' => 'required',
            'permission' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        //$user->assignRole($request->input('roles'));

        foreach ($input['permission'] as $permission) {
            $user->givePermissionTo($permission);
        }

        return redirect()->route('users.index')
            ->with('success', $user->name . ' 유저가 생성되었습니다.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
		$this->permission();

        $user = User::find($id);
        $permissions = Permission::pluck('name', 'name')->all();
        $names = $this->permissionNames;
        return view('users.show', compact('user', 'permissions', 'names'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
		$this->permission();

        $user = User::find($id);
        // $roles = Role::pluck('name','name')->all();
        // $userRole = $user->roles->pluck('name','name')->all();
        $permissions = Permission::pluck('name', 'name')->all();
        $names = $this->permissionNames;
        return view('users.edit', compact('user', 'permissions', 'names'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
		$this->permission();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'phone' => 'required',
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);

//        if ($request->input('roles')) {
//            DB::table('model_has_roles')->where('model_id',$id)->delete();
//            $user->assignRole($request->input('roles'));
//        }

        // revoke all
        foreach ($this->permissionNames as $permission => $permissionName) {
            $user->revokePermissionTo($permission);
        }

        foreach ($input['permission'] as $permission) {
            $user->givePermissionTo($permission);
        }

        $route = 'users.index';
        if ($input['from'] === 'profile') {
            $route = 'profile';
        }

        return redirect()->route($route)
            ->with('success', $user->name . ' 유저의 정보가 수정되었습니다.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
		$this->permission();

        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', $user->name . ' 유저가 삭제되었습니다.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function profile()
    {
		$this->permission();

        $id = Auth::id();
        $user = User::find($id);
        $permissions = Permission::pluck('name', 'name')->all();

        return view('users.profile', compact('user', 'permissions'));
    }

    /**
     * Reset Password for User
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function reset($id)
    {
		$this->permission();

        $user = User::find($id);
        $input['password'] = Hash::make('0000');

        $user->update($input);

        return redirect()->route('users.index')
            ->with('success', $user->name . ' 유저의 패스워드가 초기화 되었습니다.');
    }
}
