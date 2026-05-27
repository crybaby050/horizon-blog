<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>HorizonBlog — Administration</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?= WEBROOT ?>css/style.css"/>
<link rel="stylesheet" href="<?= WEBROOT ?>css/admin.css"/>
</head>
<body class="admin-body">

<div class="admin-layout">

  <!-- ══ SIDEBAR ══ -->
  <aside class="admin-sidebar" id="adminSidebar">

    <!-- Logo -->
    <a href="<?= path('admin','dashboard') ?>" class="admin-sidebar-logo">
      <svg width="28" height="28" viewBox="0 0 32 32" fill="none">
        <path d="M16 4C10 4 5 9 5 15.5c0 3.2 1.6 5.9 4.2 7.8 1.1.8 2.6-.3 2.1-1.6-1-2.8-1-5.8 1.2-8 3.2-3.2 8.5-3.2 11.8-1 1 .6 2.2-.3 1.6-1.5C24 9 20.3 4 16 4z" fill="#1a9e5c"/>
        <path d="M8.5 23.5c0 0 2.2-2.2 5.5-2.2s5.5 1.2 5.5 3.2c0 1.6-1.1 2.2-2.2 2.2-3.3 0-8.8-3.2-8.8-3.2z" fill="#157a47"/>
        <line x1="14" y1="16" x2="8.5" y2="23.5" stroke="#0d6b3c" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
      <div class="admin-sidebar-logo-text">
        <span>Horizon</span><span style="color:#1a9e5c">Blog</span>
        <small>Administration</small>
      </div>
    </a>

    <!-- Navigation -->
    <nav class="admin-nav">

      <div class="admin-nav-section">
        <span class="admin-nav-label">Principal</span>

        <a href="<?= path('admin','dashboard') ?>"
           class="admin-nav-item <?= ($currentAction ?? '') === 'dashboard' ? 'active' : '' ?>">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
          </svg>
          <span>Dashboard</span>
        </a>

        <a href="<?= path('admin','article') ?>"
           class="admin-nav-item <?= ($currentAction ?? '') === 'article' ? 'active' : '' ?>">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
          </svg>
          <span>Articles</span>
          <?php if (!empty($nbArticlesEnAttente)): ?>
            <span class="admin-nav-badge"><?= $nbArticlesEnAttente ?></span>
          <?php endif; ?>
        </a>

        <a href="<?= path('admin','auteur') ?>"
           class="admin-nav-item <?= ($currentAction ?? '') === 'auteur' ? 'active' : '' ?>">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          <span>Auteurs</span>
          <?php if (!empty($nbDemandesEnAttente)): ?>
            <span class="admin-nav-badge"><?= $nbDemandesEnAttente ?></span>
          <?php endif; ?>
        </a>

        <a href="<?= path('admin','signalement') ?>"
           class="admin-nav-item <?= ($currentAction ?? '') === 'signalement' ? 'active' : '' ?>">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
            <line x1="4" y1="22" x2="4" y2="15"/>
          </svg>
          <span>Signalements</span>
          <?php if (!empty($nbSignalementsNonTraites)): ?>
            <span class="admin-nav-badge admin-nav-badge-danger"><?= $nbSignalementsNonTraites ?></span>
          <?php endif; ?>
        </a>
      </div>

    </nav>

    <!-- Profil admin en bas -->
    <div class="admin-sidebar-footer">
      <?php $admin = $_SESSION['admin'] ?? []; ?>
      <div class="admin-sidebar-user">
        <div class="admin-sidebar-avatar">
          <?= strtoupper(substr($admin['prenom'] ?? 'A', 0, 1) . substr($admin['nom'] ?? 'D', 0, 1)) ?>
        </div>
        <div class="admin-sidebar-user-info">
          <div class="admin-sidebar-user-name">
            <?= htmlspecialchars(($admin['prenom'] ?? '') . ' ' . ($admin['nom'] ?? '')) ?>
          </div>
          <div class="admin-sidebar-user-role">Administrateur</div>
        </div>
        <a href="<?= path('admin','logout') ?>" class="admin-logout-btn" title="Se déconnecter">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </a>
      </div>
    </div>

  </aside>

  <!-- ══ CONTENU PRINCIPAL ══ -->
  <div class="admin-main">

    <!-- Topbar -->
    <header class="admin-topbar">
      <button class="admin-sidebar-toggle" onclick="toggleAdminSidebar()" id="sidebarToggle">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>

      <div class="admin-topbar-title"><?= $pageTitle ?? 'Dashboard' ?></div>

      <div class="admin-topbar-right">
        <a href="<?= path('lecteur','home') ?>" class="admin-topbar-link" target="_blank">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
            <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
          </svg>
          Voir le site
        </a>
      </div>
    </header>

    <!-- Page content -->
    <div class="admin-content">
      <?= $content ?>
    </div>

  </div>
</div>

<!-- Toast -->
<div class="admin-toast" id="adminToast"></div>

<script src="<?= WEBROOT ?>js/script.js"></script>
<script src="<?= WEBROOT ?>js/admin.js"></script>
</body>
</html>