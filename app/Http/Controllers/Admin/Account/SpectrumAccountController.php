<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin;

use Auth;
use Illuminate\Support\Facades\Gate;

class SpectrumAccountController extends Controller
{
    public function __construct() {
        $this->middleware('auth.custom');
    }

    public function index()
    {
        // $response = Gate::inspect('create');
        if(Gate::forUser(Auth::guard('admin')->user())->allows('isSuperAdmin')) {
            return view('accounts.activate-accounts');
        }
        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($status)
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isSuperAdmin');
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

    public function switch_privilege(Request $request, $id)
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isSuperAdmin');
        if(Admin::where('uuid', $id)->where('status', 1)->where('is_super_admin', 0)->update(['is_super_admin' => 1])){

            $auid = Auth::guard('admin')->user()->uuid;
            $suid = $id;
            $title = "Role Upgrade";
            $action = "Upgrade to Super Admin";
            $ip_address = $request->ip();

            log_activity($auid, $title, $action, $suid, $ip_address);

                return ['success', "User is now a Super Admin"];
        }
        elseif(Admin::where('uuid', $id)->where('status', 1)->where('is_super_admin', 1)->update(['is_super_admin' => 0])) {

            $auid = Auth::guard('admin')->user()->uuid;
            $suid = $id;
            $title = "Role Downgrade";
            $action = "Downgrade to Admin";
            $ip_address = $request->ip();

            log_activity($auid, $title, $action, $suid, $ip_address);

            return ['success', "User is now an Admin"];
        }
    }

    public function activate_account(Request $request, $id)
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isSuperAdmin');
        if(Admin::where('uuid', $id)->where('status', 0)->update(['status' => 1])){

            $auid = Auth::guard('admin')->user()->uuid;
            $suid = $id;
            $title = "Account Activation";
            $action = "Activation of registered admin account.";
            $ip_address = $request->ip();

            log_activity($auid, $title, $action, $suid, $ip_address);

            return ['success', "Account Activated"];
        }
        elseif(Admin::where('uuid', $id)->where('status', 1)->update(['status' => 0])) {

            $auid = Auth::guard('admin')->user()->uuid;
            $suid = $id;
            $title = "Account Deactivation";
            $action = "Deactivation of active admin account.";

            $ip_address = $request->ip();

            log_activity($auid, $title, $action, $suid, $ip_address);

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
    public function destroy(Request $request, $id)
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isSuperAdmin');
        $admin = Admin::where('uuid', $id);
        $admin->delete();

        $auid = Auth::guard('admin')->user()->uuid;
        $suid = $id;
        $title = "Account Deletion";
        $action = "Deletion of admin account.";

        $ip_address = $request->ip();

        log_activity($auid, $title, $action, $suid, $ip_address);

        return ['success', "User Account Deleted Successfully"];
    }
}
