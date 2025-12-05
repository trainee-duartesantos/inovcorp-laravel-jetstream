@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Gestão de Utilizadores</h1>

<table class="table w-full bg-white shadow rounded-lg">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Papel Atual</th>
            <th>Alterar Papel</th>
        </tr>
    </thead>

    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>

                <td>
                    {{ optional($user->roles->first())->name ?? 'Sem papel' }}
                </td>

                <td>
                    <form action="{{ route('admin.utilizadores.updateRole', $user) }}" method="POST">
                        @csrf

                        <select name="role_id" class="border rounded px-2 py-1">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ optional($user->roles->first())->id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>

                        <button class="btn btn-primary btn-sm ml-2">
                            Guardar
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $users->links() }}
</div>

@endsection
