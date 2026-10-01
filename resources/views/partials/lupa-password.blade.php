<div class="modal modalCentered fade modal-log" id="forgotPassword">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <span class="icon icon-close btn-hide-popup" data-bs-dismiss="modal"></span>
            <div class="modal-log-wrap list-file-delete">
                <h5 class="title fw-semibold">Atur ulang kata sandi</h5>

                @if (session("success"))
                    <div class="alert alert-success">
                        {{ session("success") }}
                    </div>
                @endif

                <form action="{{ route("lupa-password") }}" method="POST" class="form-log">
                    @csrf
                    <div class="form-content">
                        <fieldset class="mb-3">
                            <label class="fw-semibold body-md-2">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" placeholder="Masukkan email anda" class="@error('email') is-invalid @enderror"
                                   value="{{ old("email") }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                    </div>
                    <button type="submit" class="tf-btn w-100 text-white">
                        Lupa Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
