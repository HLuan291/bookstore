</main>

<footer class="site-footer">
    <p>© 2025 Bookstore. All rights reserved.</p>
</footer>

<script>
function addToCart(bookId) {
    // 1. Tạo dữ liệu gửi đi
    var formData = new FormData();
    formData.append('id', bookId);

    // 2. Gửi đến ajax_cart.php
    fetch('ajax_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // 3. Cập nhật số lượng trên header
            var badge = document.getElementById('header-cart-count');
            if (badge) {
                badge.innerText = data.total;
                badge.style.display = 'flex'; // Hiện badge lên
            }
            
            // 4. Thông báo nhỏ (Bạn có thể thay bằng thư viện Toast nếu muốn đẹp hơn)
            alert("Đã thêm sản phẩm vào giỏ hàng!");
        } else {
            alert("Lỗi: " + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

</body>
</html>