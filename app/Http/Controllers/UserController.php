<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function data()
    {
        $users = User::all();
        return response()->json(['data' => $users]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone_no' => 'required|string|max:15',
            'department' => 'required|in:HR,IT,Finance,Marketing,Operations',
            'role' => 'required|string|max:255',
            'date_of_joined' => 'required|date',
            'is_active' => 'required|boolean',
            'admin_type' => 'required|in:Admin,SuperAdmin,Employee',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return response()->json(['success' => true, 'message' => 'User created successfully']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_no' => 'required|string|max:15',
            'department' => 'required|in:HR,IT,Finance,Marketing,Operations',
            'role' => 'required|string|max:255',
            'date_of_joined' => 'required|date',
            'is_active' => 'required|boolean',
            'admin_type' => 'required|in:Admin,SuperAdmin,Employee',
            'password' => 'nullable|string|min:8',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

//     // In UserController constructor
// public function __construct()
// {
//     $this->middleware(function ($request, $next) {
//         if (auth()->user()->admin_type !== 'SuperAdmin') {
//             abort(403, 'Unauthorized action.');
//         }
//         return $next($request);
//     })->only(['store', 'update', 'destroy']);
// }
}