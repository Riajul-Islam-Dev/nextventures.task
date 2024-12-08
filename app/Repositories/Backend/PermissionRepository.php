<?php

namespace App\Repositories\Backend;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function all(): Collection
    {
        return Permission::all();
    }

    public function findById(int $id): ?Permission
    {
        return Permission::find($id);
    }

    public function create(array $data): Permission
    {
        return Permission::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $permission = Permission::findOrFail($id);
        $permission->name = $data['name'];
        return $permission->save();
    }

    public function delete(int $id): bool
    {
        $permission = Permission::findOrFail($id);
        return $permission->delete();
    }
}
