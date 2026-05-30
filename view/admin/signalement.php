<!-- ════════ SIGNALEMENTS ADMIN ════════ -->
<div class="adm-page">

  <div class="adm-page-header fade-up">
    <div>
      <h1 class="adm-page-title">Signalements</h1>
      <p class="adm-page-sub"><?= $total ?> signalement<?= $total > 1 ? 's' : '' ?> au total</p>
    </div>
    <?php if ($nbSignalementsNonTraites > 0): ?>
      <span class="adm-alert-pill">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="13" height="13" stroke="#dc2626">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="12"/>
          <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <?= $nbSignalementsNonTraites ?> non traité<?= $nbSignalementsNonTraites > 1 ? 's' : '' ?>
      </span>
    <?php endif; ?>
  </div>

  <div class="adm-filters fade-up">
    <div class="adm-filter-chips">
      <a href="<?= path('admin','signalements') ?>"                              class="adm-chip <?= empty($statut)?'active':'' ?>">Tous</a>
      <a href="<?= path('admin','signalements',['statut'=>'Non traiter']) ?>"    class="adm-chip <?= $statut==='Non traiter'?'active':'' ?>">Non traités</a>
      <a href="<?= path('admin','signalements',['statut'=>'Traiter']) ?>"        class="adm-chip <?= $statut==='Traiter'?'active':'' ?>">Traités</a>
    </div>
    <form method="GET" action="" class="adm-filter-search">
      <input type="hidden" name="controller" value="admin"/>
      <input type="hidden" name="action"     value="signalements"/>
      <?php if ($statut): ?><input type="hidden" name="statut" value="<?= htmlspecialchars($statut) ?>"/><?php endif; ?>
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher…"/>
      <button type="submit">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
      </button>
    </form>
  </div>

  <!-- Liste signalements -->
  <?php if (empty($signalements)): ?>
    <div class="adm-empty-state fade-up">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" width="48" height="48" stroke="#9ca3af">
        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
        <line x1="4" y1="22" x2="4" y2="15"/>
      </svg>
      <p>Aucun signalement trouvé.</p>
    </div>
  <?php else: ?>
  <div class="adm-signal-grid fade-up" style="transition-delay:.05s">
    <?php foreach ($signalements as $sig):
      $isTraite   = $sig['statut'] === 'Traiter';
      $isArticle  = !empty($sig['article_id']) && empty($sig['commentaire_id']);
      $type       = $isArticle ? 'Article' : 'Commentaire';
    ?>
    <div class="adm-signal-card <?= $isTraite ? 'adm-signal-card-done' : '' ?>">
      <div class="adm-signal-card-head">
        <div class="adm-signal-type-badge <?= $isArticle ? 'adm-type-article' : 'adm-type-comment' ?>">
          <?php if ($isArticle): ?>
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="11" height="11"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
          <?php else: ?>
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="11" height="11"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          <?php endif; ?>
          <?= $type ?>
        </div>
        <span class="adm-status-chip <?= $isTraite ? 'adm-status-actif' : 'adm-status-attente' ?>">
          <?= $isTraite ? 'Traité' : 'Non traité' ?>
        </span>
      </div>

      <div class="adm-signal-card-title"><?= htmlspecialchars($sig['libelle']) ?></div>

      <?php if (!empty($sig['description'])): ?>
        <p class="adm-signal-card-desc"><?= htmlspecialchars($sig['description']) ?></p>
      <?php endif; ?>

      <?php if (!empty($sig['article_libelle'])): ?>
        <div class="adm-signal-card-ref">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="12" height="12"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
          <?= htmlspecialchars(strlen($sig['article_libelle']) > 50 ? substr($sig['article_libelle'],0,50).'…' : $sig['article_libelle']) ?>
        </div>
      <?php endif; ?>

      <div class="adm-signal-card-meta">
        <span>Par <?= htmlspecialchars($sig['signaleur']) ?></span>
        <span>·</span>
        <span><?= date('d/m/Y à H:i', strtotime($sig['date_creation'])) ?></span>
      </div>

      <div class="adm-signal-card-actions">
        <?php if (!$isTraite): ?>
          <form method="POST" action="<?= path('admin','signalements') ?>" style="display:inline">
            <input type="hidden" name="post_action"     value="traiter"/>
            <input type="hidden" name="signalement_id"  value="<?= $sig['id'] ?>"/>
            <button type="submit" class="adm-signal-btn adm-signal-btn-ok">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13"><polyline points="20 6 9 17 4 12"/></svg>
              Marquer traité
            </button>
          </form>
        <?php endif; ?>

        <?php if (!$isArticle && !empty($sig['commentaire_id'])): ?>
          <form method="POST" action="<?= path('admin','signalements') ?>" style="display:inline">
            <input type="hidden" name="post_action"     value="supprimer_commentaire"/>
            <input type="hidden" name="comment_id"      value="<?= $sig['commentaire_id'] ?>"/>
            <input type="hidden" name="signalement_id"  value="<?= $sig['id'] ?>"/>
            <button type="submit" class="adm-signal-btn adm-signal-btn-del">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
              Suppr. commentaire
            </button>
          </form>
        <?php endif; ?>

        <form method="POST" action="<?= path('admin','signalements') ?>" style="display:inline">
          <input type="hidden" name="post_action"    value="ignorer"/>
          <input type="hidden" name="signalement_id" value="<?= $sig['id'] ?>"/>
          <button type="submit" class="adm-signal-btn adm-signal-btn-ignore">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="13" height="13"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Ignorer
          </button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <?php if ($totalPages > 1): ?>
  <div class="adm-pagination fade-up">
    <?php if ($page > 1): ?>
      <a href="<?= path('admin','signalements',['page'=>$page-1,'statut'=>$statut,'q'=>$search]) ?>" class="adm-page-btn">‹</a>
    <?php endif; ?>
    <?php for ($p = max(1,$page-2); $p <= min($totalPages,$page+2); $p++): ?>
      <a href="<?= path('admin','signalements',['page'=>$p,'statut'=>$statut,'q'=>$search]) ?>"
         class="adm-page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
      <a href="<?= path('admin','signalements',['page'=>$page+1,'statut'=>$statut,'q'=>$search]) ?>" class="adm-page-btn">›</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>