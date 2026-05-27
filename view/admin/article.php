<?php
$statutClass = [
    'Actif'      => 'badge-actif',
    'En attente' => 'badge-attente',
    'Invalide'   => 'badge-invalide',
    'Inactif'    => 'badge-inactif',
];
$statutLabel = [
    'Actif'      => 'Publié',
    'En attente' => 'En attente',
    'Invalide'   => 'Invalide',
    'Inactif'    => 'Supprimé',
];

// Helper tri URL
$triUrl = function(string $col) use ($tri, $ordre, $statut, $search, $auteurId): string {
    $nouvelOrdre = ($tri === $col && $ordre === 'ASC') ? 'DESC' : 'ASC';
    return path('admin', 'article', [
        'tri' => $col, 'ordre' => $nouvelOrdre,
        'statut' => $statut, 'q' => $search, 'auteur' => $auteurId
    ]);
};
$triIcon = function(string $col) use ($tri, $ordre): string {
    if ($tri !== $col) return '<svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="opacity:.3"><path d="M12 5v14M5 12l7-7 7 7"/></svg>';
    return $ordre === 'ASC'
        ? '<svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>'
        : '<svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12l7 7 7-7"/></svg>';
};
?>

<!-- Flash -->
<?php if ($flash): ?>
<div class="admin-flash admin-flash-<?= $flash['type'] ?>">
  <?= htmlspecialchars($flash['msg']) ?>
  <button onclick="this.parentElement.remove()">
    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
  </button>
</div>
<?php endif; ?>

<!-- Filtres -->
<div class="admin-filters-bar">
  <div class="admin-filters-left">
    <!-- Filtre statut -->
    <div class="admin-filter-chips">
      <a href="<?= path('admin','article',['q'=>$search,'auteur'=>$auteurId]) ?>"
         class="admin-chip <?= $statut === '' ? 'active' : '' ?>">Tous
        <span class="admin-chip-count"><?= adminCountArticles('', $search, $auteurId) ?></span>
      </a>
      <?php foreach (['Actif'=>'Publiés','En attente'=>'En attente','Invalide'=>'Invalides','Inactif'=>'Supprimés'] as $s => $l): ?>
      <a href="<?= path('admin','article',['statut'=>$s,'q'=>$search,'auteur'=>$auteurId]) ?>"
         class="admin-chip <?= $statut === $s ? 'active' : '' ?>"><?= $l ?>
        <span class="admin-chip-count"><?= adminCountArticles($s, $search, $auteurId) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="admin-filters-right">
    <!-- Filtre auteur -->
    <form method="GET" action="" class="admin-filter-form">
      <input type="hidden" name="controller" value="admin"/>
      <input type="hidden" name="action" value="article"/>
      <?php if ($statut): ?><input type="hidden" name="statut" value="<?= htmlspecialchars($statut) ?>"/><?php endif; ?>
      <select name="auteur" onchange="this.form.submit()" class="admin-select">
        <option value="0">Tous les auteurs</option>
        <?php foreach ($auteurs as $au): ?>
          <option value="<?= $au['id'] ?>" <?= $auteurId === (int)$au['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($au['nom_complet']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>

    <!-- Recherche -->
    <form method="GET" action="" class="admin-search-form">
      <input type="hidden" name="controller" value="admin"/>
      <input type="hidden" name="action" value="article"/>
      <?php if ($statut): ?><input type="hidden" name="statut" value="<?= htmlspecialchars($statut) ?>"/><?php endif; ?>
      <?php if ($auteurId): ?><input type="hidden" name="auteur" value="<?= $auteurId ?>"/><?php endif; ?>
      <input type="hidden" name="page" value="1"/>
      <div class="admin-search-wrap">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" name="q" placeholder="Rechercher un article…"
               value="<?= htmlspecialchars($search) ?>"/>
      </div>
    </form>
  </div>
</div>

<!-- Tableau articles -->
<div class="admin-card" style="padding:0">
  <div class="admin-card-header" style="padding:20px 24px">
    <div class="admin-card-title">
      <?= $totalArticles ?> article<?= $totalArticles > 1 ? 's' : '' ?>
      <?= $search ? ' pour « ' . htmlspecialchars($search) . ' »' : '' ?>
    </div>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width:36px"></th>
          <th>
            <a href="<?= $triUrl('libelle') ?>" class="admin-th-sort">
              Titre <?= $triIcon('libelle') ?>
            </a>
          </th>
          <th>Auteur</th>
          <th>Catégories</th>
          <th>Commentaires</th>
          <th>
            <a href="<?= $triUrl('date_creation') ?>" class="admin-th-sort">
              Date <?= $triIcon('date_creation') ?>
            </a>
          </th>
          <th>
            <a href="<?= $triUrl('statut') ?>" class="admin-th-sort">
              Statut <?= $triIcon('statut') ?>
            </a>
          </th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articles)): ?>
          <tr><td colspan="8" class="admin-table-empty">Aucun article trouvé.</td></tr>
        <?php else: ?>
          <?php foreach ($articles as $art):
            $cats = getCategoriesDetailByArticle((int)$art['id']);
            $img  = !empty($art['image_p']) ? htmlspecialchars($art['image_p'])
                  : 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=80&q=60';
          ?>
          <tr>
            <td>
              <img src="<?= $img ?>" alt="" class="admin-table-thumb"/>
            </td>
            <td class="admin-table-title">
              <a href="<?= path('admin','article',['detail'=>$art['id']]) ?>">
                <?= htmlspecialchars(mb_strimwidth($art['libelle'], 0, 50, '…')) ?>
              </a>
            </td>
            <td>
              <?php if ($art['auteur_id']): ?>
                <a href="<?= path('admin','auteur',['detail'=>$art['auteur_id']]) ?>" class="admin-table-link">
                  <?= htmlspecialchars($art['auteur'] ?? '—') ?>
                </a>
              <?php else: ?>—<?php endif; ?>
            </td>
            <td>
              <div class="admin-tags-wrap">
                <?php foreach (array_slice($cats, 0, 2) as $cat): ?>
                  <span class="admin-tag"><?= htmlspecialchars($cat['libelle']) ?></span>
                <?php endforeach; ?>
                <?php if (count($cats) > 2): ?>
                  <span class="admin-tag admin-tag-more">+<?= count($cats) - 2 ?></span>
                <?php endif; ?>
              </div>
            </td>
            <td style="text-align:center"><?= $art['nb_commentaires'] ?></td>
            <td><?= date('d/m/Y', strtotime($art['date_creation'])) ?></td>
            <td>
              <span class="admin-badge <?= $statutClass[$art['statut']] ?? '' ?>">
                <?= $statutLabel[$art['statut']] ?? $art['statut'] ?>
              </span>
            </td>
            <td>
              <div class="admin-actions">
                <!-- Voir détail -->
                <a href="<?= path('admin','article',['detail'=>$art['id']]) ?>"
                   class="admin-action-btn" title="Voir">
                  <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                </a>

                <!-- Approuver (si En attente) -->
                <?php if ($art['statut'] === 'En attente'): ?>
                <form method="POST" action="<?= path('admin','article') ?>" style="display:inline">
                  <input type="hidden" name="controller" value="admin"/>
                  <input type="hidden" name="action" value="article"/>
                  <input type="hidden" name="post_action" value="approuver"/>
                  <input type="hidden" name="id" value="<?= $art['id'] ?>"/>
                  <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>"/>
                  <button type="submit" class="admin-action-btn admin-action-ok" title="Approuver">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                  </button>
                </form>
                <form method="POST" action="<?= path('admin','article') ?>" style="display:inline">
                  <input type="hidden" name="controller" value="admin"/>
                  <input type="hidden" name="action" value="article"/>
                  <input type="hidden" name="post_action" value="invalider"/>
                  <input type="hidden" name="id" value="<?= $art['id'] ?>"/>
                  <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>"/>
                  <button type="submit" class="admin-action-btn admin-action-warn" title="Invalider">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </form>
                <?php endif; ?>

                <!-- Restaurer (si Inactif) -->
                <?php if ($art['statut'] === 'Inactif'): ?>
                <form method="POST" action="<?= path('admin','article') ?>" style="display:inline">
                  <input type="hidden" name="controller" value="admin"/>
                  <input type="hidden" name="action" value="article"/>
                  <input type="hidden" name="post_action" value="restaurer"/>
                  <input type="hidden" name="id" value="<?= $art['id'] ?>"/>
                  <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>"/>
                  <button type="submit" class="admin-action-btn admin-action-ok" title="Restaurer">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.51"/></svg>
                  </button>
                </form>
                <?php endif; ?>

                <!-- Supprimer (soft) si pas déjà inactif -->
                <?php if ($art['statut'] !== 'Inactif'): ?>
                <form method="POST" action="<?= path('admin','article') ?>" style="display:inline"
                      onsubmit="return confirm('Supprimer cet article ?')">
                  <input type="hidden" name="controller" value="admin"/>
                  <input type="hidden" name="action" value="article"/>
                  <input type="hidden" name="post_action" value="supprimer"/>
                  <input type="hidden" name="id" value="<?= $art['id'] ?>"/>
                  <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>"/>
                  <button type="submit" class="admin-action-btn admin-action-danger" title="Supprimer">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                  </button>
                </form>
                <?php else: ?>
                <!-- Supprimer définitivement -->
                <form method="POST" action="<?= path('admin','article') ?>" style="display:inline"
                      onsubmit="return confirm('Supprimer DEFINITVEMENT cet article ? Cette action est irreversible.')">
                  <input type="hidden" name="controller" value="admin"/>
                  <input type="hidden" name="action" value="article"/>
                  <input type="hidden" name="post_action" value="supprimer_definitif"/>
                  <input type="hidden" name="id" value="<?= $art['id'] ?>"/>
                  <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>"/>
                  <button type="submit" class="admin-action-btn admin-action-danger" title="Supprimer definitivement">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                  </button>
                </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
  <div class="admin-pagination">
    <?php if ($page > 1): ?>
      <a href="<?= path('admin','article',['page'=>$page-1,'statut'=>$statut,'q'=>$search,'auteur'=>$auteurId,'tri'=>$tri,'ordre'=>$ordre]) ?>"
         class="admin-page-btn">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M15 18l-6-6 6-6"/></svg>
      </a>
    <?php endif; ?>
    <?php for ($p = max(1,$page-2); $p <= min($totalPages,$page+2); $p++): ?>
      <a href="<?= path('admin','article',['page'=>$p,'statut'=>$statut,'q'=>$search,'auteur'=>$auteurId,'tri'=>$tri,'ordre'=>$ordre]) ?>"
         class="admin-page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
      <a href="<?= path('admin','article',['page'=>$page+1,'statut'=>$statut,'q'=>$search,'auteur'=>$auteurId,'tri'=>$tri,'ordre'=>$ordre]) ?>"
         class="admin-page-btn">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
      </a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>