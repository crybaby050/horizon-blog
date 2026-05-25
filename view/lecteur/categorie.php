<!-- ════════ PAGE HEADER ════════ -->
<div class="page-header">
  <div class="page-header-inner">
    <div>
      <div class="breadcrumb">
        <a href="<?= path('lecteur','home') ?>">Accueil</a>
        <span class="breadcrumb-sep">›</span>
        <span>Catégories</span>
      </div>
      <div class="page-header-title">Toutes les catégories</div>
      <div class="page-header-sub">Explorez nos contenus par thématique</div>
    </div>
    <div class="articles-count">5 catégories</div>
  </div>
</div>

<!-- ════════ HERO CATÉGORIES ════════ -->
<section class="categ-hero">
  <div class="categ-hero-inner">

    <!-- Grande carte gauche -->
    <div class="categ-hero-main">
      <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=1200&q=85" alt="Technologie"/>
      <div class="categ-hero-overlay"></div>
      <div class="categ-hero-content">
        <span class="categ-hero-badge" style="background:linear-gradient(135deg,#6366f1,#3b82f6);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          Technologie
        </span>
        <h2>Technologie</h2>
        <p>Intelligence artificielle, innovation numérique et transformation digitale du continent africain.</p>
        <div class="categ-hero-meta">
          <span>12 articles</span>
          <a href="<?= path('lecteur','article',['statut'=>'Actif']) ?>" class="categ-hero-btn">
            Voir les articles
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
      </div>
    </div>

    <!-- Petites cartes droite -->
    <div class="categ-hero-side">

      <div class="categ-hero-small">
        <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=700&q=85" alt="Sport"/>
        <div class="categ-hero-overlay"></div>
        <div class="categ-hero-content">
          <span class="categ-hero-badge" style="background:#7c3aed;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10"/><path d="M2 12h20"/></svg>
            Sport
          </span>
          <h3>Sport</h3>
          <div class="categ-hero-meta">
            <span>8 articles</span>
            <a href="#" class="categ-hero-btn categ-hero-btn-sm">Voir →</a>
          </div>
        </div>
      </div>

      <div class="categ-hero-small">
        <img src="https://images.unsplash.com/photo-1494172961521-33799ddd43a5?w=700&q=85" alt="Politique"/>
        <div class="categ-hero-overlay"></div>
        <div class="categ-hero-content">
          <span class="categ-hero-badge" style="background:#dc2626;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            Politique
          </span>
          <h3>Politique</h3>
          <div class="categ-hero-meta">
            <span>5 articles</span>
            <a href="#" class="categ-hero-btn categ-hero-btn-sm">Voir →</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ════════ GRILLE CATÉGORIES ════════ -->
<section class="categ-list-section">
  <div class="categ-list-inner">

    <div class="categ-list-header fade-up">
      <h2>Toutes les thématiques</h2>
      <p>Chaque catégorie regroupe des articles soigneusement sélectionnés par notre équipe.</p>
    </div>

    <div class="categ-cards-grid">

      <!-- Technologie -->
      <div class="categ-big-card fade-up">
        <div class="categ-big-img">
          <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&q=85" alt="Technologie"/>
          <div class="categ-big-overlay"></div>
        </div>
        <div class="categ-big-body">
          <div class="categ-big-icon" style="background:linear-gradient(135deg,#6366f1,#3b82f6);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          </div>
          <div class="categ-big-info">
            <h3>Technologie</h3>
            <p>IA, innovation, digital et transformation numérique du continent africain.</p>
            <div class="categ-big-stats">
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                12 articles
              </span>
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                4.2k vues
              </span>
            </div>
            <a href="#" class="categ-big-btn">
              Explorer
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Sport -->
      <div class="categ-big-card fade-up" style="transition-delay:.08s">
        <div class="categ-big-img">
          <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&q=85" alt="Sport"/>
          <div class="categ-big-overlay"></div>
        </div>
        <div class="categ-big-body">
          <div class="categ-big-icon" style="background:#7c3aed;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
          </div>
          <div class="categ-big-info">
            <h3>Sport</h3>
            <p>Football, basketball, athlétisme et toutes les actualités sportives africaines.</p>
            <div class="categ-big-stats">
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                8 articles
              </span>
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                3.1k vues
              </span>
            </div>
            <a href="#" class="categ-big-btn">
              Explorer
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Politique -->
      <div class="categ-big-card fade-up" style="transition-delay:.16s">
        <div class="categ-big-img">
          <img src="https://images.unsplash.com/photo-1494172961521-33799ddd43a5?w=800&q=85" alt="Politique"/>
          <div class="categ-big-overlay"></div>
        </div>
        <div class="categ-big-body">
          <div class="categ-big-icon" style="background:#dc2626;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          </div>
          <div class="categ-big-info">
            <h3>Politique</h3>
            <p>Élections, gouvernance, diplomatie et vie politique sur le continent.</p>
            <div class="categ-big-stats">
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                5 articles
              </span>
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                2.8k vues
              </span>
            </div>
            <a href="#" class="categ-big-btn">
              Explorer
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Culture -->
      <div class="categ-big-card fade-up" style="transition-delay:.24s">
        <div class="categ-big-img">
          <img src="https://images.unsplash.com/photo-1526749837599-b4eba9fd855e?w=800&q=85" alt="Culture"/>
          <div class="categ-big-overlay"></div>
        </div>
        <div class="categ-big-body">
          <div class="categ-big-icon" style="background:linear-gradient(135deg,#f59e0b,#ea580c);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77"/></svg>
          </div>
          <div class="categ-big-info">
            <h3>Culture</h3>
            <p>Art, musique, littérature et patrimoine culturel africain à travers les âges.</p>
            <div class="categ-big-stats">
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                7 articles
              </span>
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                1.9k vues
              </span>
            </div>
            <a href="#" class="categ-big-btn">
              Explorer
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          </div>
        </div>
      </div>

      <!-- Santé -->
      <div class="categ-big-card fade-up" style="transition-delay:.32s">
        <div class="categ-big-img">
          <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=800&q=85" alt="Santé"/>
          <div class="categ-big-overlay"></div>
        </div>
        <div class="categ-big-body">
          <div class="categ-big-icon" style="background:#16a34a;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          </div>
          <div class="categ-big-info">
            <h3>Santé</h3>
            <p>Médecine, bien-être, santé publique et avancées médicales en Afrique.</p>
            <div class="categ-big-stats">
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                4 articles
              </span>
              <span class="categ-stat">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                1.4k vues
              </span>
            </div>
            <a href="#" class="categ-big-btn">
              Explorer
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>