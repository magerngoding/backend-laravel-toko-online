<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Index
    public function index(Request $request)
    {
        // get users with pagination
        $users = DB::table('users')
        ->when($request->input('name'), function ($query, $name) {
         return $query->where('name', 'like', '%' . $name . '%');
        })
         ->paginate(5);
        return view('pages.user.index', compact('users'));
    }

    // Create
    public function create()
    {
        return view('pages.user.create');
    }

    // Store
    public function store(Request $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->input('password'));
        User::create($data);
        return redirect()->route('user.index');
    }

    // Show
    public function show($id)
    {
        return view('pages.dashboard');
    }

    // Edit
    public function edit(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.edit', compact('user'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $user = User::findOrFail($id);
        // check if password is not empty
        if($request->input('password')){
            $data['password'] = Hash::make($request->input('password'));
        }else{
            // if password is empty, then use the old password
            $data['password'] = $user->password;
        }
        $user->update($data);
        return redirect()->route('user.index');
    }

    // Destroy
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user.index');
    }
}
