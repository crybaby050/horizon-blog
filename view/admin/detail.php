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
$img = !empty($article['image_p']) ? htmlspecialchars($article['image_p'])
     : 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=900&q=80';
$redirectList = path('admin','article');
?>

<!-- Breadcrumb -->
<div class="admin-breadcrumb">
  <a href="<?= path('admin','article') ?>">Articles</a>
  <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
  <span><?= htmlspecialchars(mb_strimwidth($article['libelle'], 0, 50, '…')) ?></span>
</div>

<div class="admin-detail-layout">

  <!-- Colonne principale -->
  <div class="admin-detail-main">

    <!-- Image -->
    <div class="admin-detail-cover">
      <img src="<?= $img ?>" alt="<?= htmlspecialchars($article['libelle']) ?>"/>
      <span class="admin-badge <?= $statutClass[$article['statut']] ?? '' ?>" style="position:absolute;top:16px;right:16px;font-size:.8rem">
        <?= $statutLabel[$article['statut']] ?? $article['statut'] ?>
      </span>
    </div>

    <!-- Infos -->
    <div class="admin-card" style="margin-top:20px">
      <div class="admin-card-header">
        <div>
          <div class="admin-detail-cats">
            <?php foreach ($categories as $cat): ?>
              <span class="admin-tag"><?= htmlspecialchars($cat['libelle']) ?></span>
            <?php endforeach; ?>
          </div>
          <h2 class="admin-detail-title"><?= htmlspecialchars($article['libelle']) ?></h2>
          <p class="admin-detail-desc"><?= htmlspecialchars($article['description'] ?? '') ?></p>
        </div>
      </div>

      <!-- Actions -->
      <div class="admin-detail-actions">
        <?php if ($article['statut'] === 'En attente'): ?>
        <form method="POST" action="<?= path('admin','article') ?>" style="display:inline">
          <input type="hidden" name="controller" value="admin"/>
          <input type="hidden" name="action" value="article"/>
          <input type="hidden" name="post_action" value="approuver"/>
          <input type="hidden" name="id" value="<?= $article['id'] ?>"/>
          <input type="hidden" name="redirect" value="<?= $redirectList ?>"/>
          <button type="submit" class="admin-btn admin-btn-ok">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            Approuver
          </button>
        </form>
        <form method="POST" action="<?= path('admin','article') ?>" style="display:inline">
          <input type="hidden" name="controller" value="admin"/>
          <input type="hidden" name="action" value="article"/>
          <input type="hidden" name="post_action" value="invalider"/>
          <input type="hidden" name="id" value="<?= $article['id'] ?>"/>
          <input type="hidden" name="redirect" value="<?= $redirectList ?>"/>
          <button type="submit" class="admin-btn admin-btn-warn">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Invalider
          </button>
        </form>
        <?php endif; ?>

        <?php if ($article['statut'] === 'Inactif'): ?>
        <form method="POST" action="<?= path('admin','article') ?>" style="display:inline">
          <input type="hidden" name="controller" value="admin"/>
          <input type="hidden" name="action" value="article"/>
          <input type="hidden" name="post_action" value="restaurer"/>
          <input type="hidden" name="id" value="<?= $article['id'] ?>"/>
          <input type="hidden" name="redirect" value="<?= $redirectList ?>"/>
          <button type="submit" class="admin-btn admin-btn-ok">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.51"/></svg>
            Restaurer
          </button>
        </form>
        <?php endif; ?>

        <?php if ($article['statut'] !== 'Inactif'): ?>
        <form method="POST" action="<?= path('admin','article') ?>" style="display:inline"
              onsubmit="return confirm('Supprimer cet article ?')">
          <input type="hidden" name="controller" value="admin"/>
          <input type="hidden" name="action" value="article"/>
          <input type="hidden" name="post_action" value="supprimer"/>
          <input type="hidden" name="id" value="<?= $article['id'] ?>"/>
          <input type="hidden" name="redirect" value="<?= $redirectList ?>"/>
          <button type="submit" class="admin-btn admin-btn-danger">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
            Supprimer
          </button>
        </form>
        <?php endif; ?>

        <a href="<?= path('lecteur','detail',['id'=>$article['id']]) ?>" target="_blank" class="admin-btn admin-btn-ghost">
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          Voir sur le site
        </a>
      </div>

      <!-- Corps -->
      <?php if (!empty($article['contenu'])): ?>
      <div class="admin-detail-body">
        <?= $article['contenu'] ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Commentaires -->
    <div class="admin-card" style="margin-top:20px">
      <div class="admin-card-header">
        <div class="admin-card-title">Commentaires (<?= count($commentaires) ?>)</div>
      </div>
      <?php if (empty($commentaires)): ?>
        <p class="admin-table-empty">Aucun commentaire.</p>
      <?php else: ?>
      <div class="admin-comments-list">
        <?php foreach ($commentaires as $com): ?>
        <div class="admin-comment-item">
          <div class="admin-comment-header">
            <div class="admin-comment-meta">
              <span class="admin-comment-author"><?= htmlspecialchars($com['auteur_nom']) ?></span>
              <span class="admin-comment-type"><?= $com['type_user'] === 'auteur' ? 'Auteur' : 'Lecteur' ?></span>
              <span class="admin-comment-date"><?= date('d/m/Y H:i', strtotime($com['date'])) ?></span>
            </div>
            <form method="POST" action="<?= path('admin','article') ?>"
                  onsubmit="return confirm('Supprimer ce commentaire ?')">
              <input type="hidden" name="controller" value="admin"/>
              <input type="hidden" name="action" value="article"/>
              <input type="hidden" name="post_action" value="supprimer_commentaire"/>
              <input type="hidden" name="id" value="<?= $com['id'] ?>"/>
              <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>"/>
              <button type="submit" class="admin-action-btn admin-action-danger" title="Supprimer ce commentaire">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
              </button>
            </form>
          </div>
          <p class="admin-comment-text"><?= nl2br(htmlspecialchars($com['contenue'])) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

  </div>

  <!-- Sidebar -->
  <div class="admin-detail-sidebar">
    <div class="admin-card">
      <div class="admin-card-title" style="margin-bottom:16px">Informations</div>
      <ul class="admin-info-list">
        <li>
          <span class="admin-info-label">Statut</span>
          <span class="admin-badge <?= $statutClass[$article['statut']] ?? '' ?>">
            <?= $statutLabel[$article['statut']] ?? $article['statut'] ?>
          </span>
        </li>
        <li>
          <span class="admin-info-label">Auteur</span>
          <?php if ($article['auteur_id']): ?>
            <a href="<?= path('admin','auteur',['detail'=>$article['auteur_id']]) ?>" class="admin-table-link">
              <?= htmlspecialchars($article['auteur'] ?? '—') ?>
            </a>
          <?php else: ?>
            <span>—</span>
          <?php endif; ?>
        </li>
        <li>
          <span class="admin-info-label">Email auteur</span>
          <span><?= htmlspecialchars($article['auteur_email'] ?? '—') ?></span>
        </li>
        <li>
          <span class="admin-info-label">Créé le</span>
          <span><?= date('d/m/Y à H:i', strtotime($article['date_creation'])) ?></span>
        </li>
        <li>
          <span class="admin-info-label">Modifié le</span>
          <span><?= date('d/m/Y à H:i', strtotime($article['date_dernier_modification'])) ?></span>
        </li>
        <li>
          <span class="admin-info-label">Catégories</span>
          <div class="admin-tags-wrap" style="margin-top:4px">
            <?php foreach ($categories as $cat): ?>
              <span class="admin-tag"><?= htmlspecialchars($cat['libelle']) ?></span>
            <?php endforeach; ?>
          </div>
        </li>
        <li>
          <span class="admin-info-label">Commentaires</span>
          <span><?= count($commentaires) ?></span>
        </li>
      </ul>
    </div>
  </div>

</div>