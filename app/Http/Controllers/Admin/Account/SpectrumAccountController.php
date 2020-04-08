<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin;
class SpectrumAccountController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth.custom');
    }
    
    public function index()
    {
        return view('accounts.activate-accounts');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($status)
    {
        $user = new Admin();
        if($status == 'activated') {
            $activation_status = $user->fetch_activated_user();
            return response()->json($activation_status);
        } elseif ($status == 'unactivated') {
            $activation_status = $user->fetch_unactivated_user();
            return response()->json($activation_status);
        }

        return null;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function switch_privilege($id)
    {
        if(Admin::where('uuid', $id)->where('status', 1)->where('is_super_admin', 0)->update(['is_super_admin' => 1])){
            return ['success', "User is now a Super Admin"];
        }
        elseif(Admin::where('uuid', $id)->where('status', 1)->where('is_super_admin', 1)->update(['is_super_admin' => 0])) {
            return ['success', "User is now an Admin"];
        } 
    }

    public function activate_account($id)
    {
        if(Admin::where('uuid', $id)->where('status', 0)->update(['status' => 1])){
            return ['success', "Account Activated"];
        }
        elseif(Admin::where('uuid', $id)->where('status', 1)->update(['status' => 0])) {
            return ['success', "Account Deactivated"];
        } 
        
    }

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // return $id;
        $admin = Admin::where('uuid', $id);
        $admin->delete();
        return ['success', "User Account Deleted Successfully"];
    }
}
