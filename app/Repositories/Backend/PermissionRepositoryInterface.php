<?php

namespace App\Repositories\Backend;

use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function all(): Collection;
    public function findById(int $id): ?\Spatie\Permission\Models\Permission;
    public function create(array $data): \Spatie\Permission\Models\Permission;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
