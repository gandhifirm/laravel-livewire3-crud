<?php

namespace App\Livewire;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class Home extends Component
{
    // Memanggil fungsi untuk dapat menggunakan Pagination dengan Bootstrap Theme
    use WithPagination;
    protected $paginationTheme = "bootstrap";

    public $full_name;
    public $email;
    public $address;
    public $update_data = false;
    public $employee_id;
    public $employee_data;

    public function clear() {
        // hapus isian form input
        $this->full_name = '';
        $this->email = '';
        $this->address = '';
        $this->update_data = false;
    }

    public function store() {
        // Membuat rules untuk menentukan validasi dari setiap inputan
        $rules = [
            "full_name" => "required|regex:/^[a-zA-Z\s]*$/",
            "email" => "required|email|unique:employees",
            "address" => "required",
        ];

        // Memodifikasi nama pesan error yang ditampilkan
        $message = [
            "full_name.required" => "Nama lengkap wajib diisi",
            "full_name.regex" => "Hanya boleh diisi huruf",
            "email.required" => "Email wajib diisi",
            "email.email"   => "Format email tidak benar",
            "email.unique"   => "Email sudah digunakan",
            "address.required" => "Alamat wajib diisi",
        ];

        // Memanggil fungsi dari validasi
        $validated = $this->validate($rules, $message);

        // Memanggil fungsi create untuk menyimpan data
        $employee = Employee::create($validated);

        // Session flash untuk memberikan informasi data yang sudah berhasil disimpan
        session()->flash("success","Data berhasil dimasukan!");

        // bersihkan form input setelah submit
        $this->clear();
    }

    public function edit($id) {
        // Cari id yang sama di model database
        $employee = Employee::findOrFail($id);

        // Isikan data dari database ke dalam public property
        $this->full_name = $employee->full_name;
        $this->email = $employee->email;
        $this->address = $employee->address;
        $this->employee_id = $employee->id;

        // Setelah klik button edit, maka update_data = true
        $this->update_data = true;
    }

    public function update() {
        // Membuat rules untuk menentukan validasi dari setiap inputan
        $rules = [
            "full_name" => "required|regex:/^[a-zA-Z\s]*$/",
            "email" => "required|email",
            "address" => "required",
        ];

        // Memodifikasi nama pesan error yang ditampilkan
        $message = [
            "full_name.required" => "Nama lengkap wajib diisi",
            "full_name.regex" => "Hanya boleh diisi huruf",
            "email.required" => "Email wajib diisi",
            "email.email"   => "Format email tidak benar",
            "address.required" => "Alamat wajib diisi",
        ];

        // Memanggil fungsi dari validasi
        $validated = $this->validate($rules, $message);

        // Cari id yang akan di-update
        $employee = Employee::findOrFail($this->employee_id);
        $employee = $employee->update($validated);

        // Session flash untuk memberikan informasi data yang sudah berhasil di-update
        session()->flash("success","Data berhasil diganti!");

        // bersihkan form input setelah submit
        $this->clear();
    }

    public function delete($id) {
        Employee::findOrFail($id)->delete();

        // Session flash untuk memberikan informasi data yang sudah berhasil dihapus
        session()->flash("success","Data berhasil dihapus!");

        // Hapus data pada form input yang ada
        $this->clear();

        // Lakukan refresh untuk seolah clear page pagination
        return redirect("/");
    }

    public function render()
    {
        // Tampilkan seluruh data dari database
        $employees = Employee::orderBy("id","desc")->paginate(2);

        return view('livewire.home',[
            'employees' => $employees,
        ])
            ->layout('layouts.app');
    }
}
