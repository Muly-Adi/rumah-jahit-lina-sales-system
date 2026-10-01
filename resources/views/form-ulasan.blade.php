@extends("template")
@section("title", "Beri Ulasan - " . $produk->nama . " - Aksesoris Ria")

@section("body")
    <!-- Breakcrumbs -->
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li><a href="{{ route("home") }}" class="body-small link">Home</a></li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <a href="{{ route("produk.detail", $produk->id_produk) }}" class="body-small link">{{ $produk->nama }}</a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li><span class="body-small">Beri Ulasan</span></li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <section class="tf-sp-2">
        <div class="container">
            @if (session("success"))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="icon-check"></i> {{ session("success") }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session("error"))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icon-close"></i> {{ session("error") }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4">
                                <h4 class="card-title mb-2">Beri Ulasan untuk Produk</h4>
                                <div class="d-flex align-items-center">
                                    @if ($produk->gambarProduk->first())
                                        <img src="{{ asset($produk->gambarProduk->first()->path_gambar) }}" 
                                             alt="{{ $produk->nama }}" 
                                             style="width: 60px; height: 60px; object-fit: cover; margin-right: 15px; border-radius: 8px;">
                                    @endif
                                    <div>
                                        <h5 class="mb-0">{{ $produk->nama }}</h5>
                                        <p class="text-muted mb-0">Rp. {{ number_format($produk->harga, 0, ",", ".") }}</p>
                                    </div>
                                </div>
                            </div>

                            @if ($existingReview)
                                <div class="alert alert-info">
                                    <p class="mb-2"><strong>Anda sudah memberikan ulasan untuk produk ini.</strong></p>
                                    <p class="mb-0">Rating: 
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="icon-star {{ $i <= $existingReview->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </p>
                                    @if ($existingReview->ulasan)
                                        <p class="mt-2 mb-0">Ulasan: "{{ $existingReview->ulasan }}"</p>
                                    @endif
                                </div>
                            @endif

                            <form class="" action="{{ route('ulasan.store', $produk->id_produk) }}" method="post">
                                @csrf
                                
                                <fieldset class="mb-4">
                                    <label class="form-label fw-semibold mb-2">Rating <span class="text-danger">*</span></label>
                                    <div class="rating-input">
                                        <input type="hidden" name="rating" id="rating-value" value="{{ old('rating', $existingReview->rating ?? '') }}" required>
                                        <div class="d-flex align-items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="icon-star rating-star" 
                                                   data-rating="{{ $i }}" 
                                                   style="font-size: 2rem; cursor: pointer; color: #ddd; margin-right: 5px;"
                                                   onmouseover="highlightStars({{ $i }})"
                                                   onmouseout="resetStars()"
                                                   onclick="setRating({{ $i }})"></i>
                                            @endfor
                                            <span class="ms-3 text-muted" id="rating-text">Pilih rating</span>
                                        </div>
                                    </div>
                                    @error('rating')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </fieldset>

                                <fieldset class="mb-4">
                                    <label class="form-label fw-semibold mb-2">Ulasan</label>
                                    <textarea name="ulasan" 
                                              class="form-control" 
                                              rows="5" 
                                              placeholder="Bagikan pengalaman Anda dengan produk ini...">{{ old('ulasan', $existingReview->ulasan ?? '') }}</textarea>
                                    @error('ulasan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </fieldset>

                                <div class="btn-submit d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <span class="text-white">{{ $existingReview ? 'Update Ulasan' : 'Kirim Ulasan' }}</span>
                                    </button>
                                    <a href="{{ route('produk.detail', $produk->id_produk) }}" class="btn btn-outline-danger">
                                        <span>Batal</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        let currentRating = {{ old('rating', $existingReview ? $existingReview->rating : 0) }};
        
        function highlightStars(rating) {
            const stars = document.querySelectorAll('.rating-star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#ddd';
                }
            });
            updateRatingText(rating);
        }
        
        function resetStars() {
            const stars = document.querySelectorAll('.rating-star');
            stars.forEach((star, index) => {
                if (index < currentRating) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#ddd';
                }
            });
            updateRatingText(currentRating);
        }
        
        function setRating(rating) {
            currentRating = rating;
            document.getElementById('rating-value').value = rating;
            const stars = document.querySelectorAll('.rating-star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#ddd';
                }
            });
            updateRatingText(rating);
        }
        
        function updateRatingText(rating) {
            const textElement = document.getElementById('rating-text');
            const texts = {
                1: 'Sangat Buruk',
                2: 'Buruk',
                3: 'Cukup',
                4: 'Baik',
                5: 'Sangat Baik'
            };
            textElement.textContent = rating > 0 ? texts[rating] : 'Pilih rating';
        }
        
        // Initialize stars on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (currentRating > 0) {
                setRating(currentRating);
            }
        });
    </script>
    @endpush
@endsection
