<?php if (!isset($current_page)) $current_page = ""; ?>

<div class="sidebar">

    <div class="sidebar-header">
        <i class="fa-solid fa-book"></i>
        <div class="admin-name">HELLO ADMIN1</div>
    </div>

    <ul class="menu">

        <li class="<?= ($current_page == 'khach_hang') ? 'active' : '' ?>">
            <a href="/admin/nguoi_dung/list.php">
                <i class="fa-solid fa-user"></i> Người dùng
            </a>
        </li>

        <li class="<?= ($current_page == 'danhmuc') ? 'active' : '' ?>">
            <a href="/admin/danh_muc/list.php">
                <i class="fa-solid fa-list"></i> Danh mục
            </a>
        </li>

        <li class="<?= ($current_page == 'sach') ? 'active' : '' ?>">
            <a href="/admin/sach/list.php">
                <i class="fa-solid fa-book-open"></i> Sách
            </a>
        </li>

        <li class="<?= ($current_page == 'bo_sach') ? 'active' : '' ?>">
            <a href="/admin/bo_sach/list.php">
                <i class="fa-solid fa-box"></i> Bộ sách
            </a>
        </li>

        <li class="<?= ($current_page == 'don_hang') ? 'active' : '' ?>">
            <a href="/admin/don_hang/list.php">
                <i class="fa-solid fa-cart-shopping"></i> Đơn hàng
            </a>
        </li>

        <li class="<?= ($current_page == 'phan_hoi') ? 'active' : '' ?>">
            <a href="/admin/phan_hoi/list.php">
                <i class="fa-solid fa-comment-dots"></i> Phản hồi
            </a>
        </li>

        <li class="<?= ($current_page == 'thong_ke') ? 'active' : '' ?>">
            <a href="/admin/index.php">
                <i class="fa-solid fa-chart-column"></i> Thống kê
            </a>
        </li>

    </ul>

    <div class="logout-box">
        <a href="/admin/logout.php">ĐĂNG XUẤT</a>
    </div>

</div>
