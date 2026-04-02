@extends('admin-master')
@section('content')
<main class="main-content">
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Manage Users</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success form-status" style="color: green; text-align: center; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-section">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #1E4BA6; color: white;">
                    <th style="padding: 12px; text-align: left;">#</th>
                    <th style="padding: 12px; text-align: left;">Name</th>
                    <th style="padding: 12px; text-align: left;">Email</th>
                    <th style="padding: 12px; text-align: left;">Role</th>
                    <th style="padding: 12px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;">{{ $index + 1 }}</td>
                        <td style="padding: 10px;">{{ $user->name }}</td>
                        <td style="padding: 10px;">{{ $user->email }}</td>
                        <td style="padding: 10px;">
                            <form action="{{ route('update.role', $user) }}" method="POST" style="display: flex; gap: 8px; align-items: center; justify-content: center;">
                                @csrf
                                <select name="role" style="padding:6px 10px; border-radius:5px; border:1px solid #ccc;">
                                    <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Employee" {{ $user->role == 'Employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="Guest" {{ $user->role == 'Guest' ? 'selected' : '' }}>Guest</option>
                                </select>
                                <div style="text-align: center; width: 100%;">
                                    <button type="submit" class="btn-primary" style="padding:6px 12px;">Update</button>
                                </div>
                            </form>
                        </td>
                        <td style="padding: 10px; text-align: center;">
                            {{-- Optional: extra actions like delete/edit --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>
@endsection