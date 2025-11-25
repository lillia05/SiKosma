@if($users->count() > 0)
    <table class="w-full">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">ID</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Nama Akun</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Email</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Tanggal Daftar</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Role</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Status</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 font-poppins">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                @php
                    // Format role untuk display
                    $roleMap = [
                        'pencari' => 'Pengguna',
                        'pemilik' => 'Pemilik Kos',
                        'admin' => 'Admin'
                    ];
                    $roleDisplay = $roleMap[$user->peran] ?? ucfirst($user->peran);
                    
                    // Format tanggal
                    $tanggalDaftar = $user->created_at->format('d - m - Y');
                    
                    // Format status
                    $statusDisplay = strtoupper($user->status);
                @endphp
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $index + 1 }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins font-semibold">{{ $user->nama }}</td>
                    <td class="py-3 px-4 text-sm text-gray-600 font-poppins">{{ $user->email }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $tanggalDaftar }}</td>
                    <td class="py-3 px-4 text-sm text-gray-900 font-poppins">{{ $roleDisplay }}</td>
                    <td class="py-3 px-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold font-poppins {{ $user->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $statusDisplay }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-sm space-x-2">
                        <form action="{{ route('admin.manajemen-pengguna.update-status', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $user->status === 'Aktif' ? 'Tidak Aktif' : 'Aktif' }}">
                            <button 
                                type="submit"
                                class="text-yellow-600 hover:text-yellow-800 transition inline-block btn-edit-status"
                                data-user-id="{{ $user->id }}"
                                data-current-status="{{ $user->status }}"
                                data-new-status="{{ $user->status === 'Aktif' ? 'Tidak Aktif' : 'Aktif' }}"
                                title="Edit Status"
                            >
                                {{-- Heroicons: pencil-square (outline) - https://heroicons.com/ --}}
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.manajemen-pengguna.destroy', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 transition inline-block btn-delete-user">
                                {{-- Heroicons: trash (outline) - https://heroicons.com/ --}}
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="text-center py-12">
        <p class="text-gray-600 font-poppins">Tidak ada data pengguna.</p>
    </div>
@endif

