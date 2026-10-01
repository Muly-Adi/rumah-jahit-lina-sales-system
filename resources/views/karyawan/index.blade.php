@extends('template-dashboard')
@section('title', 'Data Karyawan')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="section-description section-description-inline">
                <h1>Data Karyawan</h1>
            </div>
        </div>
        @if (session("success"))
            <div class="alert alert-rounded alert-success alert-style-light" role="alert">
                {{ session("success") }}
            </div>
        @endif

        @if (session("error"))
            <div class="alert alert-rounded alert-success alert-style-light" role="alert">
                {{ session("error") }}
            </div>
        @endif
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route("karyawan.create") }}" class="btn btn-outline-primary float-end">
                            <i class="material-icons-two-tone text-white">add_box</i>
                            Tambah Karyawan
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="datatable1" class="display" style="width:100%">
                            <thead>
                            <tr class="text-center">
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">No HP</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($karyawans as $index => $karyawan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span
                                            class="text-md mb-0 fw-normal text-secondary-light">{{ $karyawan->nama }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-md mb-0 fw-normal text-secondary-light">{{ $karyawan->username }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-md mb-0 fw-normal text-secondary-light">{{ $karyawan->email }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-md mb-0 fw-normal text-secondary-light">{{ $karyawan->no_hp }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-10 justify-content-center">
                                            <a href="{{ route("karyawan.edit", $karyawan->id_user) }}"
                                               class="btn btn-sm text-success"
                                               title="Edit">
                                                <i class="material-icons-outlined">
                                                    edit
                                                </i>
                                            </a>
                                            <form action="{{ route("karyawan.destroy", $karyawan->id_user) }}"
                                                  method="POST" class="delete-form"
                                                  data-name="{{ $karyawan->nama }}">
                                                @csrf
                                                @method("DELETE")
                                                <button type="button"
                                                        class="btn btn-sm btn-delete"
                                                        title="Hapus">
                                                    <i class="material-icons-outlined text-danger">
                                                        delete
                                                    </i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <span class="text-secondary-light">Tidak ada data karyawan</span>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr class="text-center">
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">No HP</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('.delete-form');
                const karyawanName = form.data('name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Data karyawan "${karyawanName}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
