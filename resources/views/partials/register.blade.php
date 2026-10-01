<div class="modal modalCentered fade modal-log" id="register">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <span class="icon icon-close btn-hide-popup" data-bs-dismiss="modal"></span>
            <div class="modal-log-wrap list-file-delete">
                <h5 class="title fw-semibold">Daftar Akun Customer</h5>

                @if (session("register_success"))
                    <div class="alert alert-success">
                        {{ session("register_success") }}
                    </div>
                @endif

                <form action="{{ route("register.store") }}" method="POST" class="form-log">
                    @csrf
                    <div class="form-content">
                        <fieldset class="mb-3">
                            <label class="fw-semibold body-md-2">
                                Nama Lengkap *
                            </label>
                            <input type="text" name="nama" placeholder="Masukkan nama lengkap"
                                value="{{ old("nama") }}" required>
                        </fieldset>

                        <fieldset class="mb-3">
                            <label class="fw-semibold body-md-2">
                                Username *
                            </label>
                            <input type="text" name="username" placeholder="Masukkan username"
                                value="{{ old("username") }}" required>
                        </fieldset>

                        <fieldset class="mb-3">
                            <label class="fw-semibold body-md-2">
                                Email *
                            </label>
                            <input type="email" name="email" placeholder="Masukkan email"
                                value="{{ old("email") }}" required>
                        </fieldset>

                        <fieldset class="mb-3">
                            <label class="fw-semibold body-md-2">
                                No. HP *
                            </label>
                            <input type="text" name="no_hp" placeholder="Masukkan nomor HP"
                                value="{{ old("no_hp") }}" required>
                        </fieldset>

                        {{-- <fieldset class="mb-3">
                            <label class="fw-semibold body-md-2">
                                Alamat *
                            </label>
                            <textarea name="alamat" placeholder="Masukkan alamat lengkap" rows="3" required>{{ old("alamat") }}</textarea>
                        </fieldset> --}}

                        <fieldset class="mb-3">
                            <label class="fw-semibold body-md-2">
                                Password *
                            </label>
                            <input type="password" name="password" placeholder="Masukkan password" required>
                        </fieldset>

                        <fieldset class="mb-3">
                            <label class="fw-semibold body-md-2">
                                Konfirmasi Password *
                            </label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
                        </fieldset>
                    </div>
                    <button type="submit" class="tf-btn w-100 text-white">
                        Daftar Sekarang
                    </button>
                    <p class="body-text-3 text-center mt-3">
                        Sudah punya akun?
                        <a href="#log" data-bs-toggle="modal" class="text-primary" data-bs-dismiss="modal">
                            Login di sini
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
