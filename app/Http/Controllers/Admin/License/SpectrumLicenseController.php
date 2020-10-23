<?php

namespace App\Http\Controllers\Admin\License;

use App\AccessCode;
use App\Book;
use App\BookAccessCode;
use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Resources\LicenseKey;
use App\Http\Resources\LicenseResourceCollection;
use App\Http\Resources\UsedLicensesResourceCollection;
use App\User;
use Illuminate\Http\Request;
use DB;

class SpectrumLicenseController extends Controller
{
    public function __construct() {
        $this->middleware('auth.custom');
    }

    public function index()
    {
        return view('licenses.generated-licenses', ['trash' => false]);
    }

    public function create()
    {
        return view('licenses.create_new_license');
    }
    protected function generate_access_code () {
        $code = rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);
        return $code;
    }

    protected function check_if_code_exist(Request $request) {
        $this->validate($request, [
            'duration' => 'required',
            'license_category' => 'required',
            'max_number_of_user' => 'required',
            'price' => 'required',
            'license_name' => 'required|unique:access_codes',
        ]);
        $code = "";
        for($i=0; $i <= AccessCode::all()->count(); $i++) {
            $code = $this->generate_access_code();
            $check_if_exist = AccessCode::where('code', $code)->count();
            if ($check_if_exist === 0) {
                break;
            }
        }
        $books = Book::all();
        return ['code'=>$code, 'books'=>$books];
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'duration' => 'required',
            'license_category' => 'required',
            'max_number_of_user' => 'required',
            'price' => 'required',
            'license_name' => 'required|unique:access_codes',
            'code' => 'required|unique:access_codes',
            'books_contained' => 'required',
        ]);

        $books_attached = $request->books_contained;
        $submitted_books_id = implode(",", $books_attached);
        // return $books_attached;
        $access_code = AccessCode::create([
            'license_name' => $request->license_name,
            'group_id' => $request->license_category,
            'code' => $request->code,
            'books_contained' => $submitted_books_id,
            'price' => $request->price,
            'max_number_of_users' => $request->max_number_of_user,
            'duration' => $request->duration
        ]);
        // foreach($books_attached as $book_id) {
        //     $id = (int) $book_id;
        //     $access_code->books()->attach($id);
        // }
        $access_code->books()->attach($books_attached);
        return ['Success', 'Access Key Successfully Created'];
    }

    public function load_all_licenses() {
        return LicenseResourceCollection::collection(AccessCode::orderBy('created_at', 'desc')->get());
    }

    public function load_license_groups()
    {
        $groups = Group::all();
        return response()->json($groups);
    }

    public function edit_license(Request $request)
    {
        if($uuid = \Request::get('i')){
            if($license = AccessCode::findByUuid($uuid)) {
                if($request->expectsJson()) {
                    return new LicenseKey($license);
                }
                return view('licenses.edit_license', ['license_id' => $uuid]);
            }
            return abort(404);

        }

    }

    public function remove_book(Request $request, $book_id) {
        if($uuid = \Request::get('i')){
            if($access_code = AccessCode::findByUuid($uuid)) {
                $books_attached = explode(',', $access_code->books_contained);
                if(count($books_attached) > 1) {
                    $book_id = (int) $book_id;
                    $books_update = \array_diff($books_attached, [$book_id]);
                    $access_code->update(['books_contained' => implode(',', $books_update)]);
                    // return $access_code->books_contained;
                    $access_code->books()->detach($book_id);
                    return ['Success', 'Book Successfuly Detached'];
                } else {
                    abort(403, "Cannot Delete the last item", ['message', 'Cannot remove the last item']);
                }

            }

        }
    }

    public function distinctBook(Request $request) {
        $access_code = AccessCode::findByUuid($request->uuid);
        $exclude_books = explode(',', $access_code->books_contained);

        $filtered_books = Book::whereNotIn('id', $exclude_books)->get();
        return $filtered_books;
    }

    public function update(Request $request)
    {
        $access_code = AccessCode::findByUuid($request->uuid);
        $this->validate($request, [
            'license_name' => 'required|unique:access_codes,license_name,'.$access_code->id,
            'duration' => 'required',
            'license_type' => 'required',
            'max_number_of_users' => 'required',
            'price' => 'required',
            'code' => 'required|unique:access_codes,code,'.$access_code->id,
        ]);

        $licensed_books = $access_code->books_contained;
        if(count($request->books_contained) > 0) {
            $licensed_books = empty($licensed_books) ? implode(',', $request->books_contained) : $licensed_books . ',' . implode(',', $request->books_contained);
            $access_code->books()->attach($request->books_contained);
        }
        $access_code->update([
            'license_name'          => $request->license_name,
            'group_id'              => $request->license_type,
            'code'                  => $request->code,
            'books_contained'       => $licensed_books,
            'price'                 => $request->price,
            'max_number_of_users'   => $request->max_number_of_users,
            'duration'              => $request->duration
        ]);

       return ['success', 'Books Successfully attached to the license'];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $findLicense = AccessCode::findByUuid($uuid)
                        ->delete();
        return response()->json(['Success', 'Licese Thrashed']);
    }


    public function thrashedLicense() {
        return view('licenses.generated-licenses', ['trash' => true]);
    }

    public function load_thrashed_license() {
        return LicenseResourceCollection::collection(AccessCode::onlyTrashed()->orderBy('deleted_at', 'asc')->get());
    }

    public function delete_by_force(Request $request) {
        $access_code = AccessCode::onlyTrashed()->where('uuid', $request->get('uuid'));
        $access_code->forceDelete();
        BookAccessCode::where('access_code_uuid', $request->get('uuid'))->delete();
        return ['success', 'License permanently removed'];
    }

    public function restoreDeleted(Request $request) {
        $restored = AccessCode::withTrashed()->where('uuid', $request->get('uuid'))->restore();
        return ['success', 'Retored Thrashed License'];
    }

    public function used_licenses() {
        return view('licenses.used-licenses');
    }

    public function all_used_licenses() {
        return UsedLicensesResourceCollection::collection(User::where('access_code', '<>', null)->paginate(10));
    }

    public function findUser() {
        if($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search) {
                $query->where('access_code', '<>', null)
                ->where('imei', 'LIKE', "%$search%")
                ->orWhere('access_code', 'LIKE', "%$search%")
                ->orWhere('first_name', 'LIKE', "%$search%")
                ->orWhere('last_name', 'LIKE', "%$search%")
                ->where('access_code', 'LIKE', "%$search%");
            })->get();
        } else {
            $users = User::where('access_code', '<>', null)->get();
        }
        // return $users;
        return UsedLicensesResourceCollection::collection($users);
    }

    public function view_accounts()
    {
        return view('licenses.view-accounts');
    }

    public function accounting_module()
    {
        $access_codes = AccessCode::all();
        // dd($access_codes);

        foreach ($access_codes as $access_code) {
           $access_code->count = $this->count_license_usage($access_code->code);
        }

        return view('licenses.accounting-module', ["access_codes" => $access_codes]);
    }

    private function count_license_usage($access_code)
    {
        $count = DB::table('users')->where('access_code', $access_code)->count();

        return $count;

    }
}
