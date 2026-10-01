<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * menampilkan seluruh data customer
     */
    public function index()
    {
        $customers = User::where('role', 'customer')->latest()->get();
        return view('customer.index', compact('customers'));
    }

    /**
     * menampilkan form untuk membuat data customer baru
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['role'] = 'customer';
        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/customer'), $filename);
            $validated['foto'] = 'uploads/customer/' . $filename;
        }

        User::create($validated);

        return redirect()->route('customer.index')->with('success', 'Data customer berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        return view('customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id . ',id_user',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id . ',id_user',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($customer->foto && file_exists(public_path($customer->foto))) {
                unlink(public_path($customer->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/customer'), $filename);
            $validated['foto'] = 'uploads/customer/' . $filename;
        }

        $customer->update($validated);

        return redirect()->route('customer.index')->with('success', 'Data customer berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        // Delete photo if exists
        if ($customer->foto && file_exists(public_path($customer->foto))) {
            unlink(public_path($customer->foto));
        }

        $customer->delete();

        return redirect()->route('customer.index')->with('success', 'Data customer berhasil dihapus!');
    }
}
