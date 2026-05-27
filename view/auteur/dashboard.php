<!-- ════════ DASHBOARD ════════ -->
<div class="au-page">

  <!-- Salutation -->
  <div class="au-welcome fade-up">
    <div>
      <h1 class="au-welcome-title">
        Bonjour, <?= htmlspecialchars($_SESSION['user']['prenom'] ?? 'Auteur') ?> 
      </h1>
      <p class="au-welcome-sub">Voici un aperçu de vos activités sur HorizonBlog.</p>
    </div>
    <a href="<?= path('auteur','ajout') ?>" class="au-btn-primary">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="15" height="15">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Nouvel article
    </a>
  </div>

  <!-- ── STAT CARDS ── -->
  <div class="au-stat-grid fade-up" style="transition-delay:.05s">

    <div class="au-stat-card">
      <div class="au-stat-icon" style="background:#e6f7ee;">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="22" height="22" stroke="#1a9e5c">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
      </div>
      <div class="au-stat-info">
        <div class="au-stat-num"><?= $stats['nb_articles'] ?? 0 ?></div>
        <div class="au-stat-label">Articles publiés</div>
      </div>
      <div class="au-stat-trend au-trend-up">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13">
          <polyline points="18 15 12 9 6 15"/>
        </svg>
        Total
      </div>
    </div>

    <div class="au-stat-card">
      <div class="au-stat-icon" style="background:#eef2ff;">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="22" height="22" stroke="#4f6ef7">
          <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
        </svg>
      </div>
      <div class="au-stat-info">
        <div class="au-stat-num"><?= number_format($stats['total_vues'] ?? 0) ?></div>
        <div class="au-stat-label">Vues totales</div>
      </div>
      <div class="au-stat-trend au-trend-up">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13">
          <polyline points="18 15 12 9 6 15"/>
        </svg>
        Cumulé
      </div>
    </div>

    <div class="au-stat-card">
      <div class="au-stat-icon" style="background:#fff7ed;">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="22" height="22" stroke="#f97316">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
      </div>
      <div class="au-stat-info">
        <div class="au-stat-num"><?= $stats['total_commentaires'] ?? 0 ?></div>
        <div class="au-stat-label">Commentaires</div>
      </div>
      <div class="au-stat-trend au-trend-neutral">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13">
          <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Total
      </div>
    </div>

    <div class="au-stat-card">
      <div class="au-stat-icon" style="background:#e6f7ee;">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="22" height="22" stroke="#1a9e5c">
          <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
        </svg>
      </div>
      <div class="au-stat-info">
        <div class="au-stat-num"><?= $stats['articles_actifs'] ?? 0 ?></div>
        <div class="au-stat-label">Articles actifs</div>
      </div>
      <div class="au-stat-trend au-trend-up">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13">
          <polyline points="18 15 12 9 6 15"/>
        </svg>
        En ligne
      </div>
    </div>

  </div>

  <!-- ── GRAPHIQUE + RECENTS ── -->
  <div class="au-dashboard-grid fade-up" style="transition-delay:.1s">

    <!-- Graphique -->
    <div class="au-card au-chart-card">
      <div class="au-card-header">
        <div>
          <div class="au-card-title">Activité des 12 derniers mois</div>
          <div class="au-card-sub">Vues et articles par mois</div>
        </div>
        <div class="au-chart-legend">
          <span class="au-legend-dot" style="background:#1a9e5c;"></span><span>Vues</span>
          <span class="au-legend-dot" style="background:#4f6ef7;"></span><span>Articles</span>
        </div>
      </div>
      <div class="au-chart-wrap">
        <canvas id="auChart" height="260"></canvas>
      </div>
    </div>

    <!-- Articles récents -->
    <div class="au-card">
      <div class="au-card-header">
        <div>
          <div class="au-card-title">Articles récents</div>
          <div class="au-card-sub">Vos 5 dernières publications</div>
        </div>
        <a href="<?= path('auteur','articles') ?>" class="au-card-link">Voir tout →</a>
      </div>

      <?php if (empty($articlesRecents)): ?>
        <div class="au-empty-mini">
          <p>Aucun article encore. <a href="<?= path('auteur','ajout') ?>">Écrivez le premier !</a></p>
        </div>
      <?php else: ?>
        <div class="au-recent-list">
          <?php foreach ($articlesRecents as $art):
            $statusClass = match($art['statut']) {
              'Actif'      => 'au-status-actif',
              'En attente' => 'au-status-attente',
              'Invalide'   => 'au-status-invalide',
              default      => 'au-status-actif'
            };
          ?>
          <a href="<?= path('auteur','detail',['id'=>$art['id']]) ?>" class="au-recent-item">
            <div class="au-recent-img">
              <img src="<?= !empty($art['image_p']) ? htmlspecialchars($art['image_p']) : 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=200&q=70' ?>"
                   alt="<?= htmlspecialchars($art['libelle']) ?>"/>
            </div>
            <div class="au-recent-info">
              <div class="au-recent-title"><?= htmlspecialchars($art['libelle']) ?></div>
              <div class="au-recent-meta">
                <span class="au-status-chip <?= $statusClass ?>"><?= $art['statut'] ?></span>
                <span class="au-recent-date"><?= date('d/m/Y', strtotime($art['date_creation'])) ?></span>
              </div>
            </div>
            <div class="au-recent-vues">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2" width="12" height="12">
                <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
              </svg>
              <?= $art['vues'] ?? 0 ?>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>

</div>

<!-- Données graphique pour JS -->
<script>
window.auChartData = <?= json_encode($chartData) ?>;
</script>