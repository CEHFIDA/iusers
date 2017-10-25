<?php

namespace Selfreliance\Iusers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Selfreliance\Iusers\Models\UsersLoginLogs;

class UsersController extends Controller
{
    public function index(Request $request)
    {
    	$keyword = $request->input("searchKey");
    	$users = User::where(function($query) use ($keyword) {
    		if($keyword != ''){
	    		$query->where('id', 'LIKE', "%$keyword%")
	    			->orWhere('name', 'LIKE', "%$keyword%")
	    			->orWhere('email', 'LIKE', "%$keyword%");
    		}
    	})->orderBy('id', 'desc')->paginate(10);
        
        $users->appends(['searchKey' => $keyword]);

        return view('iusers::home')->with(["users"=>$users]);
    }

    public function edit($id)
    {
    	$user = User::findOrFail($id);
        $admin = \Auth::User()->getRole(\Auth::User()->role_id);

        $LoginLogs = UsersLoginLogs::where("user_id", $id)->orderBy('id', 'desc')->limit(10)->get();
	    $roles = \DB::table('roles')->get();
        $list_roles = '';

        foreach($roles as $role)
        {
            if($user->role_id == $role->id)
            {
                $list_roles .= '<option value = "'.$role->id.'" selected> '.$role->name.'</option>';
            }
            else $list_roles .= '<option value = "'.$role->id.'"> '.$role->name.'</option>';                        
        }

    	return view('iusers::edit')->with([
            "edituser"=>$user, 
            "LoginLogs"=>$LoginLogs, 
            "list_roles"=>$list_roles,
            "accessible"=>json_decode($admin->accessible_pages)
        ]);
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

        if($request['selected_role'] !== 'not_selected') $ModelUser->attachRole($request->input('selected_role'));
        else $ModelUser->detachRole($ModelUser->role_id);

        flash()->success('Профиль обновлен!');

    	return redirect()->route('AdminUsersEdit', ["id"=>$id]);
    }

    public function destroy($id)
    {
    	$ModelUser = User::findOrFail($id);
    	$ModelUser->delete();

        flash()->success('Пользователь удален!');

    	return redirect()->route('AdminUsers');
    }

    public function loginwith($id)
    {
        \Auth::loginUsingId($id);

        return redirect('/');
    }
}
