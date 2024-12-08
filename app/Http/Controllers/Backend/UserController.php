<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\UserRepositoryInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->middleware('role:Admin');
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.form', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $this->userRepository->create($request->only(['name', 'email', 'password', 'roles']));

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        $roles = Role::all();
        return view('users.form', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $this->userRepository->update($id, $request->only(['name', 'email', 'password', 'roles']));

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        if ($user->email === 'admin@example.com') { // Admin integrity
            return redirect()->route('users.index')->with('error', 'The admin user cannot be deleted.');
        }

        $this->userRepository->delete($id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
