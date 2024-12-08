<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Repositories\Backend\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->middleware('role:Admin');
        $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        $roles = $this->roleRepository->all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.form', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = $this->roleRepository->create(['name' => $request->name]);
        if ($request->has('permissions')) {
            $this->roleRepository->syncPermissions($role->id, $request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        try {
            $role = $this->roleRepository->findById($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roles.index')->with('error', 'Role not found.');
        }

        $permissions = Permission::all();
        return view('roles.form', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $this->roleRepository->update($id, ['name' => $request->name]);
        if ($request->has('permissions')) {
            $this->roleRepository->syncPermissions($id, $request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $role = $this->roleRepository->findById($id);

            if ($role->name === 'Admin') {
                return redirect()->route('roles.index')->with('error', 'The Admin role cannot be deleted.');
            }

            if ($role->users()->exists()) {
                return redirect()->route('roles.index')->with('error', 'Role cannot be deleted as it is assigned to users.');
            }

            $this->roleRepository->delete($id);

            return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('roles.index')->with('error', 'Role not found.');
        }
    }
}
