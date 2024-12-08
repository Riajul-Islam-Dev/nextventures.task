<?php

namespace App\Repositories\Backend;

use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data);
    public function findById(int $id);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function syncPermissions(int $roleId, array $permissions);
}
