@extends("template")
@section("title", "Keranjang Belanja - Rj Lina")

@section("body")
    <!-- Breakcrumbs -->
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li><a href="{{ route("home") }}" class="body-small link">Home</a></li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li><span class="body-small">Keranjang</span></li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <!-- Shopping Cart -->
    <div class="s-shoping-cart tf-sp-2">
        <div class="container">
            <div class="checkout-status tf-sp-2 pt-0">
                <div class="checkout-wrap">
                    <span class="checkout-bar first"></span>
                    <div class="step-payment active">
                        <span class="icon">
                            <i class="icon-shop-cart-1"></i>
                        </span>
                        <a href="{{ route("cart") }}" class="text-secondary body-text-3">Keranjang Belanja</a>
                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-2"></i>
                        </span>
                        <a href="{{ route("checkout") }}" class="link-secondary body-text-3">Checkout</a>
                    </div>
                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-3"></i>
                        </span>
                        <span class="link-secondary body-text-3">Konfirmasi</span>
                    </div>
                </div>
            </div>

            <!-- Empty Cart Message -->
            <div class="cart-empty-message text-center py-5" style="display: none;">
                <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M80.6344 72.6641H33.3641C32.8541 72.6646 32.3525 72.5345 31.907 72.2864C31.4615 72.0383 31.0869 71.6803 30.8188 71.2465C30.5507 70.8127 30.398 70.3176 30.3753 69.8081C30.3526 69.2987 30.4606 68.7919 30.6891 68.336L33.4656 62.7844C33.6401 62.4347 33.678 62.0325 33.5719 61.6563L22.0563 21.361C21.7786 20.4019 21.1977 19.5587 20.4005 18.9575C19.6033 18.3564 18.6328 18.0298 17.6344 18.0266H7.78282C7.36822 18.0266 6.97059 18.1913 6.67742 18.4845C6.38425 18.7777 6.21954 19.1753 6.21954 19.5899C6.21954 20.0045 6.38425 20.4021 6.67742 20.6953C6.97059 20.9885 7.36822 21.1532 7.78282 21.1532H17.6359C17.9554 21.1542 18.2658 21.2587 18.5208 21.4511C18.7758 21.6436 18.9615 21.9135 19.05 22.2204L30.3984 61.9313L27.8922 66.9391C27.4257 67.8717 27.2054 68.9081 27.2523 69.9497C27.2991 70.9914 27.6115 72.0038 28.1598 72.8908C28.7081 73.7777 29.4741 74.5098 30.3849 75.0173C31.2958 75.5249 32.3213 75.7911 33.3641 75.7907H80.6344C81.0488 75.7907 81.4462 75.6261 81.7392 75.333C82.0323 75.04 82.1969 74.6426 82.1969 74.2282C82.1969 73.8138 82.0323 73.4163 81.7392 73.1233C81.4462 72.8303 81.0488 72.6641 80.6344 72.6641Z"
                        fill="#73787D"></path>
                    <path
                        d="M93.175 25.3828C92.8884 24.9852 92.5114 24.6615 92.0751 24.4382C91.6388 24.2149 91.1557 24.0984 90.6656 24.0984H27.7266C27.3122 24.0984 26.9147 24.263 26.6217 24.556C26.3287 24.8491 26.1641 25.2465 26.1641 25.6609C26.1641 26.0753 26.3287 26.4727 26.6217 26.7657C26.9147 27.0588 27.3122 27.2234 27.7266 27.2234L90.625 27.1718L85.5781 42.3125H32.9312C32.5168 42.3125 32.1194 42.4771 31.8264 42.7701C31.5334 43.0631 31.3687 43.4606 31.3687 43.875C31.3687 44.2894 31.5334 44.6868 31.8264 44.9798C32.1194 45.2728 32.5168 45.4375 32.9312 45.4375H84.5359L79.5078 60.5234H38.1375C37.7229 60.5234 37.3253 60.6881 37.0321 60.9813C36.7389 61.2744 36.5742 61.6721 36.5742 62.0867C36.5742 62.5013 36.7389 62.8989 37.0321 63.1921C37.3253 63.4852 37.7229 63.6499 38.1375 63.6499H80.6344C80.9624 63.65 81.2822 63.5468 81.5484 63.355C81.8145 63.1632 82.0135 62.8925 82.1172 62.5812L93.5875 28.1671C93.7438 27.7037 93.7879 27.2099 93.7162 26.7261C93.6445 26.2423 93.459 25.7809 93.175 25.3828ZM32.0672 78.7343C21.9781 79.0562 21.9797 93.6843 32.0672 94.0031C42.1562 93.6828 42.1531 79.0515 32.0672 78.7343ZM32.0672 90.8765C30.8716 90.8765 29.7251 90.4016 28.8797 89.5562C28.0343 88.7108 27.5594 87.5642 27.5594 86.3687C27.5594 85.1732 28.0343 84.0266 28.8797 83.1812C29.7251 82.3358 30.8716 81.8609 32.0672 81.8609C33.2627 81.8609 34.4093 82.3358 35.2547 83.1812C36.1001 84.0266 36.575 85.1732 36.575 86.3687C36.575 87.5642 36.1001 88.7108 35.2547 89.5562C34.4093 90.4016 33.2627 90.8765 32.0672 90.8765ZM74.5625 78.7343C64.4734 79.0546 64.475 93.6843 74.5625 94.0031C84.6531 93.6828 84.65 79.0531 74.5625 78.7343ZM74.5625 90.8765C73.367 90.8765 72.2204 90.4016 71.375 89.5562C70.5296 88.7108 70.0547 87.5642 70.0547 86.3687C70.0547 85.1732 70.5296 84.0266 71.375 83.1812C72.2204 82.3358 73.367 81.8609 74.5625 81.8609C75.758 81.8609 76.9046 82.3358 77.75 83.1812C78.5954 84.0266 79.0703 85.1732 79.0703 86.3687C79.0703 87.5642 78.5954 88.7108 77.75 89.5562C76.9046 90.4016 75.758 90.8765 74.5625 90.8765Z"
                        fill="#73787D"></path>
                </svg>
                <h4 class="mt-4">Keranjang Belanja Kosong</h4>
                <p class="text-secondary">Mari temukan produk yang sempurna untuk Anda</p>
                <a href="{{ route("home") }}" class="tf-btn mt-3">
                    <span class="text-white">Belanja Sekarang</span>
                </a>
            </div>

            <!-- Cart Table -->
            <div class="cart-content-wrap" style="display: none;">
                <form class="form-discount">
                    <div class="overflow-x-auto">
                        <table class="tf-table-page-cart">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cart-items-table">
                                <!-- Cart items will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-bottom text-end">
                        <p class="last-total-price main-title fw-semibold" id="cart-grand-total">Total: Rp. 0</p>
                    </div>
                </form>

                <div class="box-btn">
                    <a href="{{ route("home") }}" class="tf-btn btn-gray">
                        <span class="text-white">Lanjut Belanja</span>
                    </a>
                    <a href="{{ route("checkout") }}" class="tf-btn">
                        <span class="text-white">Lanjut ke Checkout</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
    <!-- /Shopping Cart -->

    @push("scripts")
        <script>
            // Format price helper
            function formatPrice(price) {
                return new Intl.NumberFormat('id-ID').format(price);
            }

            // Render cart page from localStorage
            function renderCartPage() {
                // Get cart from localStorage directly
                const cartData = localStorage.getItem('ria_shopping_cart');
                const cart = cartData ? JSON.parse(cartData) : [];

                const cartEmpty = document.querySelector('.cart-empty-message');
                const cartContent = document.querySelector('.cart-content-wrap');
                const cartItemsTable = document.getElementById('cart-items-table');
                const cartGrandTotal = document.getElementById('cart-grand-total');

                if (cart.length === 0) {
                    cartEmpty.style.display = 'block';
                    cartContent.style.display = 'none';
                } else {
                    cartEmpty.style.display = 'none';
                    cartContent.style.display = 'block';

                    // Render cart items
                    cartItemsTable.innerHTML = cart.map(item => {
                        const itemTotal = parseInt(item.harga) * parseInt(item.quantity);
                        return `
                    <tr class="tf-cart-item">
                        <td class="tf-cart-item_product">
                            <a href="/produk/${item.id}" class="img-box">
                                <img src="${item.gambar}" alt="${item.nama}">
                            </a>
                            <div class="cart-info">
                                <a href="/produk/${item.id}" class="cart-title body-md-2 fw-semibold link">
                                    ${item.nama}
                                </a>
                                ${item.jenis_nama ? `
                                                <div class="variant-box">
                                                    <p class="body-text-3">Jenis: ${item.jenis_nama}</p>
                                                </div>
                                                ` : ''}
                            </div>
                        </td>
                        <td data-cart-title="Harga" class="tf-cart-item_price">
                            <p class="cart-price price-on-sale price-text fw-medium">
                                Rp. ${formatPrice(item.harga)}
                            </p>
                        </td>
                        <td data-cart-title="Jumlah" class="tf-cart-item_quantity">
                            <div class="wg-quantity">
                                <span class="btn-quantity btn-decrease" onclick="attemptUpdateCartQty(${item.id}, ${item.jenis_id || null}, ${item.quantity - 1}, ${item.stok || 0})" ${item.quantity <= item.minBeli ? 'style="opacity:.6;pointer-events:none" disabled' : ''}>
                                    <i class="icon-minus"></i>
                                </span>
                                <input class="quantity-product" type="text" value="${item.quantity}" readonly data-stock="${item.stock || 0}">
                                <span class="btn-quantity btn-increase" onclick="attemptUpdateCartQty(${item.id}, ${item.jenis_id || null}, ${item.quantity + 1}, ${item.stok || 0})" ${item.stok && item.quantity >= item.stok ? 'style="opacity:.6;pointer-events:none" disabled' : ''}>
                                    <i class="icon-plus"></i>
                                </span>
                            </div>
                        </td>
                        <td data-cart-title="Total" class="tf-cart-item_total">
                            <p class="cart-total total-price price-text fw-medium">
                                Rp. ${formatPrice(itemTotal)}
                            </p>
                        </td>
                        <td data-cart-title="Hapus" class="remove-cart text-xxl-end">
                            <span class="remove icon icon-close link" onclick="removeCartItem(${item.id}, ${item.jenis_id || null})"></span>
                        </td>
                    </tr>
                `;
                    }).join('');

                    // Calculate and update total
                    const total = cart.reduce((sum, item) => {
                        return sum + (parseInt(item.harga) * parseInt(item.quantity));
                    }, 0);

                    cartGrandTotal.textContent = `Total: Rp. ${formatPrice(total)}`;
                }
            }

            // Attempt to update cart qty but check stock first
            function attemptUpdateCartQty(productId, jenisId, quantity, stock, minBeli = 1) {
                // fallback: if stock is not provided (0), allow update
                const available = (typeof stock === 'number' && stock > 0) ? stock : Infinity;
                if (quantity > available) {
                    alert('Jumlah yang dipilih melebihi sisa stok (' + (available === Infinity ? 0 : available) + ').');
                    return;
                }
                updateCartQty(productId, jenisId, quantity);
            }

            function updateCartQty(productId, jenisId, quantity) {
                if (quantity > 0) {
                    if (window.cart) {
                        window.cart.updateQuantity(productId, jenisId, quantity);
                    } else {
                        // Update manually if cart not loaded yet
                        const cartData = localStorage.getItem('ria_shopping_cart');
                        const cart = cartData ? JSON.parse(cartData) : [];
                        const itemIndex = cart.findIndex(item =>
                            item.id === productId && item.jenis_id === jenisId
                        );
                        if (itemIndex > -1) {
                            cart[itemIndex].quantity = quantity;
                            localStorage.setItem('ria_shopping_cart', JSON.stringify(cart));
                        }
                    }
                    renderCartPage();
                }
            }

            function removeCartItem(productId, jenisId) {
                if (confirm('Hapus produk dari keranjang?')) {
                    if (window.cart) {
                        window.cart.removeFromCart(productId, jenisId);
                    } else {
                        // Remove manually if cart not loaded yet
                        const cartData = localStorage.getItem('ria_shopping_cart');
                        let cart = cartData ? JSON.parse(cartData) : [];
                        cart = cart.filter(item => !(item.id === productId && item.jenis_id === jenisId));
                        localStorage.setItem('ria_shopping_cart', JSON.stringify(cart));
                    }
                    renderCartPage();
                }
            }

            // Initial render - wait for DOM and cart to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(renderCartPage, 100);
                });
            } else {
                setTimeout(renderCartPage, 100);
            }
        </script>
    @endpush
@endsection
