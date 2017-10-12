<?php

namespace Selfreliance\Iusers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;

use Selfreliance\Iusers\Models\UsersLoginLogs;

class UsersController extends Controller
{
    public function index()
    {
    	$users = User::orderBy('id', 'desc')->paginate(10);
        return view('iusers::home')->with(["users"=>$users]);
    }

    public function edit($id)
    {
    	$user = User::findOrFail($id);
        $id_role = \DB::table('role_user')->where('user_id', \Auth::id())->value('role_id');
        $admin = \DB::table('roles')->where('id', $id_role)->first();

        $LoginLogs = UsersLoginLogs::where("user_id", $id)->orderBy('id', 'desc')->limit(10)->get();
	    $roles = \DB::table('roles')->get();
        $list_roles = '';
        foreach($roles as $role)
        {
            if($user->isRole($role->slug)){
                $list_roles .= '<option value = "'.$role->id.'" selected> '.$role->name.'</option>';
            }else $list_roles .= '<option value = "'.$role->id.'"> '.$role->name.'</option>';                        
        }
        $edit_role = '';
        if(in_array('adminrole', json_decode($admin->accessible_pages))){
            $edit_role = '
                <div class="form-group">
                    <label for="role" class="col-md-12">Изменить роль</label>
                    <div class="col-md-12">
                        <select class="custom-select col-12" id="role" name="selected_role">
                            <option value = "not_selected">Не выбрано</option>
                            '.$list_roles.'
                        </select>
                    </div>
                </div>
            ';
        }
    	return view('iusers::edit')->with(["edituser"=>$user, "LoginLogs"=>$LoginLogs, "edit_role"=>$edit_role]);
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


        $ModelUser->detachAllRoles();
        if($request['selected_role'] !== 'not_selected')
        {
        	$ModelUser->attachRole($request->input('selected_role'));
        }

    	return redirect()->route('AdminUsersEdit', ["id"=>$id])->with('status', 'Профиль обновлен!');
    }

    public function destroy($id){
    	$ModelUser = User::findOrFail($id);
    	$ModelUser->delete();
    	return redirect()->route('AdminUsers')->with('status', 'Пользователь удален!');
    }

    public function loginwith($id){
        \Auth::loginUsingId($id);
        return redirect('/');
    }

    public function searchUsers(Request $request) {

        $user = User::where('name', '=', $request->input('userName'))->first();
        if($user) {

            $LoginLogs = UsersLoginLog::where("user_id", $user->id)->orderBy('id', 'desc')->limit(10)->get();
            $roles = \DB::table('roles')->get();

            return view('iusers::edit')->with(["edituser"=>$user, "LoginLogs"=>$LoginLogs, "Roles"=>$roles]);
        }else {
            return redirect()->route('AdminUsers')->with('status', 'Пользователь не найден!');
        }
    }
}
