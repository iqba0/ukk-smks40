<div class="sidebar">
    <h3 class="text-center my-4">Dashboard</h3>
    <ul class="nav flex-column">
        <li class="nav-item <?php echo ($role === 'admin') ? '' : 'd-none'; ?>">
            <a class="nav-link" href="admin/">Kelola Akun</a>
        </li>
        <li class="nav-item <?php echo ($role === 'admin' || $role === 'owner') ? '' : 'd-none'; ?>">
            <a class="nav-link" href="activity/log_activity.php">Log Activity</a>
        </li>
        <li class="nav-item <?php echo ($role === 'admin' || $role === 'owner' || $role === 'kasir') ? '' : 'd-none'; ?>">
            <a class="nav-link" href="transaksi/">Transaksi</a>
        </li>
        <li class="nav-item <?php echo ($role === 'admin') ? '' : 'd-none'; ?>">
            <a class="nav-link" href="produk">Data Produk</a>
        </li>
        <li class="nav-item <?php echo ($role === 'owner') ? '' : 'd-none'; ?>">
            <a class="nav-link" href="owner/laporan.php">Laporan</a>
        </li>
    </ul>
    <ul class="nav flex-column mt-auto">
        <li class="nav-item">
            <a class="nav-link" href="auth/logout.php">Logout</a>
        </li>
    </ul>
</div>
