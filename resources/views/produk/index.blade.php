@extends('template-dashboard')
@section('title', 'Data Produk')
@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="section-description section-description-inline">
                <h1>Data Produk</h1>
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
                        <a href="{{ route("produk.create") }}" class="btn btn-outline-primary float-end">
                            <i class="material-icons-two-tone text-white">add_box</i>
                            Tambah Produk
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="datatable1" class="display" style="width:100%">
                            <thead>
                            <tr class="text-center">
                                <th scope="col" class="text-center">No</th>
                                <th scope="col" class="text-center">Nama Produk</th>
                                <th scope="col" class="text-center">Kategori</th>
                                <th scope="col" class="text-center">Harga</th>
                                <th scope="col" class="text-center">Jumlah</th>
                                <th scope="col" class="text-center">Opsi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($produks as $index => $produk)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span
                                            class="text-md mb-0 fw-semibold text-primary-light">{{ $produk->nama }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-secondary">
                                            {{ $produk->kategori->nama ?? "-" }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-md mb-0 fw-semibold text-success-600">Rp
                                            {{ number_format($produk->harga, 0, ",", ".") }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $totalStok = $produk->jumlah_produk ?? 0;
                                            $totalStok += $produk->jenisProduk->sum("jumlah_produk");
                                        @endphp
                                        <span
                                            class="text-md mb-0 fw-normal text-secondary-light">{{ $totalStok }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-10 justify-content-center">
                                            <a href="{{ route("detail-produk", $produk->id_produk) }}"
                                               class="btn btn-sm text-primary"
                                               title="Detail">
                                                <i class="material-icons-outlined">
                                                    remove_red_eye
                                                </i>
                                            </a>
                                            <a href="{{ route("produk.edit", $produk->id_produk) }}"
                                               class="btn btn-sm text-success"
                                               title="Edit">
                                                <i class="material-icons-outlined">
                                                    edit
                                                </i>
                                            </a>
                                            <form action="{{ route("produk.destroy", $produk->id_produk) }}"
                                                  method="POST" class="delete-form" data-name="{{ $produk->nama }}">
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
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr class="text-center">
                                <th scope="col" class="text-center">No</th>
                                <th scope="col" class="text-center">Nama Produk</th>
                                <th scope="col" class="text-center">Kategori</th>
                                <th scope="col" class="text-center">Harga</th>
                                <th scope="col" class="text-center">Jumlah</th>
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
                const produkName = form.data('name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Produk "${produkName}" dan semua data terkait akan dihapus permanen!`,
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
