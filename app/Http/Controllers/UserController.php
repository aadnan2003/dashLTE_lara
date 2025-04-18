<?php

namespace App\Http\Controllers;

use App\Mail\UserWelcomeEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //1: Eloquent (MODEL)
        //   SQL: SELECT * FROM users;
        //   SQL: SELECT id, name FROM users;
        // $data = User::all();
        // $data = User::all(['id', 'name']);
        // $data = User::where('id', '>', 5)->get();

        $data = User::withCount('categories')->get();

        //2: Query Builder
        // $data = DB::table('users')->get();

        //3: SQL
        // $data = DB::select('SELECT * FROM users');
        return response()->view('cms.users.index', ['users' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return response()->view('cms.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|min:3|max:40|alpha',
            'user_email' => 'required|email|unique:users,email',
            'user_address' => 'nullable|string|min:3|max:45',
            'user_mobile' => 'required|numeric|digits:12',
            'user_image' => 'required|image|mimes:jpg,png|max:1024',
            'user_password' => [
                'required', 'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ], [
            'user_email.required' => 'أدخل البريد الإلكتروني',
            'user_name.min' => 'الإسم لا يجب ان يقل عن 3 حروف'
        ]);
        //------------------------------------------------
        //------------------------------------------------
        // dd($request->all());
        //1: Eloquent
        $user = new User();
        $user->name = $request->input('user_name');
        $user->email = $request->input('user_email');
        $user->address = $request->input('user_address');
        $user->mobile = $request->input('user_mobile');
        $user->password = Hash::make($request->input('user_password'));

        if ($request->hasFile('user_image')) {
            $file = $request->file('user_image');
            $imageName = time() . '_image_' . $user->name . '.' . $file->getClientOriginalExtension();
            $file->storeAs('users', $imageName, ['disk' => 'public']);
            $user->image = "users/" . $imageName;
        }

        $saved = $user->save();
        if ($saved) {
            Mail::to($user)->send(new UserWelcomeEmail($user));
        }

        //2: Query Builder
        // $saved = DB::table('users')->insert([
        //     'name' => $request->input('user_name'),
        //     'email' => $request->input('user_email'),
        //     'address' => $request->input('user_address'),
        //     'password' => Hash::make($request->input('user_password')),
        //     // 'created_at' => now(),
        //     // 'updated_at' => now(),
        // ]);

        //3: SQL
        // $saved = DB::insert(
        //     "INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?)",
        //     [
        //         $request->input('user_name'),
        //         $request->input('user_email'),
        //         Hash::make($request->input('user_password')),
        //         now(),
        //         now(),
        //     ]
        // );
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        dd('WE ARE IN SHOW');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //SELECT * FROM users WHERE id = ?
        //Eloquent:
        // $user = User::find($id);
        $user = User::findOrFail($id);
        // $user = User::where('id', '=', $id)->first();

        //QueryBuilder:
        // $user = DB::table('users')->where('id', '=', $id)->first();
        // $user = DB::table('users')->find($id);

        //SQL
        // $user = DB::selectOne('SELECT * FROM users WHERE id = ?', [$id]);
        return response()->view('cms.users.edit', ['user' => $user]);
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
        //
        $request->validate([
            'user_name' => 'required|string|min:3|max:20|alpha',
            'user_email' => 'required|email|unique:users,email,' . $id,
            'user_address' => 'nullable|string|min:3|max:50',
            'user_password' => [
                'required', 'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ]
        ]);

        //Eloquent
        // $user = User::findOrFail($id);
        // $user->name = $request->input('user_name');
        // $user->email = $request->input('user_email');
        // $user->password = Hash::make($request->input('user_password'));
        // $user->address = $request->input('user_address');
        // $saved = $user->save();
        // return redirect()->route('users.index');

        //Query Builder
        // $countOfUpdatedRows = DB::table('users')->where('id', '=', $id)->update([
        //     'name' => $request->input('user_name'),
        //     'email' => $request->input('user_email'),
        //     'address' => $request->input('user_address'),
        //     'password' => Hash::make($request->input('user_password')),
        // ]);
        // return redirect()->route('users.index');

        //SQL
        $count = DB::update(
            'UPDATE users SET name=?, email=?, address=?, password=? WHERE id = ?',
            [
                $request->input('user_name'),
                $request->input('user_email'),
                $request->input('user_address'),
                Hash::make($request->input('user_password')),
                $id
            ]
        );
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        //SQL
        // $countOfDeletedRows = DB::delete('DELETE FROM users WHERE id = ?', [$id]);
        // if ($countOfDeletedRows == 1) {
        //     return redirect()->back();
        // }

        //Query Builder
        // DB::table('users')->where('id', '=', $id)->delete();
        // $countOfDeletedRows = DB::table('users')->delete($id);
        // if ($countOfDeletedRows == 1) {
        //     return redirect()->back();
        // }

        //Eloquent
        // $countOfDeletedRows = User::destroy($id);
        // if ($countOfDeletedRows == 1) {
        //     return redirect()->back();
        // }

        $user = User::findOrFail($id);
        $deleted = $user->delete();
        if ($deleted) {
            Storage::delete($user->image);
        }
        if ($deleted) {
            return redirect()->back();
        }
    }
}
