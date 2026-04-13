<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Lancar Ekspedisi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Flutter AppColors palette */
        :root {
            --orange:        #F97316;
            --orange-dark:   #EA580C;
            --orange-light:  #FB923C;
            --orange-surface:#FFF7ED;
            --dark-bg:       #0A0A0A;
            --dark-surface:  #141414;
            --dark-surface2: #1F1F1F;
            --dark-border:   rgba(255,255,255,0.08);
            --sidebar-width: 252px;
            --body-bg:       #F8F9FA;
        }

        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: var(--body-bg); margin: 0; }

        /* ══════════════ SIDEBAR ══════════════ */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--dark-bg);
            position: fixed; top: 0; left: 0; z-index: 100;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,0.35);
        }

        .sidebar-brand {
            padding: 22px 18px 18px;
            display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid var(--dark-border);
            margin-bottom: 10px;
        }
        .brand-icon {
            width: 40px; height: 40px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--orange-light), var(--orange-dark));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: white;
            box-shadow: 0 4px 14px rgba(249,115,22,.4);
        }
        .brand-name  { font-size: 14px; font-weight: 700; color: #F9FAFB; line-height: 1.2; }
        .brand-sub   { font-size: 11px; color: #6B7280; }

        /* Nav */
        .sidebar-nav { flex-grow: 1; padding: 0 10px; }

        .nav-section-label {
            font-size: 10px; font-weight: 600; color: #4B5563;
            text-transform: uppercase; letter-spacing: .08em;
            padding: 14px 10px 5px;
        }

        .sidebar a {
            color: #9CA3AF;
            text-decoration: none;
            padding: 10px 14px;
            display: flex; align-items: center; gap: 10px;
            border-radius: 10px; margin: 2px 0;
            font-size: 13.5px; font-weight: 500;
            transition: background .15s, color .15s;
        }
        .sidebar a i { font-size: 16px; width: 18px; }
        .sidebar a:hover { background: var(--dark-surface2); color: #F9FAFB; }
        .sidebar a.active {
            background: linear-gradient(135deg, var(--orange-light)22, var(--orange-dark)22);
            color: var(--orange-light);
            border: 1px solid rgba(249,115,22,.18);
        }

        .sidebar-footer {
            padding: 10px 10px 20px;
            border-top: 1px solid var(--dark-border);
        }
        .sidebar-footer a { color: #EF4444 !important; }
        .sidebar-footer a:hover { background: rgba(239,68,68,.1) !important; color: #EF4444 !important; }

        /* ══════════════ MAIN ══════════════ */
        .main-wrapper { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }

        /* Topbar */
        .topbar {
            background: white;
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #E5E7EB;
            position: sticky; top: 0; z-index: 99;
        }
        .topbar .page-title { font-size: 17px; font-weight: 700; color: #111827; margin: 0; }
        .admin-chip {
            display: flex; align-items: center; gap: 9px;
            background: #F9FAFB; border: 1px solid #E5E7EB;
            border-radius: 100px; padding: 5px 14px 5px 7px;
        }
        .admin-avatar {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, var(--orange-light), var(--orange-dark));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; color: white; font-weight: 700;
        }
        .admin-name { font-size: 13px; font-weight: 600; color: #374151; }

        .content-area { padding: 28px; flex-grow: 1; }

        /* ══════════════ COMPONENTS ══════════════ */
        /* Card */
        .card {
            border: 1px solid #E5E7EB !important;
            border-radius: 16px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
        }

        /* Table */
        .table th {
            font-size: 11.5px; font-weight: 600; text-transform: uppercase;
            letter-spacing: .05em; color: #6B7280; background: #F9FAFB;
            border-bottom: 1px solid #E5E7EB; padding: 12px 14px;
        }
        .table td {
            font-size: 13.5px; color: #374151; vertical-align: middle;
            border-bottom: 1px solid #F3F4F6; padding: 13px 14px;
        }
        .table tr:last-child td { border-bottom: none; }
        .table-hover tbody tr:hover td { background: #FFF7ED; }

        /* Forms */
        .form-select, .form-control {
            border-radius: 10px !important; border-color: #E5E7EB !important;
            font-size: 13.5px; padding: 9px 12px;
        }
        .form-select:focus, .form-control:focus {
            border-color: var(--orange) !important;
            box-shadow: 0 0 0 3px rgba(249,115,22,.15) !important;
        }

        /* Buttons */
        .btn { border-radius: 8px; font-size: 13px; font-weight: 500; }
        .btn-primary {
            background: var(--orange) !important; border-color: var(--orange) !important;
        }
        .btn-primary:hover { background: var(--orange-dark) !important; border-color: var(--orange-dark) !important; }
        .btn-sm { padding: 5px 11px; font-size: 12px; }

        /* ══ Badges ══ */
        .badge { font-size: 11.5px; font-weight: 500; padding: 5px 10px; border-radius: 6px; }

        /* ══ Pagination ══ */
        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-top: 1px solid #F1F5F9;
            background: #FAFBFF;
            border-radius: 0 0 16px 16px;
        }
        .pagination-info {
            font-size: 12.5px;
            color: #6B7280;
        }
        .pagination { gap: 4px; margin: 0; padding: 0; list-style: none; display: flex; }
        .page-link {
            border-radius: 8px !important;
            border: 1px solid #E5E7EB !important;
            color: var(--orange);
            font-size: 13px !important;
            line-height: 1 !important;
            padding: 0 !important;
            font-weight: 500;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 34px !important;
            height: 34px !important;
            transition: all .15s;
        }
        .page-link svg { display: none !important; }
        .page-link .bi { font-size: 13px; }
        .page-link:hover { background: var(--orange-surface) !important; border-color: var(--orange) !important; color: var(--orange-dark); }
        .page-item.active .page-link {
            background: var(--orange) !important; border-color: var(--orange) !important;
            color: white !important;
            box-shadow: 0 2px 8px rgba(249,115,22,.35);
        }
        .page-item.disabled .page-link {
            background: #F9FAFB !important; color: #D1D5DB !important;
            border-color: #F1F5F9 !important; pointer-events: none;
        }

        /* ══ Alerts ══ */
        .alert { border-radius: 12px; font-size: 13.5px; }
        .alert-success { background: #F0FDF4; border-color: #86EFAC; color: #166534; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-truck-front-fill"></i></div>
        <div>
            <div class="brand-name">Lancar Ekspedisi</div>
            <div class="brand-sub">Admin Panel</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section-label">Menu Utama</div>
        <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="/admin/sopir" class="{{ request()->is('admin/sopir*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Data Sopir
        </a>
        <a href="/admin/kendaraan" class="{{ request()->is('admin/kendaraan*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> Data Kendaraan
        </a>
        <a href="/admin/pesanan" class="{{ request()->is('admin/pesanan*') ? 'active' : '' }}">
            <i class="bi bi-box2-fill"></i> Data Pesanan
        </a>
        <a href="{{ route('admin.laporan.keuangan') }}" class="{{ request()->is('admin/laporan-keuangan*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan Keuangan
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="/logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<div class="main-wrapper">
    <div class="topbar">
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        <div class="admin-chip">
            <div class="admin-avatar">A</div>
            <span class="admin-name">Administrator</span>
        </div>
    </div>
    <div class="content-area">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
