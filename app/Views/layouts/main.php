<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= esc($title ?? 'Metafora ERP') ?> - IDMETAFORA Enterprise</title>
    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-hash" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= config('Security')->headerName ?>">

    <!-- Google Fonts: Plus Jakarta Sans + DM Serif Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- DataTables Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* =============================================
           KAWAN-STYLE ORGANIC UI THEME v2
           Animations + Mobile-First + Polished UX
           ============================================= */
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #2B3D33;
            --sidebar-hover: #354A3E;
            --sidebar-active: #4A7C5C;
            --sidebar-active-bg: rgba(74, 124, 92, 0.25);
            --sidebar-brand-bg: #243529;
            --topbar-height: 60px;
            --body-bg: #F7F5F0;
            --cream-white: #FAF8F4;
            --card-bg: #ffffff;
            --text-primary: #1A2E23;
            --text-secondary: #5F7A6A;
            --text-muted: #8DA398;
            --border-color: #E8E4DC;
            --accent-green: #2D6A4F;
            --accent-green-light: #D4E7DB;
            --accent-orange: #D4874D;
            --accent-orange-light: #FFF0E1;
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 3px rgba(26, 46, 35, 0.05);
            --shadow-md: 0 4px 12px rgba(26, 46, 35, 0.08);
            --shadow-lg: 0 8px 24px rgba(26, 46, 35, 0.12);
            --transition-fast: 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-med: 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; }

        /* =============================================
           ENTRANCE ANIMATIONS
           ============================================= */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(-12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to   { opacity: 1; transform: scale(1); }
        }
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: 200px 0; }
        }

        .animate-fade-up {
            animation: fadeInUp 0.5s var(--transition-fast) both;
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease both;
        }

        /* Stagger delays for cards */
        .row > [class*="col-"]:nth-child(1) > .stat-card-clean,
        .row > [class*="col-"]:nth-child(1) > .card,
        .row > [class*="col-"]:nth-child(1) > a > .card { animation: fadeInUp 0.45s ease both; animation-delay: 0.05s; }
        .row > [class*="col-"]:nth-child(2) > .stat-card-clean,
        .row > [class*="col-"]:nth-child(2) > .card,
        .row > [class*="col-"]:nth-child(2) > a > .card { animation: fadeInUp 0.45s ease both; animation-delay: 0.1s; }
        .row > [class*="col-"]:nth-child(3) > .stat-card-clean,
        .row > [class*="col-"]:nth-child(3) > .card,
        .row > [class*="col-"]:nth-child(3) > a > .card { animation: fadeInUp 0.45s ease both; animation-delay: 0.15s; }
        .row > [class*="col-"]:nth-child(4) > .stat-card-clean,
        .row > [class*="col-"]:nth-child(4) > .card,
        .row > [class*="col-"]:nth-child(4) > a > .card { animation: fadeInUp 0.45s ease both; animation-delay: 0.2s; }
        .row > [class*="col-"]:nth-child(5) > .stat-card-clean { animation: fadeInUp 0.45s ease both; animation-delay: 0.25s; }
        .row > [class*="col-"]:nth-child(6) > .stat-card-clean { animation: fadeInUp 0.45s ease both; animation-delay: 0.3s; }

        /* Content wrapper fade in - pure opacity without transform or fill-mode lock to prevent trapping position:fixed modals */
        .content-wrapper {
            animation: fadeInOpacity 0.25s ease;
        }
        @keyframes fadeInOpacity {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* =============================================
           BODY & TYPOGRAPHY
           ============================================= */
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--body-bg);
            color: var(--text-primary);
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            letter-spacing: -0.01em;
            font-size: 0.9rem;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .font-serif { font-family: 'DM Serif Display', Georgia, serif; }

        h1, h2, h3, h4, h5, h6 {
            letter-spacing: -0.02em;
        }

        /* =============================================
           STAT CARDS — Organic Style
           ============================================= */
        .stat-card-clean {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.35rem;
            transition: transform var(--transition-fast), box-shadow var(--transition-fast), border-color var(--transition-fast);
            box-shadow: var(--shadow-sm);
            height: 100%;
            will-change: transform;
        }
        .stat-card-clean:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: var(--accent-green-light);
        }
        .stat-icon-box {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            transition: transform var(--transition-fast);
        }
        .stat-card-clean:hover .stat-icon-box {
            transform: scale(1.08);
        }

        /* Badge Soft Colors — Earthy Tones */
        .badge-soft-primary { background: #E8F0EB; color: #2D6A4F; }
        .badge-soft-success { background: #E6F4EA; color: #1B7A3D; }
        .badge-soft-warning { background: #FFF4E5; color: #B8651A; }
        .badge-soft-danger  { background: #FDECEC; color: #C0392B; }
        .badge-soft-purple  { background: #F3ECFA; color: #6C3FA0; }
        .badge-soft-info    { background: #E8F4F8; color: #1A7FA0; }

        /* Custom Bootstrap Opacity Utilities (15% & 20%) */
        .bg-opacity-15 { --bs-bg-opacity: 0.15 !important; }
        .bg-opacity-20 { --bs-bg-opacity: 0.20 !important; }

        /* Custom Spacing Utilities (1.5 = 6px, 2.5 = 10px) */
        .gap-1\.5 { gap: 6px !important; }
        .gap-2\.5 { gap: 10px !important; }
        .me-1\.5 { margin-right: 6px !important; }
        .ms-1\.5 { margin-left: 6px !important; }
        .pe-1\.5 { padding-right: 6px !important; }
        .ps-1\.5 { padding-left: 6px !important; }

        /* =============================================
           UNIVERSAL BUTTON ICON SPACING PROTECTION
           Guarantees clear spacing between icon and text in buttons
           ============================================= */
        .btn > i:first-child:not(:last-child),
        .btn > svg:first-child:not(:last-child) {
            margin-right: 7px !important;
        }
        .btn.gap-1 > i:first-child,
        .btn.gap-1\.5 > i:first-child,
        .btn.gap-2 > i:first-child,
        .btn.gap-2\.5 > i:first-child,
        .btn.gap-3 > i:first-child {
            margin-right: 0 !important;
        }

        /* =============================================
           SIDEBAR — Forest Green Organic
           ============================================= */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--sidebar-bg);
            color: rgba(255, 255, 255, 0.7);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1020;
            overflow: hidden;
            transition: transform var(--transition-med);
            display: flex;
            flex-direction: column;
        }

        body.sidebar-collapsed #sidebar {
            transform: translateX(-100%);
        }
        body.sidebar-collapsed #main-content {
            margin-left: 0 !important;
            width: 100% !important;
        }


        /* Sidebar Brand */
        .sidebar-brand {
            padding: 1.15rem 1.15rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 0.5rem;
        }

        .brand-logo-img {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            object-fit: contain;
            background: #ffffff;
            padding: 2px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: transform var(--transition-fast);
            flex-shrink: 0;
        }
        .brand-logo-img:hover { transform: rotate(-6deg) scale(1.05); }

        /* Section Labels */
        .sidebar-section-label {
            padding: 0.85rem 1.15rem 0.35rem;
            font-size: 0.63rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.25);
        }

        /* Sidebar Menu */
        .sidebar-menu {
            padding: 0 0.55rem;
            flex: 1 1 0;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.12) transparent;
        }
        .sidebar-menu::-webkit-scrollbar { width: 3px; }
        .sidebar-menu::-webkit-scrollbar-track { background: transparent; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 10px; }

        .sidebar-item { margin-bottom: 1px; }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.55rem 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.84rem;
            font-weight: 500;
            border-radius: var(--radius-sm);
            transition: all var(--transition-fast);
            cursor: pointer;
            position: relative;
            min-height: 40px;
        }

        .sidebar-link i.menu-icon {
            width: 22px;
            font-size: 0.9rem;
            margin-right: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            text-align: center;
            transition: color var(--transition-fast), transform var(--transition-fast);
        }

        .sidebar-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }
        .sidebar-link:hover i.menu-icon {
            color: rgba(255, 255, 255, 0.8);
            transform: scale(1.1);
        }

        /* Dashboard Active / Root Active (Green pill) */
        .sidebar-link.active-root,
        .sidebar-link.active:not([data-bs-toggle="collapse"]) {
            color: #ffffff !important;
            background-color: var(--sidebar-active) !important;
            font-weight: 600;
            border-radius: var(--radius-sm);
            box-shadow: 0 2px 8px rgba(74, 124, 92, 0.35);
        }
        .sidebar-link.active-root i.menu-icon,
        .sidebar-link.active:not([data-bs-toggle="collapse"]) i.menu-icon {
            color: #ffffff !important;
        }

        /* Parent Expanded State */
        .sidebar-link[aria-expanded="true"] {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.08) !important;
            font-weight: 600;
            border-radius: var(--radius-sm) var(--radius-sm) 0 0;
        }
        .sidebar-link[aria-expanded="true"] i.menu-icon {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        .sidebar-link[aria-expanded="true"] .sidebar-chevron {
            transform: rotate(90deg);
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .sidebar-chevron {
            margin-left: auto;
            font-size: 0.65rem;
            transition: transform 0.25s ease, color var(--transition-fast);
            color: rgba(255, 255, 255, 0.2);
        }

        /* Submenu with smooth animation */
        .sidebar-submenu {
            list-style: none;
            padding: 0.2rem 0.35rem 0.45rem;
            margin: 0;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 0 0 var(--radius-sm) var(--radius-sm);
        }

        .collapse { transition: none; }
        .collapsing { transition: height 0.25s ease; }

        .submenu-link {
            display: flex;
            align-items: center;
            padding: 0.42rem 0.7rem 0.42rem 2.1rem;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            border-radius: 6px;
            transition: all var(--transition-fast);
            position: relative;
            min-height: 36px;
        }

        .submenu-link::before {
            content: '';
            position: absolute;
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.18);
            transition: all var(--transition-fast);
        }

        .submenu-link i.submenu-icon {
            display: none;
        }

        .submenu-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.06);
            padding-left: 2.3rem;
        }
        .submenu-link:hover::before {
            background: rgba(255, 255, 255, 0.6);
            transform: translateY(-50%) scale(1.3);
        }

        .submenu-link.active {
            color: #ffffff !important;
            background-color: var(--sidebar-active-bg) !important;
            font-weight: 600;
        }
        .submenu-link.active::before {
            background: #7DCBA4 !important;
            box-shadow: 0 0 8px rgba(125, 203, 164, 0.5);
            transform: translateY(-50%) scale(1.2);
        }

        /* Sidebar CTA Card */
        .sidebar-cta-card {
            margin: 0.5rem 0.65rem;
            padding: 0.85rem;
            background: linear-gradient(135deg, rgba(74, 124, 92, 0.3), rgba(43, 61, 51, 0.5));
            border-radius: var(--radius-md);
            border: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
            transition: border-color var(--transition-fast);
        }
        .sidebar-cta-card:hover { border-color: rgba(255,255,255,0.15); }
        .sidebar-cta-card h6 { color: #fff; font-size: 0.82rem; font-weight: 700; margin-bottom: 0.25rem; }
        .sidebar-cta-card p { color: rgba(255,255,255,0.5); font-size: 0.7rem; margin-bottom: 0; line-height: 1.4; }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 0.75rem 0.85rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-shrink: 0;
        }
        .sidebar-footer-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--accent-green);
            color: #fff;
            font-weight: 700;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-footer-info { line-height: 1.2; overflow: hidden; }
        .sidebar-footer-name { color: #fff; font-weight: 600; font-size: 0.78rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-footer-role { color: rgba(255,255,255,0.35); font-size: 0.66rem; }

        /* =============================================
           MAIN CONTENT WRAPPER
           ============================================= */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left var(--transition-med), width var(--transition-med);
            width: calc(100% - var(--sidebar-width));
        }

        /* =============================================
           TOP NAVBAR — Clean Cream
           ============================================= */
        .top-navbar {
            height: var(--topbar-height);
            background-color: var(--cream-white);
            border-bottom: 1px solid var(--border-color);
            padding: 0 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: rgba(250, 248, 244, 0.98);
        }

        .topbar-search {
            position: relative;
            max-width: 300px;
            flex: 1;
        }
        .topbar-search input {
            width: 100%;
            padding: 0.45rem 0.75rem 0.45rem 2.2rem;
            border: 1px solid var(--border-color);
            border-radius: 50rem;
            background: var(--card-bg);
            font-size: 0.8rem;
            color: var(--text-primary);
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast), width var(--transition-med);
            outline: none;
        }
        .topbar-search input::placeholder { color: var(--text-muted); }
        .topbar-search input:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
        }
        .topbar-search .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.78rem;
            transition: color var(--transition-fast);
        }
        .topbar-search input:focus + .search-icon,
        .topbar-search:focus-within .search-icon { color: var(--accent-green); }
        .topbar-search .search-shortcut {
            position: absolute;
            right: 0.55rem;
            top: 50%;
            transform: translateY(-50%);
            background: var(--body-bg);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 0.05rem 0.35rem;
            font-size: 0.62rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .topbar-search-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 320px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            z-index: 1050;
            display: none;
            max-height: 380px;
            overflow-y: auto;
            padding: 0.4rem;
        }
        .topbar-search-dropdown.show {
            display: block;
        }
        .search-result-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            text-decoration: none;
            font-size: 0.82rem;
            transition: background var(--transition-fast);
        }
        .search-result-item:hover, .search-result-item.active {
            background-color: rgba(45, 106, 79, 0.08);
            color: var(--accent-green);
        }
        .search-result-item i {
            width: 18px;
            text-align: center;
            color: var(--accent-green);
            font-size: 0.85rem;
        }
        .search-group-title {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            padding: 0.35rem 0.75rem 0.15rem;
        }

        .topbar-icon-btn {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all var(--transition-fast);
            position: relative;
        }
        .topbar-icon-btn:hover {
            background: var(--body-bg);
            color: var(--text-primary);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .content-wrapper {
            padding: 1.35rem 1.5rem;
            flex: 1;
        }

        /* =============================================
           NOTIFICATION BADGE & DROPDOWN
           ============================================= */
        .notif-badge-pill {
            background-color: var(--accent-orange);
            color: #ffffff;
            font-size: 0.58rem;
            font-weight: 800;
            padding: 0.1em 0.38em;
            border-radius: 10rem;
            position: absolute;
            top: -3px;
            right: -5px;
            box-shadow: 0 2px 6px rgba(212, 135, 77, 0.4);
            border: 1.5px solid var(--cream-white);
            line-height: 1.2;
            transition: all var(--transition-fast);
            animation: scaleIn 0.3s ease both;
        }

        .notif-dropdown-menu {
            width: 380px;
            max-width: 92vw;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            animation: fadeInUp 0.2s ease both;
        }

        .notif-item {
            padding: 0.7rem 1rem;
            border-bottom: 1px solid #f5f3ee;
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            transition: background-color var(--transition-fast);
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        .notif-item:hover { background-color: var(--body-bg); }
        .notif-item.unread { background-color: var(--accent-orange-light); }
        .notif-item.unread:hover { background-color: #FFE8CF; }
        .notif-item-icon {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            flex-shrink: 0;
            transition: transform var(--transition-fast);
        }
        .notif-item:hover .notif-item-icon { transform: scale(1.08); }
        .notif-item-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); line-height: 1.3; margin-bottom: 0.1rem; }
        .notif-item-desc { font-size: 0.73rem; color: var(--text-secondary); line-height: 1.35; margin-bottom: 0.2rem; }
        .notif-item-time { font-size: 0.66rem; color: var(--text-muted); }

        /* User Avatar Circle */
        .user-avatar-circle {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, var(--accent-green) 0%, #1B7A3D 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.8rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform var(--transition-fast), box-shadow var(--transition-fast);
        }
        .user-avatar-circle:hover {
            transform: scale(1.08);
            box-shadow: 0 2px 8px rgba(45, 106, 79, 0.3);
        }

        /* =============================================
           MOBILE DRAWER OVERLAY
           ============================================= */
        .sidebar-backdrop {
            display: none !important;
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background-color: rgba(26, 46, 35, 0.5);
            z-index: 1015;
            opacity: 0;
            pointer-events: none;
            transition: opacity var(--transition-med);
        }
        .sidebar-backdrop.show {
            display: block !important;
            opacity: 1;
            pointer-events: auto;
        }

        /* =============================================
           RESPONSIVE — MOBILE FIRST
           ============================================= */
        @media (max-width: 992px) {
            #sidebar {
                transform: translateX(-100%) !important;
                width: 280px;
                max-width: 84vw;
                box-shadow: 4px 0 24px rgba(0,0,0,0.15);
            }
            #sidebar.show { transform: translateX(0) !important; }
            #main-content { margin-left: 0 !important; width: 100% !important; }
            .content-wrapper { padding: 1rem; }
            .topbar-search { max-width: 180px; }
            .top-navbar { padding: 0 0.85rem; }
        }

        @media (max-width: 768px) {
            :root {
                --topbar-height: 54px;
            }
            .content-wrapper { padding: 0.85rem 0.75rem; }
            .stat-card-clean { padding: 0.85rem 0.95rem; }
            .stat-icon-box { width: 36px; height: 36px; font-size: 0.95rem; }
            h3, .fs-3 { font-size: 1.25rem !important; }
            h4, .fs-4 { font-size: 1.1rem !important; }
            .card-header { padding: 0.75rem 0.9rem; }
            .card-body { padding: 0.85rem !important; }
            .topbar-search { display: none !important; }
            .notif-dropdown-menu { width: 320px; }
            .row.g-3 > .col-sm-6 { flex: 0 0 50%; max-width: 50%; }
        }

        @media (max-width: 576px) {
            .content-wrapper { padding: 0.65rem 0.5rem; }
            .top-navbar { height: 52px; padding: 0 0.65rem; }

            /* Centered Fixed Dropdown on Mobile to prevent clipping or off-screen overflow */
            #quickActionDropdown + .dropdown-menu {
                position: fixed !important;
                top: 56px !important;
                left: 50% !important;
                right: auto !important;
                transform: translateX(-50%) !important;
                width: calc(100vw - 1rem) !important;
                max-width: 330px !important;
                max-height: calc(100vh - 70px) !important;
                overflow-y: auto !important;
                border-radius: 14px !important;
                box-shadow: 0 16px 40px -6px rgba(0, 0, 0, 0.35) !important;
                z-index: 1065 !important;
                margin: 0 !important;
            }

            .notif-dropdown-menu {
                position: fixed !important;
                top: 56px !important;
                left: 50% !important;
                right: auto !important;
                transform: translateX(-50%) !important;
                width: calc(100vw - 1rem) !important;
                max-width: 340px !important;
                max-height: calc(100vh - 70px) !important;
                overflow-y: auto !important;
                border-radius: 14px !important;
                box-shadow: 0 16px 40px -6px rgba(0, 0, 0, 0.35) !important;
                z-index: 1065 !important;
                margin: 0 !important;
            }

            .topbar-user-dropdown-menu,
            #userDropdown + .dropdown-menu {
                position: fixed !important;
                top: 56px !important;
                right: 0.5rem !important;
                left: auto !important;
                transform: none !important;
                width: 220px !important;
                border-radius: 12px !important;
                box-shadow: 0 16px 40px -6px rgba(0, 0, 0, 0.35) !important;
                z-index: 1065 !important;
            }

            .stat-card-clean {
                padding: 0.75rem 0.85rem !important;
                border-radius: var(--radius-md) !important;
            }
            .stat-card-clean .stat-icon-box {
                width: 32px !important;
                height: 32px !important;
                font-size: 0.85rem !important;
            }
            .stat-card-clean .fs-2,
            .stat-card-clean h3,
            .stat-card-clean h4 {
                font-size: 1.2rem !important;
            }

            .table-responsive {
                -webkit-overflow-scrolling: touch;
                border-radius: var(--radius-sm);
            }
            .table th, .table td {
                padding: 0.5rem 0.55rem !important;
                font-size: 0.78rem !important;
            }

            .card { border-radius: var(--radius-md) !important; }
            .card-header { padding: 0.75rem 0.85rem !important; }
            .card-body { padding: 0.85rem !important; }
            .btn { font-size: 0.8rem; }

            /* Ensure DataTables are touch-friendly and fit mobile width */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none !important;
                text-align: left !important;
                width: 100% !important;
                margin-bottom: 0.5rem !important;
            }
            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                margin-left: 0 !important;
            }
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                float: none !important;
                text-align: center !important;
                margin-top: 0.5rem !important;
            }
            .dataTables_wrapper .dataTables_paginate .pagination {
                justify-content: center !important;
                flex-wrap: wrap !important;
            }

            /* Prevent modal overflowing on mobile */
            .modal-dialog {
                margin: 0.5rem !important;
                max-width: calc(100vw - 1rem) !important;
            }
        }

        /* =============================================
           CARDS & GENERAL UI
           ============================================= */
        .card {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background-color: var(--card-bg);
            box-shadow: var(--shadow-sm);
            transition: box-shadow var(--transition-fast), transform var(--transition-fast);
            overflow: hidden;
        }
        .card:hover {
            box-shadow: var(--shadow-md);
        }
        .card-header {
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 1.1rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Table Styling — Clean Organic */
        .table { color: var(--text-primary); font-size: 0.85rem; }
        .table > thead > tr > th {
            background: var(--body-bg);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.65rem 0.8rem;
            white-space: nowrap;
        }
        .table > tbody > tr > td {
            padding: 0.7rem 0.8rem;
            border-bottom: 1px solid #f0ede6;
            vertical-align: middle;
            transition: background-color var(--transition-fast);
        }
        .table > tbody > tr:hover > td {
            background-color: rgba(212, 231, 219, 0.2);
        }
        .table > tbody > tr:last-child > td { border-bottom: none; }

        /* DataTables Override */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 50rem !important;
            border: 1px solid var(--border-color);
            padding: 0.38rem 0.8rem;
            font-size: 0.8rem;
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
            outline: none;
        }
        .dataTables_wrapper .dataTables_length select {
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            padding: 0.25rem 0.5rem;
        }
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length label {
            font-size: 0.78rem;
            color: var(--text-secondary);
        }
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 0.75rem;
        }
        .page-item.active .page-link {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
        }
        .page-link {
            color: var(--accent-green);
            border-radius: var(--radius-sm) !important;
            border: 1px solid var(--border-color);
            margin: 0 2px;
            transition: all var(--transition-fast);
            font-size: 0.82rem;
        }
        .page-link:hover {
            background-color: var(--accent-green-light);
            color: var(--accent-green);
            border-color: var(--accent-green-light);
            transform: translateY(-1px);
        }

        /* Action Buttons — Softer + Hover Animations */
        .btn-action-view { background-color: #38A3A5; color: white; border: none; border-radius: 6px; transition: all var(--transition-fast); }
        .btn-action-view:hover { background-color: #2E8B8E; color: white; transform: translateY(-1px); box-shadow: 0 2px 6px rgba(56, 163, 165, 0.3); }
        .btn-action-edit { background-color: var(--accent-orange); color: white; border: none; border-radius: 6px; transition: all var(--transition-fast); }
        .btn-action-edit:hover { background-color: #C07A42; color: white; transform: translateY(-1px); box-shadow: 0 2px 6px rgba(212, 135, 77, 0.3); }
        .btn-action-delete { background-color: #D9534F; color: white; border: none; border-radius: 6px; transition: all var(--transition-fast); }
        .btn-action-delete:hover { background-color: #C0392B; color: white; transform: translateY(-1px); box-shadow: 0 2px 6px rgba(217, 83, 79, 0.3); }

        /* Responsive Table Action Buttons: Icon + Text on Desktop, Icon-Only on Mobile */
        .col-aksi-btn {
            white-space: nowrap !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            vertical-align: middle !important;
            line-height: 1 !important;
            border-radius: 6px !important;
            transition: all var(--transition-fast);
        }
        .col-aksi-btn i {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            vertical-align: 0 !important;
            margin: 0 !important;
            text-align: center !important;
        }
        .col-aksi-btn .btn-text {
            display: inline-block;
            line-height: 1;
            vertical-align: middle;
        }
        .col-aksi, .col-aksi-th {
            text-align: center !important;
            vertical-align: middle !important;
        }
        @media (max-width: 767.98px) {
            .col-aksi-btn .btn-text {
                display: none !important;
            }
            .col-aksi-btn {
                width: 34px !important;
                height: 34px !important;
                min-width: 34px !important;
                min-height: 34px !important;
                max-width: 34px !important;
                max-height: 34px !important;
                padding: 0 !important;
                border-radius: 8px !important;
                gap: 0 !important;
                margin: 0 auto !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .col-aksi-btn i {
                margin: 0 !important;
                font-size: 0.95rem !important;
                line-height: 1 !important;
                vertical-align: 0 !important;
                width: 100% !important;
                height: 100% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                text-align: center !important;
            }
            .col-aksi, .col-aksi-th {
                width: 52px !important;
                min-width: 52px !important;
                max-width: 52px !important;
                padding-left: 2px !important;
                padding-right: 2px !important;
                text-align: center !important;
            }
        }

        /* Primary Button — Green CTA */
        .btn-primary {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
            border-radius: var(--radius-sm);
            transition: all var(--transition-fast);
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #245A42;
            border-color: #245A42;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(45, 106, 79, 0.25);
        }
        .btn-outline-primary {
            color: var(--accent-green);
            border-color: var(--accent-green);
            transition: all var(--transition-fast);
        }
        .btn-outline-primary:hover {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
            transform: translateY(-1px);
        }

        /* All buttons get subtle animation */
        .btn {
            transition: all var(--transition-fast);
            font-weight: 500;
        }
        .btn:active { transform: scale(0.97); }

        /* Form Controls */
        .form-control, .form-select {
            border-color: var(--border-color);
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
        }
        .form-label {
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--text-primary);
            margin-bottom: 0.35rem;
        }

        /* Alerts — Organic with animation */
        .alert {
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            animation: fadeInUp 0.35s ease both;
        }

        /* Select2 Override */
        .select2-container--bootstrap-5 .select2-selection {
            border-color: var(--border-color) !important;
            border-radius: var(--radius-sm) !important;
            min-height: 38px;
        }
        .select2-container--bootstrap-5 .select2-selection--single:focus,
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: var(--accent-green) !important;
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1) !important;
        }
        .select2-dropdown {
            border-radius: var(--radius-sm) !important;
            box-shadow: var(--shadow-md);
            border-color: var(--border-color) !important;
        }

        /* =============================================
           MODAL SYSTEM — Clean Stacking, No Cutoff, Full Scrollability
           ============================================= */
        .modal {
            z-index: 1060 !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            padding-right: 0 !important;
        }
        .modal-dialog {
            margin: 1.75rem auto !important;
            max-width: 580px;
            pointer-events: none;
        }
        .modal-dialog.modal-sm { max-width: 380px !important; }
        .modal-dialog.modal-lg { max-width: 820px !important; }
        .modal-dialog.modal-xl { max-width: 1120px !important; }

        /* Dialog Centering - safe minimum spacing so modal header is NEVER cut off at top */
        .modal-dialog-centered {
            display: flex !important;
            align-items: center !important;
            min-height: calc(100% - 3.5rem) !important;
            margin-top: 1.75rem !important;
            margin-bottom: 1.75rem !important;
        }

        .modal-backdrop {
            z-index: 1055 !important;
            background-color: rgba(15, 23, 42, 0.65) !important;
        }
        .modal-backdrop.show {
            opacity: 1 !important;
        }
        .modal-content {
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.28);
            background: #ffffff;
            max-height: calc(100vh - 3.5rem);
            display: flex;
            flex-direction: column;
            pointer-events: auto;
            overflow: hidden;
            width: 100%;
        }
        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            background: #ffffff;
        }
        .modal-header .modal-title {
            font-size: 1.05rem;
            line-height: 1.35;
            margin: 0;
            color: var(--text-primary);
        }
        .modal-body {
            padding: 1.25rem 1.35rem;
            overflow-y: auto;
            flex: 1 1 auto;
            max-height: calc(100vh - 11rem);
            background: #ffffff;
        }
        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 0.85rem 1.35rem;
            flex-shrink: 0;
            background-color: #f8fafc;
        }

        /* Short screens / laptops - guarantee modal starts near top so header and close button are always visible */
        @media (max-height: 700px) {
            .modal-dialog-centered {
                align-items: flex-start !important;
                min-height: auto !important;
                margin-top: 1.25rem !important;
                margin-bottom: 1.25rem !important;
            }
            .modal-content {
                max-height: calc(100vh - 2.5rem);
            }
            .modal-body {
                max-height: calc(100vh - 9rem);
            }
        }

        /* Breadcrumb */
        .breadcrumb { font-size: 0.8rem; }
        .breadcrumb-item a { color: var(--accent-green); text-decoration: none; transition: color var(--transition-fast); }
        .breadcrumb-item a:hover { color: #245A42; }
        .breadcrumb-item.active { color: var(--text-muted); }

        /* Badge pill animation */
        .badge {
            transition: all var(--transition-fast);
        }

        /* Dropdown menus */
        .dropdown-menu {
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            animation: fadeInUp 0.18s ease both;
            padding: 0.35rem;
        }
        .dropdown-item {
            border-radius: 6px;
            font-size: 0.84rem;
            padding: 0.45rem 0.75rem;
            transition: background-color var(--transition-fast);
        }
        .dropdown-item:hover, .dropdown-item:focus {
            background-color: var(--body-bg);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #C5BEB3; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #A9A29B; }

        /* SweetAlert2 custom overrides */
        .swal2-popup { border-radius: var(--radius-lg) !important; }
        .swal2-confirm { border-radius: var(--radius-sm) !important; }
        .swal2-cancel { border-radius: var(--radius-sm) !important; }

        /* Footer */
        .app-footer {
            background: var(--cream-white);
            border-top: 1px solid var(--border-color);
            padding: 0.75rem 1.25rem;
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        /* =============================================
           UTILITY CLASSES
           ============================================= */
        .text-accent { color: var(--accent-green) !important; }
        .bg-accent-light { background-color: var(--accent-green-light) !important; }
        .border-accent { border-color: var(--accent-green) !important; }
        .hover-lift { transition: transform var(--transition-fast), box-shadow var(--transition-fast); }
        .hover-lift:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

        /* Skeleton loading placeholder */
        .skeleton {
            background: linear-gradient(90deg, #f0ede6 25%, #e8e4dc 50%, #f0ede6 75%);
            background-size: 200px 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 4px;
        }

        /* Touch targets for mobile */
        @media (pointer: coarse) {
            .btn { min-height: 42px; min-width: 42px; }
            .btn-sm { min-height: 36px; }
            .sidebar-link { min-height: 44px; }
            .submenu-link { min-height: 40px; }
            .form-control, .form-select { min-height: 42px; }
            .dropdown-item { padding: 0.55rem 0.85rem; }
        }

        /* Print styles */
        @media print {
            @page {
                size: landscape;
                margin: 8mm 6mm;
            }
            #sidebar, .top-navbar, .sidebar-backdrop, .app-footer, .breadcrumb, .toast-container, .alert,
            #debug-icon, #debug-bar, .toolbar, [id^="ci-debugbar"], #ci-history, .d-print-none,
            [id$="CardsContainer"], [id$="PaginationContainer"], .mobile-card-actions { display: none !important; }
            a[href]:after { content: none !important; }
            #main-content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
            .content-wrapper { padding: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
            .btn, .dropdown, .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate, th:last-child, td:last-child { display: none !important; }

            /* Ensure data tables hidden on mobile screens are ALWAYS forced visible when printing */
            .card-body.d-none.d-md-block,
            .d-none.d-md-block,
            .table-responsive {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                overflow: visible !important;
                width: 100% !important;
            }
            table.table {
                display: table !important;
                width: 100% !important;
                visibility: visible !important;
            }
            table.table thead { display: table-header-group !important; }
            table.table tbody { display: table-row-group !important; }
            table.table tr { display: table-row !important; page-break-inside: avoid !important; }
            table.table th, table.table td { display: table-cell !important; }
            /* Pastikan kolom Aksi / Action dan elemen d-print-none tidak tampil pada saat cetak/PDF */
            table.table th.d-print-none,
            table.table td.d-print-none,
            table.table th:last-child:not(.print-keep-last),
            table.table td:last-child:not(.print-keep-last) {
                display: none !important;
            }
        }

        /* Reduce motion for accessibility */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- SIDEBAR -->
    <nav id="sidebar" role="navigation" aria-label="Menu Utama">
        <div class="sidebar-brand">
            <a href="<?= base_url('/') ?>" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="<?= base_url('logo.png') ?>" alt="IDMETAFORA" class="brand-logo-img">
                <span class="fs-6 fw-bold text-white" style="letter-spacing: -0.03em;">IDMETAFORA</span>
            </a>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-link text-white-50 p-1 d-none d-lg-inline-flex align-items-center justify-content-center text-decoration-none" id="btnCollapseDesktop" title="Tutup Sidebar" aria-label="Tutup Sidebar">
                    <i class="fa-solid fa-chevron-left fs-6"></i>
                </button>
                <button type="button" class="btn-close btn-close-white d-lg-none" id="sidebarClose" aria-label="Tutup Menu"></button>
            </div>
        </div>

        <div class="sidebar-menu" id="sidebarMenu">
            <!-- Section: WORKSPACE -->
            <div class="sidebar-section-label">Workspace</div>

            <!-- 1. Dashboard -->
            <div class="sidebar-item">
                <a href="<?= base_url('/') ?>" class="sidebar-link <?= (uri_string() === '' || uri_string() === 'dashboard') ? 'active-root' : '' ?>">
                    <i class="menu-icon fa-solid fa-chart-line"></i>
                    <span>Dashboard Utama</span>
                </a>
            </div>

            <!-- Section: ALUR OPERASIONAL (LANGKAH 1 - 4) -->
            <?php 
                $canMaster = has_permission(['master_produk', 'master_pelanggan', 'master_supplier', 'master_kategori', 'master_satuan']);
                $canPembelian = has_permission(['po_list', 'po_create', 'po_receive', 'retur_pembelian']);
                $canStok = has_permission(['stok_dashboard', 'stok_opname', 'stok_export']);
                $canPenjualan = has_permission(['pos_kasir', 'penjualan_transaksi', 'penjualan_marketplace', 'penjualan_invoice', 'retur_penjualan']);
            ?>
            <?php if ($canMaster || $canPembelian || $canStok || $canPenjualan): ?>
            <div class="sidebar-section-label d-flex align-items-center justify-content-between">
                <span>Alur Operasional</span>
                <span class="badge bg-white bg-opacity-10 text-white-50" style="font-size: 0.6rem;">Tahap 1-4</span>
            </div>
            <?php endif; ?>

            <!-- 1. Master Data -->
            <?php if ($canMaster): ?>
            <?php $isMasterActive = str_starts_with(uri_string(), 'master'); ?>
            <div class="sidebar-item">
                <a class="sidebar-link <?= $isMasterActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#menuMaster" role="button" aria-expanded="<?= $isMasterActive ? 'true' : 'false' ?>" aria-controls="menuMaster">
                    <i class="menu-icon fa-solid fa-database"></i>
                    <span>1. Master Data</span>
                    <i class="sidebar-chevron fa-solid fa-chevron-right"></i>
                </a>
                <div class="collapse <?= $isMasterActive ? 'show' : '' ?>" id="menuMaster" data-bs-parent="#sidebarMenu">
                    <ul class="sidebar-submenu">
                        <?php if (has_permission('master_produk')): ?>
                        <li><a href="<?= base_url('master/produk') ?>" class="submenu-link <?= str_starts_with(uri_string(), 'master/produk') ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-cubes"></i><span>Produk &amp; Katalog</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('master_pelanggan')): ?>
                        <li><a href="<?= base_url('master/pelanggan') ?>" class="submenu-link <?= str_starts_with(uri_string(), 'master/pelanggan') ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-users"></i><span>Pelanggan</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('master_supplier')): ?>
                        <li><a href="<?= base_url('master/supplier') ?>" class="submenu-link <?= str_starts_with(uri_string(), 'master/supplier') ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-truck"></i><span>Supplier</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('master_kategori')): ?>
                        <li><a href="<?= base_url('master/kategori') ?>" class="submenu-link <?= uri_string() === 'master/kategori' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-tag"></i><span>Kategori Barang</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('master_satuan')): ?>
                        <li><a href="<?= base_url('master/satuan') ?>" class="submenu-link <?= uri_string() === 'master/satuan' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-scale-balanced"></i><span>Unit / Satuan</span></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- 2. Pembelian (PO ke Supplier) -->
            <?php if ($canPembelian): ?>
            <?php $isPembelianActive = str_starts_with(uri_string(), 'pembelian'); ?>
            <div class="sidebar-item">
                <a class="sidebar-link <?= $isPembelianActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#menuPembelian" role="button" aria-expanded="<?= $isPembelianActive ? 'true' : 'false' ?>" aria-controls="menuPembelian">
                    <i class="menu-icon fa-solid fa-cart-shopping"></i>
                    <span>2. Pembelian (PO)</span>
                    <i class="sidebar-chevron fa-solid fa-chevron-right"></i>
                </a>
                <div class="collapse <?= $isPembelianActive ? 'show' : '' ?>" id="menuPembelian" data-bs-parent="#sidebarMenu">
                    <ul class="sidebar-submenu">
                        <?php if (has_permission('po_list')): ?>
                        <li><a href="<?= base_url('pembelian') ?>" class="submenu-link <?= uri_string() === 'pembelian' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-file-lines"></i><span>Daftar Order PO</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('po_create')): ?>
                        <li><a href="<?= base_url('pembelian/create') ?>" class="submenu-link <?= uri_string() === 'pembelian/create' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-plus-circle text-success"></i><span>Buat PO Baru</span></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- 3. Manajemen Stok -->
            <?php if ($canStok): ?>
            <?php $isStokActive = str_starts_with(uri_string(), 'stok'); ?>
            <div class="sidebar-item">
                <a class="sidebar-link <?= $isStokActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#menuStok" role="button" aria-expanded="<?= $isStokActive ? 'true' : 'false' ?>" aria-controls="menuStok">
                    <i class="menu-icon fa-solid fa-boxes-stacked"></i>
                    <span>3. Manajemen Stok</span>
                    <i class="sidebar-chevron fa-solid fa-chevron-right"></i>
                </a>
                <div class="collapse <?= $isStokActive ? 'show' : '' ?>" id="menuStok" data-bs-parent="#sidebarMenu">
                    <ul class="sidebar-submenu">
                        <?php if (has_permission('stok_dashboard')): ?>
                        <li><a href="<?= base_url('stok') ?>" class="submenu-link <?= uri_string() === 'stok' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-chart-pie"></i><span>Monitoring Stok</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('stok_opname')): ?>
                        <li><a href="<?= base_url('stok/opname') ?>" class="submenu-link <?= str_starts_with(uri_string(), 'stok/opname') ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-pen-to-square"></i><span>Stok Opname / Edit</span></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- 4. Penjualan & Kasir -->
            <?php if ($canPenjualan): ?>
            <?php $isPenjualanActive = str_starts_with(uri_string(), 'penjualan') || str_starts_with(uri_string(), 'pos') || str_starts_with(uri_string(), 'marketplace') || str_starts_with(uri_string(), 'retur') || uri_string() === 'invoice'; ?>
            <div class="sidebar-item">
                <a class="sidebar-link <?= $isPenjualanActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#menuPenjualan" role="button" aria-expanded="<?= $isPenjualanActive ? 'true' : 'false' ?>" aria-controls="menuPenjualan">
                    <i class="menu-icon fa-solid fa-cash-register"></i>
                    <span>4. Penjualan &amp; Kasir</span>
                    <i class="sidebar-chevron fa-solid fa-chevron-right"></i>
                </a>
                <div class="collapse <?= $isPenjualanActive ? 'show' : '' ?>" id="menuPenjualan" data-bs-parent="#sidebarMenu">
                    <ul class="sidebar-submenu">
                        <?php if (has_permission('pos_kasir')): ?>
                        <li><a href="<?= base_url('pos') ?>" class="submenu-link <?= str_starts_with(uri_string(), 'pos') ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-bolt text-warning"></i><span>Kasir Cepat (POS)</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('penjualan_transaksi')): ?>
                        <li><a href="<?= base_url('penjualan') ?>" class="submenu-link <?= uri_string() === 'penjualan' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-receipt"></i><span>Transaksi Penjualan</span></a></li>
                        <li><a href="<?= base_url('penjualan/create') ?>" class="submenu-link <?= uri_string() === 'penjualan/create' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-plus-circle text-success"></i><span>Buat Order (SO)</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('penjualan_marketplace')): ?>
                        <li><a href="<?= base_url('marketplace') ?>" class="submenu-link <?= uri_string() === 'marketplace' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-shop"></i><span>Penjualan Marketplace</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission(['penjualan_marketplace', 'penjualan_transaksi'])): ?>
                        <li><a href="<?= base_url('marketplace/scan-resi') ?>" class="submenu-link <?= str_starts_with(uri_string(), 'marketplace/scan-resi') || str_starts_with(uri_string(), 'marketplace/import-resi') ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-barcode"></i><span>Scan / Cek Resi</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('penjualan_invoice')): ?>
                        <li><a href="<?= base_url('penjualan/invoice') ?>" class="submenu-link <?= uri_string() === 'invoice' || uri_string() === 'penjualan/invoice' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-file-invoice"></i><span>Daftar Faktur (Invoice)</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('retur_penjualan')): ?>
                        <li><a href="<?= base_url('retur/penjualan') ?>" class="submenu-link <?= str_starts_with(uri_string(), 'retur') ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-rotate-left"></i><span>Retur Penjualan</span></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- Section: KEUANGAN & ANALITIK (LANGKAH 5 - 6) -->
            <?php 
                $canKeuangan = has_permission(['ar_piutang', 'bkm_masuk', 'ap_utang', 'bkk_keluar', 'gl_buku_besar']);
                $canCrm = has_permission(['crm_dashboard', 'crm_followup']);
                $canRfm = has_permission('rfm_analisis');
            ?>
            <?php if ($canKeuangan || $canCrm || $canRfm): ?>
            <div class="sidebar-section-label d-flex align-items-center justify-content-between">
                <span>Keuangan &amp; Analitik</span>
                <span class="badge bg-white bg-opacity-10 text-white-50" style="font-size: 0.6rem;">Tahap 5-6</span>
            </div>
            <?php endif; ?>

            <!-- 5. Keuangan & Kas -->
            <?php if ($canKeuangan): ?>
            <?php $isKeuanganActive = str_starts_with(uri_string(), 'keuangan'); ?>
            <div class="sidebar-item">
                <a class="sidebar-link <?= $isKeuanganActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#menuKeuangan" role="button" aria-expanded="<?= $isKeuanganActive ? 'true' : 'false' ?>" aria-controls="menuKeuangan">
                    <i class="menu-icon fa-solid fa-money-bill-wave"></i>
                    <span>5. Keuangan &amp; Kas</span>
                    <i class="sidebar-chevron fa-solid fa-chevron-right"></i>
                </a>
                <div class="collapse <?= $isKeuanganActive ? 'show' : '' ?>" id="menuKeuangan" data-bs-parent="#sidebarMenu">
                    <ul class="sidebar-submenu">
                        <?php if (has_permission('ar_piutang')): ?>
                        <li><a href="<?= base_url('keuangan/piutang') ?>" class="submenu-link <?= uri_string() === 'keuangan/piutang' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-hand-holding-dollar"></i><span>Piutang Usaha (AR)</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('bkm_masuk')): ?>
                        <li><a href="<?= base_url('keuangan/bkm') ?>" class="submenu-link <?= uri_string() === 'keuangan/bkm' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-arrow-down-long text-success"></i><span>Penerimaan Kas (BKM)</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('ap_utang')): ?>
                        <li><a href="<?= base_url('keuangan/utang') ?>" class="submenu-link <?= uri_string() === 'keuangan/utang' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-file-invoice-dollar"></i><span>Utang Usaha (AP)</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('bkk_keluar')): ?>
                        <li><a href="<?= base_url('keuangan/bkk') ?>" class="submenu-link <?= uri_string() === 'keuangan/bkk' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-arrow-up-long text-danger"></i><span>Pengeluaran Kas (BKK)</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('gl_buku_besar')): ?>
                        <li><a href="<?= base_url('keuangan/jurnal') ?>" class="submenu-link <?= uri_string() === 'keuangan/jurnal' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-book"></i><span>Buku Besar (GL)</span></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- 6. CRM & Retensi Pelanggan -->
            <?php if ($canCrm): ?>
            <?php $isCrmActive = str_starts_with(uri_string(), 'crm'); ?>
            <div class="sidebar-item">
                <a class="sidebar-link <?= $isCrmActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#menuCrm" role="button" aria-expanded="<?= $isCrmActive ? 'true' : 'false' ?>" aria-controls="menuCrm">
                    <i class="menu-icon fa-solid fa-comments"></i>
                    <span>6. CRM &amp; Follow-Up</span>
                    <i class="sidebar-chevron fa-solid fa-chevron-right"></i>
                </a>
                <div class="collapse <?= $isCrmActive ? 'show' : '' ?>" id="menuCrm" data-bs-parent="#sidebarMenu">
                    <ul class="sidebar-submenu">
                        <?php if (has_permission('crm_dashboard')): ?>
                        <li><a href="<?= base_url('crm/dashboard') ?>" class="submenu-link <?= uri_string() === 'crm/dashboard' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-gauge"></i><span>Dashboard CRM</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('crm_followup')): ?>
                        <li><a href="<?= base_url('crm') ?>" class="submenu-link <?= uri_string() === 'crm' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-book-journal-whills"></i><span>Journal Follow-Up</span></a></li>
                        <li><a href="<?= base_url('crm/broadcast') ?>" class="submenu-link <?= uri_string() === 'crm/broadcast' ? 'active' : '' ?>"><i class="submenu-icon fa-brands fa-whatsapp text-success"></i><span>Broadcast WhatsApp</span></a></li>
                        <li><a href="<?= base_url('crm/directory') ?>" class="submenu-link <?= uri_string() === 'crm/directory' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-address-book"></i><span>Customer Directory</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('crm_dashboard')): ?>
                        <li><a href="<?= base_url('crm/kinerja') ?>" class="submenu-link <?= uri_string() === 'crm/kinerja' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-user-check"></i><span>Kinerja Tim CS</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('crm_followup')): ?>
                        <li><a href="<?= base_url('crm/kelompok') ?>" class="submenu-link <?= uri_string() === 'crm/kelompok' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-layer-group"></i><span>Kelompok Follow-Up</span></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- 7. Analisis RFM -->
            <?php if ($canRfm): ?>
            <?php $isRfmActive = str_starts_with(uri_string(), 'rfm'); ?>
            <div class="sidebar-item">
                <a class="sidebar-link <?= $isRfmActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#menuRfm" role="button" aria-expanded="<?= $isRfmActive ? 'true' : 'false' ?>" aria-controls="menuRfm">
                    <i class="menu-icon fa-solid fa-chart-pie"></i>
                    <span>7. Analisis RFM</span>
                    <i class="sidebar-chevron fa-solid fa-chevron-right"></i>
                </a>
                <div class="collapse <?= $isRfmActive ? 'show' : '' ?>" id="menuRfm" data-bs-parent="#sidebarMenu">
                    <ul class="sidebar-submenu">
                        <li><a href="<?= base_url('rfm') ?>" class="submenu-link <?= uri_string() === 'rfm' || uri_string() === 'rfm/dashboard' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-chart-line"></i><span>Dashboard RFM</span></a></li>
                        <li><a href="<?= base_url('rfm/customer') ?>" class="submenu-link <?= uri_string() === 'rfm/customer' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-users-viewfinder"></i><span>Daftar Customer</span></a></li>
                        <li><a href="<?= base_url('rfm/tracking') ?>" class="submenu-link <?= uri_string() === 'rfm/tracking' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-arrows-split-up-and-left"></i><span>Tracking RFM</span></a></li>
                        <li><a href="<?= base_url('rfm/kohort') ?>" class="submenu-link <?= uri_string() === 'rfm/kohort' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-calendar-week"></i><span>Analisis Kohort</span></a></li>
                        <li><a href="<?= base_url('rfm/tren-produk') ?>" class="submenu-link <?= uri_string() === 'rfm/tren-produk' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-arrow-trend-up"></i><span>Tren Segmen Produk</span></a></li>
                        <li><a href="<?= base_url('rfm/matriks') ?>" class="submenu-link <?= uri_string() === 'rfm/matriks' ? 'active' : '' ?>"><i class="submenu-icon fa-solid fa-table-cells"></i><span>Matriks Aturan RFM</span></a></li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- Section: SISTEM -->
            <div class="sidebar-section-label">Sistem</div>

            <!-- Pusat Notifikasi -->
            <?php 
                $isNotifActive = str_starts_with(uri_string(), 'notifications'); 
                $initialNotifCount = 0;
                try {
                    $notifModel = new \App\Models\NotificationModel();
                    $initialNotifCount = $notifModel->getUnreadCount(session()->get('user')['id'] ?? null);
                } catch (\Throwable $e) {}
            ?>
            <div class="sidebar-item">
                <a href="<?= base_url('notifications') ?>" class="sidebar-link <?= $isNotifActive ? 'active-root' : '' ?>">
                    <i class="menu-icon fa-solid fa-bell"></i>
                    <span>Pusat Notifikasi</span>
                    <span class="badge bg-danger rounded-pill ms-auto px-2 py-0 sidebar-notif-count" style="font-size: 0.68rem; <?= $initialNotifCount > 0 ? '' : 'display: none;' ?>">
                        <?= $initialNotifCount > 99 ? '99+' : $initialNotifCount ?>
                    </span>
                </a>
            </div>

            <!-- 9. Pengaturan & Akses -->
            <?php 
                $canPengaturan = has_permission(['menu_access', 'menu_user', 'menu_group', 'menu_permission']);
                $canDev = has_permission(['menu_crud', 'menu_page', 'menu_form', 'menu_menu', 'menu_docs_web']);
            ?>
            <?php if ($canPengaturan): ?>
            <?php $isPengaturanActive = str_starts_with(uri_string(), 'pengaturan'); ?>
            <div class="sidebar-item">
                <a class="sidebar-link <?= $isPengaturanActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#menuPengaturan" role="button" aria-expanded="<?= $isPengaturanActive ? 'true' : 'false' ?>" aria-controls="menuPengaturan">
                    <i class="menu-icon fa-solid fa-gear"></i>
                    <span>Pengaturan &amp; Akses</span>
                    <i class="sidebar-chevron fa-solid fa-chevron-right"></i>
                </a>
                <div class="collapse <?= $isPengaturanActive ? 'show' : '' ?>" id="menuPengaturan" data-bs-parent="#sidebarMenu">
                    <ul class="sidebar-submenu">
                        <?php if (has_permission(['menu_access', 'menu_user', 'menu_group'])): ?>
                        <li><a href="<?= base_url('pengaturan/settings') ?>" class="submenu-link <?= uri_string() === 'pengaturan/settings' ? 'active' : '' ?>"><span>Settings</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('menu_user')): ?>
                        <li><a href="<?= base_url('pengaturan/user') ?>" class="submenu-link <?= uri_string() === 'pengaturan/user' ? 'active' : '' ?>"><span>User</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('menu_group')): ?>
                        <li><a href="<?= base_url('pengaturan/groups') ?>" class="submenu-link <?= uri_string() === 'pengaturan/groups' ? 'active' : '' ?>"><span>Groups</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission(['menu_user', 'menu_access'])): ?>
                        <li><a href="<?= base_url('pengaturan/approval') ?>" class="submenu-link <?= uri_string() === 'pengaturan/approval' ? 'active' : '' ?>"><span>Approval Calon Customer</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('menu_access')): ?>
                        <li><a href="<?= base_url('pengaturan/access') ?>" class="submenu-link <?= uri_string() === 'pengaturan/access' ? 'active' : '' ?>"><span>Access</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('menu_permission')): ?>
                        <li><a href="<?= base_url('pengaturan/permission') ?>" class="submenu-link <?= uri_string() === 'pengaturan/permission' ? 'active' : '' ?>"><span>Permission</span></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- 10. Developer Tools -->
            <?php if ($canDev): ?>
            <?php $isDevActive = str_starts_with(uri_string(), 'dev'); ?>
            <div class="sidebar-item">
                <a class="sidebar-link <?= $isDevActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#menuDevTools" role="button" aria-expanded="<?= $isDevActive ? 'true' : 'false' ?>" aria-controls="menuDevTools">
                    <i class="menu-icon fa-solid fa-code"></i>
                    <span>Developer Tools</span>
                    <i class="sidebar-chevron fa-solid fa-chevron-right"></i>
                </a>
                <div class="collapse <?= $isDevActive ? 'show' : '' ?>" id="menuDevTools" data-bs-parent="#sidebarMenu">
                    <ul class="sidebar-submenu">
                        <?php if (has_permission('menu_crud')): ?>
                        <li><a href="<?= base_url('dev/crud') ?>" class="submenu-link <?= uri_string() === 'dev/crud' ? 'active' : '' ?>"><span>CRUD Builder</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('menu_page')): ?>
                        <li><a href="<?= base_url('dev/page') ?>" class="submenu-link <?= uri_string() === 'dev/page' ? 'active' : '' ?>"><span>Page Builder</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('menu_form')): ?>
                        <li><a href="<?= base_url('dev/form') ?>" class="submenu-link <?= uri_string() === 'dev/form' ? 'active' : '' ?>"><span>Form Builder</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('menu_menu')): ?>
                        <li><a href="<?= base_url('dev/menu') ?>" class="submenu-link <?= uri_string() === 'dev/menu' ? 'active' : '' ?>"><span>Menu</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('menu_crud')): ?>
                        <li><a href="<?= base_url('dev/extension') ?>" class="submenu-link <?= uri_string() === 'dev/extension' ? 'active' : '' ?>"><span>Extension</span></a></li>
                        <?php endif; ?>
                        <?php if (has_permission('menu_docs_web')): ?>
                        <li><a href="<?= base_url('dev/docs/web') ?>" class="submenu-link <?= uri_string() === 'dev/docs/web' ? 'active' : '' ?>"><span>Web Documentation</span></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar CTA Card -->
        <div class="sidebar-cta-card">
            <h6><i class="fa-solid fa-rocket me-1"></i> Tips Cepat</h6>
            <p>Gunakan pencarian di topbar untuk navigasi cepat ke modul manapun.</p>
        </div>

        <!-- Sidebar Footer: User Info -->
        <a href="<?= base_url('profile') ?>" class="sidebar-footer text-decoration-none" title="Lihat Profil Saya">
            <?php 
                $sessionAvatar = session()->get('user')['avatar'] ?? null;
                $userInitial = strtoupper(substr(session()->get('user')['username'] ?? 'A', 0, 1));
            ?>
            <?php if (!empty($sessionAvatar) && file_exists(FCPATH . 'uploads/avatars/' . $sessionAvatar)): ?>
                <img src="<?= base_url('uploads/avatars/' . esc($sessionAvatar)) ?>" alt="Avatar" class="sidebar-footer-avatar rounded-circle object-fit-cover" style="padding: 0; border: 2px solid rgba(255,255,255,0.2);">
            <?php else: ?>
                <div class="sidebar-footer-avatar"><?= $userInitial . strtoupper(substr(session()->get('user')['username'] ?? 'Ad', 1, 1)) ?></div>
            <?php endif; ?>
            <div class="sidebar-footer-info">
                <div class="sidebar-footer-name d-flex align-items-center justify-content-between">
                    <span class="text-truncate"><?= esc(session()->get('user')['username'] ?? 'Administrator') ?></span>
                    <i class="fa-solid fa-chevron-right text-white-50 ms-1" style="font-size: 0.65rem;"></i>
                </div>
                <div class="sidebar-footer-role text-truncate"><?= esc(session()->get('user')['role_display_name'] ?? 'Super Admin') ?></div>
            </div>
        </a>
    </nav>

    <!-- MAIN CONTENT -->
    <div id="main-content">
        <!-- TOP NAVBAR -->
        <header class="top-navbar" role="banner">
            <div class="d-flex align-items-center gap-2 gap-md-3">
                <button class="topbar-icon-btn border-0 bg-transparent" id="toggleSidebar" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <!-- Search Bar -->
                <div class="topbar-search d-none d-md-block position-relative">
                    <input type="text" placeholder="Cari modul, data, menu..." id="globalSearchInput" aria-label="Pencarian" autocomplete="off">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <span class="search-shortcut d-none d-lg-inline">Ctrl K</span>
                    <div class="topbar-search-dropdown shadow-lg" id="globalSearchDropdown">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- 1. Panduan Alur Kerja Modal Trigger -->
                <button type="button" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1 rounded-pill px-2 px-md-3 py-1 shadow-xs fw-semibold" data-bs-toggle="modal" data-bs-target="#modalWorkflowGuide" title="Buka Panduan Alur Kerja ERP" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-route"></i>
                    <span class="d-none d-md-inline">Alur Bisnis</span>
                </button>

                <!-- 2. Tombol Aksi Cepat / Menu Pintas Transaksi -->
                <?php
                    $qaPos = has_permission('pos_kasir');
                    $qaSo = has_permission('penjualan_transaksi');
                    $qaResi = has_permission(['penjualan_marketplace', 'penjualan_transaksi']);
                    $qaPo = has_permission('po_create');
                    $qaOpname = has_permission('stok_opname');
                    $qaBkm = has_permission('bkm_masuk');
                    $qaWa = has_permission('crm_followup');
                    $hasAnyQa = ($qaPos || $qaSo || $qaResi || $qaPo || $qaOpname || $qaBkm || $qaWa);
                ?>
                <?php if ($hasAnyQa): ?>
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 rounded-pill px-2 px-md-3 py-1 shadow-xs fw-semibold" type="button" id="quickActionDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-bolt text-warning"></i>
                        <span class="d-none d-sm-inline">+ Aksi Cepat</span>
                        <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.65rem;"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border p-2" aria-labelledby="quickActionDropdown" style="width: 290px; border-radius: 14px;">
                        <?php if ($qaPos || $qaSo || $qaResi): ?>
                        <div class="px-2 py-1 text-muted fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">
                            <i class="fa-solid fa-cash-register text-success me-1"></i> Penjualan &amp; Kasir
                        </div>
                        <?php if ($qaPos): ?>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= base_url('pos') ?>">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                <i class="fa-solid fa-bolt small"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Kasir Cepat (POS)</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Transaksi ritel kasir &amp; struk thermal</div>
                            </div>
                        </a>
                        <?php endif; ?>
                        <?php if ($qaSo): ?>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= base_url('penjualan/create') ?>">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                <i class="fa-solid fa-plus small"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Buat Sales Order (SO)</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Order penjualan &amp; cetak invoice</div>
                            </div>
                        </a>
                        <?php endif; ?>
                        <?php if ($qaResi): ?>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= base_url('marketplace/scan-resi') ?>">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                <i class="fa-solid fa-barcode small"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Scan / Cek Resi Marketplace</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Packing pesanan e-commerce</div>
                            </div>
                        </a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($qaPo || $qaOpname): ?>
                        <?php if ($qaPos || $qaSo || $qaResi): ?><div class="dropdown-divider my-1"></div><?php endif; ?>
                        <div class="px-2 py-1 text-muted fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">
                            <i class="fa-solid fa-truck text-primary me-1"></i> Pengadaan &amp; Stok
                        </div>
                        <?php if ($qaPo): ?>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= base_url('pembelian/create') ?>">
                            <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                <i class="fa-solid fa-file-invoice-dollar small"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Buat PO Supplier Baru</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Pengadaan stok ke vendor resmi</div>
                            </div>
                        </a>
                        <?php endif; ?>
                        <?php if ($qaOpname): ?>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= base_url('stok/opname') ?>">
                            <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                <i class="fa-solid fa-boxes-stacked small"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Stok Opname / Edit Stok</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Penyesuaian fisik stok barang</div>
                            </div>
                        </a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($qaBkm || $qaWa): ?>
                        <?php if ($qaPos || $qaSo || $qaResi || $qaPo || $qaOpname): ?><div class="dropdown-divider my-1"></div><?php endif; ?>
                        <div class="px-2 py-1 text-muted fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">
                            <i class="fa-solid fa-wallet text-secondary me-1"></i> Keuangan &amp; CRM
                        </div>
                        <?php if ($qaBkm): ?>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= base_url('keuangan/bkm') ?>">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                <i class="fa-solid fa-arrow-down small"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Penerimaan Kas (BKM)</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Pelunasan piutang / kas masuk</div>
                            </div>
                        </a>
                        <?php endif; ?>
                        <?php if ($qaWa): ?>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="<?= base_url('crm/broadcast') ?>">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                <i class="fa-brands fa-whatsapp small"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">Kirim Broadcast WhatsApp</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Kirim info promo ke pelanggan</div>
                            </div>
                        </a>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Notification Bell -->
                <div class="dropdown">
                    <button class="topbar-icon-btn position-relative" type="button" id="notifDropdownToggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" title="Pusat Notifikasi" aria-label="Notifikasi">
                        <i class="fa-regular fa-bell"></i>
                        <span class="notif-badge-pill" id="notifBadge">99+</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notif-dropdown-menu p-0" aria-labelledby="notifDropdownToggle">
                        <!-- Dropdown Header -->
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 bg-white border-bottom" style="border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold" style="font-size: 0.88rem; color: var(--text-primary);">Notifikasi</span>
                                <span class="badge bg-warning text-dark fw-bold px-2 py-0" id="notifDropdownCount" style="font-size: 0.68rem; border-radius: 50rem;">...</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 fw-semibold" id="btnMarkAllReadAjax" style="font-size: 0.75rem; color: var(--accent-green);">
                                <i class="fa-solid fa-check-double me-1"></i>Tandai Dibaca
                            </button>
                        </div>

                        <!-- Category Tabs -->
                        <div class="d-flex align-items-center gap-1 px-3 py-2 border-bottom overflow-x-auto" style="scrollbar-width: none; background: var(--body-bg);">
                            <button type="button" class="btn btn-xs rounded-pill px-2 py-0 notif-tab active" data-category="all" style="font-size: 0.7rem; background: var(--accent-green); color: white; border: none;">Semua</button>
                            <button type="button" class="btn btn-xs btn-light rounded-pill px-2 py-0 notif-tab" data-category="penjualan" style="font-size: 0.7rem;">Penjualan</button>
                            <button type="button" class="btn btn-xs btn-light rounded-pill px-2 py-0 notif-tab" data-category="stok" style="font-size: 0.7rem;">Stok</button>
                            <button type="button" class="btn btn-xs btn-light rounded-pill px-2 py-0 notif-tab" data-category="keuangan" style="font-size: 0.7rem;">Keuangan</button>
                            <button type="button" class="btn btn-xs btn-light rounded-pill px-2 py-0 notif-tab" data-category="crm" style="font-size: 0.7rem;">CRM</button>
                            <button type="button" class="btn btn-xs btn-light rounded-pill px-2 py-0 notif-tab" data-category="approval" style="font-size: 0.7rem;">Approval</button>
                        </div>

                        <!-- Scrollable Feed -->
                        <div class="notif-feed-container" id="notifFeedContainer" style="max-height: 320px; overflow-y: auto;">
                            <div class="text-center py-4 text-muted small">
                                <i class="fa-solid fa-spinner fa-spin me-1"></i> Memuat notifikasi...
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="p-2 border-top text-center" style="background: var(--body-bg); border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                            <a href="<?= base_url('notifications') ?>" class="small fw-bold text-decoration-none d-block py-1" style="color: var(--accent-green);">
                                Lihat Semua Notifikasi <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--text-primary);">
                        <?php if (!empty($sessionAvatar) && file_exists(FCPATH . 'uploads/avatars/' . $sessionAvatar)): ?>
                            <img src="<?= base_url('uploads/avatars/' . esc($sessionAvatar)) ?>" alt="Avatar" class="user-avatar-circle object-fit-cover" style="padding: 0; border: 2px solid var(--accent-green, #2D6A4F);">
                        <?php else: ?>
                            <div class="user-avatar-circle">
                                <?= $userInitial ?>
                            </div>
                        <?php endif; ?>
                        <span class="fw-medium small d-none d-sm-inline"><?= esc(session()->get('user')['username'] ?? 'Developer') ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 mt-2 shadow-sm" style="border-radius: 12px; min-width: 200px;">
                        <li><h6 class="dropdown-header small pb-1"><?= esc(session()->get('user')['email'] ?? 'developer@metafora.local') ?></h6></li>
                        <li><span class="dropdown-item-text small text-muted pt-0"><i class="fa-solid fa-shield-halved me-1 text-primary"></i><?= esc(session()->get('user')['role_display_name'] ?? 'Super Admin') ?></span></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item small d-flex align-items-center gap-2 py-1.5 <?= uri_string() === 'profile' ? 'active' : '' ?>" href="<?= base_url('profile') ?>"><i class="fa-solid fa-circle-user text-primary"></i><span>Profil Saya</span></a></li>
                        <li><a class="dropdown-item text-danger small d-flex align-items-center gap-2 py-1.5" href="<?= base_url('logout') ?>"><i class="fa-solid fa-right-from-bracket"></i><span>Keluar</span></a></li>
                    </ul>
                </div>

            </div>
        </header>

        <!-- FLASH NOTIFICATIONS -->
        <div class="content-wrapper" role="main">
            <?php if ($successMsg = session()->getFlashdata('success')): ?>
                <?php
                    try {
                        $notifService = new \App\Services\NotificationService();
                        $userId = session()->get('user')['id'] ?? null;
                        $uriStr = uri_string();
                        $cat = 'sistem';
                        if (str_starts_with($uriStr, 'master') || str_starts_with($uriStr, 'stok')) $cat = 'stok';
                        elseif (str_starts_with($uriStr, 'pembelian') || str_starts_with($uriStr, 'penjualan') || str_starts_with($uriStr, 'pos') || str_starts_with($uriStr, 'retur')) $cat = 'penjualan';
                        elseif (str_starts_with($uriStr, 'keuangan')) $cat = 'keuangan';
                        elseif (str_starts_with($uriStr, 'pengaturan')) $cat = 'approval';
                        elseif (str_starts_with($uriStr, 'crm')) $cat = 'crm';
                        $notifService->logActivity('Aktivitas: ' . strip_tags($successMsg), 'Perubahan data berhasil dilakukan pada modul ' . ucfirst($cat) . ' (' . date('d/m/Y H:i') . ')', $cat, 'success', current_url(), $userId);
                    } catch (\Throwable $e) {}
                ?>
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-left: 4px solid #2D6A4F;">
                    <i class="fa-solid fa-circle-check fs-5" style="color: var(--accent-green);"></i>
                    <div class="flex-grow-1"><?= strip_tags($successMsg, '<strong><b><i><em><code><br><small><ul><ol><li><span><p><hr>') ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-left: 4px solid #C0392B;">
                    <i class="fa-solid fa-triangle-exclamation fs-5 text-danger"></i>
                    <div class="flex-grow-1"><?= strip_tags(session()->getFlashdata('error'), '<strong><b><i><em><code><br><small><ul><ol><li><span><p><hr>') ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 4px solid #C0392B;">
                    <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Terjadi kesalahan input:</div>
                    <ul class="mb-0 ps-3 small">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>

        <!-- FOOTER -->
        <footer class="app-footer mt-auto d-flex flex-column flex-sm-row align-items-center justify-content-between" role="contentinfo">
            <div>Copyright &copy; 2020 &ndash; <?= date('Y') ?> <strong>IDMETAFORA</strong>. All rights reserved.</div>
            <div class="mt-1 mt-sm-0">v3.0.6</div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // ==========================================
        // CSRF AJAX & FORM SETUP
        // ==========================================
        function getCsrfCookie() {
            const name = '<?= config('Security')->cookieName ?>' + '=';
            const decodedCookie = decodeURIComponent(document.cookie);
            const ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i].trim();
                if (c.indexOf(name) === 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return $('meta[name="csrf-token-hash"]').attr('content') || '';
        }

        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    const csrfToken = getCsrfCookie();
                    const csrfHeader = $('meta[name="csrf-header"]').attr('content') || 'X-CSRF-TOKEN';
                    xhr.setRequestHeader(csrfHeader, csrfToken);
                }
            },
            complete: function(xhr) {
                const newToken = xhr.getResponseHeader('X-CSRF-TOKEN') || getCsrfCookie();
                if (newToken) {
                    $('meta[name="csrf-token-hash"]').attr('content', newToken);
                    $('input[name="<?= csrf_token() ?>"]').val(newToken);
                }
            }
        });

        // Selalu pastikan token form sinkron dengan cookie CSRF saat submit
        $(document).on('submit', 'form', function() {
            const latestToken = getCsrfCookie();
            if (latestToken) {
                let csrfInput = $(this).find('input[name="<?= csrf_token() ?>"]');
                if (csrfInput.length) {
                    csrfInput.val(latestToken);
                } else {
                    $(this).append('<input type="hidden" name="<?= csrf_token() ?>" value="' + latestToken + '">');
                }
            }
        });

        // ==========================================
        // SIDEBAR TOGGLE (Desktop & Mobile)
        // ==========================================
        function isMobile() { return window.innerWidth <= 992; }

        function toggleSidebar() {
            if (isMobile()) {
                $('#sidebar').hasClass('show') ? closeMobileSidebar() : openMobileSidebar();
            } else {
                $('body').toggleClass('sidebar-collapsed');
                localStorage.setItem('metafora_sidebar_collapsed', $('body').hasClass('sidebar-collapsed') ? 'true' : 'false');
            }
        }

        function openMobileSidebar() {
            $('#sidebar').addClass('show');
            $('#sidebarBackdrop').addClass('show');
            $('body').css('overflow-y', 'hidden');
        }

        function closeMobileSidebar() {
            $('#sidebar').removeClass('show');
            $('#sidebarBackdrop').removeClass('show');
            $('body').css('overflow-y', '');
        }

        $('#toggleSidebar').on('click', function(e) { e.preventDefault(); e.stopPropagation(); toggleSidebar(); });
        $('#btnCollapseDesktop').on('click', function(e) { e.preventDefault(); $('body').addClass('sidebar-collapsed'); localStorage.setItem('metafora_sidebar_collapsed', 'true'); });
        $('#sidebarClose, #sidebarBackdrop').on('click', closeMobileSidebar);
        $('.submenu-link').on('click', function() { if (isMobile()) closeMobileSidebar(); });

        // Restore sidebar state
        if (!isMobile() && localStorage.getItem('metafora_sidebar_collapsed') === 'true') {
            $('body').addClass('sidebar-collapsed');
        }

        // ==========================================
        // MODAL SYSTEM & UNLOCK ENGINE
        // Guarantees zero trapping, zero negative cutoff, and instant cleanup
        // ==========================================
        function cleanupLingeringBackdrops() {
            if ($('.modal.show').length === 0) {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css({'overflow': '', 'padding-right': ''});
            }
        }

        // Hoist all modals directly to body on document ready
        // Prevents modals from being trapped inside animated containers or local stacking contexts
        $(document).ready(function() {
            cleanupLingeringBackdrops();
            $('.modal').each(function() {
                if ($(this).parent()[0] !== document.body) {
                    $(this).appendTo('body');
                }
            });
        });

        // Also hoist dynamically inserted modals before showing
        $(document).on('show.bs.modal', '.modal', function () {
            if ($(this).parent()[0] !== document.body) {
                $(this).appendTo('body');
            }
        });

        // Cleanup immediately upon modal hidden
        $(document).on('hidden.bs.modal', '.modal', function () {
            cleanupLingeringBackdrops();
        });

        // Universal dismiss button safety net
        $(document).on('click', '[data-bs-dismiss="modal"]', function() {
            const modalEl = $(this).closest('.modal');
            if (modalEl.length) {
                const inst = bootstrap.Modal.getInstance(modalEl[0]);
                if (inst) inst.hide();
                else modalEl.modal('hide');
            }
            setTimeout(cleanupLingeringBackdrops, 250);
        });

        // Universal Escape key modal closer
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('#globalSearchDropdown').removeClass('show');
                if ($('.modal.show').length > 0) {
                    const activeModal = $('.modal.show').last();
                    const inst = bootstrap.Modal.getInstance(activeModal[0]);
                    if (inst) inst.hide();
                    else activeModal.modal('hide');
                    setTimeout(cleanupLingeringBackdrops, 250);
                }
            }
        });

        // ==========================================
        // GLOBAL SEARCH ENGINE (Ctrl+K / Cmd+K)
        // ==========================================
        const erpModules = [
            { category: 'Workspace', name: 'Dashboard', url: '<?= base_url('/') ?>', icon: 'fa-chart-line' },
            <?php if (has_permission('master_pelanggan')): ?>{ category: 'Master Data', name: 'Pelanggan', url: '<?= base_url('master/pelanggan') ?>', icon: 'fa-users' },<?php endif; ?>
            <?php if (has_permission('master_supplier')): ?>{ category: 'Master Data', name: 'Supplier & Kurs Valas', url: '<?= base_url('master/supplier') ?>', icon: 'fa-truck' },<?php endif; ?>
            <?php if (has_permission('master_produk')): ?>{ category: 'Master Data', name: 'Master Produk & Stok', url: '<?= base_url('master/produk') ?>', icon: 'fa-cubes' },<?php endif; ?>
            <?php if (has_permission('master_kategori')): ?>{ category: 'Master Data', name: 'Kategori Barang', url: '<?= base_url('master/kategori') ?>', icon: 'fa-tag' },<?php endif; ?>
            <?php if (has_permission('master_satuan')): ?>{ category: 'Master Data', name: 'Unit / Satuan Barang', url: '<?= base_url('master/satuan') ?>', icon: 'fa-scale-balanced' },<?php endif; ?>
            <?php if (has_permission('stok_dashboard')): ?>{ category: 'Manajemen Stok', name: 'Dashboard Stok', url: '<?= base_url('stok') ?>', icon: 'fa-chart-pie' },<?php endif; ?>
            <?php if (has_permission('stok_opname')): ?>{ category: 'Manajemen Stok', name: 'Edit Stok Opname', url: '<?= base_url('stok/opname') ?>', icon: 'fa-boxes-stacked' },<?php endif; ?>
            <?php if (has_permission('po_list')): ?>{ category: 'Transaksi', name: 'Purchase Order (PO)', url: '<?= base_url('pembelian') ?>', icon: 'fa-cart-shopping' },<?php endif; ?>
            <?php if (has_permission('po_create')): ?>{ category: 'Transaksi', name: 'Buat PO Baru', url: '<?= base_url('pembelian/create') ?>', icon: 'fa-plus-circle' },<?php endif; ?>
            <?php if (has_permission('pos_kasir')): ?>{ category: 'Transaksi', name: 'Kasir Cepat POS', url: '<?= base_url('pos') ?>', icon: 'fa-cash-register' },<?php endif; ?>
            <?php if (has_permission('penjualan_transaksi')): ?>{ category: 'Transaksi', name: 'Transaksi Penjualan', url: '<?= base_url('penjualan') ?>', icon: 'fa-receipt' },<?php endif; ?>
            <?php if (has_permission('penjualan_marketplace')): ?>{ category: 'Transaksi', name: 'Pesanan Marketplace Online', url: '<?= base_url('marketplace') ?>', icon: 'fa-shop' },<?php endif; ?>
            <?php if (has_permission(['penjualan_marketplace', 'penjualan_transaksi'])): ?>{ category: 'Transaksi', name: 'Scan & Import Resi', url: '<?= base_url('marketplace/scan-resi') ?>', icon: 'fa-barcode' },<?php endif; ?>
            <?php if (has_permission('penjualan_invoice')): ?>{ category: 'Transaksi', name: 'Daftar Invoice', url: '<?= base_url('invoice') ?>', icon: 'fa-file-invoice' },<?php endif; ?>
            <?php if (has_permission(['retur_penjualan', 'retur_pembelian'])): ?>{ category: 'Transaksi', name: 'Retur Penjualan & Pembelian', url: '<?= base_url('retur') ?>', icon: 'fa-rotate-left' },<?php endif; ?>
            <?php if (has_permission('gl_buku_besar')): ?>{ category: 'Keuangan', name: 'Buku Besar & Jurnal (GL)', url: '<?= base_url('keuangan/jurnal') ?>', icon: 'fa-book-journal-whills' },<?php endif; ?>
            <?php if (has_permission(['bkm_masuk', 'bkk_keluar', 'gl_buku_besar'])): ?>{ category: 'Keuangan', name: 'Kas & Bank', url: '<?= base_url('keuangan/kas-bank') ?>', icon: 'fa-building-columns' },<?php endif; ?>
            <?php if (has_permission('ar_piutang')): ?>{ category: 'Keuangan', name: 'Piutang Usaha (AR)', url: '<?= base_url('keuangan/piutang') ?>', icon: 'fa-money-bill-trend-up' },<?php endif; ?>
            <?php if (has_permission('ap_utang')): ?>{ category: 'Keuangan', name: 'Utang Usaha (AP)', url: '<?= base_url('keuangan/utang') ?>', icon: 'fa-money-bill-transfer' },<?php endif; ?>
            <?php if (has_permission('crm_dashboard')): ?>{ category: 'Analitik CRM', name: 'Dashboard CRM & Follow Up', url: '<?= base_url('crm') ?>', icon: 'fa-users-gear' },<?php endif; ?>
            <?php if (has_permission('rfm_analisis')): ?>{ category: 'Analitik RFM', name: 'Analisis RFM & Segmentasi', url: '<?= base_url('rfm') ?>', icon: 'fa-chart-simple' },<?php endif; ?>
            <?php if (has_permission(['menu_access', 'menu_user'])): ?>{ category: 'Sistem', name: 'Pengaturan & Akses Pengguna', url: '<?= base_url('pengaturan/user') ?>', icon: 'fa-user-gear' },<?php endif; ?>
            { category: 'Akun', name: 'Profil Saya (Identitas, Foto & Kata Sandi)', url: '<?= base_url('profile') ?>', icon: 'fa-circle-user' },
            <?php if (has_permission('menu_crud')): ?>{ category: 'Developer', name: 'CRUD & Code Generator', url: '<?= base_url('dev/crud') ?>', icon: 'fa-code' },<?php endif; ?>
        ];

        function renderSearchResults(filter = '') {
            const dropdown = $('#globalSearchDropdown');
            const q = (filter || '').trim().toLowerCase();
            const filtered = q ? erpModules.filter(m => m.name.toLowerCase().includes(q) || m.category.toLowerCase().includes(q)) : erpModules.slice(0, 8);
            
            if (filtered.length === 0) {
                dropdown.html('<div class="p-3 text-center text-muted small"><i class="fa-solid fa-magnifying-glass me-1"></i> Tidak ditemukan menu untuk "<strong>' + $('<div>').text(q).html() + '</strong>"</div>');
                return;
            }
            
            let html = '';
            let currentCat = '';
            filtered.forEach(item => {
                if (item.category !== currentCat) {
                    currentCat = item.category;
                    html += '<div class="search-group-title">' + currentCat + '</div>';
                }
                html += '<a href="' + item.url + '" class="search-result-item">' +
                        '<i class="fa-solid ' + item.icon + '"></i>' +
                        '<span>' + item.name + '</span>' +
                        '</a>';
            });
            dropdown.html(html);
        }

        $('#globalSearchInput').on('focus', function() {
            renderSearchResults(this.value);
            $('#globalSearchDropdown').addClass('show');
        });

        $('#globalSearchInput').on('input', function() {
            renderSearchResults(this.value);
            $('#globalSearchDropdown').addClass('show');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.topbar-search').length) {
                $('#globalSearchDropdown').removeClass('show');
            }
        });

        $(document).on('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                $('#globalSearchInput').focus().select();
                renderSearchResults($('#globalSearchInput').val());
                $('#globalSearchDropdown').addClass('show');
            }
        });

        // Handle window resize — close mobile sidebar if resized to desktop
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (!isMobile()) {
                    closeMobileSidebar();
                }
            }, 150);
        });

        // Swipe to close sidebar on mobile
        let touchStartX = 0;
        document.getElementById('sidebar')?.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        document.getElementById('sidebar')?.addEventListener('touchend', function(e) {
            const diff = touchStartX - e.changedTouches[0].screenX;
            if (diff > 60 && isMobile()) closeMobileSidebar();
        }, { passive: true });

        // ==========================================
        // NOTIFICATION SYSTEM ENGINE
        // ==========================================
        let currentNotifCategory = 'all';

        function loadNotifications(category = 'all') {
            currentNotifCategory = category;
            const container = $('#notifFeedContainer');

            $.ajax({
                url: '<?= base_url('notifications/feed') ?>',
                type: 'GET',
                data: { category: category },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        const badge = $('#notifBadge');
                        const count = res.unread_count;
                        if (count > 0) {
                            badge.text(res.unread_label).show();
                            $('#notifDropdownCount').text(count + ' Baru');
                            $('.sidebar-notif-count').text(res.unread_label).show();
                        } else {
                            badge.text('0').hide();
                            $('#notifDropdownCount').text('0 Baru');
                            $('.sidebar-notif-count').text('0').hide();
                        }

                        if (!res.notifications || res.notifications.length === 0) {
                            container.html('<div class="text-center py-4 text-muted small"><i class="fa-regular fa-bell-slash fs-3 d-block mb-2 opacity-50"></i>Tidak ada notifikasi baru</div>');
                            return;
                        }

                        let html = '';
                        res.notifications.forEach(item => {
                            const isUnread = item.is_read == 0;
                            const unreadClass = isUnread ? 'unread' : '';
                            const unreadDot = isUnread ? '<span class="badge bg-warning text-dark px-1 py-0 ms-1 fw-bold" style="font-size: 0.58rem; border-radius: 50rem;">BARU</span>' : '';

                            let iconBg = 'badge-soft-info';
                            if (item.type === 'danger') iconBg = 'badge-soft-danger';
                            else if (item.type === 'warning') iconBg = 'badge-soft-warning';
                            else if (item.type === 'success') iconBg = 'badge-soft-success';
                            else if (item.type === 'primary') iconBg = 'badge-soft-primary';

                            html += `
                                <a href="${item.url || '#'}" class="notif-item ${unreadClass}" data-id="${item.id}" data-url="${item.url || '#'}">
                                    <div class="notif-item-icon ${iconBg}">
                                        <i class="${item.icon || 'fa-solid fa-bell'}"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="notif-item-title text-truncate">${escapeHtml(item.title)} ${unreadDot}</div>
                                        <div class="notif-item-desc" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${escapeHtml(item.message)}</div>
                                        <div class="notif-item-time d-flex align-items-center gap-1"><i class="fa-regular fa-clock"></i><span>${escapeHtml(item.time_ago)}</span></div>
                                    </div>
                                </a>`;
                        });
                        container.html(html);
                    }
                },
                error: function() {
                    container.html('<div class="text-center py-3 text-danger small"><i class="fa-solid fa-triangle-exclamation me-1"></i> Gagal memuat</div>');
                }
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            return $('<div>').text(text).html();
        }

        $(document).on('click', '.notif-tab', function(e) {
            e.preventDefault();
            $('.notif-tab').removeClass('active').css({'background': '', 'color': ''}).addClass('btn-light');
            $(this).removeClass('btn-light').addClass('active').css({'background': 'var(--accent-green)', 'color': 'white', 'border': 'none'});
            loadNotifications($(this).data('category'));
        });

        $(document).on('click', '.notif-item', function(e) {
            const $item = $(this);
            const notifId = $item.data('id');
            const targetUrl = $item.data('url');
            if ($item.hasClass('unread')) {
                $.ajax({
                    url: '<?= base_url('notifications/mark-read') ?>/' + notifId,
                    type: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        if (res && typeof res.unread_count !== 'undefined') {
                            if (res.unread_count > 0) {
                                $('#notifBadge').text(res.unread_label).show();
                                $('#notifDropdownCount').text(res.unread_count + ' Baru');
                                $('.sidebar-notif-count').text(res.unread_label).show();
                            } else {
                                $('#notifBadge').text('0').hide();
                                $('#notifDropdownCount').text('0 Baru');
                                $('.sidebar-notif-count').text('0').hide();
                            }
                        }
                    }
                });
            }
            if (targetUrl && targetUrl !== '#') {
                window.location.href = targetUrl;
            } else {
                e.preventDefault();
                $item.slideUp(180, function() {
                    $item.remove();
                    if ($('#notifFeedContainer .notif-item').length === 0) {
                        $('#notifFeedContainer').html('<div class="text-center py-4 text-muted small"><i class="fa-regular fa-bell-slash fs-3 d-block mb-2 opacity-50"></i>Tidak ada notifikasi baru</div>');
                    }
                });
            }
        });

        $('#btnMarkAllReadAjax').on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');
            $.ajax({
                url: '<?= base_url('notifications/mark-all-read') ?>',
                type: 'POST', dataType: 'json',
                success: function() {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-check-double me-1"></i>Tandai Dibaca');
                    $('#notifBadge').text('0').hide();
                    $('#notifDropdownCount').text('0 Baru');
                    $('.sidebar-notif-count').text('0').hide();
                    $('#notifFeedContainer').html('<div class="text-center py-4 text-muted small"><i class="fa-regular fa-bell-slash fs-3 d-block mb-2 opacity-50"></i>Tidak ada notifikasi baru</div>');
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-check-double me-1"></i>Tandai Dibaca');
                }
            });
        });

        $(document).ready(function() { loadNotifications(); });

        // ==========================================
        // SWEETALERT2 TOAST & INTERACTION ENGINE
        // ==========================================
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false,
            timer: 3500, timerProgressBar: true,
            showClass: { popup: 'animate__animated animate__fadeInDown animate__faster' },
            hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        window.notifySuccess = function(msg, t='Berhasil') { Toast.fire({ icon: 'success', title: msg || t }); loadNotifications(); };
        window.notifyError   = function(msg, t='Gagal')     { Toast.fire({ icon: 'error',   title: msg || t }); };
        window.notifyWarning = function(msg, t='Peringatan'){ Toast.fire({ icon: 'warning', title: msg || t }); };
        window.notifyInfo    = function(msg, t='Informasi') { Toast.fire({ icon: 'info',    title: msg || t }); };

        <?php if ($successMsg = session()->getFlashdata('success')): ?>
            Toast.fire({ icon: 'success', title: <?= json_encode(strip_tags((string)$successMsg)) ?> });
        <?php endif; ?>
        <?php if ($errorMsg = session()->getFlashdata('error')): ?>
            Toast.fire({ icon: 'error', title: <?= json_encode(strip_tags((string)$errorMsg)) ?> });
        <?php endif; ?>
        <?php if ($errorsList = session()->getFlashdata('errors')): ?>
            Swal.fire({
                icon: 'warning', title: 'Periksa Kembali Inputan',
                html: '<div class="text-start small mt-2"><ul class="mb-0 ps-3">' + <?= json_encode(implode('', array_map(fn($e) => '<li>' . esc($e) . '</li>', (array)$errorsList))) ?> + '</ul></div>',
                confirmButtonColor: '#2D6A4F', confirmButtonText: 'Tutup'
            });
        <?php endif; ?>

        // Universal Delete Confirmation
        $(document).on('click', 'a[href*="/delete/"], .btn-action-delete, a.text-danger:has(.fa-trash), button.text-danger:has(.fa-trash), [data-action="delete"]', function(e) {
            const href = $(this).attr('href');
            if (!href || href === '#' || href.startsWith('javascript:')) return;
            e.preventDefault(); e.stopPropagation();
            Swal.fire({
                title: 'Hapus Data?', text: 'Data yang dihapus tidak dapat dipulihkan.',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#C0392B', cancelButtonColor: '#8DA398',
                confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Hapus',
                cancelButtonText: 'Batal', reverseButtons: true, focusCancel: true
            }).then((r) => { if (r.isConfirmed) window.location.href = href; });
        });

        // Universal Form Delete / Clear / Reject / Cancel Confirmation
        $(document).on('submit', 'form[action*="/delete/"], form[action*="/clear"], form[action*="/reject/"], form[action*="/cancel"], form[action*="/revoke"], form.form-confirm', function(e) {
            const form = this;
            if (form.dataset.confirmed === 'true') return true;
            e.preventDefault(); e.stopPropagation();
            Swal.fire({
                title: 'Konfirmasi', text: 'Yakin ingin melanjutkan tindakan ini?',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#C0392B', cancelButtonColor: '#8DA398',
                confirmButtonText: '<i class="fa-solid fa-check me-1"></i> Lanjutkan',
                cancelButtonText: 'Batal', reverseButtons: true, focusCancel: true
            }).then((r) => { if (r.isConfirmed) { form.dataset.confirmed = 'true'; form.submit(); } });
        });

        // Auto-sanitize any inline native confirm() calls and upgrade to SweetAlert2
        $('[onclick*="confirm("], [onsubmit*="confirm("]').each(function() {
            if ($(this).attr('onclick')?.includes('confirm(')) {
                $(this).removeAttr('onclick').addClass('btn-action-delete');
            }
            if ($(this).attr('onsubmit')?.includes('confirm(')) {
                $(this).removeAttr('onsubmit');
            }
        });

        // ==========================================
        // GLOBAL DATATABLES DEFAULTS
        // ==========================================
        if ($.fn.DataTable) {
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    search: '',
                    searchPlaceholder: 'Cari data...',
                    lengthMenu: 'Tampil _MENU_ data',
                    info: 'Menampilkan _START_-_END_ dari _TOTAL_',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total)',
                    zeroRecords: '<div class="text-center py-3 text-muted"><i class="fa-solid fa-inbox fs-3 d-block mb-2 opacity-50"></i>Data tidak ditemukan</div>',
                    paginate: { previous: '<i class="fa-solid fa-chevron-left"></i>', next: '<i class="fa-solid fa-chevron-right"></i>' },
                    emptyTable: '<div class="text-center py-4 text-muted"><i class="fa-solid fa-database fs-3 d-block mb-2 opacity-50"></i>Belum ada data</div>'
                },
                dom: "<'row align-items-center mb-3'<'col-sm-6 col-12'l><'col-sm-6 col-12'f>>" +
                     "<'table-responsive'tr>" +
                     "<'row align-items-center mt-3'<'col-sm-5 col-12 small text-muted'i><'col-sm-7 col-12'p>>",
                pageLength: 10,
                responsive: true
            });
        }
    </script>
    <!-- =============================================
         MODAL PANDUAN ALUR KERJA ERP (WORKFLOW GUIDE)
         ============================================= -->
    <div class="modal fade" id="modalWorkflowGuide" tabindex="-1" aria-labelledby="modalWorkflowGuideLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-dark text-white py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-success bg-opacity-20 text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-route fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="modalWorkflowGuideLabel">Peta Alur Kerja &amp; Navigasi ERP</h5>
                            <small class="text-white-50">Panduan langkah operasional bisnis dari awal hingga akhir</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                        <span class="fw-bold text-dark small"><i class="fa-solid fa-arrows-spin me-1 text-success"></i> 6 Tahapan Siklus Bisnis ERP</span>
                        <span class="badge bg-white text-secondary border shadow-xs small">Alur Terintegrasi</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                                <!-- Step 1 -->
                                <div class="card border-0 shadow-xs rounded-3 p-3 bg-white">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">1</div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-database text-success me-1"></i> Input Data Master (Katalog &amp; Rekanan)</h6>
                                                <span class="badge bg-light text-secondary border small">Langkah Awal</span>
                                            </div>
                                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Daftarkan katalog produk barang, harga jual/beli, data pelanggan member/retail, serta rekanan supplier resmi.</p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="<?= base_url('master/produk') ?>" class="btn btn-xs btn-outline-success rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-cubes me-1"></i>Produk</a>
                                                <a href="<?= base_url('master/pelanggan') ?>" class="btn btn-xs btn-outline-success rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-users me-1"></i>Pelanggan</a>
                                                <a href="<?= base_url('master/supplier') ?>" class="btn btn-xs btn-outline-success rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-truck me-1"></i>Supplier</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2 -->
                                <div class="card border-0 shadow-xs rounded-3 p-3 bg-white">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">2</div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-cart-shopping text-primary me-1"></i> Pengadaan / Pembelian (Purchase Order)</h6>
                                                <span class="badge bg-primary bg-opacity-10 text-primary small">Order ke Supplier</span>
                                            </div>
                                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Saat stok menipis, buat order pembelian (PO) ke supplier dengan kalkulasi kurs valas dan jatuh tempo utang usaha (AP).</p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="<?= base_url('pembelian/create') ?>" class="btn btn-xs btn-primary rounded-pill px-2 py-1 small text-white" style="font-size: 0.75rem;"><i class="fa-solid fa-plus me-1"></i>Buat PO Baru</a>
                                                <a href="<?= base_url('pembelian') ?>" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-file-lines me-1"></i>Daftar PO</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div class="card border-0 shadow-xs rounded-3 p-3 bg-white">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">3</div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-boxes-stacked text-warning me-1"></i> Manajemen &amp; Penerimaan Stok</h6>
                                                <span class="badge bg-warning bg-opacity-20 text-dark small">Penyimpanan Fisik</span>
                                            </div>
                                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Barang yang diterima dicocokkan dengan fisik. Lakukan stok opname berkala untuk audit selisih stok fisik.</p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="<?= base_url('stok') ?>" class="btn btn-xs btn-outline-warning text-dark rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-chart-pie me-1"></i>Monitoring Stok</a>
                                                <a href="<?= base_url('stok/opname') ?>" class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-pen-to-square me-1"></i>Stok Opname</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4 -->
                                <div class="card border-0 shadow-xs rounded-3 p-3 bg-white">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle bg-danger text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">4</div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-cash-register text-danger me-1"></i> Penjualan &amp; Kasir (POS / Marketplace)</h6>
                                                <span class="badge bg-danger bg-opacity-10 text-danger small">Pendapatan Utama</span>
                                            </div>
                                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Layani transaksi kasir cepat di toko, buat pesanan SO pesanan grosir, serta proses scan resi order e-commerce.</p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="<?= base_url('pos') ?>" class="btn btn-xs btn-success rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-bolt me-1"></i>Kasir Cepat (POS)</a>
                                                <a href="<?= base_url('penjualan/create') ?>" class="btn btn-xs btn-outline-danger rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-plus me-1"></i>Buat Pesanan SO</a>
                                                <a href="<?= base_url('marketplace/scan-resi') ?>" class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-barcode me-1"></i>Scan Resi</a>
                                                <a href="<?= base_url('penjualan/invoice') ?>" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-file-invoice me-1"></i>Invoice</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 5 -->
                                <div class="card border-0 shadow-xs rounded-3 p-3 bg-white">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle bg-secondary text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">5</div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-money-bill-wave text-secondary me-1"></i> Keuangan, Piutang (AR) &amp; Utang (AP)</h6>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary small">Arus Kas &amp; Neraca</span>
                                            </div>
                                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Catat pembayaran piutang pelanggan via Kas Masuk (BKM), pembayaran utang supplier via Kas Keluar (BKK), dan cek Buku Besar (GL).</p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="<?= base_url('keuangan/piutang') ?>" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-hand-holding-dollar me-1"></i>Piutang (AR)</a>
                                                <a href="<?= base_url('keuangan/bkm') ?>" class="btn btn-xs btn-outline-success rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-arrow-down me-1"></i>Kas Masuk (BKM)</a>
                                                <a href="<?= base_url('keuangan/jurnal') ?>" class="btn btn-xs btn-outline-dark rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-book me-1"></i>Buku Besar (GL)</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 6 -->
                                <div class="card border-0 shadow-xs rounded-3 p-3 bg-white">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-circle bg-info text-dark fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">6</div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-comments text-info me-1"></i> CRM &amp; Analisis Loyalitas RFM</h6>
                                                <span class="badge bg-info bg-opacity-20 text-dark small">Retensi Pelanggan</span>
                                            </div>
                                            <p class="text-muted small mb-2" style="font-size: 0.8rem;">Catat follow-up komplain, kirim broadcast WA promosi, dan analisa loyalitas pelanggan lewat segmentasi 125 aturan RFM.</p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="<?= base_url('crm') ?>" class="btn btn-xs btn-outline-info text-dark rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-book-journal-whills me-1"></i>Journal CRM</a>
                                                <a href="<?= base_url('crm/broadcast') ?>" class="btn btn-xs btn-success rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-brands fa-whatsapp me-1"></i>Broadcast WA</a>
                                                <a href="<?= base_url('rfm') ?>" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1 small" style="font-size: 0.75rem;"><i class="fa-solid fa-chart-pie me-1"></i>Analisis RFM</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>
                <div class="modal-footer py-2 px-4 bg-white justify-content-between">
                    <span class="small text-muted"><i class="fa-solid fa-circle-info me-1 text-primary"></i>Gunakan Ctrl+K di keyboard untuk mencari menu instan.</span>
                    <button type="button" class="btn btn-sm btn-dark px-3 rounded-pill" data-bs-dismiss="modal">Tutup Panduan</button>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('js/bluetooth-thermal-printer.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
