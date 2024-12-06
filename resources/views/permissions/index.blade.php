@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Permissions</h2>
        <a href="{{ route('permissions.create') }}" class="btn btn-primary mb-3">Create Permission</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
