<div>
    <div class="row">
        {{-- message --}}
        @if (session()->has('success'))
            <div class="py-3 mb-2">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        {{-- message --}}

        {{-- form input --}}
        <div class="col-12 mb-5">
            <div class="card">
                <div class="card-body">
                    <form
                    @if ($update_data == false)
                        wire:submit.prevent='store'
                    @else
                        wire:submit.prevent='update'
                    @endif
                    enctype="multipart/form-data">
                        @csrf

                        {{-- Full Name --}}
                        <div class="mb-3 row">
                            <label for="full_name" class="col-sm-2 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="full_name" wire:model='full_name' autofocus>
                                @error('full_name')
                                    <div class="text-sm text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Full Name --}}

                        {{-- Email --}}
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="email" wire:model='email'>
                                @error('email')
                                    <div class="text-sm text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Email --}}

                        {{-- Address --}}
                        <div class="mb-3 row">
                            <label for="address" class="col-sm-2 col-form-label">Alamat Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="address" wire:model='address'>
                                @error('address')
                                    <div class="text-sm text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Address --}}

                        <div class="row">
                            <div class="offset-md-2 col-md-10">
                                @if ($update_data == false)
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                @else
                                    <button type="submit" class="btn btn-primary">Update</button>
                                @endif

                                <button wire:click='clear()' class="btn btn-outline-primary ms-2">Kosongkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- form input --}}

        {{-- table --}}
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h2 class="mb-0">Data Pegawai</h2>

                            <input type="text" class="form-control w-25" wire:model.live='keywords' placeholder="Masukan kata kunci ...">
                        </div>

                        @if ($employee_selected_id)
                            <div class="mt-3 mb-2">
                                <button type="button" class="btn btn-sm btn-danger" wire:click="deleteConfirmation('')" data-bs-toggle="modal" data-bs-target="#modal_confirm_delete">Hapus {{ count($employee_selected_id) }} Data</button>

                                {{-- modal confirm delete data --}}
                                <div wire:ignore.self class="modal fade" id="modal_confirm_delete" tabindex="-1" aria-labelledby="modal_confirm_delete_label" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="modal_confirm_delete_label">
                                                    Konfirmasi Hapus Data
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body py-4">
                                                <div class="text-center">
                                                    <h4 class="mb-0">Apakah anda yakin akan menghapus <br> <span class="text-danger">"{{ count($employee_selected_id) }} Data"</span> ?</h4>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button wire:click='deleteBulk()' type="button" class="btn btn-primary" data-bs-dismiss="modal">Ya, Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- modal confirm delete data --}}
                            </div>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sortable">
                            <thead>
                                <tr class="text-center align-middle">
                                    <th>
                                        <input type="checkbox" class="form-check-input" @if($countData == 0) disabled @endif wire:model.live='selectAll'>
                                    </th>
                                    <th>No</th>
                                    <th class="sort @if ($sortColumn == 'full_name') {{ $sortDirection }} @endif" wire:click="sort('full_name')">Nama Lengkap</th>
                                    <th class="sort @if ($sortColumn == 'email') {{ $sortDirection }} @endif" wire:click="sort('email')">Email</th>
                                    <th class="sort @if ($sortColumn == 'address') {{ $sortDirection }} @endif" wire:click="sort('address')">Alamat Lengkap</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($employees as $key => $employee)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input" wire:key='{{ $employee->id }}' value="{{ $employee->id }}" wire:model.live='employee_selected_id'>
                                    </td>
                                    <td class="text-center">
                                        {{ $employees->firstItem() + $key }}
                                    </td>
                                    <td>
                                        {{ $employee->full_name }}
                                    </td>
                                    <td>
                                        {{ $employee->email }}
                                    </td>
                                    <td>
                                        {{ $employee->address }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <button type="button" wire:click='edit({{ $employee->id }})' class="btn btn-sm btn-warning">Edit</button>
                                            <button type="button" class="btn btn-sm btn-danger ms-2" wire:click='deleteConfirmation({{ $employee->id }})' data-bs-toggle="modal" data-bs-target="#modal_confirm_delete_{{ $employee->id }}">Hapus</button>
                                        </div>

                                        {{-- modal confirm delete data --}}
                                        <div wire:ignore.self class="modal fade" id="modal_confirm_delete_{{ $employee->id }}" tabindex="-1" aria-labelledby="modal_confirm_delete_{{ $employee->id }}_label" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="modal_confirm_delete_{{ $employee->id }}_label">
                                                            Konfirmasi Hapus Data
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body py-4">
                                                        <div class="text-center">
                                                            <h4 class="mb-0">Apakah anda yakin akan menghapus <br> <span class="text-danger">"{{ $employee->full_name }}"</span> ?</h4>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button wire:click='delete()' type="button" class="btn btn-primary" data-bs-dismiss="modal">Ya, Hapus</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- modal confirm delete data --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Data Tidak Tersedia!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                    <div class="mt-2 d-flex justify-content-center">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
        {{-- table --}}
    </div>

    @push('style')
        <style>
            .table-sortable>thead>tr>th.sort {
                cursor: pointer;
                position: relative;
            }

            .table-sortable>thead>tr>th.sort:after,
            .table-sortable>thead>tr>th.sort:after,
            .table-sortable>thead>tr>th.sort:after {
                content: ' ';
                position: absolute;
                height: 0;
                width: 0;
                right: 10px;
                top: 18px;
            }

            .table-sortable>thead>tr>th.sort:after {
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-top: 5px solid #ccc;
                border-bottom: 0px solid transparent;
            }

            .table-sortable>thead>tr>th:hover:after {
                border-top: 5px solid #888;
            }

            .table-sortable>thead>tr>th.sort.asc:after {
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-top: 0px solid transparent;
                border-bottom: 5px solid #333;
            }

            .table-sortable>thead>tr>th.sort.asc:hover:after {
                border-bottom: 5px solid #888;
            }

            .table-sortable>thead>tr>th.sort.desc:after {
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-top: 5px solid #333;
                border-bottom: 5px solid transparent;
            }
        </style>
    @endpush
</div>
