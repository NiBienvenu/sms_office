{{-- show.blade.php --}}
@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-person"></i> User Details</h5>
            <div>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-light ms-2">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <img src="{{ $user->photo ? asset($user->photo) : asset('images/default-avatar.jpg') }}"
                         alt="User photo" class="rounded-circle img-thumbnail" width="200">
                    <h4 class="mt-3">{{ $user->first_name }} {{ $user->last_name }}</h4>
                    <p class="text-muted">
                        @foreach($user->roles as $role)
                            <span class="badge bg-info">{{ $role->name }}</span>
                        @endforeach
                    </p>
                </div>

                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $user->phone }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $user->address }}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{ $user->city }}</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{ $user->country }}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{ ucfirst($user->gender) }}</td>
                            </tr>
                            <tr>
                                <th>Birth Date</th>
                                <td>{{ $user->birth_date->format('F d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Last Login</th>
                                <td>
                                    @if($user->last_login_at)
                                    {{-- @dump($user->last_login_at) --}}
                                        {{  \Carbon\Carbon::createFromTimestamp($user->last_login_at)->format('F d, Y H:i') }}
                                        <small class="text-muted">({{ \Carbon\Carbon::createFromTimestamp($user->last_login_at)->diffForHumans() }})</small>
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $user->created_at->format('F d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $user->updated_at->format('F d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection
