@extends('template-dashboard')
@section('title', 'Data Kategori')
@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="section-description section-description-inline">
                <h1>Data Kategori</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route("kategori.create") }}" class="btn btn-outline-primary float-end">
                            <i class="material-icons-two-tone text-white">add_box</i>
                            Tambah Kategori
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="datatable1" class="display" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Nama Kategori</th>
                                    <th scope="col" class="text-center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($kategoris as $index => $kategori)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="text-md mb-0 fw-semibold text-primary-light">{{ $kategori->nama }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-10 justify-content-center">
                                            <a href="{{ route("kategori.edit", $kategori->id_kategori) }}"
                                               class="btn btn-sm text-success">
                                                <i class="material-icons-outlined">
                                                    edit
                                                </i>
                                            </a>
                                            <form action="{{ route("kategori.destroy", $kategori->id_kategori) }}"
                                                  method="POST" class="delete-form"
                                                  data-name="{{ $kategori->nama }}">
                                                @csrf
                                                @method("DELETE")
                                                <button type="button" class="btn btn-sm text-center btn-delete">
                                                    <i class="material-icons-outlined text-danger">
                                                        delete
                                                    </i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="text-center">
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Nama Kategori</th>
                                    <th scope="col" class="text-center">Opsi</th>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('.delete-form');
                const kategoriName = form.data('name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Kategori "${kategoriName}" akan dihapus permanen!`,
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
