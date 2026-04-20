@extends('layouts.admin')
@section('title', 'Kategori Produk')
@section('header', 'Kategori Produk')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">{{ $categories->count() }} kategori terdaftar</p>
    <a href="{{ route('admin.categories.create') }}"
       class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
        + Tambah Kategori
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left font-medium">Nama</th>
                <th class="px-4 py-3 text-left font-medium">Slug</th>
                <th class="px-4 py-3 text-left font-medium">Produk</th>
                <th class="px-4 py-3 text-left font-medium">Status</th>
                <th class="px-4 py-3 text-left font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($categories as $cat)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $cat->name }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs font-mono">{{ $cat->slug }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $cat->products_count }} produk</td>
                <td class="px-4 py-3">
                    @if($cat->is_active)
                        <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full">Aktif</span>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Nonaktif</span>
                    @endif
                </td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('admin.categories.edit', $cat) }}"
                       class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                          onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-10 text-gray-400">
                    <p class="text-3xl mb-2">📦</p>
                    Belum ada kategori.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
