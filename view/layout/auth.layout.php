<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>HorizonBlog — <?= $pageTitle ?? 'Authentification' ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?= WEBROOT ?>css/style.css"/>
</head>
<body class="auth-body">

<div class="auth-wrap">

  <!-- ── Panneau gauche décoratif ── -->
  <div class="auth-left">
    <div class="auth-left-inner">

      <!-- Logo -->
      <a href="<?= path('lecteur','home') ?>" class="auth-brand">
        <svg width="44" height="44" viewBox="0 0 32 32" fill="none">
          <path d="M16 4C10 4 5 9 5 15.5c0 3.2 1.6 5.9 4.2 7.8 1.1.8 2.6-.3 2.1-1.6-1-2.8-1-5.8 1.2-8 3.2-3.2 8.5-3.2 11.8-1 1 .6 2.2-.3 1.6-1.5C24 9 20.3 4 16 4z" fill="#fff"/>
          <path d="M8.5 23.5c0 0 2.2-2.2 5.5-2.2s5.5 1.2 5.5 3.2c0 1.6-1.1 2.2-2.2 2.2-3.3 0-8.8-3.2-8.8-3.2z" fill="rgba(255,255,255,.7)"/>
        </svg>
        <div>
          <div class="auth-brand-name">HorizonBlog</div>
          <div class="auth-brand-sub">Votre espace de lecture et d'écriture</div>
        </div>
      </a>

      <!-- Texte -->
      <div class="auth-left-text">
        <h2><?= $leftTitle ?? 'Rejoignez la communauté' ?></h2>
        <p><?= $leftDesc ?? 'Découvrez des articles passionnants, partagez vos idées et connectez-vous avec des auteurs talentueux.' ?></p>
      </div>

      <!-- Stats -->
      <div class="auth-left-stats">
        <div class="auth-left-stat">
          <div class="auth-left-stat-num">10+</div>
          <div class="auth-left-stat-lbl">Articles</div>
        </div>
        <div class="auth-left-stat-sep"></div>
        <div class="auth-left-stat">
          <div class="auth-left-stat-num">3</div>
          <div class="auth-left-stat-lbl">Auteurs</div>
        </div>
        <div class="auth-left-stat-sep"></div>
        <div class="auth-left-stat">
          <div class="auth-left-stat-num">3</div>
          <div class="auth-left-stat-lbl">Lecteurs</div>
        </div>
      </div>

      <!-- Témoignage -->
      <div class="auth-left-quote">
        <p>"HorizonBlog m'a permis de partager ma passion pour la technologie avec des milliers de lecteurs."</p>
        <div class="auth-left-quote-author">
          <div class="auth-left-quote-avatar">IB</div>
          <div>
            <div class="auth-left-quote-name">Ibrahima Sow</div>
            <div class="auth-left-quote-role">Auteur confirmé</div>
          </div>
        </div>
      </div>

      <!-- Décorations -->
      <div class="auth-deco auth-deco1"></div>
      <div class="auth-deco auth-deco2"></div>
      <div class="auth-deco auth-deco3"></div>
    </div>
  </div>

  <!-- ── Panneau droit formulaire ── -->
  <div class="auth-right">
    <div class="auth-form-wrap">
      <?= $content ?>
    </div>
  </div>

</div>

<script src="<?= WEBROOT ?>js/script.js"></script>
</body>
</html>