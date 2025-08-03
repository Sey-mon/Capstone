@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">System Users</h1>
    <div class="mb-2 text-gray-600">Showing {{ $users->count() }} of {{ $users->total() }} users</div>
    <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approval</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-semibold text-gray-900">{{ $user->display_name }}</div>
                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        @if($user->phone_number)
                        <div class="text-xs text-gray-400">{{ $user->phone_number }}</div>
                        @endif
                        @if($user->role === 'nutritionist' && $user->status === 'pending')
                            <div class="mt-2 text-xs text-yellow-700">Nutritionist Application Pending</div>
                            <div class="mt-1">
                                <a href="{{ asset('storage/' . $user->id_document) }}" target="_blank" class="text-blue-600 underline text-xs mr-2">View ID</a>
                                <a href="{{ asset('storage/' . $user->certificate) }}" target="_blank" class="text-blue-600 underline text-xs">View Certificate</a>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->hasEmployeeId())
                            <div class="font-mono text-sm text-gray-700">{{ $user->employee_id }}</div>
                            <div class="text-xs text-gray-500">{{ $user->role === 'parent_guardian' ? 'Parent' : ucfirst($user->role) }}</div>
                        @else
                            <div class="text-xs text-gray-400">Not assigned</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $roleColors = [
                                'admin' => 'bg-purple-100 text-purple-700',
                                'nutritionist' => 'bg-green-100 text-green-700',
                                'parent_guardian' => 'bg-blue-100 text-blue-700',
                                'parents' => 'bg-blue-100 text-blue-700', // Legacy support
                                'worker' => 'bg-yellow-100 text-yellow-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $user->role === 'parent_guardian' ? 'Parent/Guardian' : ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->role === 'nutritionist' && $user->status === 'pending')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Pending Approval</span>
                        @elseif($user->is_active)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->role === 'nutritionist' && $user->status === 'pending')
                            <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline-form">
                                @csrf
                                <button type="submit" class="ml-2 px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">Approve</button>
                            </form>
                            <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline-form">
                                @csrf
                                <button type="submit" class="ml-1 px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Reject</button>
                            </form>
                        @elseif($user->role === 'nutritionist' && $user->status === 'approved')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600">Approved</span>
                        @elseif($user->role === 'nutritionist' && $user->status === 'rejected')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">Rejected</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600">Approved</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->last_login_at)
                            <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>{{ $user->last_login_at->format('Y-m-d') }}</span>
                        @else
                            <span class="text-gray-400">Never</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                        <a href="#" class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs rounded"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M4 7v6a2 2 0 002 2h6" /></svg>Edit</a>
                        <form action="#" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs rounded"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $users->links() }}</div>
    </div>
</div>
@endsection
