    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Dashboard</h3>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item <?php echo ($role === 'admin') ? '' : 'd-none'; ?>">
                <a class="nav-link" href="../admin/">Kelola Akun</a>
            </li>
            <li class="nav-item <?php echo ($role === 'admin' || $role === 'owner') ? '' : 'd-none'; ?>">
                <a class="nav-link" href="../activity/">Log Activity</a>
            </li>
            <li class="nav-item <?php echo ($role === 'admin' || $role === 'kasir') ? '' : 'd-none'; ?>">
                <a class="nav-link" href="../transaksi/">Transaksi</a>
            </li>
            <li class="nav-item <?php echo ($role === 'admin') ? '' : 'd-none'; ?>">
                <a class="nav-link" href="../product/">Data Produk</a>
            </li>
        </ul>
        <ul class="nav flex-column mt-auto">
            <li class="nav-item">
                <a class="nav-link logout-link" href="../auth/logout.php">Keluar</a>
            </li>
        </ul>
    </div>

