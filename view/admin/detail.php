<!-- ════════ DETAIL ARTICLE ADMIN ════════ -->
<div class="adm-page">

  <div class="adm-page-header fade-up">
    <div class="adm-breadcrumb">
      <a href="<?= path('admin','articles') ?>">Articles</a>
      <span>›</span>
      <span><?= htmlspecialchars(strlen($article['libelle']) > 50 ? substr($article['libelle'],0,50).'…' : $article['libelle']) ?></span>
    </div>
    <div class="adm-page-header-actions">
      <?php if ($article['statut'] !== 'Actif'): ?>
        <form method="POST" action="<?= path('admin','article_detail',['id'=>$article['id']]) ?>" style="display:inline">
          <input type="hidden" name="post_action" value="valider"/>
          <button type="submit" class="adm-btn-success">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14"><polyline points="20 6 9 17 4 12"/></svg>
            Valider
          </button>
        </form>
      <?php endif; ?>
      <?php if ($article['statut'] !== 'Invalide'): ?>
        <form method="POST" action="<?= path('admin','article_detail',['id'=>$article['id']]) ?>" style="display:inline">
          <input type="hidden" name="post_action" value="invalider"/>
          <button type="submit" class="adm-btn-warning">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Invalider
          </button>
        </form>
      <?php endif; ?>
      <form method="POST" action="<?= path('admin','article_detail',['id'=>$article['id']]) ?>" style="display:inline"
            onsubmit="return confirm('Supprimer définitivement cet article ?')">
        <input type="hidden" name="post_action" value="supprimer_article"/>
        <button type="submit" class="adm-btn-danger">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
          Supprimer
        </button>
      </form>
    </div>
  </div>

  <div class="adm-detail-layout fade-up">

    <!-- ── CONTENU ── -->
    <main class="adm-detail-main">

      <!-- Statut + infos -->
      <div class="adm-detail-status-bar">
        <?php
          $sc = match($article['statut']) {
            'Actif'      => 'adm-status-actif',
            'En attente' => 'adm-status-attente',
            'Invalide'   => 'adm-status-invalide',
            default      => 'adm-status-actif'
          };
        ?>
        <span class="adm-status-chip <?= $sc ?>"><?= $article['statut'] ?></span>
        <span class="adm-detail-date">Créé le <?= date('d/m/Y à H:i', strtotime($article['date_creation'])) ?></span>
        <span class="adm-detail-date">Par <strong><?= htmlspecialchars($article['auteur']) ?></strong></span>
      </div>

      <!-- Tags catégories -->
      <div class="adm-detail-tags">
        <?php foreach ($categories as $i => $cat): ?>
          <span class="detail-tag <?= $i===0?'detail-tag-primary':'detail-tag-sec' ?>"><?= htmlspecialchars($cat['libelle']) ?></span>
        <?php endforeach; ?>
      </div>

      <!-- Titre -->
      <h1 class="adm-detail-title"><?= htmlspecialchars($article['libelle']) ?></h1>
      <p class="adm-detail-subtitle"><?= htmlspecialchars($article['description']) ?></p>

      <!-- Image -->
      <?php if (!empty($article['image_p'])): ?>
      <div class="adm-detail-cover">
        <img src="<?= htmlspecialchars($article['image_p']) ?>" alt=""/>
      </div>
      <?php endif; ?>

      <!-- Contenu -->
      <div class="adm-detail-body">
        <?= $article['contenu'] ?? '<p>Aucun contenu.</p>' ?>
      </div>

      <hr class="adm-detail-divider"/>

      <!-- ══ COMMENTAIRES ══ -->
      <div id="commentsSection">
        <div class="dc-header">
          <h2>Commentaires <span class="dc-count"><?= $nbCommentaires ?></span></h2>
        </div>

        <div class="dc-list">
          <?php if (empty($commentaires)): ?>
            <p style="color:var(--gray);text-align:center;padding:32px 0;">Aucun commentaire.</p>
          <?php else: ?>
            <?php foreach ($commentaires as $com):
              $nomCom  = $com['nom_complet'] ?? 'Inconnu';
              $parts   = explode(' ', $nomCom);
              $initCom = strtoupper(substr($parts[0],0,1).(isset($parts[1])?substr($parts[1],0,1):''));
              $isAuteur = $com['type_user'] === 'auteur';
              $styleAv  = $isAuteur
                  ? 'background:#e8f7f0;color:#0f6e40'
                  : 'background:#e8f0fe;color:#1a56db';
            ?>
            <div class="dc-item" id="comment-<?= $com['id'] ?>">
              <div class="dc-item-avatar" style="<?= $styleAv ?>"><?= $initCom ?></div>
              <div class="dc-item-body">
                <div class="dc-item-header">
                  <div>
                    <span class="dc-item-name"><?= htmlspecialchars($nomCom) ?></span>
                    <span class="dc-item-badge <?= $isAuteur?'':'dc-item-badge-lecteur' ?>"><?= $isAuteur?'Auteur':'Lecteur' ?></span>
                    <span class="dc-item-date"><?= date('d/m/Y à H:i', strtotime($com['date'])) ?></span>
                  </div>
                  <div class="dc-item-actions">
                    <form method="POST"
                          action="<?= path('admin','article_detail',['id'=>$article['id']]) ?>"
                          style="display:inline"
                          onsubmit="return confirm('Supprimer ce commentaire ?')">
                      <input type="hidden" name="post_action"  value="supprimer_commentaire"/>
                      <input type="hidden" name="comment_id"   value="<?= $com['id'] ?>"/>
                      <button type="submit" class="dc-action-btn dc-delete-btn" title="Supprimer">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                          <polyline points="3 6 5 6 21 6"/>
                          <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        </svg>
                        Supprimer
                      </button>
                    </form>
                  </div>
                </div>
                <div class="dc-item-text"><?= nl2br(htmlspecialchars($com['contenue'])) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </main>

    <!-- ── SIDEBAR ── -->
    <aside class="adm-detail-sidebar">

      <!-- Actions -->
      <div class="adm-detail-scard">
        <div class="ds-label">Actions rapides</div>
        <div class="adm-detail-actions">
          <?php if ($article['statut'] !== 'Actif'): ?>
          <form method="POST" action="<?= path('admin','article_detail',['id'=>$article['id']]) ?>">
            <input type="hidden" name="post_action" value="valider"/>
            <button type="submit" class="adm-detail-action-btn adm-detail-action-ok">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15"><polyline points="20 6 9 17 4 12"/></svg>
              Valider l'article
            </button>
          </form>
          <?php endif; ?>
          <?php if ($article['statut'] !== 'Invalide'): ?>
          <form method="POST" action="<?= path('admin','article_detail',['id'=>$article['id']]) ?>">
            <input type="hidden" name="post_action" value="invalider"/>
            <button type="submit" class="adm-detail-action-btn adm-detail-action-warn">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              Invalider
            </button>
          </form>
          <?php endif; ?>
          <form method="POST" action="<?= path('admin','article_detail',['id'=>$article['id']]) ?>"
                onsubmit="return confirm('Supprimer définitivement ?')">
            <input type="hidden" name="post_action" value="supprimer_article"/>
            <button type="submit" class="adm-detail-action-btn adm-detail-action-del">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
              Supprimer
            </button>
          </form>
        </div>
      </div>

      <!-- Stats -->
      <div class="adm-detail-scard">
        <div class="ds-label">Informations</div>
        <div class="adm-detail-info-list">
          <div class="adm-detail-info-item">
            <span class="adm-detail-info-label">Auteur</span>
            <span class="adm-detail-info-val"><?= htmlspecialchars($article['auteur']) ?></span>
          </div>
          <div class="adm-detail-info-item">
            <span class="adm-detail-info-label">Commentaires</span>
            <span class="adm-detail-info-val"><?= $nbCommentaires ?></span>
          </div>
          <div class="adm-detail-info-item">
            <span class="adm-detail-info-label">Créé le</span>
            <span class="adm-detail-info-val"><?= date('d/m/Y', strtotime($article['date_creation'])) ?></span>
          </div>
        </div>
      </div>

      <!-- Catégories -->
      <div class="adm-detail-scard">
        <div class="ds-label">Catégories</div>
        <div class="ds-categ-chips">
          <?php foreach ($categories as $cat): ?>
            <span class="ds-chip ds-chip-active"><?= htmlspecialchars($cat['libelle']) ?></span>
          <?php endforeach; ?>
        </div>
      </div>

    </aside>
  </div>
</div>