<div class="adm-page">

  <div class="adm-page-header fade-up">
    <div>
      <h1 class="adm-page-title">Corbeille</h1>
      <p class="adm-page-sub"><?= $total ?> article<?= $total > 1 ? 's' : '' ?> supprimé<?= $total > 1 ? 's' : '' ?></p>
    </div>
  </div>

  <!-- Recherche -->
  <div class="adm-filters fade-up">
    <form method="GET" action="" class="adm-filter-search" style="margin-left:0">
      <input type="hidden" name="controller" value="admin"/>
      <input type="hidden" name="action"     value="corbeille"/>
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher…"/>
      <button type="submit">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
      </button>
    </form>
  </div>

  <!-- Table -->
  <div class="adm-card fade-up" style="transition-delay:.05s">
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr>
            <th>Article</th><th>Auteur</th><th>Supprimé le</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($articles)): ?>
            <tr><td colspan="4" class="adm-table-empty">La corbeille est vide.</td></tr>
          <?php else: ?>
          <?php foreach ($articles as $art):
            $imgAdm = !empty($art['image_p'])
                ? (str_starts_with($art['image_p'], 'http')
                    ? htmlspecialchars($art['image_p'])
                    : WEBROOT . htmlspecialchars($art['image_p']))
                : 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=100&q=60';
          ?>
          <tr>
            <td>
              <div class="adm-table-art">
                <div class="adm-table-art-img">
                  <img src="<?= $imgAdm ?>" alt=""/>
                </div>
                <span><?= htmlspecialchars(strlen($art['libelle'])>50?substr($art['libelle'],0,50).'…':$art['libelle']) ?></span>
              </div>
            </td>
            <td><?= htmlspecialchars($art['auteur']) ?></td>
            <td><?= date('d/m/Y', strtotime($art['date_dernier_modification'])) ?></td>
            <td>
              <div class="adm-table-actions">
                <!-- Restaurer -->
                <form method="POST" action="<?= path('admin','corbeille') ?>" style="display:inline">
                  <input type="hidden" name="post_action" value="restaurer"/>
                  <input type="hidden" name="article_id"  value="<?= $art['id'] ?>"/>
                  <button class="adm-tbl-btn adm-tbl-ok" title="Restaurer">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="13" height="13">
                      <polyline points="1 4 1 10 7 10"/>
                      <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                    </svg>
                  </button>
                </form>
                <!-- Supprimer définitivement -->
                <form method="POST" action="<?= path('admin','corbeille') ?>" style="display:inline"
                      onsubmit="return confirm('Supprimer définitivement cet article ? Action irréversible.')">
                  <input type="hidden" name="post_action" value="supprimer_def"/>
                  <input type="hidden" name="article_id"  value="<?= $art['id'] ?>"/>
                  <button class="adm-tbl-btn adm-tbl-del" title="Supprimer définitivement">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="13" height="13">
                      <polyline points="3 6 5 6 21 6"/>
                      <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    </svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
  <div class="adm-pagination fade-up">
    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
      <a href="<?= path('admin','corbeille',['page'=>$p,'q'=>$search]) ?>"
         class="adm-page-btn <?= $p===$page?'active':'' ?>"><?= $p ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>

</div>