@extends("template")
@section("title", "Checkout - Rj Lina")

@section("body")
    <!-- Breakcrumbs -->
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li><a href="{{ route("home") }}" class="body-small link">Home</a></li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li><a href="{{ route("cart") }}" class="body-small link">Keranjang</a></li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li><span class="body-small">Checkout</span></li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <!-- Checkout -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="checkout-status tf-sp-2 pt-0">
                <div class="checkout-wrap">
                    <span class="checkout-bar next"></span>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-1"></i>
                        </span>
                        <a href="{{ route("cart") }}" class="text-dark">Keranjang Belanja</a>
                    </div>
                    <div class="step-payment active">
                        <span class="icon">
                            <i class="icon-shop-cart-2"></i>
                        </span>
                        <span class="text-secondary body-text-3">Checkout</span>
                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-3"></i>
                        </span>
                        <span class="link-secondary body-text-3">Konfirmasi</span>
                    </div>
                </div>
            </div>

            <div class="tf-checkout-wrap flex-lg-nowrap">
                <div class="page-checkout">

                    <div class="wrap">
                        <h5 class="title fw-semibold">Informasi Pengiriman</h5>
                        <form id="checkout-form" class="def">
                            @csrf
                            <input type="hidden" name="ongkir" id="input-ongkir" value="0" required>
                            <input type="hidden" name="kurir" id="input-kurir" value="" required>
                            <input type="hidden" name="layanan_ongkir" id="input-layanan-ongkir" value="" required>
                            <div class="cols">
                                <fieldset>
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" value="{{ $customer->nama ?? "" }}"
                                        placeholder="Nama lengkap Anda" required>
                                </fieldset>
                            </div>
                            <fieldset>
                                <label>Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                                <input type="tel" name="no_hp" value="{{ $customer->no_hp ?? "" }}"
                                    placeholder="08xx xxxx xxxx" required>
                            </fieldset>
                            <fieldset>
                                <label>Cari Alamat untuk cek ongkir <span class="text-danger">*</span></label>
                                <select class="form-control" name="destination" id="select-destination" required>
                                    <option value="">Cari lokasi domestik (Ketik kecamatan/kota)</option>
                                </select>
                                <p class="caption text-main-2 mt-1">Ketik nama kecamatan atau kota untuk mencari alamat
                                    pengiriman</p>
                            </fieldset>
                            <fieldset id="shipping-services-wrapper" style="display: none;">
                                <label>Pilih Layanan Pengiriman <span class="text-danger">*</span></label>
                                <select class="form-control" id="select-shipping-service" required>
                                    <option value="">Pilih layanan pengiriman</option>
                                </select>
                            </fieldset>
                            <fieldset>
                                <label>Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea name="alamat" rows="4" placeholder="Alamat lengkap untuk pengiriman" required>{{ $customer->alamat ?? "" }}</textarea>
                            </fieldset>
                        </form>
                    </div>

                    <div class="wrap">
                        <div class="form-payment">
                            <div class="box-btn">
                                <button type="button" id="btn-place-order" class="tf-btn w-100">
                                    <span class="text-white">Buat Pesanan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flat-sidebar-checkout">
                    <div class="sidebar-checkout-content">
                        <h5 class="fw-semibold">Ringkasan Pesanan</h5>
                        <ul class="list-product" id="checkout-summary">
                            <!-- Items will be dynamically inserted -->
                        </ul>
                        <ul class="sec-total-price">
                            <li><span class="body-text-3">Subtotal</span><span class="body-text-3"
                                    id="checkout-subtotal">Rp. 0</span></li>
                            <li><span class="body-text-3">Ongkir</span><span class="body-text-3" id="checkout-ongkir">Rp.
                                    0</span></li>
                            <li><span class="body-md-2 fw-semibold">Total</span><span
                                    class="body-md-2 fw-semibold text-primary" id="checkout-total">Rp. 0</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Checkout -->

    @push("scripts")
        <script>
            // Format price helper
            function formatPrice(price) {
                return new Intl.NumberFormat('id-ID').format(price);
            }

            // Safely format variant text to avoid duplicated parts (e.g. "Biru - 4 Inch - Biru - 4 Inch")
            function formatVariant(item) {
                const nama = (item.nama || '').toLowerCase();
                const jenisRaw = item.jenis_nama || '';
                if (!jenisRaw) return '';

                // split by ' - ' or commas if present
                const parts = jenisRaw.split(/\s*-\s*/).map(p => p.trim()).filter(Boolean);
                const unique = [];

                parts.forEach(p => {
                    const low = p.toLowerCase();

                    // skip if already added
                    if (unique.find(up => up.toLowerCase() === low)) return;

                    // skip if this part is already included in item nama (to avoid repeats)
                    if (nama.indexOf(low) !== -1) return;

                    unique.push(p);
                });

                if (unique.length === 0) return '';
                return `<p class="body-md-2 text-main-2">${unique.join(' - ')}</p>`;
            }

            // Render checkout summary
            function renderCheckoutSummary() {
                // Get cart from localStorage directly
                const cartData = localStorage.getItem('ria_shopping_cart');
                const cart = cartData ? JSON.parse(cartData) : [];

                const checkoutSummary = document.getElementById('checkout-summary');
                const checkoutSubtotal = document.getElementById('checkout-subtotal');
                const checkoutTotal = document.getElementById('checkout-total');

                if (cart.length === 0) {
                    window.location.href = '{{ route("cart") }}';
                    return;
                }

                // Render items using template structure (use formatVariant to avoid duplicated variant text)
                checkoutSummary.innerHTML = cart.map(item => {
                    const itemTotal = parseInt(item.harga) * parseInt(item.quantity);
                    return `
                <li class="item-product">
                    <a href="#" class="img-product">
                        <img src="${item.gambar}" alt="${item.nama}">
                    </a>
                    <div class="content-box">
                        <a href="#" class="link-secondary body-md-2 fw-semibold">
                            ${item.nama}
                        </a>
                        <p class="price-quantity price-text fw-semibold">
                            Rp. ${formatPrice(itemTotal)}
                            <span class="body-md-2 text-main-2 fw-normal">X${item.quantity}</span>
                        </p>
                        ${formatVariant(item)}
                    </div>
                </li>
            `;
                }).join('');

                // Calculate and update totals
                const total = cart.reduce((sum, item) => {
                    return sum + (parseInt(item.harga) * parseInt(item.quantity));
                }, 0);

                checkoutSubtotal.textContent = `Rp. ${formatPrice(total)}`;
                checkoutTotal.textContent = `Rp. ${formatPrice(total)}`;
            }

            // Process checkout
            document.getElementById('btn-place-order').addEventListener('click', function() {
                const form = document.getElementById('checkout-form');

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                // Get cart from localStorage
                const cartData = localStorage.getItem('ria_shopping_cart');
                const cart = cartData ? JSON.parse(cartData) : [];

                if (cart.length === 0) {
                    alert('Keranjang belanja kosong!');
                    return;
                }

                // Debug: Log cart data
                console.log('Cart data being sent:', cart);

                // Disable button
                this.disabled = true;
                this.innerHTML = '<span class="text-white">Memproses...</span>';

                // Prepare FormData with file upload
                const formData = new FormData(form);
                formData.append('cart_data', JSON.stringify(cart));

                // Send to server
                fetch('{{ route("checkout.process") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': formData.get('_token')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Clear cart from localStorage
                            localStorage.removeItem('ria_shopping_cart');

                            // Clear cart object if available
                            if (window.cart) {
                                window.cart.clearCart();
                            }

                            // Show success message
                            alert('Pesanan berhasil dibuat! Kode Invoice: ' + data.kode_invoice);

                            // Redirect to order confirmation
                            window.location.href = data.redirect_url;
                        } else {
                            alert('Terjadi kesalahan: ' + data.message);
                            this.disabled = false;
                            this.innerHTML = '<span class="text-white">Buat Pesanan</span>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses pesanan');
                        this.disabled = false;
                        this.innerHTML = '<span class="text-white">Buat Pesanan</span>';
                    });
            });

            // Initial render - wait for DOM
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(renderCheckoutSummary, 100);
                    initializeSelect2();
                });
            } else {
                setTimeout(renderCheckoutSummary, 100);
                initializeSelect2();
            }

            // Initialize Select2 for district search
            function initializeSelect2() {
                $('#select-destination').select2({
                    ajax: {
                        url: '{{ route("rajaongkir.districts") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term,
                                page: params.page || 1
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results,
                                pagination: data.pagination
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Ketik nama kecamatan atau kota',
                    minimumInputLength: 3,
                    theme: 'bootstrap-5'
                });

                // When destination is selected, calculate shipping cost
                $('#select-destination').on('select2:select', function(e) {
                    const destinationId = e.params.data.id;
                    calculateShipping(destinationId);
                });
            }

            // Calculate shipping cost
            function calculateShipping(destinationId) {
                // Get cart to calculate weight (assuming 1kg per item for now)
                const cartData = localStorage.getItem('ria_shopping_cart');
                const cart = cartData ? JSON.parse(cartData) : [];

                if (cart.length === 0) {
                    return;
                }

                // Calculate total weight (in grams, minimum 1000g = 1kg)
                // You can adjust this logic based on your product weight data
                const totalWeight = cart.reduce((sum, item) => sum + (item.quantity * 1000), 0);

                // Show loading state
                $('#shipping-services-wrapper').hide();
                $('#select-shipping-service').html('<option value="">Memuat layanan pengiriman...</option>');

                // Call API to get shipping cost
                fetch('{{ route("rajaongkir.cost") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            destination: destinationId,
                            weight: totalWeight
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.services && data.services.length > 0) {
                            // Populate shipping services
                            let options = '<option value="">Pilih layanan pengiriman</option>';
                            data.services.forEach(service => {
                                const cost = parseInt(service.cost);
                                options += `<option value="${service.service}"
                                    data-cost="${cost}"
                                    data-courier="${data.courier}"
                                    data-etd="${service.etd}">
                                    ${data.courier_name} - ${service.service} (${service.description}) - Rp. ${formatPrice(cost)} (${service.etd} hari)
                                </option>`;
                            });

                            $('#select-shipping-service').html(options);
                            $('#shipping-services-wrapper').show();

                            // Handle service selection
                            $('#select-shipping-service').off('change').on('change', function() {
                                const selectedOption = $(this).find('option:selected');
                                const cost = parseInt(selectedOption.data('cost')) || 0;
                                const courier = selectedOption.data('courier') || '';
                                const service = selectedOption.val() || '';

                                // Update hidden inputs
                                $('#input-ongkir').val(cost);
                                $('#input-kurir').val(courier);
                                $('#input-layanan-ongkir').val(service);

                                // Update checkout summary
                                updateCheckoutTotal(cost);
                            });
                        } else {
                            alert(data.message || 'Tidak ada layanan pengiriman yang tersedia');
                            $('#select-shipping-service').html('<option value="">Tidak ada layanan tersedia</option>');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghitung ongkir');
                        $('#select-shipping-service').html('<option value="">Error memuat layanan</option>');
                    });
            }

            // Update checkout total with shipping cost
            function updateCheckoutTotal(shippingCost) {
                const cartData = localStorage.getItem('ria_shopping_cart');
                const cart = cartData ? JSON.parse(cartData) : [];

                const subtotal = cart.reduce((sum, item) => {
                    return sum + (parseInt(item.harga) * parseInt(item.quantity));
                }, 0);

                const total = subtotal + shippingCost;

                document.getElementById('checkout-ongkir').textContent = `Rp. ${formatPrice(shippingCost)}`;
                document.getElementById('checkout-total').textContent = `Rp. ${formatPrice(total)}`;
            }
        </script>
    @endpush
@endsection
