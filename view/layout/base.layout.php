<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>HorizonBlog</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?= WEBROOT ?>css/style.css"/>
</head>
<body>

<!-- ════════ NAV ════════ -->
<nav>
  <div class="nav-inner">
    <a href="<?= path('lecteur','home') ?>" class="nav-logo">
      <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
        <path d="M16 4C10 4 5 9 5 15.5c0 3.2 1.6 5.9 4.2 7.8 1.1.8 2.6-.3 2.1-1.6-1-2.8-1-5.8 1.2-8 3.2-3.2 8.5-3.2 11.8-1 1 .6 2.2-.3 1.6-1.5C24 9 20.3 4 16 4z" fill="#1a9e5c"/>
        <path d="M8.5 23.5c0 0 2.2-2.2 5.5-2.2s5.5 1.2 5.5 3.2c0 1.6-1.1 2.2-2.2 2.2-3.3 0-8.8-3.2-8.8-3.2z" fill="#157a47"/>
        <line x1="14" y1="16" x2="8.5" y2="23.5" stroke="#0d6b3c" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
      <span class="nav-logo-text">
        <span class="horizon">Horizon</span><span class="blog">Blog</span>
      </span>
    </a>

    <div class="nav-menu">
      <a href="<?= path('lecteur','home') ?>" <?= ($currentAction ?? '') === 'home' ? 'class="active"' : '' ?>>Accueil</a>
      <a href="<?= path('lecteur','article') ?>" <?= ($currentAction ?? '') === 'article' ? 'class="active"' : '' ?>>Articles</a>
      <a href="<?= path('lecteur','categorie') ?>" <?= ($currentAction ?? '') === 'categorie' ? 'class="active"' : '' ?>>Catégorie</a>
      <a href="<?= path('lecteur','contact') ?>" <?= ($currentAction ?? '') === 'contact' ? 'class="active"' : '' ?>">Contact</a>
    </div>

    <!--<div class="auth-wrap">
      <div class="auth-fused">
        <button class="btn-s">S'inscrire</button>
        <button class="btn-c">Se Connecter</button>
      </div>
    </div>-->

    <!-- Remplacer la section auth-wrap dans base.layout.php par : -->

<div class="auth-wrap">
    <?php if (isset($_SESSION['user'])): 
        $user = $_SESSION['user'];
        $initiales = strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1));
    ?>
        <div class="user-menu" id="userMenu">
            <button class="user-avatar" onclick="toggleUserDropdown()">
                <span class="user-initials"><?= htmlspecialchars($initiales) ?></span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>
            <div class="user-dropdown" id="userDropdown">
                <div class="dropdown-header">
                    <div class="dropdown-name"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></div>
                    <div class="dropdown-email"><?= htmlspecialchars($user['email']) ?></div>
                    <div class="dropdown-badge <?= $user['type'] === 'auteur' ? 'badge-auteur' : 'badge-lecteur' ?>">
                        <?= $user['type'] === 'auteur' ? 'Auteur' : 'Lecteur' ?>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="<?= path('auth', 'logout') ?>" class="dropdown-item logout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Se déconnecter
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="auth-fused">
            <a href="<?= path('auth', 'register') ?>" class="btn-s">S'inscrire</a>
            <a href="<?= path('auth', 'login') ?>" class="btn-c">Se Connecter</a>
        </div>
    <?php endif; ?>
</div>

    <!-- Hamburger (mobile/tablette) -->
    <button class="hamburger" id="hamburger" aria-label="Menu" onclick="toggleMenu()">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<?= $content ?>
<!-- ════════ FOOTER ════════ -->
<footer>
  <div class="footer-inner">
    <div>
      <a href="<?= path('lecteur','home') ?>" class="flogo">
        <svg width="30" height="30" viewBox="0 0 32 32" fill="none">
          <path d="M16 4C10 4 5 9 5 15.5c0 3.2 1.6 5.9 4.2 7.8 1.1.8 2.6-.3 2.1-1.6-1-2.8-1-5.8 1.2-8 3.2-3.2 8.5-3.2 11.8-1 1 .6 2.2-.3 1.6-1.5C24 9 20.3 4 16 4z" fill="#1a9e5c"/>
          <path d="M8.5 23.5c0 0 2.2-2.2 5.5-2.2s5.5 1.2 5.5 3.2c0 1.6-1.1 2.2-2.2 2.2-3.3 0-8.8-3.2-8.8-3.2z" fill="#157a47"/>
        </svg>
        <span class="flogo-text"><span class="fh">Horizon</span><span class="fb">Blog</span></span>
      </a>
      <p>A digital product agency focusing on branding, UI/UX design, and web development for forward-thinking companies.</p>
      <div class="social-row">
        <a href="#" class="sico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
        <a href="#" class="sico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
        <a href="#" class="sico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".5" fill="currentColor" stroke="none"/></svg></a>
        <a href="#" class="sico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
      </div>
    </div>
    <div>
      <h4>Legal</h4>
      <a href="#">Terms &amp; conditions</a>
      <a href="#">Privacy Policy</a>
    </div>
    <div>
      <h4>Company</h4>
      <a href="<?= path('lecteur','home') ?>">Accueil</a>
      <a href="<?= path('lecteur','article') ?>">Articles</a>
      <a href="#">À propos</a>
      <a href="#">Contactez-nous</a>
    </div>
  </div>
  <div class="footer-divider">© <?= date('Y') ?> HorizonBlog. Tous droits réservés.</div>
</footer>
<script src="<?= WEBROOT ?>js/script.js"></script>
</body>
</html>