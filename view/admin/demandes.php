<!-- ════════ DEMANDES AUTEUR ════════ -->
<div class="adm-page">

  <div class="adm-page-header fade-up">
    <div>
      <h1 class="adm-page-title">Demandes pour devenir auteur</h1>
      <p class="adm-page-sub"><?= $total ?> demande<?= $total > 1 ? 's' : '' ?></p>
    </div>
  </div>

  <!-- Filtres statut -->
  <div class="adm-filters fade-up">
    <a href="<?= path('admin','demandes',['statut'=>'En attente']) ?>"
       class="filter-chip <?= $statut === 'En attente' ? 'active' : '' ?>">En attente</a>
    <a href="<?= path('admin','demandes',['statut'=>'Acceptee']) ?>"
       class="filter-chip <?= $statut === 'Acceptee' ? 'active' : '' ?>">Acceptées</a>
    <a href="<?= path('admin','demandes',['statut'=>'Refusee']) ?>"
       class="filter-chip <?= $statut === 'Refusee' ? 'active' : '' ?>">Refusées</a>
  </div>

  <?php if (empty($demandes)): ?>
    <div class="au-empty-state fade-up">
      <div class="au-empty-icon">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" width="48" height="48" stroke="#1a9e5c">
          <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="8.5" cy="7" r="4"/>
        </svg>
      </div>
      <h2>Aucune demande</h2>
      <p>Il n'y a aucune demande dans cette catégorie pour le moment.</p>
    </div>
  <?php else: ?>

    <div class="adm-card fade-up" style="transition-delay:.05s">
      <?php foreach ($demandes as $d):
        $initiales = strtoupper(substr($d['prenom'],0,1) . substr($d['nom'],0,1));
        $photoUrl = !empty($d['photo'])
            ? (str_starts_with($d['photo'], 'http') ? $d['photo'] : WEBROOT . $d['photo'])
            : null;
      ?>
      <div class="adm-signal-card" style="padding:20px;border-bottom:1px solid #f0f0f0;">
        <div style="display:flex;gap:14px;align-items:flex-start;">
          <?php if ($photoUrl): ?>
            <img src="<?= htmlspecialchars($photoUrl) ?>" alt=""
                 style="width:44px;height:44px;border-radius:50%;object-fit:cover;flex-shrink:0;"/>
          <?php else: ?>
            <div style="width:44px;height:44px;border-radius:50%;background:#e8f0fe;color:#1a56db;
                        display:flex;align-items:center;justify-content:center;font-weight:600;flex-shrink:0;">
              <?= $initiales ?>
            </div>
          <?php endif; ?>
          <div style="flex:1;">
            <div style="font-weight:600;"><?= htmlspecialchars($d['prenom'].' '.$d['nom']) ?></div>
            <div style="font-size:.85rem;color:var(--gray);margin-bottom:8px;">
              <?= htmlspecialchars($d['email']) ?> · <?= date('d/m/Y à H:i', strtotime($d['date_demande'])) ?>
            </div>
            <p class="adm-signal-card-desc" style="margin:0;"><?= nl2br(htmlspecialchars($d['message'])) ?></p>
          </div>
        </div>

        <?php if ($statut === 'En attente'): ?>
        <div style="display:flex;gap:10px;margin-top:14px;justify-content:flex-end;">
          <form method="POST" action="<?= path('admin','demandes') ?>" style="display:inline">
            <input type="hidden" name="post_action" value="refuser"/>
            <input type="hidden" name="demande_id"  value="<?= $d['id'] ?>"/>
            <button type="submit" class="modal-btn-cancel" style="padding:8px 16px;">Refuser</button>
          </form>
          <form method="POST" action="<?= path('admin','demandes') ?>" style="display:inline">
            <input type="hidden" name="post_action" value="accepter"/>
            <input type="hidden" name="demande_id"  value="<?= $d['id'] ?>"/>
            <input type="hidden" name="lecteur_id"  value="<?= $d['lecteur_id'] ?>"/>
            <button type="submit" class="modal-btn-confirm" style="padding:8px 16px;background:#1a9e5c;">
              Accepter
            </button>
          </form>
        </div>
        <?php else: ?>
        <div style="margin-top:10px;">
          <span class="adm-table-status <?= $statut === 'Acceptee' ? 'status-actif' : 'status-invalide' ?>">
            <?= $statut === 'Acceptee' ? 'Acceptée' : 'Refusée' ?>
          </span>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="adm-pagination fade-up">
      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="<?= path('admin','demandes',['page'=>$p,'statut'=>$statut]) ?>"
           class="adm-page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>

  <?php endif; ?>

</div>