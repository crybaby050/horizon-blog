<!-- ════════ CORBEILLE ════════ -->
<div class="au-page">

  <div class="au-page-header fade-up">
    <div>
      <h1 class="au-page-title">Corbeille</h1>
      <p class="au-page-sub"><?= $total ?> article<?= $total > 1 ? 's' : '' ?> dans la corbeille</p>
    </div>
    <a href="<?= path('auteur','articles') ?>" class="au-btn-ghost">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
        <path d="M15 18l-6-6 6-6"/>
      </svg>
      Retour aux articles
    </a>
  </div>

  <!-- Recherche -->
  <form method="GET" action="" class="au-filters fade-up">
    <input type="hidden" name="controller" value="auteur"/>
    <input type="hidden" name="action"     value="corbeille"/>
    <div class="au-filter-search" style="margin-left:0">
      <input type="hidden" name="page" value="1"/>
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
             placeholder="Rechercher dans la corbeille…"/>
      <button type="submit">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="15" height="15">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
      </button>
    </div>
  </form>

  <?php if (empty($articles)): ?>
    <div class="au-empty-state fade-up">
      <div class="au-empty-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" width="48" height="48" stroke="#1a9e5c">
          <polyline points="3 6 5 6 21 6"/>
          <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        </svg>
      </div>
      <h2>La corbeille est vide</h2>
      <p>Les articles supprimés apparaîtront ici. Vous pourrez les restaurer ou les supprimer définitivement.</p>
    </div>
  <?php else: ?>
    <div class="au-articles-grid fade-up" style="transition-delay:.08s">
      <?php foreach ($articles as $art):
        $img = !empty($art['image_p'])
            ? (str_starts_with($art['image_p'], 'http')
                ? htmlspecialchars($art['image_p'])
                : WEBROOT . htmlspecialchars($art['image_p']))
            : 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=700&q=80';
      ?>
      <div class="au-art-card" style="opacity:.75">
        <div class="au-art-img">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($art['libelle']) ?>"/>
          <span class="au-art-status au-status-invalide">Supprimé</span>
        </div>
        <div class="au-art-body">
          <div class="au-art-title"><?= htmlspecialchars($art['libelle']) ?></div>
          <div class="au-art-desc"><?= htmlspecialchars($art['description']) ?></div>
          <div class="au-art-footer">
            <span class="au-meta-item">
              Supprimé le <?= date('d/m/Y', strtotime($art['date_dernier_modification'])) ?>
            </span>
            <div class="au-art-actions">
              <!-- Restaurer -->
              <button type="button" class="au-art-btn au-art-btn-view" title="Restaurer"
                      onclick="openCorbeilleModal('restaurer', <?= $art['id'] ?>, '<?= htmlspecialchars(addslashes($art['libelle'])) ?>')">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="14" height="14">
                  <polyline points="1 4 1 10 7 10"/>
                  <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                </svg>
              </button>
              <!-- Supprimer définitivement -->
              <button type="button" class="au-art-btn au-art-btn-del" title="Supprimer définitivement"
                      onclick="openCorbeilleModal('supprimer_def', <?= $art['id'] ?>, '<?= htmlspecialchars(addslashes($art['libelle'])) ?>')">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="14" height="14">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="au-pagination fade-up">
      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="<?= path('auteur','corbeille',['page'=>$p,'q'=>$search]) ?>"
           class="au-page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
  <?php endif; ?>

</div>

<!-- Modal corbeille (restaurer / suppr. définitive) -->
<div class="au-modal-overlay" id="corbeilleModal">
  <div class="au-modal">
    <div class="au-modal-icon-wrap" id="corbeilleModalIconWrap">
      <svg id="corbeilleModalIcon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="28" height="28"></svg>
    </div>
    <h3 class="au-modal-title" id="corbeilleModalTitle"></h3>
    <p class="au-modal-sub" id="corbeilleModalSub"></p>
    <form method="POST" action="<?= path('auteur','corbeille') ?>" id="corbeilleModalForm">
      <input type="hidden" name="controller" value="auteur"/>
      <input type="hidden" name="action"     value="corbeille"/>
      <input type="hidden" name="post_action" id="corbeilleModalAction" value=""/>
      <input type="hidden" name="id"          id="corbeilleModalId"     value=""/>
      <div class="au-modal-actions">
        <button type="button" class="au-modal-cancel" onclick="closeCorbeilleModal()">Annuler</button>
        <button type="submit" class="au-modal-confirm" id="corbeilleModalConfirm">Confirmer</button>
      </div>
    </form>
  </div>
</div>