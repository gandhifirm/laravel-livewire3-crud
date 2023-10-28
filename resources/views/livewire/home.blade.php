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
                                <input type="text" class="form-control" id="full_name" wire:model='full_name'>
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
                        <h2>Data Pegawai</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Alamat Lengkap</th>
                                <th>Aksi</th>
                            </tr>

                            @forelse ($employees as $key => $employee)
                            <tr>
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
                                        <button type="button" class="btn btn-sm btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#modal_confirm_delete_{{ $employee->id }}">Hapus</button>
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
                                                    <button wire:click='delete({{ $employee->id }})' type="button" class="btn btn-primary" data-bs-dismiss="modal">Ya, Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- modal confirm delete data --}}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Data Tidak Tersedia!</td>
                            </tr>
                            @endforelse
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
</div>
