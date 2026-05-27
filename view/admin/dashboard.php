<?php
// Prépare les données du graphique pour JS
$graphLabels = array_column($articlesParMois, 'mois');
$graphData   = array_map('intval', array_column($articlesParMois, 'total'));

// Classes de statut
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
?>

<!-- ════════ DASHBOARD ════════ -->

<!-- Alertes rapides -->
<?php if ($nbArticlesEnAttente > 0 || $nbDemandesEnAttente > 0 || $nbSignalementsNonTraites > 0): ?>
<div class="admin-alerts-row">
  <?php if ($nbArticlesEnAttente > 0): ?>
  <a href="<?= path('admin','article',['statut'=>'En attente']) ?>" class="admin-alert-pill admin-alert-warn">
    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <?= $nbArticlesEnAttente ?> article<?= $nbArticlesEnAttente > 1 ? 's' : '' ?> en attente de validation
  </a>
  <?php endif; ?>
  <?php if ($nbDemandesEnAttente > 0): ?>
  <a href="<?= path('admin','auteur',['vue'=>'demandes']) ?>" class="admin-alert-pill admin-alert-info">
    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    <?= $nbDemandesEnAttente ?> demande<?= $nbDemandesEnAttente > 1 ? 's' : '' ?> auteur en attente
  </a>
  <?php endif; ?>
  <?php if ($nbSignalementsNonTraites > 0): ?>
  <a href="<?= path('admin','signalement') ?>" class="admin-alert-pill admin-alert-danger">
    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
    <?= $nbSignalementsNonTraites ?> signalement<?= $nbSignalementsNonTraites > 1 ? 's' : '' ?> non traité<?= $nbSignalementsNonTraites > 1 ? 's' : '' ?>
  </a>
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- Cartes statistiques -->
<div class="admin-stats-grid">

  <div class="admin-stat-card">
    <div class="admin-stat-icon" style="background:#e6f7ee;color:#1a9e5c">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
    <div class="admin-stat-info">
      <div class="admin-stat-value"><?= number_format($stats['articles']) ?></div>
      <div class="admin-stat-label">Articles total</div>
    </div>
    <div class="admin-stat-sub">
      <span class="badge-attente" style="font-size:.7rem;padding:2px 8px"><?= $statsStatuts['En attente'] ?> en attente</span>
    </div>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-icon" style="background:#eef2ff;color:#4f6ef7">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
    </div>
    <div class="admin-stat-info">
      <div class="admin-stat-value"><?= number_format($stats['auteurs']) ?></div>
      <div class="admin-stat-label">Auteurs</div>
    </div>
    <div class="admin-stat-sub">
      <span style="font-size:.75rem;color:var(--admin-gray)"><?= $nbDemandesEnAttente ?> demande<?= $nbDemandesEnAttente > 1 ? 's' : '' ?></span>
    </div>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-icon" style="background:#fff7ed;color:#f97316">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    </div>
    <div class="admin-stat-info">
      <div class="admin-stat-value"><?= number_format($stats['lecteurs']) ?></div>
      <div class="admin-stat-label">Lecteurs</div>
    </div>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-icon" style="background:#fef2f2;color:#ef4444">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
    </div>
    <div class="admin-stat-info">
      <div class="admin-stat-value"><?= number_format($stats['signalements']) ?></div>
      <div class="admin-stat-label">Signalements</div>
    </div>
    <div class="admin-stat-sub">
      <span style="font-size:.75rem;color:#ef4444"><?= $nbSignalementsNonTraites ?> non traité<?= $nbSignalementsNonTraites > 1 ? 's' : '' ?></span>
    </div>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-icon" style="background:#f0fdf4;color:#16a34a">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    </div>
    <div class="admin-stat-info">
      <div class="admin-stat-value"><?= number_format($stats['commentaires']) ?></div>
      <div class="admin-stat-label">Commentaires</div>
    </div>
  </div>

  <div class="admin-stat-card">
    <div class="admin-stat-icon" style="background:#faf5ff;color:#9333ea">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
    </div>
    <div class="admin-stat-info">
      <div class="admin-stat-value"><?= $statsStatuts['Actif'] ?></div>
      <div class="admin-stat-label">Articles publiés</div>
    </div>
  </div>

</div>

<!-- Graphique + derniers articles -->
<div class="admin-dashboard-grid">

  <!-- Graphique articles par mois -->
  <div class="admin-card admin-card-chart">
    <div class="admin-card-header">
      <div class="admin-card-title">Articles publiés par mois</div>
      <span class="admin-card-sub">12 derniers mois</span>
    </div>
    <div class="admin-chart-wrap">
      <canvas id="articlesChart" height="260"></canvas>
    </div>
  </div>

  <!-- Répartition statuts -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Répartition des articles</div>
    </div>
    <div class="admin-statut-bars">
      <?php
        $total = max(1, array_sum($statsStatuts));
        $barColors = [
          'Actif'      => '#1a9e5c',
          'En attente' => '#f59e0b',
          'Invalide'   => '#ef4444',
          'Inactif'    => '#9ca3af',
        ];
        $barLabels = [
          'Actif'      => 'Publiés',
          'En attente' => 'En attente',
          'Invalide'   => 'Invalides',
          'Inactif'    => 'Supprimés',
        ];
        foreach ($statsStatuts as $statut => $count):
          $pct = round($count / $total * 100);
          $color = $barColors[$statut] ?? '#9ca3af';
          $label = $barLabels[$statut] ?? $statut;
      ?>
      <div class="admin-statut-bar-item">
        <div class="admin-statut-bar-meta">
          <span class="admin-statut-bar-label"><?= $label ?></span>
          <span class="admin-statut-bar-count"><?= $count ?> <small>(<?= $pct ?>%)</small></span>
        </div>
        <div class="admin-statut-bar-track">
          <div class="admin-statut-bar-fill" style="width:<?= $pct ?>%;background:<?= $color ?>"></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<!-- Derniers articles + signalements -->
<div class="admin-dashboard-grid">

  <!-- Derniers articles -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Derniers articles</div>
      <a href="<?= path('admin','article') ?>" class="admin-card-link">Voir tout</a>
    </div>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Titre</th>
          <th>Auteur</th>
          <th>Date</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($derniersArticles)): ?>
          <tr><td colspan="4" class="admin-table-empty">Aucun article.</td></tr>
        <?php else: ?>
          <?php foreach ($derniersArticles as $art): ?>
          <tr>
            <td class="admin-table-title">
              <a href="<?= path('admin','article',['detail'=>$art['id']]) ?>">
                <?= htmlspecialchars(mb_strimwidth($art['libelle'], 0, 40, '…')) ?>
              </a>
            </td>
            <td><?= htmlspecialchars($art['auteur'] ?? '—') ?></td>
            <td><?= date('d/m/Y', strtotime($art['date_creation'])) ?></td>
            <td>
              <span class="admin-badge <?= $statutClass[$art['statut']] ?? '' ?>">
                <?= $statutLabel[$art['statut']] ?? $art['statut'] ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Derniers signalements -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Derniers signalements</div>
      <a href="<?= path('admin','signalement') ?>" class="admin-card-link">Voir tout</a>
    </div>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Raison</th>
          <th>Cible</th>
          <th>Date</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($derniersSignalements)): ?>
          <tr><td colspan="4" class="admin-table-empty">Aucun signalement.</td></tr>
        <?php else: ?>
          <?php foreach ($derniersSignalements as $sig): ?>
          <tr>
            <td><?= htmlspecialchars(mb_strimwidth($sig['libelle'], 0, 30, '…')) ?></td>
            <td>
              <?php if ($sig['article_id']): ?>
                <a href="<?= path('admin','article',['detail'=>$sig['article_id']]) ?>">Article #<?= $sig['article_id'] ?></a>
              <?php else: ?>
                Commentaire #<?= $sig['commentaire_id'] ?>
              <?php endif; ?>
            </td>
            <td><?= date('d/m/Y', strtotime($sig['date_creation'])) ?></td>
            <td>
              <span class="admin-badge <?= $sig['statut'] === 'Non traiter' ? 'badge-attente' : 'badge-actif' ?>">
                <?= $sig['statut'] === 'Non traiter' ? 'Non traité' : 'Traité' ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

<!-- Données graphique injectées pour admin.js -->
<script>
window.adminChartData = {
  labels : <?= json_encode($graphLabels) ?>,
  data   : <?= json_encode($graphData) ?>
};
</script>