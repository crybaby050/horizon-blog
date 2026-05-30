<!-- ════════ ARTICLES ADMIN ════════ -->
<div class="adm-page">

  <div class="adm-page-header fade-up">
    <div>
      <h1 class="adm-page-title">Articles</h1>
      <p class="adm-page-sub"><?= $total ?> article<?= $total > 1 ? 's' : '' ?> au total</p>
    </div>
  </div>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="adm-alert adm-alert-err fade-up">Article supprimé avec succès.</div>
  <?php endif; ?>

  <!-- Filtres -->
  <div class="adm-filters fade-up">
    <div class="adm-filter-chips">
      <a href="<?= path('admin','articles') ?>" class="adm-chip <?= empty($statut)?'active':'' ?>">Tous</a>
      <a href="<?= path('admin','articles',['statut'=>'Actif']) ?>" class="adm-chip <?= $statut==='Actif'?'active':'' ?>">Publiés</a>
      <a href="<?= path('admin','articles',['statut'=>'En attente']) ?>" class="adm-chip <?= $statut==='En attente'?'active':'' ?>">En attente</a>
      <a href="<?= path('admin','articles',['statut'=>'Invalide']) ?>" class="adm-chip <?= $statut==='Invalide'?'active':'' ?>">Invalides</a>
    </div>
    <form method="GET" action="" class="adm-filter-search">
      <input type="hidden" name="controller" value="admin"/>
      <input type="hidden" name="action"     value="articles"/>
      <?php if ($statut): ?><input type="hidden" name="statut" value="<?= htmlspecialchars($statut) ?>"/><?php endif; ?>
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher…"/>
      <button type="submit">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
      </button>
    </form>
  </div>

  <!-- Table -->
  <div class="adm-card fade-up" style="transition-delay:.05s">
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Article</th><th>Auteur</th><th>Date</th><th>Statut</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($articles)): ?>
            <tr><td colspan="5" class="adm-table-empty">Aucun article trouvé.</td></tr>
          <?php else: ?>
          <?php foreach ($articles as $art):
            $sc = match($art['statut']) {
              'Actif'=>'adm-status-actif','En attente'=>'adm-status-attente',
              'Invalide'=>'adm-status-invalide',default=>'adm-status-actif'
            };
          ?>
          <tr>
            <td>
              <div class="adm-table-art">
                <div class="adm-table-art-img">
                  <img src="<?= !empty($art['image_p'])?htmlspecialchars($art['image_p']):'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=100&q=60' ?>" alt=""/>
                </div>
                <span><?= htmlspecialchars(strlen($art['libelle'])>50?substr($art['libelle'],0,50).'…':$art['libelle']) ?></span>
              </div>
            </td>
            <td><?= htmlspecialchars($art['auteur']) ?></td>
            <td><?= date('d/m/Y', strtotime($art['date_creation'])) ?></td>
            <td><span class="adm-status-chip <?= $sc ?>"><?= $art['statut'] ?></span></td>
            <td>
              <div class="adm-table-actions">
                <a href="<?= path('admin','article_detail',['id'=>$art['id']]) ?>" class="adm-tbl-btn adm-tbl-view" title="Voir">
                  <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="13" height="13"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                </a>
                <?php if ($art['statut'] !== 'Actif'): ?>
                <form method="POST" action="<?= path('admin','articles') ?>" style="display:inline">
                  <input type="hidden" name="post_action" value="valider"/>
                  <input type="hidden" name="article_id"  value="<?= $art['id'] ?>"/>
                  <button class="adm-tbl-btn adm-tbl-ok" title="Valider">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13"><polyline points="20 6 9 17 4 12"/></svg>
                  </button>
                </form>
                <?php endif; ?>
                <?php if ($art['statut'] !== 'Invalide'): ?>
                <form method="POST" action="<?= path('admin','articles') ?>" style="display:inline">
                  <input type="hidden" name="post_action" value="invalider"/>
                  <input type="hidden" name="article_id"  value="<?= $art['id'] ?>"/>
                  <button class="adm-tbl-btn adm-tbl-warn" title="Invalider">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </form>
                <?php endif; ?>
                <button class="adm-tbl-btn adm-tbl-del" title="Supprimer"
                        onclick="admOpenDelete('article',<?= $art['id'] ?>,'<?= htmlspecialchars(addslashes($art['libelle'])) ?>')">
                  <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="13" height="13"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
  <div class="adm-pagination fade-up">
    <?php if ($page > 1): ?>
      <a href="<?= path('admin','articles',['page'=>$page-1,'statut'=>$statut,'q'=>$search]) ?>" class="adm-page-btn">‹</a>
    <?php endif; ?>
    <?php for ($p = max(1,$page-2); $p <= min($totalPages,$page+2); $p++): ?>
      <a href="<?= path('admin','articles',['page'=>$p,'statut'=>$statut,'q'=>$search]) ?>"
         class="adm-page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
      <a href="<?= path('admin','articles',['page'=>$page+1,'statut'=>$statut,'q'=>$search]) ?>" class="adm-page-btn">›</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>

<!-- Modal suppression article -->
<div class="adm-modal-overlay" id="admDeleteModal">
  <div class="adm-modal">
    <div class="adm-modal-icon"><svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="28" height="28" stroke="#dc2626"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></div>
    <h3 class="adm-modal-title">Supprimer ?</h3>
    <p class="adm-modal-sub">Vous allez supprimer <strong id="admDeleteName"></strong>. Action irréversible.</p>
    <form method="POST" id="admDeleteForm" action="<?= path('admin','articles') ?>">
      <input type="hidden" name="post_action" id="admDeleteAction" value="supprimer"/>
      <input type="hidden" name="article_id"  id="admDeleteId"     value=""/>
      <div class="adm-modal-actions">
        <button type="button" class="adm-modal-cancel" onclick="admCloseDelete()">Annuler</button>
        <button type="submit" class="adm-modal-confirm">Supprimer</button>
      </div>
    </form>
  </div>
</div>