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
    public $keywords = '';
    public $employee_selected_id = [];
    public $selectAll = false;
    public $sortColumn = 'full_name';
    public $sortDirection = 'asc';

    public function clear() {
        // hapus isian form input
        $this->full_name = '';
        $this->email = '';
        $this->address = '';
        $this->update_data = false;
        $this->employee_id = [];
    }

    public function updatedSelectAll($value) {
        // Jika check Select All dipilih, maka seluruh pilihan akan terseleksi
        if ($value) {
            $this->employee_selected_id = Employee::pluck('id')->toArray();
        } else {
            $this->employee_selected_id = [];
        }
    }

    public function updatedEmployeeSelectedId($value) {
        // Hitung berapa jumlah data di database
        $totalData = Employee::all()->count();

        // Jika jumlah yang di select sama dengan total data
        // Maka Select All is True (checked)
        if ($value == $totalData) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }

        // Jika jumlah data yang dipilih sama dengan jumlah total data pada database
        // Maka Select All is True (checked)
        if (count($this->employee_selected_id) == $totalData) {
            $this->selectAll = true;
        }
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
        return redirect()->route("home");
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

    public function deleteConfirmation($id) {
        $this->employee_id = $id;
    }

    public function delete() {
        Employee::findOrFail($this->employee_id)->delete();

        // Session flash untuk memberikan informasi data yang sudah berhasil dihapus
        session()->flash("success","Data berhasil dihapus!");

        // Lakukan refresh untuk seolah clear page pagination
        $this->clear();
        return redirect()->route("home");
    }

    public function deleteBulk() {
        // Jika ada employee_id yang dipilih
        if (count($this->employee_selected_id)) {
            for ($i=0; $i < count($this->employee_selected_id); $i++) {
                Employee::findOrFail($this->employee_selected_id[$i])->delete();
            }
        }

        // Session flash untuk memberikan informasi data yang sudah berhasil dihapus
        session()->flash("success","Data berhasil dihapus!");

        // Lakukan refresh untuk seolah clear page pagination
        $this->clear();
        return redirect()->route("home");
    }

    public function updatingKeywords()
    {
        // Lakukan refresh ketika inputan dihapus, dan data akan dikembalikan ke halaman awal
        $this->resetPage();
    }

    public function sort($columnName) {
        $this->sortColumn = $columnName;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc':'asc';
    }

    public function render()
    {
        $countData = Employee::all()->count();

        // Cek kondisi apakah ada inputan pada form pencarian
        if ($this->keywords != null) {
            // Tampilkan kondisi sesuai dengan keyword yang dimasukan
            $employees = Employee::where("full_name","LIKE","%". $this->keywords ."%")
                        ->orWhere("email","LIKE","%". $this->keywords ."%")
                        ->orWhere("address","LIKE","%". $this->keywords ."%")
                        ->orderBy($this->sortColumn, $this->sortDirection)
                        ->paginate(2);
        } else {
            // Tampilkan seluruh data dari database
            $employees = Employee::orderBy($this->sortColumn, $this->sortDirection)->paginate(3);
        }

        return view('livewire.home',[
            'employees' => $employees,
            'countData' => $countData
        ])
            ->layout('layouts.app');
    }
}