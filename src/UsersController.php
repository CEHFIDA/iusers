<?php

namespace Selfreliance\Iusers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
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
    	// dd($user);
    	return view('iusers::edit')->with(["user"=>$user]);
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
}
