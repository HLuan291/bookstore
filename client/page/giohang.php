<!-- Giỏ hàng -->
    <link rel="stylesheet" href="<?= dirname(__DIR__) ?>/assets/css/cart.css">

    <div class="cart-container">

        <h1 class="cart-title">Giỏ hàng của bạn</h1>

        <div class="cart-grid">
            
            <!-- LEFT: DANH SÁCH SẢN PHẨM -->
            <div class="cart-items">

                <!-- SP 1 -->
                <div class="cart-item">
                    <img src="assets/img/book1.jpg" class="cart-img">

                    <div class="cart-info">
                        <div class="cart-name">To Kill a Mockingbird</div>
                        <div class="cart-author">Harper Lee</div>
                    </div>

                    <div class="cart-qty">
                        <button class="qty-btn">−</button>
                        <span class="qty-number">1</span>
                        <button class="qty-btn">+</button>
                    </div>

                    <div class="cart-price">
                        250.000₫
                    </div>

                    <div class="cart-total">
                        <strong>250.000₫</strong>
                    </div>
                </div>

                <!-- SP 2 -->
                <div class="cart-item">
                    <img src="assets/img/book2.jpg" class="cart-img">

                    <div class="cart-info">
                        <div class="cart-name">1984</div>
                        <div class="cart-author">George Orwell</div>
                    </div>

                    <div class="cart-qty">
                        <button class="qty-btn">−</button>
                        <span class="qty-number">2</span>
                        <button class="qty-btn">+</button>
                    </div>

                    <div class="cart-price">
                        220.000₫
                    </div>

                    <div class="cart-total">
                        <strong>440.000₫</strong>
                    </div>
                </div>

                <a href="index.php" class="continue-link">← Tiếp tục mua sắm</a>

            </div>

            <!-- RIGHT: THÔNG TIN GIAO HÀNG -->
            <div class="cart-shipping">

                <h3 class="ship-title">Địa chỉ giao hàng</h3>

                <form class="ship-form">
                    <label>Họ và tên người nhận</label>
                    <input type="text" placeholder="Nhập họ và tên">

                    <label>Email</label>
                    <input type="email" placeholder="Nhập email">

                    <label>Số điện thoại</label>
                    <input type="text" placeholder="Ví dụ: 0979123xxx">

                    <label>Quốc gia</label>
                    <select>
                        <option>Việt Nam</option>
                    </select>

                    <label>Tỉnh/Thành phố</label>
                    <select>
                        <option>Chọn tỉnh/thành</option>
                    </select>

                    <label>Quận/Huyện</label>
                    <select>
                        <option>Chọn quận/huyện</option>
                    </select>

                    <label>Phường/Xã</label>
                    <select>
                        <option>Chọn phường/xã</option>
                    </select>

                    <label>Địa chỉ nhận hàng</label>
                    <input type="text" placeholder="Nhập địa chỉ">
                </form>

                <a href="checkout.php" class="btn-checkout">Thanh toán</a>

            </div>

        </div>
    </div>

<!-- Footer handled by client/index.php -->
