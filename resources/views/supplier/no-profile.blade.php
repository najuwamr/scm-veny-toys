@extends('layouts.supplier')

@section('title', 'Akun Supplier Belum Lengkap')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="rounded-3xl bg-white p-10 shadow-sm">
        <h1 class="text-3xl font-semibold text-slate-900">Data Supplier Belum Tersedia</h1>
        <p class="mt-4 text-sm text-slate-500">Akun Anda terdaftar sebagai supplier tetapi data profile supplier belum dihubungkan. Silakan hubungi admin untuk melengkapi data supplier Anda.</p>
        <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-6">
            <p class="text-sm text-slate-700">Jika Anda adalah supplier baru, administrator harus membuat entri supplier di tabel <code>suppliers</code> dan menautkannya ke user Anda.</p>
        </div>
    </div>
</div>
@endsection
