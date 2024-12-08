<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionController extends Controller
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->middleware('role:Admin');
        $this->permissionRepository = $permissionRepository;
    }

    public function index()
    {
        $permissions = $this->permissionRepository->all();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        $this->permissionRepository->create($request->only(['name']));

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit($id)
    {
        $permission = $this->permissionRepository->findById($id);
        if (!$permission) {
            return redirect()->route('permissions.index')->with('error', 'Permission not found.');
        }

        return view('permissions.form', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        $this->permissionRepository->update($id, $request->only(['name']));

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy($id)
    {
        $permission = $this->permissionRepository->findById($id);

        if (!$permission) {
            return redirect()->route('permissions.index')->with('error', 'Permission not found.');
        }

        if ($permission->name === 'admin') {
            return redirect()->route('permissions.index')->with('error', 'The Admin permission cannot be deleted.');
        }

        $this->permissionRepository->delete($id);
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
