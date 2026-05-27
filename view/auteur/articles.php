<!-- ════════ MES ARTICLES ════════ -->
<div class="au-page">

  <!-- En-tête -->
  <div class="au-page-header fade-up">
    <div>
      <h1 class="au-page-title">Mes articles</h1>
      <p class="au-page-sub"><?= $total ?> article<?= $total > 1 ? 's' : '' ?> au total</p>
    </div>
    <a href="<?= path('auteur','ajout') ?>" class="au-btn-primary">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Nouvel article
    </a>
  </div>

  <!-- Notification succès -->
  <?php if (isset($_GET['success'])): ?>
    <div class="au-alert au-alert-ok fade-up">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="16" height="16">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
      </svg>
      Article créé avec succès ! Il est en attente de validation.
    </div>
  <?php endif; ?>
  <?php if (isset($_GET['deleted'])): ?>
    <div class="au-alert au-alert-err fade-up">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="16" height="16">
        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
      </svg>
      Article supprimé.
    </div>
  <?php endif; ?>

  <!-- Filtres -->
  <form method="GET" action="" class="au-filters fade-up" style="transition-delay:.05s">
    <input type="hidden" name="controller" value="auteur"/>
    <input type="hidden" name="action"     value="articles"/>

    <div class="au-filter-chips">
      <a href="<?= path('auteur','articles') ?>"
         class="au-chip <?= empty($statut) ? 'active' : '' ?>">Tous</a>
      <a href="<?= path('auteur','articles',['statut'=>'Actif']) ?>"
         class="au-chip <?= $statut === 'Actif' ? 'active' : '' ?>">Publiés</a>
      <a href="<?= path('auteur','articles',['statut'=>'En attente']) ?>"
         class="au-chip <?= $statut === 'En attente' ? 'active' : '' ?>">En attente</a>
      <a href="<?= path('auteur','articles',['statut'=>'Invalide']) ?>"
         class="au-chip <?= $statut === 'Invalide' ? 'active' : '' ?>">Invalides</a>
    </div>

    <div class="au-filter-search">
      <?php if (!empty($statut)): ?>
        <input type="hidden" name="statut" value="<?= htmlspecialchars($statut) ?>"/>
      <?php endif; ?>
      <input type="hidden" name="page" value="1"/>
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
             placeholder="Rechercher un article…"/>
      <button type="submit">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="15" height="15">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
      </button>
    </div>
  </form>

  <!-- ── PAS D'ARTICLES ── -->
  <?php if (empty($articles) && empty($search) && empty($statut)): ?>
    <div class="au-empty-state fade-up">
      <div class="au-empty-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" width="48" height="48" stroke="#1a9e5c">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
      </div>
      <h2>Vous n'avez pas encore d'article</h2>
      <p>Partagez vos idées avec notre communauté. Rédigez votre premier article dès maintenant !</p>
      <a href="<?= path('auteur','ajout') ?>" class="au-btn-primary au-btn-lg">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="16" height="16">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Écrire mon premier article
      </a>
    </div>

  <?php elseif (empty($articles)): ?>
    <div class="au-empty-state au-empty-search fade-up">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" width="40" height="40" stroke="#9ca3af">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
      <p>Aucun article trouvé<?= !empty($search) ? ' pour « '.htmlspecialchars($search).' »' : '' ?>.</p>
    </div>

  <?php else: ?>
    <!-- Grille articles -->
    <div class="au-articles-grid fade-up" style="transition-delay:.08s">
      <?php foreach ($articles as $art):
        $statusClass = match($art['statut']) {
          'Actif'      => 'au-status-actif',
          'En attente' => 'au-status-attente',
          'Invalide'   => 'au-status-invalide',
          default      => 'au-status-actif'
        };
        $img = !empty($art['image_p'])
            ? htmlspecialchars($art['image_p'])
            : 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=700&q=80';
      ?>
      <div class="au-art-card">
        <a href="<?= path('auteur','detail',['id'=>$art['id']]) ?>" class="au-art-img">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($art['libelle']) ?>"/>
          <span class="au-art-status <?= $statusClass ?>"><?= $art['statut'] ?></span>
        </a>
        <div class="au-art-body">
          <!-- Tags -->
          <div class="au-art-tags">
            <?php foreach ($art['categories'] as $cat): ?>
              <span class="au-art-tag"><?= htmlspecialchars($cat['libelle']) ?></span>
            <?php endforeach; ?>
          </div>
          <!-- Titre -->
          <div class="au-art-title"><?= htmlspecialchars($art['libelle']) ?></div>
          <!-- Description -->
          <div class="au-art-desc"><?= htmlspecialchars($art['description']) ?></div>
          <!-- Footer -->
          <div class="au-art-footer">
            <div class="au-art-meta">
              <!--<span class="au-meta-item">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" width="12" height="12">
                  <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
                </svg>
                <?= $art['vues'] ?? 0 ?>
              </span>-->
              <span class="au-meta-item">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" width="12" height="12" stroke-linecap="round">
                  <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                <?= date('d/m/Y', strtotime($art['date_creation'])) ?>
              </span>
            </div>
            <div class="au-art-actions">
              <a href="<?= path('auteur','detail',['id'=>$art['id']]) ?>"
                 class="au-art-btn au-art-btn-view" title="Voir">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="14" height="14">
                  <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
                </svg>
              </a>
              <a href="<?= path('auteur','modifier',['id'=>$art['id']]) ?>"
                 class="au-art-btn au-art-btn-edit" title="Modifier">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="14" height="14">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </a>
              <button class="au-art-btn au-art-btn-del"
                      onclick="openDeleteModal(<?= $art['id'] ?>, '<?= htmlspecialchars(addslashes($art['libelle'])) ?>')"
                      title="Supprimer">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="14" height="14">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="au-pagination fade-up">
      <?php if ($page > 1): ?>
        <a href="<?= path('auteur','articles',['page'=>$page-1,'statut'=>$statut,'q'=>$search]) ?>"
           class="au-page-btn">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
            <path d="M15 18l-6-6 6-6"/>
          </svg>
        </a>
      <?php endif; ?>
      <?php for ($p = max(1,$page-2); $p <= min($totalPages,$page+2); $p++): ?>
        <a href="<?= path('auteur','articles',['page'=>$p,'statut'=>$statut,'q'=>$search]) ?>"
           class="au-page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $totalPages): ?>
        <a href="<?= path('auteur','articles',['page'=>$page+1,'statut'=>$statut,'q'=>$search]) ?>"
           class="au-page-btn">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
            <path d="M9 18l6-6-6-6"/>
          </svg>
        </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  <?php endif; ?>
</div>

<!-- Modal suppression article -->
<div class="au-modal-overlay" id="deleteModal">
  <div class="au-modal">
    <div class="au-modal-icon-wrap" style="background:#fef2f2;">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="28" height="28" stroke="#dc2626">
        <polyline points="3 6 5 6 21 6"/>
        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
      </svg>
    </div>
    <h3 class="au-modal-title">Supprimer l'article ?</h3>
    <p class="au-modal-sub">
      Vous êtes sur le point de supprimer <strong id="deleteArticleTitle"></strong>.<br/>
      Cette action est irréversible.
    </p>
    <form method="POST" action="<?= path('auteur','supprimer') ?>" id="deleteForm">
      <input type="hidden" name="controller" value="auteur"/>
      <input type="hidden" name="action"     value="supprimer"/>
      <input type="hidden" name="id"         id="deleteArticleId" value=""/>
      <div class="au-modal-actions">
        <button type="button" class="au-modal-cancel" onclick="closeDeleteModal()">Annuler</button>
        <button type="submit" class="au-modal-confirm">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
          </svg>
          Supprimer
        </button>
      </div>
    </form>
  </div>
</div>