<!-- ════════ EN-TÊTE DE PAGE ════════ -->
<div class="page-header">
  <div class="page-header-inner">
    <div>
      <div class="breadcrumb">
        <a href="<?= path('lecteur','home') ?>">Accueil</a>
        <span class="breadcrumb-sep">›</span>
        <span>Articles</span>
      </div>
      <div class="page-header-title">Tous les articles</div>
      <div class="page-header-sub">Découvrez l'ensemble de nos publications</div>
    </div>
    <div class="articles-count"><?= $totalArticles ?> article<?= $totalArticles > 1 ? 's' : '' ?></div>
  </div>
</div>

<!-- ════════ FILTRES (formulaire PHP GET) ════════ -->
<form method="GET" action="" class="filters-bar" id="filtersForm">
  <input type="hidden" name="controller" value="lecteur"/>
  <input type="hidden" name="action" value="article"/>

  <span class="filter-label">Filtrer :</span>

  <a href="<?= path('lecteur','article') ?>"
     class="filter-chip <?= empty($statut) ? 'active' : '' ?>">Tous</a>

  <a href="<?= path('lecteur','article',['statut'=>'Actif','page'=>1,'q'=>$search]) ?>"
     class="filter-chip <?= $statut === 'Actif' ? 'active' : '' ?>">Publiés</a>

  <a href="<?= path('lecteur','article',['statut'=>'En attente','page'=>1,'q'=>$search]) ?>"
     class="filter-chip <?= $statut === 'En attente' ? 'active' : '' ?>">En attente</a>

  <a href="<?= path('lecteur','article',['statut'=>'Invalide','page'=>1,'q'=>$search]) ?>"
     class="filter-chip <?= $statut === 'Invalide' ? 'active' : '' ?>">Invalides</a>

  <div class="filter-search">
    <input type="text" name="q" placeholder="Rechercher un article…"
           value="<?= htmlspecialchars($search) ?>"/>
    <?php if (!empty($statut)): ?>
      <input type="hidden" name="statut" value="<?= htmlspecialchars($statut) ?>"/>
    <?php endif; ?>
    <input type="hidden" name="page" value="1"/>
    <button type="submit">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
    </button>
  </div>
</form>

<!-- ════════ GRILLE ARTICLES ════════ -->
<div class="articles-grid" id="articlesGrid">

  <?php if (empty($articles)): ?>
    <div style="grid-column:1/-1;text-align:center;padding:60px 0;color:var(--gray);">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
           style="margin:0 auto 16px;display:block;opacity:.4;">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
      <p>Aucun article trouvé<?= !empty($search) ? ' pour « '.htmlspecialchars($search).' »' : '' ?>.</p>
    </div>

  <?php else: ?>
    <?php foreach ($articles as $index => $art): ?>

      <?php
        // Initialisation des catégories de cet article
        $cats = $art['categories'] ?? [];

        // Classes de statut
        $statusClass = match($art['statut']) {
          'Actif'      => 'status-actif',
          'En attente' => 'status-attente',
          'Invalide'   => 'status-invalide',
          'Valide'     => 'status-valide',
          default      => 'status-actif'
        };
        $statusLabel = match($art['statut']) {
          'Actif'      => 'Publié',
          'En attente' => 'En attente',
          'Invalide'   => 'Invalide',
          'Valide'     => 'Validé',
          default      => $art['statut']
        };

        // Initiales auteur
        $parts    = explode(' ', $art['auteur'] ?? 'In Co');
        $initials = strtoupper(substr($parts[0],0,1) . (isset($parts[1]) ? substr($parts[1],0,1) : ''));

        // La 1re carte est mise en avant
        $featured = ($index === 0) ? 'a-card-featured' : '';
        $delay    = $index * 0.05;

        // Image de fallback
        $img = !empty($art['image_p'])
            ? htmlspecialchars($art['image_p'])
            : 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=700&q=80';
      ?>

      <div class="a-card <?= $featured ?> fade-up" style="transition-delay:<?= $delay ?>s">
        <div class="a-card-img">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($art['libelle']) ?>"/>
          <span class="a-card-status <?= $statusClass ?>"><?= $statusLabel ?></span>
        </div>
        <div class="a-card-body">
          <div class="a-card-tags">
            <?php foreach ($cats as $i => $cat): ?>
              <span class="a-tag <?= $i > 0 ? 'a-tag-sec' : '' ?>">
                <?= htmlspecialchars($cat['libelle']) ?>
              </span>
            <?php endforeach; ?>
          </div>
          <div class="a-card-title"><?= htmlspecialchars($art['libelle']) ?></div>
          <div class="a-card-desc"><?= htmlspecialchars($art['description']) ?></div>
          <div class="a-card-footer">
            <div class="a-card-author">
              <div class="a-avatar"><?= $initials ?></div>
              <div>
                <div class="a-author-name"><?= htmlspecialchars($art['auteur'] ?? 'Inconnu') ?></div>
                <div class="a-author-date"><?= date('d/m/Y', strtotime($art['date_creation'])) ?></div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
              <div class="a-card-meta">
                <div class="a-meta-item">
                  <svg fill="none" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
                  </svg>
                  <?= $art['vues'] ?? 0 ?>
                </div>
                <div class="a-meta-item">
                  <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                  </svg>
                  <?= $art['commentaires'] ?? 0 ?>
                </div>
              </div>
              <a href="<?= path('lecteur','detail',['id'=>$art['id']]) ?>" class="btn-lire">
                Lire
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round">
                  <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

    <?php endforeach; ?>
  <?php endif; ?>

</div>

<!-- ════════ PAGINATION PHP ════════ -->
<?php if ($totalPages > 1): ?>
<div class="pagination">

  <!-- Précédent -->
  <?php if ($page > 1): ?>
    <a href="<?= path('lecteur','article',['page'=>$page-1,'statut'=>$statut,'q'=>$search]) ?>"
       class="page-btn" aria-label="Page précédente">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round">
        <path d="M15 18l-6-6 6-6"/>
      </svg>
    </a>
  <?php else: ?>
    <span class="page-btn" style="opacity:.35;cursor:not-allowed;" aria-disabled="true">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round">
        <path d="M15 18l-6-6 6-6"/>
      </svg>
    </span>
  <?php endif; ?>

  <!-- Numéros de page -->
  <?php
    // Affiche max 5 pages autour de la page courante
    $startPage = max(1, $page - 2);
    $endPage   = min($totalPages, $page + 2);
  ?>
  <?php if ($startPage > 1): ?>
    <a href="<?= path('lecteur','article',['page'=>1,'statut'=>$statut,'q'=>$search]) ?>"
       class="page-btn">1</a>
    <?php if ($startPage > 2): ?>
      <span class="page-btn" style="cursor:default;border:none;">…</span>
    <?php endif; ?>
  <?php endif; ?>

  <?php for ($p = $startPage; $p <= $endPage; $p++): ?>
    <a href="<?= path('lecteur','article',['page'=>$p,'statut'=>$statut,'q'=>$search]) ?>"
       class="page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
  <?php endfor; ?>

  <?php if ($endPage < $totalPages): ?>
    <?php if ($endPage < $totalPages - 1): ?>
      <span class="page-btn" style="cursor:default;border:none;">…</span>
    <?php endif; ?>
    <a href="<?= path('lecteur','article',['page'=>$totalPages,'statut'=>$statut,'q'=>$search]) ?>"
       class="page-btn"><?= $totalPages ?></a>
  <?php endif; ?>

  <!-- Suivant -->
  <?php if ($page < $totalPages): ?>
    <a href="<?= path('lecteur','article',['page'=>$page+1,'statut'=>$statut,'q'=>$search]) ?>"
       class="page-btn" aria-label="Page suivante">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round">
        <path d="M9 18l6-6-6-6"/>
      </svg>
    </a>
  <?php else: ?>
    <span class="page-btn" style="opacity:.35;cursor:not-allowed;" aria-disabled="true">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round">
        <path d="M9 18l6-6-6-6"/>
      </svg>
    </span>
  <?php endif; ?>

</div>
<?php endif; ?>