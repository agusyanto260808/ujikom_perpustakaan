<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-800 dark:text-gray-100 tracking-tight">
                    {{ __('Kelola') }} <span class="text-indigo-600">Akun</span>
                </h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Daftarkan dan atur hak akses Petugas serta Siswa.</p>
            </div>
            <a href="{{ route('kelola_akun.create') }}" class="flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl shadow-lg shadow-indigo-200 transition-all duration-300 group">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                <span class="font-bold">Tambah Pengguna</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc] dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-500 text-white rounded-2xl shadow-lg flex items-center animate-bounce">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:rounded-[2.5rem] border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-10">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                           <thead>
    <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
        <th class="px-6 py-4">Pengguna</th>
        <th class="px-6 py-4">Email</th>
        <th class="px-6 py-4 text-center">Jabatan</th>
        @if(request('role') != 'petugas') {{-- NISN hanya untuk Siswa --}}
            <th class="px-6 py-4 text-center">NISN</th>
        @endif
        <th class="px-6 py-4 text-right">Aksi</th>
    </tr>
</thead>
                            <tbody class="divide-y-8 divide-transparent">
                               @forelse ($users as $user)
<tr class="group bg-white dark:bg-gray-800/50 hover:shadow-xl transition-all duration-300">
    <td class="px-6 py-6 bg-gray-50/50 dark:bg-gray-700/30 rounded-l-[1.5rem] border-y border-l">
        <div class="flex items-center">
            <div class="text-sm font-black text-gray-900 dark:text-white">{{ $user->name }}</div>
        </div>
    </td>
    
    <td class="px-6 py-6 border-y">
        <span class="text-sm text-gray-500 font-medium">{{ $user->email }}</span>
    </td>

    <td class="px-6 py-6 text-center border-y">
        <span class="px-4 py-1.5 {{ $user->role == 'petugas' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }} rounded-full text-[10px] font-black uppercase tracking-widest">
            {{ $user->role }}
        </span>
    </td>

    @if(request('role') != 'petugas')
    <td class="px-6 py-6 text-center border-y">
        <span class="text-sm font-bold text-gray-400">{{ $user->nisn ?? '-' }}</span>
    </td>
    @endif

    <td class="px-6 py-6 text-right bg-gray-50/50 dark:bg-gray-700/30 rounded-r-[1.5rem] border-y border-r">
                                        <form action="{{ route('kelola_akun.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                                            <a href="{{ route('kelola_akun.edit', $user->id) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-xl transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
    </a>
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                  
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="py-20 text-center text-gray-400 font-bold italic">
        Belum ada akun {{ request('role') ?? 'siswa' }} yang terdaftar.
    </td>
</tr>
@endforelse
                            </tbody>
                        </table>
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
