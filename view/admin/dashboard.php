<!-- ════════ DASHBOARD ADMIN ════════ -->
<div class="adm-page">

  <div class="adm-welcome fade-up">
    <div>
      <h1 class="adm-welcome-title">
        Bonjour, <?= htmlspecialchars($_SESSION['admin']['prenom'] ?? 'Admin') ?> 👋
      </h1>
      <p class="adm-welcome-sub">Vue d'ensemble de la plateforme HorizonBlog.</p>
    </div>
  </div>

  <!-- ── STAT CARDS ── -->
  <div class="adm-stat-grid fade-up" style="transition-delay:.05s">

    <div class="adm-stat-card">
      <div class="adm-stat-icon" style="background:#e6f7ee;">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="22" height="22" stroke="#1a9e5c">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
      </div>
      <div class="adm-stat-info">
        <div class="adm-stat-num"><?= $stats['nb_articles'] ?? 0 ?></div>
        <div class="adm-stat-label">Articles total</div>
      </div>
      <div class="adm-stat-sub">
        <span class="adm-badge-green"><?= $stats['articles_actifs'] ?? 0 ?> actifs</span>
        <span class="adm-badge-orange"><?= $stats['articles_attente'] ?? 0 ?> en attente</span>
      </div>
    </div>

    <div class="adm-stat-card">
      <div class="adm-stat-icon" style="background:#eef2ff;">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="22" height="22" stroke="#4f6ef7">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
      </div>
      <div class="adm-stat-info">
        <div class="adm-stat-num"><?= $stats['nb_auteurs'] ?? 0 ?></div>
        <div class="adm-stat-label">Auteurs</div>
      </div>
      <div class="adm-stat-sub">
        <span class="adm-badge-green"><?= $stats['auteurs_actifs'] ?? 0 ?> actifs</span>
      </div>
    </div>

    <div class="adm-stat-card">
      <div class="adm-stat-icon" style="background:#fff7ed;">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="22" height="22" stroke="#f97316">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
        </svg>
      </div>
      <div class="adm-stat-info">
        <div class="adm-stat-num"><?= $stats['nb_lecteurs'] ?? 0 ?></div>
        <div class="adm-stat-label">Lecteurs</div>
      </div>
      <div class="adm-stat-sub">
        <span class="adm-badge-green"><?= $stats['lecteurs_actifs'] ?? 0 ?> actifs</span>
      </div>
    </div>

    <div class="adm-stat-card">
      <div class="adm-stat-icon" style="background:#fef2f2;">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="22" height="22" stroke="#ef4444">
          <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
          <line x1="4" y1="22" x2="4" y2="15"/>
        </svg>
      </div>
      <div class="adm-stat-info">
        <div class="adm-stat-num"><?= $stats['nb_signalements'] ?? 0 ?></div>
        <div class="adm-stat-label">Signalements</div>
      </div>
      <div class="adm-stat-sub">
        <span class="adm-badge-red"><?= $stats['signalements_attente'] ?? 0 ?> non traités</span>
      </div>
    </div>

  </div>

  <!-- ── GRAPHIQUE + SIGNALEMENTS ── -->
  <div class="adm-dash-grid fade-up" style="transition-delay:.1s">

    <!-- Graphique -->
    <div class="adm-card">
      <div class="adm-card-header">
        <div>
          <div class="adm-card-title">Articles par mois</div>
          <div class="adm-card-sub">12 derniers mois</div>
        </div>
      </div>
      <div style="position:relative;height:220px;">
        <canvas id="admChart"></canvas>
      </div>
    </div>

    <!-- Signalements récents -->
    <div class="adm-card">
      <div class="adm-card-header">
        <div class="adm-card-title">Signalements récents</div>
        <a href="<?= path('admin','signalements') ?>" class="adm-card-link">Voir tout →</a>
      </div>
      <?php if (empty($derniersSignal)): ?>
        <p class="adm-empty-txt">Aucun signalement.</p>
      <?php else: ?>
        <?php foreach ($derniersSignal as $sig):
          $isTraite = $sig['statut'] === 'Traiter';
        ?>
        <div class="adm-signal-item">
          <div class="adm-signal-icon <?= $isTraite ? 'adm-signal-ok' : 'adm-signal-warn' ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="14" height="14">
              <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
              <line x1="4" y1="22" x2="4" y2="15"/>
            </svg>
          </div>
          <div class="adm-signal-info">
            <div class="adm-signal-title"><?= htmlspecialchars($sig['libelle']) ?></div>
            <div class="adm-signal-meta">
              Par <?= htmlspecialchars($sig['signaleur']) ?> ·
              <?= date('d/m/Y', strtotime($sig['date_creation'])) ?>
            </div>
          </div>
          <span class="adm-status-chip <?= $isTraite ? 'adm-status-actif' : 'adm-status-attente' ?>">
            <?= $isTraite ? 'Traité' : 'En attente' ?>
          </span>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>

  <!-- ── DERNIERS ARTICLES ── -->
  <div class="adm-card fade-up" style="transition-delay:.15s">
    <div class="adm-card-header">
      <div class="adm-card-title">Derniers articles</div>
      <a href="<?= path('admin','articles') ?>" class="adm-card-link">Voir tout →</a>
    </div>
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Article</th>
            <th>Auteur</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($derniersArts as $art):
            $sc = match($art['statut']) {
              'Actif'      => 'adm-status-actif',
              'En attente' => 'adm-status-attente',
              'Invalide'   => 'adm-status-invalide',
              default      => 'adm-status-actif'
            };
          ?>
          <tr>
            <td>
              <div class="adm-table-art">
                <div class="adm-table-art-img">
                  <img src="<?= !empty($art['image_p']) ? htmlspecialchars($art['image_p']) : 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=100&q=60' ?>" alt=""/>
                </div>
                <span><?= htmlspecialchars(strlen($art['libelle']) > 45 ? substr($art['libelle'],0,45).'…' : $art['libelle']) ?></span>
              </div>
            </td>
            <td><?= htmlspecialchars($art['auteur']) ?></td>
            <td><?= date('d/m/Y', strtotime($art['date_creation'])) ?></td>
            <td><span class="adm-status-chip <?= $sc ?>"><?= $art['statut'] ?></span></td>
            <td>
              <div class="adm-table-actions">
                <a href="<?= path('admin','article_detail',['id'=>$art['id']]) ?>" class="adm-tbl-btn adm-tbl-view">
                  <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="13" height="13">
                    <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
                  </svg>
                </a>
                <?php if ($art['statut'] !== 'Actif'): ?>
                <form method="POST" action="<?= path('admin','articles') ?>" style="display:inline">
                  <input type="hidden" name="post_action"  value="valider"/>
                  <input type="hidden" name="article_id"   value="<?= $art['id'] ?>"/>
                  <button type="submit" class="adm-tbl-btn adm-tbl-ok" title="Valider">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13">
                      <polyline points="20 6 9 17 4 12"/>
                    </svg>
                  </button>
                </form>
                <?php endif; ?>
                <?php if ($art['statut'] !== 'Invalide'): ?>
                <form method="POST" action="<?= path('admin','articles') ?>" style="display:inline">
                  <input type="hidden" name="post_action"  value="invalider"/>
                  <input type="hidden" name="article_id"   value="<?= $art['id'] ?>"/>
                  <button type="submit" class="adm-tbl-btn adm-tbl-warn" title="Invalider">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13">
                      <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                  </button>
                </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>window.admChartData = <?= json_encode($chartData) ?>;</script>