<?php include "includes/header.php"; ?>
<link rel="stylesheet" href="/client/assets/css/checkout.css">

<div class="checkout-container">

    <h1 class="checkout-title">Thanh toán</h1>

    <div class="checkout-grid">

        <!-- LEFT: INFO -->
        <div class="checkout-left">

            <div class="ship-box">
                <h3>Thông tin giao hàng</h3>
                <p>Nguyễn Văn A ( 084 987 654 321 )</p>
                <p>123 Đường ABC, Phường 1, Quận 2, TP. HCM</p>
                <a class="change-link">Thay đổi</a>
            </div>

            <div class="payment-box">
                <h3>Phương thức thanh toán</h3>

                <label class="pay-option active">
                    <input type="radio" checked>
                    Thẻ tín dụng/ghi nợ
                </label>

                <div class="card-form">
                    <input type="text" placeholder="Số thẻ">
                    <input type="text" placeholder="Tên chủ thẻ">
                    <div class="row">
                        <input type="text" placeholder="MM/YY">
                        <input type="text" placeholder="CVV">
                    </div>
                </div>

                <label class="pay-option">
                    <input type="radio">
                    Thanh toán khi nhận hàng (COD)
                </label>

                <label class="pay-option">
                    <input type="radio">
                    Ví điện tử
                </label>

            </div>

        </div>

        <!-- RIGHT: ORDER SUMMARY -->
        <div class="checkout-summary">

            <h3>Tóm tắt đơn hàng</h3>

            <div class="summary-item">
                <img src="assets/img/book1.jpg">
                <div class="sum-info">
                    <div class="sum-name">Nhà Giả Kim</div>
                    <div class="sum-qty">Số lượng: 1</div>
                </div>
                <div class="sum-price">69.000₫</div>
            </div>

            <div class="summary-item">
                <img src="assets/img/book2.jpg">
                <div class="sum-info">
                    <div class="sum-name">Đắc Nhân Tâm</div>
                    <div class="sum-qty">Số lượng: 1</div>
                </div>
                <div class="sum-price">84.000₫</div>
            </div>

            <div class="summary-total">
                <div>Tạm tính</div> <strong>153.000₫</strong>
                <div>Phí vận chuyển</div> <strong>15.000₫</strong>
                <div>Giảm giá</div> <strong class="green">−10.000₫</strong>
            </div>

            <div class="grand-total">
                <span>Tổng cộng</span>
                <strong>158.000₫</strong>
            </div>

            <a class="btn-finish">Hoàn tất đặt hàng</a>

        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>
