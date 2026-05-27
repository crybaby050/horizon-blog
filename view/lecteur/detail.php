<?php
/* ── Helpers locaux ── */
// Initiales à partir d'un nom complet
$initiales = function(string $nom): string {
    $parts = explode(' ', $nom);
    return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
};

// Couleurs avatar selon type
$avatarStyle = function(string $type): string {
    return $type === 'auteur'
        ? 'background:#e8f7f0;color:#0f6e40'
        : 'background:#e8f0fe;color:#1a56db';
};

// Image fallback
$imgArticle = !empty($article['image_p'])
    ? htmlspecialchars($article['image_p'])
    : 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=1400&q=85';

// Image fallback similaires
$imgSim = function(array $art): string {
    return !empty($art['image_p'])
        ? htmlspecialchars($art['image_p'])
        : 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=200&q=70';
};
?>

<!-- ════════ DETAIL ARTICLE ════════ -->
<div class="detail-wrapper">

  <!-- ══ COLONNE PRINCIPALE ══ -->
  <main class="detail-main">

    <!-- Tags -->
    <div class="detail-tags">
      <?php foreach ($categories as $i => $cat): ?>
        <span class="detail-tag <?= $i === 0 ? 'detail-tag-primary' : 'detail-tag-sec' ?>">
          <?= htmlspecialchars($cat['libelle']) ?>
        </span>
      <?php endforeach; ?>
    </div>

    <!-- Titre -->
    <h1 class="detail-title"><?= htmlspecialchars($article['libelle']) ?></h1>

    <!-- Sous-titre -->
    <p class="detail-subtitle"><?= htmlspecialchars($article['description']) ?></p>

    <!-- Meta -->
    <div class="detail-meta">
      <div class="detail-author-block">
        <div class="detail-avatar"><?= $initiales($article['auteur'] ?? 'IN') ?></div>
        <div>
          <div class="detail-author-name"><?= htmlspecialchars($article['auteur'] ?? 'Inconnu') ?></div>
          <div class="detail-author-role">Auteur</div>
        </div>
      </div>
      <div class="detail-meta-item">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        <?= date('d/m/Y', strtotime($article['date_creation'])) ?>
      </div>
      <div class="detail-meta-item">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <?= $nbCommentaires ?> commentaire<?= $nbCommentaires > 1 ? 's' : '' ?>
      </div>
    </div>

    <!-- Image couverture -->
    <div class="detail-cover">
      <img src="<?= $imgArticle ?>" alt="<?= htmlspecialchars($article['libelle']) ?>"/>
      <div class="detail-cover-caption"><?= htmlspecialchars($article['libelle']) ?> — HorizonBlog</div>
      <!-- Signaler l'article (form POST caché, déclenché par la modale) -->
      <button class="detail-report-btn" onclick="openModal('modalSignalArticle')" title="Signaler cet article">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
        Signaler
      </button>
    </div>

    <!-- Partage + stats -->
    <div class="detail-share-row">
      <div class="detail-share-btns">
        <span class="detail-share-label">Partager :</span>
        <button class="detail-share-btn" title="Twitter" onclick="shareClick(this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231z"/></svg>
        </button>
        <button class="detail-share-btn" title="Facebook" onclick="shareClick(this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </button>
        <button class="detail-share-btn" title="Copier le lien" onclick="copyLink(this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </button>
      </div>
    </div>

    <hr class="detail-divider"/>

    <!-- Corps article -->
    <div class="detail-body">
      <?= $article['contenu'] ?? '<p>Contenu non disponible.</p>' ?>
    </div>

    <hr class="detail-divider"/>

    <!-- Tags footer -->
    <div class="detail-tags-footer">
      <span class="detail-tf-label">Tags :</span>
      <?php foreach ($categories as $i => $cat): ?>
        <span class="detail-tag <?= $i === 0 ? 'detail-tag-primary' : 'detail-tag-sec' ?>">
          <?= htmlspecialchars($cat['libelle']) ?>
        </span>
      <?php endforeach; ?>
    </div>

    <hr class="detail-divider"/>

    <!-- ══ COMMENTAIRES ══ -->
    <div class="detail-comments" id="commentsSection">
      <div class="dc-header">
        <h2>Commentaires <span class="dc-count"><?= $nbCommentaires ?></span></h2>
      </div>

      <!-- Formulaire ajouter commentaire -->
      <?php if ($currentAuteurId || $currentLecteurId): ?>
      <form method="POST" action="<?= path('lecteur','detail',['id'=>$article['id']]) ?>#commentsSection">
        <input type="hidden" name="controller" value="lecteur"/>
        <input type="hidden" name="action" value="detail"/>
        <input type="hidden" name="id" value="<?= $article['id'] ?>"/>
        <input type="hidden" name="post_action" value="add_comment"/>
        <div class="dc-form-wrap" id="dcFormWrap">
          <?php
            $meNom = '';
            if ($currentAuteurId) {
                // récupère le nom auteur depuis la session si dispo
                $meNom = $_SESSION['user']['initiales'] ?? 'AU';
            } else {
                $meNom = $_SESSION['user']['initiales'] ?? 'LE';
            }
          ?>
          <div class="dc-form-avatar"><?= htmlspecialchars($meNom) ?></div>
          <div class="dc-form-inner" id="dcFormInner">
            <textarea id="dcTextarea" name="contenu" placeholder="Laissez un commentaire…" rows="1"
              oninput="autoResize(this)" onfocus="expandForm()" required></textarea>
            <div class="dc-form-actions" id="dcFormActions" style="display:none">
              <button type="button" class="dc-cancel" onclick="collapseForm()">Annuler</button>
              <button type="submit" class="dc-submit">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5" stroke-linecap="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Publier
              </button>
            </div>
          </div>
        </div>
      </form>
      <?php else: ?>
        <div class="dc-login-notice">
          <a href="<?= path('auth','login') ?>">Connectez-vous</a> pour laisser un commentaire.
        </div>
      <?php endif; ?>

      <!-- Liste commentaires -->
      <div class="dc-list" id="dcList">
        <?php if (empty($commentaires)): ?>
          <p style="color:var(--gray);text-align:center;padding:32px 0;">
            Aucun commentaire pour l'instant. Soyez le premier !
          </p>
        <?php else: ?>
          <?php foreach ($commentaires as $com):
            $nomCom     = $com['nom_complet'] ?? 'Inconnu';
            $initCom    = $initiales($nomCom);
            $styleAv    = $avatarStyle($com['type_user']);
            $isAuteur   = $com['type_user'] === 'auteur';
            $isMine     = ($currentAuteurId && (int)$com['auteur_id'] === $currentAuteurId)
                        || ($currentLecteurId && (int)$com['lecteur_id'] === $currentLecteurId);
          ?>
          <div class="dc-item" id="comment-<?= $com['id'] ?>">
            <div class="dc-item-avatar" style="<?= $styleAv ?>"><?= $initCom ?></div>
            <div class="dc-item-body">
              <div class="dc-item-header">
                <div>
                  <span class="dc-item-name"><?= htmlspecialchars($nomCom) ?></span>
                  <?php if ($isAuteur): ?>
                    <span class="dc-item-badge">Auteur</span>
                  <?php else: ?>
                    <span class="dc-item-badge dc-item-badge-lecteur">Lecteur</span>
                  <?php endif; ?>
                  <span class="dc-item-date"><?= date('d/m/Y à H:i', strtotime($com['date'])) ?></span>
                </div>
                <div class="dc-item-actions">
                  <?php if ($isMine): ?>
                    <!-- Modifier -->
                    <button class="dc-action-btn dc-edit-btn" onclick="editComment(<?= $com['id'] ?>)" title="Modifier">
                      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      Modifier
                    </button>
                    <!-- Supprimer -->
                    <button class="dc-action-btn dc-delete-btn"
                            onclick="openModal('modalDeleteComment', <?= $com['id'] ?>)" title="Supprimer">
                      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                      Supprimer
                    </button>
                  <?php endif; ?>
                  <!-- Signaler (tout le monde sauf le proprio) -->
                  <?php if (!$isMine && ($currentAuteurId || $currentLecteurId)): ?>
                    <button class="dc-action-btn dc-report-btn"
                            onclick="openModal('modalSignalComment', <?= $com['id'] ?>)" title="Signaler">
                      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                      Signaler
                    </button>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Texte commentaire -->
              <div class="dc-item-text" id="comment-text-<?= $com['id'] ?>">
                <?= nl2br(htmlspecialchars($com['contenue'])) ?>
              </div>

              <!-- Zone édition -->
              <?php if ($isMine): ?>
              <div class="dc-edit-zone" id="dc-edit-<?= $com['id'] ?>" style="display:none">
                <form method="POST" action="<?= path('lecteur','detail',['id'=>$article['id']]) ?>#commentsSection">
                  <input type="hidden" name="controller" value="lecteur"/>
                  <input type="hidden" name="action" value="detail"/>
                  <input type="hidden" name="id" value="<?= $article['id'] ?>"/>
                  <input type="hidden" name="post_action" value="edit_comment"/>
                  <input type="hidden" name="comment_id" value="<?= $com['id'] ?>"/>
                  <textarea class="dc-edit-textarea" name="contenu"
                            id="dc-edit-ta-<?= $com['id'] ?>"><?= htmlspecialchars($com['contenue']) ?></textarea>
                  <div class="dc-edit-btns">
                    <button type="button" class="dc-cancel" onclick="cancelEdit(<?= $com['id'] ?>)">Annuler</button>
                    <button type="submit" class="dc-submit">Enregistrer</button>
                  </div>
                </form>
              </div>

              <!-- Form suppression (caché, soumis par la modale) -->
              <form method="POST" id="form-delete-<?= $com['id'] ?>"
                    action="<?= path('lecteur','detail',['id'=>$article['id']]) ?>#commentsSection"
                    style="display:none">
                <input type="hidden" name="controller" value="lecteur"/>
                <input type="hidden" name="action" value="detail"/>
                <input type="hidden" name="id" value="<?= $article['id'] ?>"/>
                <input type="hidden" name="post_action" value="delete_comment"/>
                <input type="hidden" name="comment_id" value="<?= $com['id'] ?>"/>
              </form>
              <?php endif; ?>

            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div><!-- /dc-list -->
    </div><!-- /detail-comments -->

  </main>

  <!-- ══ SIDEBAR ══ -->
  <aside class="detail-sidebar">

    <!-- Auteur -->
    <div class="ds-card">
      <div class="ds-label">À propos de l'auteur</div>
      <div class="ds-author">
        <div class="ds-author-avatar"><?= $initiales($article['auteur'] ?? 'IN') ?></div>
        <div>
          <div class="ds-author-name"><?= htmlspecialchars($article['auteur'] ?? 'Inconnu') ?></div>
          <div class="ds-author-role">Auteur confirmé</div>
        </div>
      </div>
      <p class="ds-author-bio">
        <?= htmlspecialchars($article['auteur_bio'] ?? 'Contributeur actif sur HorizonBlog.') ?>
      </p>
      <div class="ds-author-stats">
        <span class="ds-stat-pill"><?= $nbArticlesAuteur ?> article<?= $nbArticlesAuteur > 1 ? 's' : '' ?></span>
      </div>
    </div>

    <!-- Articles similaires -->
    <?php if (!empty($similaires)): ?>
    <div class="ds-card">
      <div class="ds-label">Articles similaires</div>
      <?php foreach ($similaires as $sim): ?>
      <a href="<?= path('lecteur','detail',['id'=>$sim['id']]) ?>" class="ds-related">
        <img src="<?= $imgSim($sim) ?>" alt="<?= htmlspecialchars($sim['libelle']) ?>"/>
        <div>
          <div class="ds-related-title"><?= htmlspecialchars($sim['libelle']) ?></div>
          <div class="ds-related-date"><?= date('d/m/Y', strtotime($sim['date_creation'])) ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Catégories -->
    <div class="ds-card">
      <div class="ds-label">Catégories</div>
      <div class="ds-categ-chips">
        <?php foreach ($toutesCategories as $cat): ?>
          <?php
            $isActive = in_array($cat['id'], array_column($categories, 'id'));
          ?>
          <a href="<?= path('lecteur','article',['categorie'=>$cat['id']]) ?>"
             class="ds-chip <?= $isActive ? 'ds-chip-active' : '' ?>">
            <?= htmlspecialchars($cat['libelle']) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Newsletter -->
    <div class="ds-newsletter">
      <div class="ds-nl-title">Restez informé</div>
      <p>Recevez les meilleurs articles chaque semaine.</p>
      <input type="email" placeholder="votre@email.com"/>
      <button>S'abonner</button>
    </div>

  </aside>
</div><!-- /detail-wrapper -->


<!-- ════════ MODALS ════════ -->

<!-- Modal signaler article -->
<div class="modal-overlay" id="modalSignalArticle" onclick="closeModalOutside(event, 'modalSignalArticle')">
  <div class="modal">
    <div class="modal-header">
      <h3>Signaler cet article</h3>
      <button class="modal-close" onclick="closeModal('modalSignalArticle')">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form method="POST" action="<?= path('lecteur','detail',['id'=>$article['id']]) ?>">
      <input type="hidden" name="controller" value="lecteur"/>
      <input type="hidden" name="action" value="detail"/>
      <input type="hidden" name="id" value="<?= $article['id'] ?>"/>
      <input type="hidden" name="post_action" value="signal_article"/>
      <div class="modal-body">
        <p class="modal-desc">Pourquoi souhaitez-vous signaler cet article ?</p>
        <div class="signal-options">
          <label class="signal-opt"><input type="radio" name="raison" value="Informations fausses" checked/> Informations fausses ou trompeuses</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Discours haineux"/> Discours haineux</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Spam"/> Spam ou publicité</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Contenu violent"/> Contenu violent</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Autre"/> Autre</label>
        </div>
        <textarea class="modal-textarea" name="description" placeholder="Détails supplémentaires (optionnel)…"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="modal-btn-cancel" onclick="closeModal('modalSignalArticle')">Annuler</button>
        <button type="submit" class="modal-btn-confirm modal-btn-danger">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
          Signaler
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal signaler commentaire -->
<div class="modal-overlay" id="modalSignalComment" onclick="closeModalOutside(event, 'modalSignalComment')">
  <div class="modal">
    <div class="modal-header">
      <h3>Signaler ce commentaire</h3>
      <button class="modal-close" onclick="closeModal('modalSignalComment')">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form method="POST" action="<?= path('lecteur','detail',['id'=>$article['id']]) ?>#commentsSection"
          id="formSignalComment">
      <input type="hidden" name="controller" value="lecteur"/>
      <input type="hidden" name="action" value="detail"/>
      <input type="hidden" name="id" value="<?= $article['id'] ?>"/>
      <input type="hidden" name="post_action" value="signal_comment"/>
      <input type="hidden" name="comment_id" id="signalCommentId" value=""/>
      <div class="modal-body">
        <p class="modal-desc">Pourquoi souhaitez-vous signaler ce commentaire ?</p>
        <div class="signal-options">
          <label class="signal-opt"><input type="radio" name="raison" value="Harcèlement" checked/> Harcèlement ou intimidation</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Discours haineux"/> Discours haineux</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Spam"/> Spam</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Informations fausses"/> Informations fausses</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Autre"/> Autre</label>
        </div>
        <textarea class="modal-textarea" name="description" placeholder="Détails supplémentaires (optionnel)…"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="modal-btn-cancel" onclick="closeModal('modalSignalComment')">Annuler</button>
        <button type="submit" class="modal-btn-confirm modal-btn-danger">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
          Signaler
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal supprimer commentaire -->
<div class="modal-overlay" id="modalDeleteComment" onclick="closeModalOutside(event, 'modalDeleteComment')">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3>Supprimer le commentaire</h3>
      <button class="modal-close" onclick="closeModal('modalDeleteComment')">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="modal-delete-icon">
        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
      </div>
      <p class="modal-desc" style="text-align:center">
        Cette action est irréversible. Voulez-vous vraiment supprimer ce commentaire ?
      </p>
    </div>
    <div class="modal-footer">
      <button type="button" class="modal-btn-cancel" onclick="closeModal('modalDeleteComment')">Annuler</button>
      <button type="button" class="modal-btn-confirm modal-btn-danger" onclick="deleteComment()">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
        Supprimer
      </button>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="toast" id="toast"></div>