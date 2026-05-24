<!-- ════════ BREADCRUMB ════════ -->
<style>
  /* ─── ARTICLES LIST PAGE ─── */
  .articles-page { background: #f0f5f2; min-height: 100vh; padding: 48px 0 80px; }

  /* ─── PAGE HEADER ─── */
  .page-header {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 32px 0;
    margin-bottom: 48px;
  }
  .page-header-inner {
    max-width: 1440px; margin: 0 auto; padding: 0 80px;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 16px;
  }
  .breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: .8rem; color: var(--gray);
  }
  .breadcrumb a { color: var(--gray); text-decoration: none; transition: color .2s; }
  .breadcrumb a:hover { color: var(--green); }
  .breadcrumb span { color: var(--green); font-weight: 600; }
  .breadcrumb-sep { color: #d1d5db; }
  .page-header-title { font-size: 1.6rem; font-weight: 800; color: var(--text); margin-top: 8px; }
  .page-header-sub { font-size: .88rem; color: var(--gray); margin-top: 4px; }
  .articles-count {
    background: var(--green); color: #fff;
    padding: 6px 18px; border-radius: 999px;
    font-size: .82rem; font-weight: 600;
  }

  /* ─── FILTERS BAR ─── */
  .filters-bar {
    max-width: 1440px; margin: 0 auto 40px; padding: 0 80px;
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
  }
  .filter-label { font-size: .82rem; font-weight: 600; color: var(--gray); margin-right: 4px; }
  .filter-chip {
    padding: 7px 18px; border-radius: 999px;
    border: 1.5px solid #d1d5db; background: #fff;
    font-size: .8rem; font-weight: 500; color: var(--text);
    cursor: pointer; font-family: 'Poppins', sans-serif;
    transition: all .2s;
  }
  .filter-chip:hover { border-color: var(--green); color: var(--green); background: var(--green-light); }
  .filter-chip.active { background: var(--green); color: #fff; border-color: var(--green); }
  .filter-search {
    margin-left: auto;
    display: flex; align-items: center; gap: 0;
    border: 1.5px solid #d1d5db; border-radius: 999px;
    overflow: hidden; background: #fff;
    transition: border-color .2s;
  }
  .filter-search:focus-within { border-color: var(--green); }
  .filter-search input {
    border: none; outline: none; padding: 8px 16px;
    font-size: .82rem; font-family: 'Poppins', sans-serif;
    background: transparent; color: var(--text); width: 220px;
  }
  .filter-search button {
    background: var(--green); border: none; padding: 8px 16px;
    cursor: pointer; color: #fff; display: flex; align-items: center;
    transition: background .2s;
  }
  .filter-search button:hover { background: var(--green-dark); }
  .filter-search button svg { width: 16px; height: 16px; stroke: #fff; }

  /* ─── GRID ─── */
  .articles-grid {
    max-width: 1440px; margin: 0 auto; padding: 0 80px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
  }

  /* ─── ARTICLE CARD ─── */
  .a-card {
    background: #fff; border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
    display: flex; flex-direction: column;
    transition: transform .25s, box-shadow .25s;
    cursor: pointer;
  }
  .a-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(26,158,92,.15);
  }
  .a-card-img {
    position: relative; height: 200px; overflow: hidden;
    background: #e5e7eb;
  }
  .a-card-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .5s ease;
  }
  .a-card:hover .a-card-img img { transform: scale(1.06); }
  .a-card-status {
    position: absolute; top: 12px; left: 12px;
    padding: 4px 12px; border-radius: 999px;
    font-size: .72rem; font-weight: 700; letter-spacing: .04em;
    text-transform: uppercase;
  }
  .status-actif    { background: var(--green); color: #fff; }
  .status-attente  { background: #f59e0b; color: #fff; }
  .status-invalide { background: #ef4444; color: #fff; }
  .status-valide   { background: #3b82f6; color: #fff; }
  .a-card-body { padding: 20px 22px; flex: 1; display: flex; flex-direction: column; }
  .a-card-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }
  .a-tag {
    padding: 3px 12px; border-radius: 999px;
    font-size: .72rem; font-weight: 600;
    background: var(--green-light); color: var(--green-dark);
    border: 1px solid var(--green-pale);
  }
  .a-tag-sec {
    background: #f3f4f6; color: var(--gray);
    border: 1px solid #e5e7eb;
  }
  .a-card-title {
    font-size: 1rem; font-weight: 700; color: var(--text);
    line-height: 1.4; margin-bottom: 10px;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
  }
  .a-card-desc {
    font-size: .82rem; color: var(--gray); line-height: 1.65;
    flex: 1; margin-bottom: 16px;
    display: -webkit-box; -webkit-line-clamp: 3;
    -webkit-box-orient: vertical; overflow: hidden;
  }
  .a-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 14px; border-top: 1px solid #f3f4f6;
  }
  .a-card-author { display: flex; align-items: center; gap: 8px; }
  .a-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: var(--green-light); color: var(--green-dark);
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; font-weight: 700; flex-shrink: 0;
  }
  .a-author-name { font-size: .78rem; font-weight: 600; color: var(--text); }
  .a-author-date { font-size: .72rem; color: var(--gray); }
  .a-card-meta { display: flex; align-items: center; gap: 10px; }
  .a-meta-item {
    display: flex; align-items: center; gap: 4px;
    font-size: .75rem; color: var(--gray);
  }
  .a-meta-item svg { width: 13px; height: 13px; stroke: var(--gray); }

  /* ─── CARD FEATURED (1ère carte grande) ─── */
  .a-card-featured {
    grid-column: span 2;
  }
  .a-card-featured .a-card-img { height: 280px; }
  .a-card-featured .a-card-title { font-size: 1.2rem; -webkit-line-clamp: 2; }
  .a-card-featured .a-card-desc { -webkit-line-clamp: 4; }

  /* ─── BOUTON LIRE ─── */
  .btn-lire {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--green); color: #fff;
    padding: 8px 18px; border-radius: 8px;
    font-size: .78rem; font-weight: 600;
    text-decoration: none; border: none; cursor: pointer;
    font-family: 'Poppins', sans-serif;
    transition: background .2s, transform .15s;
    white-space: nowrap;
  }
  .btn-lire:hover { background: var(--green-dark); transform: translateY(-1px); }
  .btn-lire svg { width: 14px; height: 14px; stroke: currentColor; }

  /* ─── PAGINATION ─── */
  .pagination {
    max-width: 1440px; margin: 48px auto 0; padding: 0 80px;
    display: flex; align-items: center; justify-content: center; gap: 8px;
  }
  .page-btn {
    width: 38px; height: 38px; border-radius: 8px;
    border: 1.5px solid #d1d5db; background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 600; color: var(--text);
    cursor: pointer; font-family: 'Poppins', sans-serif;
    transition: all .2s;
  }
  .page-btn:hover { border-color: var(--green); color: var(--green); background: var(--green-light); }
  .page-btn.active { background: var(--green); color: #fff; border-color: var(--green); }
  .page-btn svg { width: 16px; height: 16px; stroke: currentColor; }

  /* ─── FADE UP ─── */
  .fade-up { opacity: 0; transform: translateY(24px); transition: opacity .5s ease, transform .5s ease; }
  .fade-up.visible { opacity: 1; transform: none; }

  /* ─── RESPONSIVE ─── */
  @media (max-width: 1024px) {
    .page-header-inner, .filters-bar, .articles-grid, .pagination { padding: 0 40px; }
    .articles-grid { grid-template-columns: repeat(2, 1fr); }
    .a-card-featured { grid-column: span 2; }
  }
  @media (max-width: 640px) {
    .page-header-inner, .filters-bar, .articles-grid, .pagination { padding: 0 20px; }
    .articles-grid { grid-template-columns: 1fr; }
    .a-card-featured { grid-column: span 1; }
    .a-card-featured .a-card-img { height: 200px; }
    .filter-search { display: none; }
    .page-header-inner { flex-direction: column; align-items: flex-start; }
  }
</style>

<!-- ════════ PAGE HEADER ════════ -->
<div class="page-header">
  <div class="page-header-inner">
    <div>
      <div class="breadcrumb">
        <a href="?action=home">Accueil</a>
        <span class="breadcrumb-sep">›</span>
        <span>Articles</span>
      </div>
      <div class="page-header-title">Tous les articles</div>
      <div class="page-header-sub">Découvrez l'ensemble de nos publications</div>
    </div>
    <div class="articles-count">4 articles</div>
  </div>
</div>

<!-- ════════ FILTERS ════════ -->
<div class="filters-bar">
  <span class="filter-label">Filtrer :</span>
  <button class="filter-chip active">Tous</button>
  <button class="filter-chip">Publiés</button>
  <button class="filter-chip">En attente</button>
  <button class="filter-chip">Invalides</button>

  <div class="filter-search">
    <input type="text" placeholder="Rechercher un article..." id="searchInput"/>
    <button>
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
    </button>
  </div>
</div>

<!-- ════════ GRILLE ARTICLES ════════ -->
<div class="articles-grid" id="articlesGrid">

  <!-- CARTE 1 — FEATURED -->
  <div class="a-card a-card-featured fade-up" data-statut="Actif" data-title="L'avenir de l'intelligence artificielle en Afrique">
    <div class="a-card-img">
      <img src="https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=900&q=85" alt="IA en Afrique"/>
      <span class="a-card-status status-actif">Publié</span>
    </div>
    <div class="a-card-body">
      <div class="a-card-tags">
        <span class="a-tag">Technologie</span>
        <span class="a-tag a-tag-sec">Culture</span>
      </div>
      <div class="a-card-title">L'avenir de l'intelligence artificielle en Afrique</div>
      <div class="a-card-desc">Un regard sur le développement de l'intelligence artificielle sur le continent africain. Depuis quelques années, l'IA s'impose comme un levier de transformation majeur, capable de révolutionner des secteurs aussi variés que la santé, l'agriculture et l'éducation.</div>
      <div class="a-card-footer">
        <div class="a-card-author">
          <div class="a-avatar">IB</div>
          <div>
            <div class="a-author-name">Ibrahima Sow</div>
            <div class="a-author-date">01 avril 2024</div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
          <div class="a-card-meta">
            <div class="a-meta-item">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
              1 243
            </div>
            <div class="a-meta-item">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              12
            </div>
          </div>
          <a href="?action=article&id=1" class="btn-lire">
            Lire
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- CARTE 2 -->
  <div class="a-card fade-up" data-statut="Actif" data-title="Les élections en 2024" style="transition-delay:.1s">
    <div class="a-card-img">
      <img src="https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?w=700&q=85" alt="Élections"/>
      <span class="a-card-status status-actif">Publié</span>
    </div>
    <div class="a-card-body">
      <div class="a-card-tags">
        <span class="a-tag">Politique</span>
      </div>
      <div class="a-card-title">Les élections en 2024</div>
      <div class="a-card-desc">Analyse des grands enjeux politiques des élections africaines de 2024 et ce qu'elles révèlent des dynamiques démocratiques du continent.</div>
      <div class="a-card-footer">
        <div class="a-card-author">
          <div class="a-avatar" style="background:#fde8e8;color:#c81e1e">AM</div>
          <div>
            <div class="a-author-name">Aminata Baldé</div>
            <div class="a-author-date">05 avril 2024</div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
          <div class="a-card-meta">
            <div class="a-meta-item">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
              876
            </div>
            <div class="a-meta-item">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              7
            </div>
          </div>
          <a href="?action=article&id=2" class="btn-lire">
            Lire
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- CARTE 3 -->
  <div class="a-card fade-up" data-statut="En attente" data-title="La CAN 2024 : bilan et perspectives" style="transition-delay:.15s">
    <div class="a-card-img">
      <img src="https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=700&q=85" alt="CAN 2024"/>
      <span class="a-card-status status-attente">En attente</span>
    </div>
    <div class="a-card-body">
      <div class="a-card-tags">
        <span class="a-tag">Sport</span>
      </div>
      <div class="a-card-title">La CAN 2024 : bilan et perspectives</div>
      <div class="a-card-desc">Retour sur la Coupe d'Afrique des Nations et ce qu'elle révèle du football africain, entre passion populaire et enjeux économiques.</div>
      <div class="a-card-footer">
        <div class="a-card-author">
          <div class="a-avatar">IB</div>
          <div>
            <div class="a-author-name">Ibrahima Sow</div>
            <div class="a-author-date">10 avril 2024</div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
          <div class="a-card-meta">
            <div class="a-meta-item">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
              542
            </div>
            <div class="a-meta-item">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              3
            </div>
          </div>
          <a href="?action=article&id=3" class="btn-lire">
            Lire
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- CARTE 4 -->
  <div class="a-card fade-up" data-statut="Invalide" data-title="Médecine traditionnelle et modernité" style="transition-delay:.2s">
    <div class="a-card-img">
      <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=700&q=85" alt="Médecine"/>
      <span class="a-card-status status-invalide">Invalide</span>
    </div>
    <div class="a-card-body">
      <div class="a-card-tags">
        <span class="a-tag">Santé</span>
      </div>
      <div class="a-card-title">Médecine traditionnelle et modernité</div>
      <div class="a-card-desc">Comment la médecine traditionnelle africaine s'intègre dans les systèmes de santé modernes pour offrir des solutions complémentaires et accessibles.</div>
      <div class="a-card-footer">
        <div class="a-card-author">
          <div class="a-avatar" style="background:#e8f0fe;color:#1a56db">OD</div>
          <div>
            <div class="a-author-name">Oumar Diop</div>
            <div class="a-author-date">12 avril 2024</div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
          <div class="a-card-meta">
            <div class="a-meta-item">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
              318
            </div>
            <div class="a-meta-item">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              1
            </div>
          </div>
          <a href="?action=article&id=4" class="btn-lire">
            Lire
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- ════════ PAGINATION ════════ -->
<div class="pagination">
  <button class="page-btn">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round"><path d="M15 18l-6-6 6-6"/></svg>
  </button>
  <button class="page-btn active">1</button>
  <button class="page-btn">2</button>
  <button class="page-btn">3</button>
  <button class="page-btn">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round"><path d="M9 18l6-6-6-6"/></svg>
  </button>
</div>

<script>
/* ── FADE UP ── */
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: .1 });
document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));

/* ── FILTER BY STATUT ── */
function filterArticles(statut, btn) {
  document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
  btn.classList.add('active');

  document.querySelectorAll('.a-card').forEach(card => {
    const match = statut === 'tous' || card.dataset.statut === statut;
    card.style.display = match ? '' : 'none';
    if (match) card.classList.add('visible');
  });

  updateFeatured();
}

/* ── SEARCH ── */
function searchArticles() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('.a-card').forEach(card => {
    const title = card.dataset.title.toLowerCase();
    card.style.display = title.includes(q) ? '' : 'none';
  });
  updateFeatured();
}

/* ── FEATURED : premier visible = featured ── */
function updateFeatured() {
  document.querySelectorAll('.a-card').forEach(c => c.classList.remove('a-card-featured'));
  const first = [...document.querySelectorAll('.a-card')].find(c => c.style.display !== 'none');
  if (first) first.classList.add('a-card-featured');
}

/* ── PAGINATION ── */
document.querySelectorAll('.page-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    if (this.querySelector('svg')) return;
    document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
  });
});
</script>