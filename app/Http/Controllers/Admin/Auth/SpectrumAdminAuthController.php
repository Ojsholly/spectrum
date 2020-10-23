<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Admin;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SpectrumAdminAuthController extends Controller
{


    /**
     * Only guests for "admin" guard are allowed except
     * for logout.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function create()
    {
        // return admin registration page
        return view('admin.register');
    }

    public function store(Request $request)
    {
        //

        $this->validate($request, [
            'name' => 'required|max:100',
            'username' => 'required|max:50|unique:admins',
            'phone' => 'required|numeric|unique:admins',
            'email' => 'required|email|unique:admins',
            'password' => 'required|confirmed|min:6'
        ]);

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->username = $request->username;
        $admin->password = Hash::make($request->password);
        $admin->remember_token = $request->_token;
        $admin->status = 0;

        // dd($admin);
        if ($admin->save()) {

            $auid = $admin->uuid;
            $title = "New Admin Registration";
            $action = "Registration on the Admin Portal";
            $ip_address = $request->ip();

            log_activity($auid, $title, $action, null, $ip_address);

            $request
                ->session()
                ->flash('success', 'New Admin Profile Created Successfully.');

            return redirect('admin/login');
        } else {
            $request
                ->session()
                ->flash('fail', 'Failure Creating Account. Please try again.');

            return back()->withInput();
        }
    }

    public function show_login_page()
    {
        return view('admin.login');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (
            Auth::guard('admin')->attempt(
                $request->only('username', 'password'),
                $request->filled('remember')
            )
        ) {
            // dd(Auth::guard('admin')->user()->status);

            if (Auth::guard('admin')->user()->status == 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('fail', 'Your account has not been activated yet.!');
            } else {
                $request->session()->regenerate();

                $auid = Auth::guard('admin')->user()->uuid;
                $title = "Successful Login";
                $action = "Successful Login into the Admin Portal";

                $ip_address = $request->ip();

                log_activity($auid, $title, $action, null, $ip_address);

                return $this->authenticated($request, Auth::guard('admin')->user())
                ?: redirect()
                    ->intended(route('admin/dashbard'))
                    ->with('success', 'You are successfully Logged in as Admin!');
            }

        }

        // Authentication failed...
        return $this->loginFailed();
    }

    private function loginFailed()
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('fail', 'Incorrect Username or Password Combination');
    }
    protected function authenticated(Request $request, $user)
    {
        //
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('admin/login')
            ->with('success', 'Admin has been logged out!');
    }

    public function show_access_keys()
    {
        return view('admin.show-access-keys');
    }

    public function audit_logs()
    {
        return view('admin.audit-logs');
    }
}
