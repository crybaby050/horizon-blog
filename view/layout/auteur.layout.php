<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>HorizonBlog — Espace Auteur</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?= WEBROOT ?>css/style.css"/>
</head>
<body class="auteur-body">

<!-- ════════ SIDEBAR ════════ -->
<aside class="au-sidebar" id="auSidebar">

  <!-- Logo -->
  <a href="<?= path('auteur','dashboard') ?>" class="au-sidebar-logo">
    <svg width="30" height="30" viewBox="0 0 32 32" fill="none">
      <path d="M16 4C10 4 5 9 5 15.5c0 3.2 1.6 5.9 4.2 7.8 1.1.8 2.6-.3 2.1-1.6-1-2.8-1-5.8 1.2-8 3.2-3.2 8.5-3.2 11.8-1 1 .6 2.2-.3 1.6-1.5C24 9 20.3 4 16 4z" fill="#1a9e5c"/>
      <path d="M8.5 23.5c0 0 2.2-2.2 5.5-2.2s5.5 1.2 5.5 3.2c0 1.6-1.1 2.2-2.2 2.2-3.3 0-8.8-3.2-8.8-3.2z" fill="#157a47"/>
    </svg>
    <span class="au-logo-text"><span class="au-logo-h">Horizon</span><span class="au-logo-b">Blog</span></span>
  </a>

  <!-- Profil auteur -->
  <div class="au-sidebar-profile">
    <?php
        $prenom     = $_SESSION['user']['prenom'] ?? '';
        $nom        = $_SESSION['user']['nom']    ?? '';
        $initiales  = strtoupper(substr($prenom,0,1) . substr($nom,0,1)) ?: 'AU';
        $nomComplet = trim($prenom . ' ' . $nom) ?: 'Auteur';
?>
    <div class="au-profile-avatar"><?= $initiales ?></div>
    <div class="au-profile-info">
      <div class="au-profile-name"><?= htmlspecialchars($nomComplet) ?></div>
      <div class="au-profile-role">
        <span class="au-role-dot"></span>Auteur
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="au-nav">
    <div class="au-nav-group">
      <div class="au-nav-label">Principal</div>

      <a href="<?= path('auteur','dashboard') ?>"
         class="au-nav-item <?= ($currentAction ?? '') === 'dashboard' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
        </svg>
        <span>Dashboard</span>
      </a>

      <a href="<?= path('auteur','ajout') ?>"
         class="au-nav-item <?= ($currentAction ?? '') === 'ajout' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="16"/>
          <line x1="8" y1="12" x2="16" y2="12"/>
        </svg>
        <span>Nouvel article</span>
      </a>

      <a href="<?= path('auteur','articles') ?>"
         class="au-nav-item <?= ($currentAction ?? '') === 'articles' ? 'active' : '' ?>">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
        <span>Mes articles</span>
        <?php if (!empty($nbArticlesAuteur) && $nbArticlesAuteur > 0): ?>
          <span class="au-nav-badge"><?= $nbArticlesAuteur ?></span>
        <?php endif; ?>
      </a>
    </div>

    <div class="au-nav-group au-nav-group-bottom">
      <div class="au-nav-label">Compte</div>
      <a href="<?= path('lecteur','home') ?>" class="au-nav-item" target="_blank">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
          <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
          <polyline points="15 3 21 3 21 9"/>
          <line x1="10" y1="14" x2="21" y2="3"/>
        </svg>
        <span>Voir le blog</span>
      </a>
      <a href="<?= path('auteur','deconnexion') ?>" class="au-nav-item au-nav-deconnexion">
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

<!-- ════════ OVERLAY MOBILE ════════ -->
<div class="au-overlay" id="auOverlay" onclick="toggleSidebar()"></div>

<!-- ════════ MAIN AREA ════════ -->
<div class="au-main-wrap">

  <!-- Topbar -->
  <header class="au-topbar">
    <button class="au-hamburger" id="auHamburger" onclick="toggleSidebar()" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>

    <div class="au-topbar-title" id="auTopbarTitle">
      <?php
        $titles = [
          'dashboard' => 'Dashboard',
          'ajout'     => 'Nouvel article',
          'articles'  => 'Mes articles',
          'detail'    => 'Détail article',
          'modifier'  => 'Modifier l\'article',
        ];
        echo $titles[$currentAction ?? 'dashboard'] ?? 'Espace Auteur';
      ?>
    </div>

    <div class="au-topbar-right">
      <a href="<?= path('auteur','ajout') ?>" class="au-topbar-btn">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
          <line x1="12" y1="5" x2="12" y2="19"/>
          <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nouvel article
      </a>
      <div class="au-topbar-avatar"><?= $initiales ?></div>
    </div>
  </header>

  <!-- Contenu principal -->
  <main class="au-content">
    <?= $content ?>
  </main>

</div><!-- /au-main-wrap -->

<!-- Toast -->
<div class="au-toast" id="auToast"></div>

<script src="<?= WEBROOT ?>js/script.js"></script>
</body>
</html>