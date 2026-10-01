@extends('template-dashboard')
@section('title', 'Laporan Penjualan')
@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="section-description section-description-inline">
                <h1>Laporan Penjualan</h1>
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

        @if ($errors->any())
            <div class="alert alert-danger alert-style-light" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card h-100 p-0 radius-12">
                    <div class="card-header bg-base py-16 px-24">
                        <h6 class="text-lg fw-bold card-title mb-0">Pilih Periode Laporan</h6>
                    </div>
                    <div class="card-body p-24">
                        <form action="{{ route("laporan.generate") }}" method="POST">
                            @csrf
                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Tanggal Awal <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="date" class="form-control radius-8" name="tanggal_awal"
                                           value="{{ old("tanggal_awal") }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Tanggal Akhir <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="date" class="form-control radius-8" name="tanggal_akhir"
                                           value="{{ old("tanggal_akhir") }}" required>
                                </div>
                                <div class="col-md-12">
                                    <div
                                        class="alert alert-warning border-start-width-4-px border-top-0 border-end-0 border-bottom-0 px-24 py-13 mb-0 fw-medium text-sm radius-4">
                                        ℹ️ Laporan akan menampilkan transaksi dengan status pembayaran
                                        <strong>"Terima"</strong> pada periode yang dipilih.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-center gap-3 mt-2">
                                <button type="reset"
                                        class="btn btn-lg btn-danger">
                                    Reset
                                </button>
                                <button type="submit"
                                        class="btn btn-lg btn-primary">
                                    <i class="material-icons-outlined">
                                        analytics
                                    </i>
                                    Buat Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body p-24">
                        <h6 class="text-md fw-semibold mb-3">Periode Cepat:</h6>
                        <div class="d-flex flex-wrap gap-2 justify-content-around">
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                    onclick="setToday()">
                                Hari Ini
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                    onclick="setThisWeek()">
                                Minggu Ini
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                    onclick="setThisMonth()">
                                Bulan Ini
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                    onclick="setLastMonth()">
                                Bulan Lalu
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function setToday() {
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="tanggal_awal"]').value = today;
            document.querySelector('input[name="tanggal_akhir"]').value = today;
        }

        function setThisWeek() {
            const today = new Date();
            const firstDay = new Date(today.setDate(today.getDate() - today.getDay()));
            const lastDay = new Date(today.setDate(today.getDate() - today.getDay() + 6));

            document.querySelector('input[name="tanggal_awal"]').value = firstDay.toISOString().split('T')[0];
            document.querySelector('input[name="tanggal_akhir"]').value = lastDay.toISOString().split('T')[0];
        }

        function setThisMonth() {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            document.querySelector('input[name="tanggal_awal"]').value = firstDay.toISOString().split('T')[0];
            document.querySelector('input[name="tanggal_akhir"]').value = lastDay.toISOString().split('T')[0];
        }

        function setLastMonth() {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth(), 0);

            document.querySelector('input[name="tanggal_awal"]').value = firstDay.toISOString().split('T')[0];
            document.querySelector('input[name="tanggal_akhir"]').value = lastDay.toISOString().split('T')[0];
        }
    </script>
@endpush
