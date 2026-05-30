<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>HorizonBlog — Administration</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?= WEBROOT ?>css/style.css"/>
</head>
<body class="admin-body">

<!-- ════════ SIDEBAR ════════ -->
<aside class="adm-sidebar" id="admSidebar">

  <a href="<?= path('admin','dashboard') ?>" class="adm-logo">
    <svg width="28" height="28" viewBox="0 0 32 32" fill="none">
      <path d="M16 4C10 4 5 9 5 15.5c0 3.2 1.6 5.9 4.2 7.8 1.1.8 2.6-.3 2.1-1.6-1-2.8-1-5.8 1.2-8 3.2-3.2 8.5-3.2 11.8-1 1 .6 2.2-.3 1.6-1.5C24 9 20.3 4 16 4z" fill="#1a9e5c"/>
      <path d="M8.5 23.5c0 0 2.2-2.2 5.5-2.2s5.5 1.2 5.5 3.2c0 1.6-1.1 2.2-2.2 2.2-3.3 0-8.8-3.2-8.8-3.2z" fill="#157a47"/>
    </svg>
    <div>
      <div class="adm-logo-text"><span class="adm-logo-h">Horizon</span><span class="adm-logo-b">Blog</span></div>
      <div class="adm-logo-sub">Administration</div>
    </div>
  </a>

  <nav class="adm-nav">

    <div class="adm-nav-group">
      <div class="adm-nav-label">Principal</div>
      <a href="<?= path('admin','dashboard') ?>"
         class="adm-nav-item <?= ($currentAction??'') === 'dashboard' ? 'active':'' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
        </svg>
        <span>Dashboard</span>
      </a>
    </div>

    <div class="adm-nav-group">
      <div class="adm-nav-label">Contenu</div>
      <a href="<?= path('admin','articles') ?>"
         class="adm-nav-item <?= ($currentAction??'') === 'articles' ? 'active':'' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
        <span>Articles</span>
      </a>
      <a href="<?= path('admin','signalements') ?>"
         class="adm-nav-item <?= ($currentAction??'') === 'signalements' ? 'active':'' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
          <line x1="4" y1="22" x2="4" y2="15"/>
        </svg>
        <span>Signalements</span>
        <?php if (!empty($nbSignalementsNonTraites) && $nbSignalementsNonTraites > 0): ?>
          <span class="adm-nav-badge"><?= $nbSignalementsNonTraites ?></span>
        <?php endif; ?>
      </a>
    </div>

    <div class="adm-nav-group">
      <div class="adm-nav-label">Utilisateurs</div>
      <a href="<?= path('admin','auteurs') ?>"
         class="adm-nav-item <?= ($currentAction??'') === 'auteurs' ? 'active':'' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        <span>Auteurs</span>
      </a>
      <a href="<?= path('admin','lecteurs') ?>"
         class="adm-nav-item <?= ($currentAction??'') === 'lecteurs' ? 'active':'' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
        </svg>
        <span>Lecteurs</span>
      </a>
    </div>

    <div class="adm-nav-group adm-nav-bottom">
      <div class="adm-nav-label">Compte</div>
      <a href="<?= path('lecteur','home') ?>" class="adm-nav-item" target="_blank">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
          <polyline points="15 3 21 3 21 9"/>
          <line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
        <span>Voir le blog</span>
      </a>
      <a href="<?= path('admin','deconnexion') ?>" class="adm-nav-item adm-nav-logout">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        <span>Déconnexion</span>
      </a>
    </div>

  </nav>
</aside>

<!-- Overlay mobile -->
<div class="adm-overlay" id="admOverlay" onclick="admToggleSidebar()"></div>

<!-- ════════ MAIN ════════ -->
<div class="adm-main-wrap">

  <header class="adm-topbar">
    <button class="adm-hamburger" id="admHamburger" onclick="admToggleSidebar()">
      <span></span><span></span><span></span>
    </button>

    <div class="adm-topbar-title">
      <?php
        $titles = [
          'dashboard'    => 'Dashboard',
          'articles'     => 'Gestion des articles',
          'article_detail'=> 'Détail article',
          'auteurs'      => 'Gestion des auteurs',
          'lecteurs'     => 'Gestion des lecteurs',
          'signalements' => 'Signalements',
        ];
        echo $titles[$currentAction ?? 'dashboard'] ?? 'Administration';
      ?>
    </div>

    <div class="adm-topbar-right">
      <?php if (!empty($nbSignalementsNonTraites) && $nbSignalementsNonTraites > 0): ?>
      <a href="<?= path('admin','signalements') ?>" class="adm-topbar-icon-btn" title="Signalements en attente">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#6b7280">
          <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
          <line x1="4" y1="22" x2="4" y2="15"/>
        </svg>
        <span class="adm-notif-dot"></span>
      </a>
      <?php endif; ?>

      <?php
        $adminPrenom = $_SESSION['admin']['prenom'] ?? '';
        $adminNom    = $_SESSION['admin']['nom']    ?? '';
        $adminInit   = strtoupper(substr($adminPrenom,0,1).substr($adminNom,0,1)) ?: 'AD';
      ?>
      <div class="adm-topbar-admin">
        <div class="adm-topbar-avatar"><?= $adminInit ?></div>
        <div class="adm-topbar-info">
          <div class="adm-topbar-name"><?= htmlspecialchars($adminPrenom.' '.$adminNom) ?></div>
          <div class="adm-topbar-role">Administrateur</div>
        </div>
      </div>
    </div>
  </header>

  <main class="adm-content">
    <?= $content ?>
  </main>

</div>

<div class="adm-toast" id="admToast"></div>
<script src="<?= WEBROOT ?>js/script.js"></script>
</body>
</html>