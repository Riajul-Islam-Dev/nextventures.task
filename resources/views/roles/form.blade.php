@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" method="POST">
            @csrf
            @if (isset($role))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Role Name</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ old('name', $role->name ?? '') }}" placeholder="Enter role name">
            </div>

            <div class="mb-3">
                <label for="permissions" class="form-label">Permissions</label>
                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="col-md-2 mb-3">
                            <div class="card border-primary h-100">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]"
                                            class="form-check-input" value="{{ $permission->id }}"
                                            @if (isset($role) && $role->hasPermissionTo($permission)) checked @endif>
                                        <label for="permission-{{ $permission->id }}"
                                            class="form-check-label">{{ $permission->name }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
@endsection
