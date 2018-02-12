<?php

namespace Selfreliance\Iusers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Selfreliance\Iusers\Models\UsersLoginLogs;
use PaymentSystem;
use App\Models\Users_Wallet;
use Hash;
use Carbon\Carbon;
use App\Models\Users_Levels_Structure;
use App\Models\Deposit;
use App\Models\Users_History;
class UsersController extends Controller
{
    public function registerBlock()
    {
        $days = 8;
        $users_reg = User::selectRaw('DATE_FORMAT(`created_at`, \'%Y-%m-%d\') as groupDate, COUNT(`id`) as CntByDate')->orderBy('groupDate', 'desc')->limit($days-1)->groupBy('groupDate')->get();
        $real_users = [];
        foreach($users_reg as $row){
            $real_users[$row->groupDate] = $row->CntByDate;
        }

        $weekago = Carbon::now()->subDays($days);
        $null_array = [];
        while ($days > 0) {
            $weekago->addDays(1);
            $result = strtolower($weekago->format('Y-m-d'));
            $null_array[$result] = 0;
            $days--;
        }
        $users_by_date = array_merge($null_array, $real_users);
        ksort($users_by_date);
        $imploade_data = implode(", ", $users_by_date);
        $users_today = $users_by_date[Carbon::now()->format('Y-m-d')];
        $users_yesterday = $users_by_date[Carbon::now()->subDays(1)->format('Y-m-d')];
        

        $count = User::count('id');
        return view('iusers::block', compact('count', 'imploade_data', 'users_today', 'users_yesterday'))->render();
    }

    public function index(Request $request)
    {
    	$keyword = $request->input("searchKey");
    	$users = User::where(function($query) use ($keyword) {
    		if($keyword != ''){
	    		$query->where('id', 'LIKE', "%$keyword%")
	    			->orWhere('name', 'LIKE', "%$keyword%")
                    ->orWhere('email', 'LIKE', "%$keyword%")
	    			->orWhere('aff_ref', 'LIKE', "%$keyword%");
    		}
    	})->
        with('upline')->
        orderBy('id', 'desc')->paginate(20);
        
        $users->appends(['searchKey' => $keyword]);

        $users->each(function($row){
            if($row->parent_id > 0){
                // $row->parent_email = User::where('id', $row->parent_id)->value('email');
                $row->parent_email = $row->upline->email;
            }
        });
        
        if(count($users) == 1){
            return redirect()->route('AdminUsersEdit', ["id"=>$users[0]->id]);
        }

        return view('iusers::home', compact('users'));
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

        $edituser = $user;
        $accessible = json_decode($admin->accessible_pages);

        $wallets = PaymentSystem::get_with_wallet($id);

    	return view('iusers::edit', compact('edituser', 'LoginLogs', 'list_roles', 'accessible', 'wallets'));
    }

    public function update($id, Request $request)
    {
    	$this->validate($request, [
			'name'         => 'required|min:2|max:191',
			'email'        => 'required|email|unique:users,email,'.$id, 
	    ]);
        // dd($request);
        $ModelUser                   = User::findOrFail($id);
        $ModelUser->name             = $request->input('name');
        $ModelUser->email            = $request->input('email');
        if($request['new_password'] != null){
            $ModelUser->password = Hash::make($request['new_password']);
        }
        $ModelUser->aff_ref          = $request->input('aff_ref');
        if($request->input('google2fa_secret')){
            $ModelUser->google2fa_secret = $request->input('google2fa_secret');
        }
        if($request->input('google2fa_ts')){
            $ModelUser->google2fa_ts     = $request->input('google2fa_ts');
        }
        $ModelUser->representative   = ($request->input('representative'))?1:0;
        $ModelUser->google2fa_status = ($request->input('google2fa_status'))?1:0;
    	$ModelUser->save();

        if($request['selected_role'] !== 'not_selected') {
            $ModelUser->attachRole($request->input('selected_role'));
        }else {
            if($ModelUser->role_id != '-1'){
                $ModelUser->detachRole($ModelUser->role_id);
            }
        }

        flash()->success('Профиль успешно обновлен');

    	return redirect()->route('AdminUsersEdit', ["id"=>$id]);
    }

    public function destroy($id)
    {
    	$ModelUser = User::findOrFail($id);
    	$ModelUser->delete();

        flash()->success('Пользователь удален');

    	return redirect()->route('AdminUsers');
    }

    public function loginwith($id)
    {
        \Auth::loginUsingId($id);
        $to = env('PERSONAL_LINK_CAB', '/');
        return redirect($to);
    }

    public function save_wallet($id, Request $request){
        if(count($request->input('wallet')) > 0){
            foreach($request->input('wallet') as $key=>$value){
                $res = Users_Wallet::where('user_id', $id)->where('payment_system_id', $key)->first();
                    
                if($res){
                    $res->delete();
                }
                if($value != ''){
                    $ModelUserWallet = new Users_Wallet;
                    $ModelUserWallet->user_id = $id;
                    $ModelUserWallet->payment_system_id = $key;
                    $ModelUserWallet->wallet = $value;
                    $ModelUserWallet->save();
                }
            }
        }

        flash()->success('Кошельки успешно сохранены');
        return redirect()->back();
    }

    public function structure($id, $level = 1){
        $user = User::find($id);
        
        $users = Users_Levels_Structure::
            where('user_id', $id)->
            where('level', $level)->
            orderBy('id', 'desc')->
            with(['info', 'info.upline'])->
            paginate(20);

        $levels = Users_Levels_Structure::
            where('user_id', $id)->
            selectRaw('level, count(id) as count, GROUP_CONCAT(DISTINCT id SEPARATOR ", ") as user_ids')->
            groupBy('level')->
            get();

        $levels->each(function($row) use ($id){
            $ids = explode(',', $row->user_ids);
            $row->invested = number(Deposit::whereIn('user_id', $ids)->sum('amount_default_currency'),2);

            $row->withdraw = number(Users_History::whereIn('user_id', $ids)->where('type', 'WITHDRAW')->where('status', 'completed')->sum('amount_default_currency'),2);
        });
        return view('iusers::structure', compact('user', 'level', 'users', 'levels'));
    }
}
