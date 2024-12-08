<?php

namespace App\Repositories\Backend;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{
    public function all(): Collection
    {
        return User::all();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if (!empty($data['roles'])) {
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $data['roles'])->pluck('name')->toArray();
            $user->syncRoles($roles);
        }

        return $user;
    }

    public function update(int $id, array $data): bool
    {
        $user = User::findOrFail($id);
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $result = $user->save();

        if (!empty($data['roles'])) {
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $data['roles'])->pluck('name')->toArray();
            $user->syncRoles($roles);
        }

        return $result;
    }

    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
