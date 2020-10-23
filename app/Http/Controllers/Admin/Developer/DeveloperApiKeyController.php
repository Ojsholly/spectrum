<?php

namespace App\Http\Controllers\Admin\Developer;


use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DeveloperApiKey;
use App\Http\Resources\DeveloperApiKeysCollection;
use Auth;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Validator;

class DeveloperApiKeyController extends Controller
{
    public function __construct() {
        $this->middleware('auth.custom');
    }

    public function showAll(Request $request) {
        // $created_keys = DeveloperApiKey::orderBy('created_at', 'desc')->get();
        // return response()->json($created_keys);
        Gate::forUser(Auth::guard('admin')->user())->authorize('isSuperAdmin');
        return DeveloperApiKeysCollection::collection(DeveloperApiKey::orderBy('created_at', 'desc')->get());
    }

    public function showKey(Request $request)
    {
        if($key = \Request::get('i')) {
            $accessKey = DeveloperApiKey::where('duid', $key)->first();
            if($request->expectsJson()) {
                return response()->json($accessKey);
            }
            if($accessKey) {
                return view('admin.print_key', compact('key'));
            }
        }
        return redirect()->back();
    }


    public function index()
    {
        if(Gate::forUser(Auth::guard('admin')->user())->allows('isSuperAdmin')) {
            return view('admin.show-access-keys');
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isSuperAdmin');
        Validator::make($request->all(), [
            'valid_from' => 'required|date|date_format:Y-m-d|before:valid_till',
            'valid_till' => 'required|date|date_format:Y-m-d|after:valid_from',
        ], [
            'required' => 'The :attribute date must be set',
            'before' => 'The :attribute must be at least a day before Valid Till',
            'after' => 'The :attribute must be at least a day after Valid From',
        ])->validate();

        // $validFrom = Carbon::createFromDate($request->valid_from);
        // $validTill = Carbon::createFromDate($request->valid_till);

        $created_by = Auth::guard('admin')->user()->uuid;
        $present = Carbon::now();

        $validFrom = new DateTime($request->valid_from);
        $validTill = new DateTime($request->valid_till);
        $duration = Carbon::now()->addDays($validTill->diff($validFrom)->format("%a"))->diffInDays();
        $randomKey = rand(111111, 999999) . time() . md5($created_by);
        $accessKey = md5($randomKey);

        DeveloperApiKey::create([
            'key' => $accessKey,
            'duration' => (int) $duration,
            'valid_from' => $validFrom,
            'valid_till' => $validTill,
            'created_by' => $created_by
        ]);


            $auid = Auth::guard('admin')->user()->uuid;
            $title = "Creation of New API Key.";
            $action = " Creation of New API Key.(".$randomKey.")";
            $ip_address = $request->ip();

            log_activity($auid, $title, $action, null, $ip_address);

        return ['success', 'Developer Key Generated Successfully'];



        // $check_can_use = $validFrom < $present;
        // $check_has_expired = $validTill < $present;

        return ['success',Carbon::parse($duration)->diffForHumans()];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isSuperAdmin');
        Validator::make($request->all(), [
            'valid_from' => 'required|date|date_format:Y-m-d|before:valid_till',
            'valid_till' => 'required|date|date_format:Y-m-d|after:valid_from',
        ], [
            'required' => 'The :attribute date must be set',
            'before' => 'The :attribute must be at least a day before Valid Till',
            'after' => 'The :attribute must be at least a day after Valid From',
        ])->validate();

        $validFrom = new DateTime($request->valid_from);
        $validTill = new DateTime($request->valid_till);

        $key = DeveloperApiKey::where('duid', $id);
        $key->update([
            'valid_from' => $validFrom,
            'valid_till' => $validTill
        ]);


        $auid = Auth::guard('admin')->user()->uuid;
        $title = "Creation of New API Key.";
        $action = "Editing an API Key.";
        $ip_address = $request->ip();

        log_activity($auid, $title, $action, null, $ip_address);

        return $request->all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $developerKey)
    {
        Gate::forUser(Auth::guard('admin')->user())->authorize('isSuperAdmin');
        $status = DeveloperApiKey::where('duid', $developerKey)->first();

        if(DeveloperApiKey::destroy($status->id)) {
            return response(['success', 'Api key removed']);
        }


        $auid = Auth::guard('admin')->user()->uuid;
        $title = "Removal of API Key.";
        $action = "Removal of API Key";
        $ip_address = $request->ip();

        log_activity($auid, $title, $action, null, $ip_address);

        return response(['success', 'Unable to remove key']);
    }
}
