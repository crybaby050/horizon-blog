<!-- ════════ AUTEURS ADMIN ════════ -->
<div class="adm-page">

  <div class="adm-page-header fade-up">
    <div>
      <h1 class="adm-page-title">Auteurs</h1>
      <p class="adm-page-sub"><?= $total ?> auteur<?= $total > 1 ? 's' : '' ?> au total</p>
    </div>
  </div>

  <div class="adm-filters fade-up">
    <div class="adm-filter-chips">
      <a href="<?= path('admin','auteurs') ?>"                         class="adm-chip <?= empty($statut)?'active':'' ?>">Tous</a>
      <a href="<?= path('admin','auteurs',['statut'=>'Actif']) ?>"     class="adm-chip <?= $statut==='Actif'?'active':'' ?>">Actifs</a>
      <a href="<?= path('admin','auteurs',['statut'=>'Inactif']) ?>"   class="adm-chip <?= $statut==='Inactif'?'active':'' ?>">Inactifs</a>
    </div>
    <form method="GET" action="" class="adm-filter-search">
      <input type="hidden" name="controller" value="admin"/>
      <input type="hidden" name="action"     value="auteurs"/>
      <?php if ($statut): ?><input type="hidden" name="statut" value="<?= htmlspecialchars($statut) ?>"/><?php endif; ?>
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Nom, email…"/>
      <button type="submit"><svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></button>
    </form>
  </div>

  <div class="adm-card fade-up" style="transition-delay:.05s">
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr><th>Auteur</th><th>Email</th><th>Articles</th><th>Inscription</th><th>Statut</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if (empty($auteurs)): ?>
            <tr><td colspan="6" class="adm-table-empty">Aucun auteur trouvé.</td></tr>
          <?php else: ?>
          <?php foreach ($auteurs as $aut):
            $initials = strtoupper(substr($aut['prenom'],0,1).substr($aut['nom'],0,1));
            $sc = $aut['statut'] === 'Actif' ? 'adm-status-actif' : 'adm-status-invalide';
          ?>
          <tr>
            <td>
              <div class="adm-table-user">
                <div class="adm-table-avatar"><?= $initials ?></div>
                <span><?= htmlspecialchars($aut['prenom'].' '.$aut['nom']) ?></span>
              </div>
            </td>
            <td><?= htmlspecialchars($aut['email']) ?></td>
            <td><span class="adm-badge-blue"><?= $aut['nb_articles'] ?></span></td>
            <td><?= date('d/m/Y', strtotime($aut['date_inscription'])) ?></td>
            <td><span class="adm-status-chip <?= $sc ?>"><?= $aut['statut'] ?></span></td>
            <td>
              <div class="adm-table-actions">
                <?php if ($aut['statut'] === 'Inactif'): ?>
                <form method="POST" action="<?= path('admin','auteurs') ?>" style="display:inline">
                  <input type="hidden" name="post_action" value="activer"/>
                  <input type="hidden" name="auteur_id"   value="<?= $aut['id'] ?>"/>
                  <button class="adm-tbl-btn adm-tbl-ok" title="Activer"><svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13"><polyline points="20 6 9 17 4 12"/></svg></button>
                </form>
                <?php else: ?>
                <form method="POST" action="<?= path('admin','auteurs') ?>" style="display:inline">
                  <input type="hidden" name="post_action" value="desactiver"/>
                  <input type="hidden" name="auteur_id"   value="<?= $aut['id'] ?>"/>
                  <button class="adm-tbl-btn adm-tbl-warn" title="Désactiver"><svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                </form>
                <?php endif; ?>
                <button class="adm-tbl-btn adm-tbl-del" title="Supprimer"
                        onclick="admOpenDeleteUser('auteur',<?= $aut['id'] ?>,'<?= htmlspecialchars(addslashes($aut['prenom'].' '.$aut['nom'])) ?>')">
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

  <?php if ($totalPages > 1): ?>
  <div class="adm-pagination fade-up">
    <?php if ($page > 1): ?><a href="<?= path('admin','auteurs',['page'=>$page-1,'statut'=>$statut,'q'=>$search]) ?>" class="adm-page-btn">‹</a><?php endif; ?>
    <?php for ($p = max(1,$page-2); $p <= min($totalPages,$page+2); $p++): ?>
      <a href="<?= path('admin','auteurs',['page'=>$p,'statut'=>$statut,'q'=>$search]) ?>" class="adm-page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?><a href="<?= path('admin','auteurs',['page'=>$page+1,'statut'=>$statut,'q'=>$search]) ?>" class="adm-page-btn">›</a><?php endif; ?>
  </div>
  <?php endif; ?>

</div>

<!-- Modal suppression utilisateur -->
<div class="adm-modal-overlay" id="admDeleteUserModal">
  <div class="adm-modal">
    <div class="adm-modal-icon"><svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="28" height="28" stroke="#dc2626"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></div>
    <h3 class="adm-modal-title">Supprimer cet auteur ?</h3>
    <p class="adm-modal-sub"><strong id="admDeleteUserName"></strong> et tous ses articles seront supprimés.</p>
    <form method="POST" id="admDeleteUserForm" action="<?= path('admin','auteurs') ?>">
      <input type="hidden" name="post_action" value="supprimer"/>
      <input type="hidden" name="auteur_id"   id="admDeleteUserId" value=""/>
      <div class="adm-modal-actions">
        <button type="button" class="adm-modal-cancel" onclick="admCloseDeleteUser()">Annuler</button>
        <button type="submit" class="adm-modal-confirm">Supprimer</button>
      </div>
    </form>
  </div>
</div>