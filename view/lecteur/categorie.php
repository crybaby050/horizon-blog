<!-- ════════ PAGE HEADER ════════ -->
<div class="page-header">
  <div class="page-header-inner">
    <div>
      <div class="breadcrumb">
        <a href="<?= path('lecteur','home') ?>">Accueil</a>
        <span class="breadcrumb-sep">›</span>
        <span>Catégories</span>
      </div>
      <div class="page-header-title">Toutes les catégories</div>
      <div class="page-header-sub">Explorez nos contenus par thématique</div>
    </div>
    <div class="articles-count"><?= count($categories) ?> catégorie<?= count($categories) > 1 ? 's' : '' ?></div>
  </div>
</div>

<!-- ════════ HERO CATÉGORIES ════════ -->
<?php if (!empty($categories)):
  $heroMain  = $categories[0];
  $heroSide1 = $categories[2] ?? $categories[1] ?? null;
  $heroSide2 = $categories[1] ?? null;
?>
<section class="categ-hero">
  <div class="categ-hero-inner">

    <!-- Grande carte gauche : 1ère catégorie -->
    <div class="categ-hero-main">
      <img src="<?= htmlspecialchars($heroMain['image'] ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1200&q=85') ?>"
           alt="<?= htmlspecialchars($heroMain['libelle']) ?>"/>
      <div class="categ-hero-overlay"></div>
      <div class="categ-hero-content">
        <span class="categ-hero-badge" style="background:<?= getCouleurCategorie($heroMain['icone'] ?? '') ?>;">
          <?= getIconeCategorie($heroMain['icone'] ?? '', 18) ?>
          <?= htmlspecialchars($heroMain['libelle']) ?>
        </span>
        <h2><?= htmlspecialchars($heroMain['libelle']) ?></h2>
        <div class="categ-hero-meta">
          <span><?= $heroMain['nb_articles'] ?? 0 ?> articles</span>
          <a href="<?= path('lecteur','article',['categorie'=>$heroMain['id']]) ?>" class="categ-hero-btn">
            Voir les articles
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>
    </div>

    <!-- Petites cartes droite -->
    <div class="categ-hero-side">
      <?php foreach ([$heroSide2, $heroSide1] as $hcat):
        if (!$hcat) continue; ?>
      <div class="categ-hero-small">
        <img src="<?= htmlspecialchars($hcat['image'] ?? 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=700&q=85') ?>"
             alt="<?= htmlspecialchars($hcat['libelle']) ?>"/>
        <div class="categ-hero-overlay"></div>
        <div class="categ-hero-content">
          <span class="categ-hero-badge" style="background:<?= getCouleurCategorie($hcat['icone'] ?? '') ?>;">
            <?= getIconeCategorie($hcat['icone'] ?? '', 15) ?>
            <?= htmlspecialchars($hcat['libelle']) ?>
          </span>
          <h3><?= htmlspecialchars($hcat['libelle']) ?></h3>
          <div class="categ-hero-meta">
            <span><?= $hcat['nb_articles'] ?? 0 ?> articles</span>
            <a href="<?= path('lecteur','article',['categorie'=>$hcat['id']]) ?>" class="categ-hero-btn categ-hero-btn-sm">Voir →</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
<?php endif; ?>

<!-- ════════ GRILLE CATÉGORIES ════════ -->
<section class="categ-list-section">
  <div class="categ-list-inner">

    <div class="categ-list-header fade-up">
      <h2>Toutes les thématiques</h2>
      <p>Chaque catégorie regroupe des articles soigneusement sélectionnés par notre équipe.</p>
    </div>

    <div class="categ-cards-grid">
      <?php foreach ($categories as $index => $cat):
        $couleur = getCouleurCategorie($cat['icone'] ?? '');
        $icone   = getIconeCategorie($cat['icone'] ?? '', 22);
        $img     = !empty($cat['image'])
                    ? htmlspecialchars($cat['image'])
                    : 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&q=85';
        $nbArt   = $cat['nb_articles'] ?? 0;
        $delay   = round(($index % 3) * 0.08, 2);
      ?>
      <div class="categ-big-card fade-up" style="transition-delay:<?= $delay ?>s">
        <div class="categ-big-img">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($cat['libelle']) ?>"/>
          <div class="categ-big-overlay"></div>
        </div>
        <div class="categ-big-body">
          <div class="categ-big-icon" style="background:<?= $couleur ?>;">
            <?= $icone ?>
          </div>
          <div class="categ-big-info">
            <h3><?= htmlspecialchars($cat['libelle']) ?></h3>
            <div class="categ-big-stats">
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                  <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                  <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                <?= $nbArt ?> article<?= $nbArt > 1 ? 's' : '' ?>
              </span>
            </div>
            <a href="<?= path('lecteur','article',['categorie'=>$cat['id']]) ?>" class="categ-big-btn">
              Explorer
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>