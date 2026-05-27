<!-- ════════ DETAIL ARTICLE (AUTEUR) ════════ -->
<div class="au-page">

  <!-- En-tête -->
  <div class="au-page-header fade-up">
    <div class="au-breadcrumb">
      <a href="<?= path('auteur','articles') ?>">Mes articles</a>
      <span>›</span>
      <span><?= htmlspecialchars(strlen($article['libelle']) > 50 
    ? substr($article['libelle'], 0, 50) . '…' 
    : $article['libelle']) ?></span>
    </div>
    <div class="au-page-header-actions">
      <a href="<?= path('auteur','modifier',['id'=>$article['id']]) ?>" class="au-btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
        Modifier
      </a>
      <button class="au-btn-danger"
              onclick="openDeleteModal(<?= $article['id'] ?>, '<?= htmlspecialchars(addslashes($article['libelle'])) ?>')">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
          <polyline points="3 6 5 6 21 6"/>
          <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        </svg>
        Supprimer
      </button>
    </div>
  </div>

  <?php if (isset($_GET['success']) && $_GET['success'] === 'edit'): ?>
    <div class="au-alert au-alert-ok fade-up">Article modifié avec succès ! Il est repassé en attente de validation.</div>
  <?php endif; ?>

  <div class="au-detail-layout fade-up">

    <!-- ── CONTENU PRINCIPAL ── -->
    <main class="au-detail-main">

      <!-- Statut + dates -->
      <div class="au-detail-status-bar">
        <?php
          $statusClass = match($article['statut']) {
            'Actif'      => 'au-status-actif',
            'En attente' => 'au-status-attente',
            'Invalide'   => 'au-status-invalide',
            default      => 'au-status-actif'
          };
        ?>
        <span class="au-status-chip <?= $statusClass ?>"><?= $article['statut'] ?></span>
        <span class="au-detail-date">
          Créé le <?= date('d/m/Y à H:i', strtotime($article['date_creation'])) ?>
        </span>
        <?php if ($article['date_dernier_modification'] !== $article['date_creation']): ?>
          <span class="au-detail-date">
            · Modifié le <?= date('d/m/Y à H:i', strtotime($article['date_dernier_modification'])) ?>
          </span>
        <?php endif; ?>
      </div>

      <!-- Tags -->
      <div class="au-detail-tags">
        <?php foreach ($article['categories'] as $i => $cat): ?>
          <span class="detail-tag <?= $i === 0 ? 'detail-tag-primary' : 'detail-tag-sec' ?>">
            <?= htmlspecialchars($cat['libelle']) ?>
          </span>
        <?php endforeach; ?>
      </div>

      <!-- Titre -->
      <h1 class="au-detail-title"><?= htmlspecialchars($article['libelle']) ?></h1>

      <!-- Sous-titre -->
      <p class="au-detail-subtitle"><?= htmlspecialchars($article['description']) ?></p>

      <!-- Méta stats -->
      <div class="au-detail-meta">
        <!--<span class="au-meta-item">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2" width="14" height="14">
            <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
          </svg>
          <?= $article['vues'] ?? 0 ?> vue<?= ($article['vues'] ?? 0) > 1 ? 's' : '' ?>
        </span>-->
        <span class="au-meta-item">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2" width="14" height="14" stroke-linecap="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
          </svg>
          <?= $nbCommentaires ?> commentaire<?= $nbCommentaires > 1 ? 's' : '' ?>
        </span>
      </div>

      <!-- Image couverture -->
      <?php if (!empty($article['image_p'])): ?>
      <div class="au-detail-cover">
        <img src="<?= htmlspecialchars($article['image_p']) ?>" alt="<?= htmlspecialchars($article['libelle']) ?>"/>
      </div>
      <?php endif; ?>

      <!-- Contenu -->
      <div class="au-detail-body">
        <?= $article['contenu'] ?? '<p>Aucun contenu.</p>' ?>
      </div>

      <hr class="au-detail-divider"/>

      <!-- ══ COMMENTAIRES ══ -->
      <div id="commentsSection">
        <div class="dc-header">
          <h2>Commentaires <span class="dc-count"><?= $nbCommentaires ?></span></h2>
        </div>

        <!-- Formulaire commentaire -->
        <form method="POST"
              action="<?= path('auteur','detail',['id'=>$article['id']]) ?>#commentsSection">
          <input type="hidden" name="controller"  value="auteur"/>
          <input type="hidden" name="action"      value="detail"/>
          <input type="hidden" name="id"          value="<?= $article['id'] ?>"/>
          <input type="hidden" name="post_action" value="add_comment"/>
          <div class="dc-form-wrap">
            <?php
                $prenom = $_SESSION['utilisateur']['prénom'] ?? 'A';
                $nom    = $_SESSION['utilisateur']['nom']    ?? 'U';
              $initials = strtoupper(substr($prenom,0,1).substr($nom,0,1));
            ?>
            <div class="dc-form-avatar" style="background:#e8f7f0;color:#0f6e40;">
              <?= $initials ?>
            </div>
            <div class="dc-form-inner">
              <textarea id="dcTextarea" name="contenu"
                        placeholder="Laissez un commentaire sur votre article…"
                        rows="1"
                        oninput="autoResize(this)"
                        onfocus="expandForm()"
                        required></textarea>
              <div class="dc-form-actions" id="dcFormActions" style="display:none">
                <button type="button" class="dc-cancel" onclick="collapseForm()">Annuler</button>
                <button type="submit" class="dc-submit">
                  <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5" stroke-linecap="round">
                    <line x1="22" y1="2" x2="11" y2="13"/>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                  </svg>
                  Publier
                </button>
              </div>
            </div>
          </div>
        </form>

        <!-- Liste commentaires -->
        <div class="dc-list" id="dcList">
          <?php if (empty($commentaires)): ?>
            <p style="color:var(--gray);text-align:center;padding:32px 0;">
              Aucun commentaire pour l'instant.
            </p>
          <?php else: ?>
            <?php foreach ($commentaires as $com):
              $nomCom   = $com['nom_complet'] ?? 'Inconnu';
              $parts    = explode(' ', $nomCom);
              $initCom  = strtoupper(substr($parts[0],0,1).(isset($parts[1])?substr($parts[1],0,1):''));
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
                    <?php if ($isAuteur): ?>
                      <span class="dc-item-badge">Auteur</span>
                    <?php else: ?>
                      <span class="dc-item-badge dc-item-badge-lecteur">Lecteur</span>
                    <?php endif; ?>
                    <span class="dc-item-date"><?= date('d/m/Y à H:i', strtotime($com['date'])) ?></span>
                  </div>
                  <div class="dc-item-actions">
                    <!-- Supprimer (auteur peut supprimer tous les commentaires de son article) -->
                    <button class="dc-action-btn dc-delete-btn"
                            onclick="openModal('modalDeleteComment', <?= $com['id'] ?>)" title="Supprimer">
                      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                      </svg>
                      Supprimer
                    </button>
                    <!-- Signaler (seulement si pas son propre commentaire) -->
                    <?php if (!$isAuteur): ?>
                    <button class="dc-action-btn dc-report-btn"
                            onclick="openModal('modalSignalComment', <?= $com['id'] ?>)" title="Signaler">
                      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                        <line x1="4" y1="22" x2="4" y2="15"/>
                      </svg>
                      Signaler
                    </button>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="dc-item-text">
                  <?= nl2br(htmlspecialchars($com['contenue'])) ?>
                </div>

                <!-- Form suppression commentaire (caché) -->
                <form method="POST" id="form-delete-<?= $com['id'] ?>"
                      action="<?= path('auteur','detail',['id'=>$article['id']]) ?>#commentsSection"
                      style="display:none">
                  <input type="hidden" name="controller"  value="auteur"/>
                  <input type="hidden" name="action"      value="detail"/>
                  <input type="hidden" name="id"          value="<?= $article['id'] ?>"/>
                  <input type="hidden" name="post_action" value="delete_comment"/>
                  <input type="hidden" name="comment_id"  value="<?= $com['id'] ?>"/>
                </form>

              </div>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </main>

    <!-- ── SIDEBAR ── -->
    <aside class="au-detail-sidebar">

      <!-- Actions rapides -->
      <div class="au-detail-scard">
        <div class="ds-label">Actions</div>
        <div class="au-detail-actions">
          <a href="<?= path('auteur','modifier',['id'=>$article['id']]) ?>"
             class="au-detail-action-btn au-detail-action-edit">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Modifier l'article
          </a>
          <button onclick="openDeleteModal(<?= $article['id'] ?>, '<?= htmlspecialchars(addslashes($article['libelle'])) ?>')"
                  class="au-detail-action-btn au-detail-action-del">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15">
              <polyline points="3 6 5 6 21 6"/>
              <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
            </svg>
            Supprimer l'article
          </button>
        </div>
      </div>

      <!-- Statistiques -->
      <div class="au-detail-scard">
        <div class="ds-label">Statistiques</div>
        <div class="au-detail-stats">
          <div class="au-detail-stat">
            <div class="au-detail-stat-num"><?= $article['vues'] ?? 0 ?></div>
            <div class="au-detail-stat-label">Vues</div>
          </div>
          <div class="au-detail-stat">
            <div class="au-detail-stat-num"><?= $nbCommentaires ?></div>
            <div class="au-detail-stat-label">Commentaires</div>
          </div>
        </div>
      </div>

      <!-- Catégories -->
      <div class="au-detail-scard">
        <div class="ds-label">Catégories</div>
        <div class="ds-categ-chips">
          <?php foreach ($article['categories'] as $cat): ?>
            <span class="ds-chip ds-chip-active"><?= htmlspecialchars($cat['libelle']) ?></span>
          <?php endforeach; ?>
        </div>
      </div>

    </aside>
  </div>
</div>

<!-- Modal suppression article -->
<div class="au-modal-overlay" id="deleteModal">
  <div class="au-modal">
    <div class="au-modal-icon-wrap" style="background:#fef2f2;">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="28" height="28" stroke="#dc2626">
        <polyline points="3 6 5 6 21 6"/>
        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
      </svg>
    </div>
    <h3 class="au-modal-title">Supprimer l'article ?</h3>
    <p class="au-modal-sub">Cette action est irréversible. L'article et tous ses commentaires seront supprimés.</p>
    <form method="POST" action="<?= path('auteur','supprimer') ?>">
      <input type="hidden" name="controller" value="auteur"/>
      <input type="hidden" name="action"     value="supprimer"/>
      <input type="hidden" name="id"         id="deleteArticleId" value="<?= $article['id'] ?>"/>
      <div class="au-modal-actions">
        <button type="button" class="au-modal-cancel" onclick="closeDeleteModal()">Annuler</button>
        <button type="submit" class="au-modal-confirm">Supprimer</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal suppression commentaire -->
<div class="modal-overlay" id="modalDeleteComment" onclick="closeModalOutside(event,'modalDeleteComment')">
  <div class="modal modal-sm">
    <div class="modal-header">
      <h3>Supprimer ce commentaire ?</h3>
      <button class="modal-close" onclick="closeModal('modalDeleteComment')">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="modal-delete-icon">
        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2" stroke-linecap="round">
          <polyline points="3 6 5 6 21 6"/>
          <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
          <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
        </svg>
      </div>
      <p class="modal-desc" style="text-align:center">Cette action est irréversible.</p>
    </div>
    <div class="modal-footer">
      <button class="modal-btn-cancel" onclick="closeModal('modalDeleteComment')">Annuler</button>
      <button class="modal-btn-confirm modal-btn-danger" onclick="deleteComment()">Supprimer</button>
    </div>
  </div>
</div>

<!-- Modal signaler commentaire -->
<div class="modal-overlay" id="modalSignalComment" onclick="closeModalOutside(event,'modalSignalComment')">
  <div class="modal">
    <div class="modal-header">
      <h3>Signaler ce commentaire</h3>
      <button class="modal-close" onclick="closeModal('modalSignalComment')">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>
    <form method="POST"
          action="<?= path('auteur','detail',['id'=>$article['id']]) ?>#commentsSection"
          id="formSignalComment">
      <input type="hidden" name="controller"  value="auteur"/>
      <input type="hidden" name="action"      value="detail"/>
      <input type="hidden" name="id"          value="<?= $article['id'] ?>"/>
      <input type="hidden" name="post_action" value="signal_comment"/>
      <input type="hidden" name="comment_id"  id="signalCommentId" value=""/>
      <div class="modal-body">
        <p class="modal-desc">Pourquoi souhaitez-vous signaler ce commentaire ?</p>
        <div class="signal-options">
          <label class="signal-opt"><input type="radio" name="raison" value="Harcèlement" checked/> Harcèlement ou intimidation</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Discours haineux"/> Discours haineux</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Spam"/> Spam</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Informations fausses"/> Informations fausses</label>
          <label class="signal-opt"><input type="radio" name="raison" value="Autre"/> Autre</label>
        </div>
        <textarea class="modal-textarea" name="description" placeholder="Détails (optionnel)…"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="modal-btn-cancel" onclick="closeModal('modalSignalComment')">Annuler</button>
        <button type="submit" class="modal-btn-confirm modal-btn-danger">Signaler</button>
      </div>
    </form>
  </div>
</div>

<div class="toast" id="toast"></div>