<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * index() — Tampilkan daftar semua pelanggan
     * URL: GET /admin/customers
     */
    public function index()
    {
        // with('cats') = eager loading, ambil data kucing sekaligus
        // Ini mencegah N+1 query (query berulang untuk setiap pelanggan)
        // paginate(10) = tampilkan 10 data per halaman
        $customers = Customer::with('cats')
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * create() — Tampilkan form tambah pelanggan baru
     * URL: GET /admin/customers/create
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * store() — Simpan pelanggan baru ke database
     * URL: POST /admin/customers
     */
    public function store(StoreCustomerRequest $request)
    {
        // DB::transaction() — jika ada error di tengah jalan,
        // semua perubahan database dibatalkan (rollback)
        // Ini penting karena kita menyimpan ke 2 tabel sekaligus (users + customers)
        DB::transaction(function () use ($request) {

            // 1. Buat akun user untuk login
            $pelangganRole = Role::where('name', 'pelanggan')->first();

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role_id'  => $pelangganRole->id,
                'phone'    => $request->phone,
                'address'  => $request->address,
                'is_active' => true,
            ]);

            // 2. Buat profil customer yang terhubung ke user
            Customer::create([
                'user_id'         => $user->id,
                'name'            => $request->name,
                'phone'           => $request->phone,
                'address'         => $request->address,
                'gender'          => $request->gender,
                'identity_number' => $request->identity_number,
            ]);
        });

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * show() — Tampilkan detail satu pelanggan beserta semua kucingnya
     * URL: GET /admin/customers/{customer}
     */
    public function show(Customer $customer)
    {
        // Load semua relasi yang dibutuhkan halaman detail
        $customer->load([
            'cats',
            'groomingBookings.cat',
            'groomingBookings.package',
            'boardingBookings.cat',
            'boardingBookings.room.roomType',
        ]);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * edit() — Tampilkan form edit pelanggan
     * URL: GET /admin/customers/{customer}/edit
     */
    public function edit(Customer $customer)
    {
        // Load user untuk isi form email
        $customer->load('user');
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * update() — Simpan perubahan data pelanggan
     * URL: PUT /admin/customers/{customer}
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        DB::transaction(function () use ($request, $customer) {

            // Update data user (akun login)
            $userData = [
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'address' => $request->address,
            ];

            // Hanya update password jika diisi
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $customer->user->update($userData);

            // Update profil customer
            $customer->update([
                'name'            => $request->name,
                'phone'           => $request->phone,
                'address'         => $request->address,
                'gender'          => $request->gender,
                'identity_number' => $request->identity_number,
            ]);
        });

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * destroy() — Hapus pelanggan
     * URL: DELETE /admin/customers/{customer}
     */
    public function destroy(Customer $customer)
    {
        // cascadeOnDelete di migration akan otomatis hapus kucing
        // dan semua data terkait customer ini
        $customer->user->delete(); // hapus user = hapus customer juga (cascade)

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
