<?php

namespace App\Repositories\Backend;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleRepository implements RoleRepositoryInterface
{
    public function all(): Collection
    {
        return Role::all();
    }

    public function create(array $data)
    {
        return Role::create(['name' => $data['name']]);
    }

    public function findById(int $id)
    {
        return Role::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $role = $this->findById($id);
        $role->update(['name' => $data['name']]);
        return $role;
    }

    public function delete(int $id)
    {
        $role = $this->findById($id);
        $role->delete();
    }

    public function syncPermissions(int $roleId, array $permissions)
    {
        $role = $this->findById($roleId);
        $role->syncPermissions($permissions);
    }
}
