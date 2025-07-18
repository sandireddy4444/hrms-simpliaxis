<?php
namespace App\Http\Controllers;

use App\Mail\NewEmployeeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function data()
    {
        $users = User::all();
        return response()->json($users);
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
            'admin_type' => 'required|in:Admin,SuperAdmin,Employee,HR Manager,Department Manager',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        // Send welcome email to the new employee
        $creatorName = Auth::user()->name;
        $employeeData = [
            'name' => $user->name,
            'phone_no' => $user->phone_no,
            'email' => $user->email,
            'password' => $request->input('password'), // Plain text password for initial setup
            'role' => $user->role,
        ];
        Mail::to($user->email)->send(new NewEmployeeMail($employeeData, $creatorName));

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
            'admin_type' => 'required|in:Admin,SuperAdmin,Employee,HR Manager,Department Manager',
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
        if (!in_array(Auth::user()->admin_type, ['SuperAdmin', 'Admin', 'HR Manager', 'Department Manager'])) {
            return response()->json(['message' => 'Only Admin, SuperAdmin, HR Manager, or Department Manager can delete users.'], 403);
        }
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}
