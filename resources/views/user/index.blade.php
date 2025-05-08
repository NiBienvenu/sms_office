    {{-- index.blade.php --}}
    @extends('layouts.app')

    @section('title', 'Users Management')

    @section('content')
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-people"></i> Users Management</h5>
                <a href="{{ route('users.create') }}" class="btn btn-light">
                    <i class="bi bi-person-plus"></i> New User
                </a>
            </div>

            <div class="card-body">
                <!-- Filters -->
                <form action="{{ route('users.index') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by name, email..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Roles</th>
                                <th>Last Login</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <img src="{{ $user->photo ? asset($user->photo) : asset('images/default-avatar.jpg') }}"
                                        class="rounded-circle" width="40" height="40"
                                        alt="Photo of {{ $user->first_name }}">
                                </td>
                                <td>
                                    <div>{{ $user->first_name }} {{ $user->last_name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </td>
                                <td>
                                    <small>
                                        <i class="bi bi-telephone"></i> {{ $user->phone }}<br>
                                        <i class="bi bi-geo-alt"></i> {{ $user->city }}, {{ $user->country }}
                                    </small>
                                </td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-info">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($user->last_login_at)
                                        <small>{{ \Carbon\Carbon::createFromTimestamp($user->last_login_at)->diffForHumans() }}</small>
                                    @else
                                        <small class="text-muted">Never</small>
                                    @endif

                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-search display-6"></i>
                                        <p class="mt-2">No users found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end">
                        {{ $users->links() }}
                    </div>
                    </div>
                    </div>
                    </div>

        @endsection
        @push('scripts')
        <script>
            // Script pour la prévisualisation de la photo
            document.getElementById('photo').addEventListener('change', function(e) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-photo').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            });
        </script>
        @endpush

