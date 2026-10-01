<div class="modal modalCentered fade modal-log" id="log">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <span class="icon icon-close btn-hide-popup" data-bs-dismiss="modal"></span>
            <div class="modal-log-wrap list-file-delete">
                <h5 class="title fw-semibold">Log In</h5>
                <form action="{{ route("auth.login") }}" method="POST" class="form-log">
                    @csrf
                    <div class="form-content">
                        <fieldset>
                            <label class="fw-semibold body-md-2">
                                Username *
                            </label>
                            <input type="text" placeholder="masukkan username anda" name="username">
                        </fieldset>
                        <fieldset>
                            <label class="fw-semibold body-md-2">
                                Password *
                            </label>
                            <input type="password" placeholder="masukkan password anda" name="password">
                        </fieldset>
                        <a href="#forgotPassword"  data-bs-toggle="modal" class="link text-end body-text-3">
                            Forgot password ?
                        </a>
                    </div>
                    <button type="submit" class="tf-btn w-100 text-white">
                        Login
                    </button>
                    <p class="body-text-3 text-center">
                        Tidak memiliki akun?
                        <a href="#register" data-bs-toggle="modal" class="text-primary">
                            Daftar
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
