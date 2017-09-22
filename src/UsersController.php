<?php

namespace Selfreliance\Iusers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;

use Selfreliance\Iusers\Models\UsersLoginLog;

class UsersController extends Controller
{

    public function index()
    {
    	$users = User::orderBy('id', 'desc')->paginate(10);
	$roles = DB::table('roles')->get();
        return view('iusers::home')->with(["users"=>$users,"Roles"=>$roles]);
    }

    public function edit($id)
    {
    	$user = User::findOrFail($id);
    	// dd($user);

        $LoginLogs = UsersLoginLog::where("user_id", $id)->orderBy('id', 'desc')->limit(10)->get();
    	return view('iusers::edit')->with(["edituser"=>$user, "LoginLogs"=>$LoginLogs]);
    }

    public function update($id, Request $request)
    {
    	$this->validate($request, [
			'name'         => 'required|min:2|max:191',
			'email'        => 'required|email|unique:users,email,'.$id, 
	    ]);

    	$ModelUser = User::findOrFail($id);
    	$ModelUser->name = $request->input('name');
    	$ModelUser->email = $request->input('email');
    	$ModelUser->save();

    	return redirect()->route('AdminUsersEdit', ["id"=>$id])->with('status', 'Профиль обновлен!');
    }

    public function destroy($id){
    	$ModelUser = User::findOrFail($id);
    	$ModelUser->delete();
    	return redirect()->route('AdminUsers')->with('status', 'Пользователь удален!');
    }

    public function loginwith($id){
        Auth::loginUsingId($id);
        return redirect('/');
    }
}
