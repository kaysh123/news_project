<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {

        $users = User::paginate(10);
        return view('users.index', ['users' => $users]);
        // $users = User::paginate(10);
        // $users = User::get();
        // return view('users.index', ['users' => $users,]);
    }
    public function usermade(Request $request)
    {

        $request->validate([
            'name' => 'required',     'email' => 'required|email|unique:users,email', 'password' => 'required|min:8'
        ]);
        $users = new User();
        $users->name = $request->input('name'); // Use 'question' here
        $users->email = $request->input('email');
        $users->password = $request->input('password');
        $users->save();
        // return redirect->route('route.name')->with('success', 'Task completed successfully');
        return redirect('/user');
    }
    public function deleteUser(Request $request, $id)
    {
        // Validation
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            // Perform the delete operation
            $users = User::find($request->input('user_id'));
            $users->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
