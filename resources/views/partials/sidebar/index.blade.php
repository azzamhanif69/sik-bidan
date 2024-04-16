@can('admin')
    <li class="menu-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
        <a class="menu-link cursor-pointer" onclick="window.location.href='/admin/dashboard'">
            <i class="menu-icon tf-icons bx bx-home"></i>
            <div>Dashboard</div>
        </a>
    </li>
    <li class="menu-item {{ Request::is('admin/pasien*') ? 'active' : '' }}">
        <a class="menu-link cursor-pointer" onclick="window.location.href='/admin/pasien'">
            <i class="menu-icon tf-icons bx bx-group"></i>
            <div>Data Pasien</div>
        </a>
    </li>
    <li class="menu-item {{ Request::is('admin/obat*') ? 'active' : '' }}">
        <a class="menu-link cursor-pointer" onclick="window.location.href='/admin/obat'">
            <i class="menu-icon tf-icons bx bx-capsule"></i>
            <div>Data Obat</div>
        </a>
    </li>
    <li class="menu-item {{ Request::is('admin/medis*') ? 'active' : '' }}">
        <a class="menu-link cursor-pointer" onclick="window.location.href='/admin/medis'">
            <i class="menu-icon tf-icons bx bx-book-alt"></i>
            <div>Rekam Medis</div>
        </a>
    </li>
    <li class="menu-item {{ Request::is('admin/pengaturan*') ? 'active' : '' }}">
        <a class="menu-link cursor-pointer" onclick="window.location.href='/admin/pengaturan'">
            <i class="menu-icon tf-icons bx bx-cog"></i>
            <div>Pengaturan</div>
        </a>
    </li>
@endcan
