<!-- ════════ LECTEURS ADMIN ════════ -->
<div class="adm-page">

  <div class="adm-page-header fade-up">
    <div>
      <h1 class="adm-page-title">Lecteurs</h1>
      <p class="adm-page-sub"><?= $total ?> lecteur<?= $total > 1 ? 's' : '' ?> au total</p>
    </div>
  </div>

  <div class="adm-filters fade-up">
    <div class="adm-filter-chips">
      <a href="<?= path('admin','lecteurs') ?>"                        class="adm-chip <?= empty($statut)?'active':'' ?>">Tous</a>
      <a href="<?= path('admin','lecteurs',['statut'=>'Actif']) ?>"    class="adm-chip <?= $statut==='Actif'?'active':'' ?>">Actifs</a>
      <a href="<?= path('admin','lecteurs',['statut'=>'Inactif']) ?>"  class="adm-chip <?= $statut==='Inactif'?'active':'' ?>">Inactifs</a>
    </div>
    <form method="GET" action="" class="adm-filter-search">
      <input type="hidden" name="controller" value="admin"/>
      <input type="hidden" name="action"     value="lecteurs"/>
      <?php if ($statut): ?><input type="hidden" name="statut" value="<?= htmlspecialchars($statut) ?>"/><?php endif; ?>
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Nom, email…"/>
      <button type="submit">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
      </button>
    </form>
  </div>

  <div class="adm-card fade-up" style="transition-delay:.05s">
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr><th>Lecteur</th><th>Email</th><th>Commentaires</th><th>Statut</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if (empty($lecteurs)): ?>
            <tr><td colspan="5" class="adm-table-empty">Aucun lecteur trouvé.</td></tr>
          <?php else: ?>
          <?php foreach ($lecteurs as $lec):
            $initials = strtoupper(substr($lec['prenom'],0,1).substr($lec['nom'],0,1));
            $sc = $lec['statut'] === 'Actif' ? 'adm-status-actif' : 'adm-status-invalide';
          ?>
          <tr>
            <td>
              <div class="adm-table-user">
                <div class="adm-table-avatar" style="background:#eef2ff;color:#4f6ef7;"><?= $initials ?></div>
                <span><?= htmlspecialchars($lec['prenom'].' '.$lec['nom']) ?></span>
              </div>
            </td>
            <td><?= htmlspecialchars($lec['email']) ?></td>
            <td><span class="adm-badge-blue"><?= $lec['nb_commentaires'] ?></span></td>
            <td><span class="adm-status-chip <?= $sc ?>"><?= $lec['statut'] ?></span></td>
            <td>
              <div class="adm-table-actions">
                <?php if ($lec['statut'] === 'Inactif'): ?>
                <form method="POST" action="<?= path('admin','lecteurs') ?>" style="display:inline">
                  <input type="hidden" name="post_action" value="activer"/>
                  <input type="hidden" name="lecteur_id"  value="<?= $lec['id'] ?>"/>
                  <button class="adm-tbl-btn adm-tbl-ok" title="Activer">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13"><polyline points="20 6 9 17 4 12"/></svg>
                  </button>
                </form>
                <?php else: ?>
                <form method="POST" action="<?= path('admin','lecteurs') ?>" style="display:inline">
                  <input type="hidden" name="post_action" value="desactiver"/>
                  <input type="hidden" name="lecteur_id"  value="<?= $lec['id'] ?>"/>
                  <button class="adm-tbl-btn adm-tbl-warn" title="Désactiver">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  </button>
                </form>
                <?php endif; ?>
                <button class="adm-tbl-btn adm-tbl-del" title="Supprimer"
                        onclick="admOpenDeleteLecteur(<?= $lec['id'] ?>,'<?= htmlspecialchars(addslashes($lec['prenom'].' '.$lec['nom'])) ?>')">
                  <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="13" height="13">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  </svg>
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

  <?php if ($totalPages > 1): ?>
  <div class="adm-pagination fade-up">
    <?php if ($page > 1): ?>
      <a href="<?= path('admin','lecteurs',['page'=>$page-1,'statut'=>$statut,'q'=>$search]) ?>" class="adm-page-btn">‹</a>
    <?php endif; ?>
    <?php for ($p = max(1,$page-2); $p <= min($totalPages,$page+2); $p++): ?>
      <a href="<?= path('admin','lecteurs',['page'=>$p,'statut'=>$statut,'q'=>$search]) ?>"
         class="adm-page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
      <a href="<?= path('admin','lecteurs',['page'=>$page+1,'statut'=>$statut,'q'=>$search]) ?>" class="adm-page-btn">›</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>

<!-- Modal suppression lecteur -->
<div class="adm-modal-overlay" id="admDeleteLecteurModal">
  <div class="adm-modal">
    <div class="adm-modal-icon">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="28" height="28" stroke="#dc2626">
        <polyline points="3 6 5 6 21 6"/>
        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
      </svg>
    </div>
    <h3 class="adm-modal-title">Supprimer ce lecteur ?</h3>
    <p class="adm-modal-sub"><strong id="admDeleteLecteurName"></strong> et tous ses commentaires seront supprimés.</p>
    <form method="POST" action="<?= path('admin','lecteurs') ?>">
      <input type="hidden" name="post_action" value="supprimer"/>
      <input type="hidden" name="lecteur_id"  id="admDeleteLecteurId" value=""/>
      <div class="adm-modal-actions">
        <button type="button" class="adm-modal-cancel" onclick="admCloseDeleteLecteur()">Annuler</button>
        <button type="submit" class="adm-modal-confirm">Supprimer</button>
      </div>
    </form>
  </div>
</div>