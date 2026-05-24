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
      <a href="#">Catégorie</a>
      <a href="#">Contact</a>
    </div>

    <div class="auth-wrap">
      <div class="auth-fused">
        <button class="btn-s">S'inscrire</button>
        <button class="btn-c">Se Connecter</button>
      </div>
    </div>

    <!-- Hamburger (mobile/tablette) -->
    <button class="hamburger" id="hamburger" aria-label="Menu" onclick="toggleMenu()">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<?= $content ?>

<script src="<?= WEBROOT ?>js/script.js"></script>
</body>
</html>