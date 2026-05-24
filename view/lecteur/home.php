<!-- Mobile drawer -->
<div class="mobile-drawer" id="mobileDrawer">
  <a href="#" class="active">Acceuil</a>
  <a href="#">Article</a>
  <a href="#">Catégorie</a>
  <a href="#">Contact</a>
  <div class="mobile-drawer-auth">
    <button class="mobile-btn-s">S'inscrire</button>
    <button class="mobile-btn-c">Se Connecter</button>
  </div>
</div>

<!-- ════════ HERO ════════ -->
<div class="hero">
  <div class="hero-bg" id="heroBg"></div>
  <button class="hero-arrow left" onclick="changeHero(-1)">&#8249;</button>
  <button class="hero-arrow right" onclick="changeHero(1)">&#8250;</button>

  <div class="hero-content">
    <div class="hero-text">
      <h1 id="heroTitle">
        <?= htmlspecialchars(!empty($articles) ? $articles[0]['libelle'] : 'Bienvenue sur HorizonBlog') ?>
      </h1>
      <p id="heroDesc">
        <?= htmlspecialchars(!empty($articles) ? $articles[0]['description'] : '') ?>
      </p>
      <a href="?action=article&id=<?= !empty($articles) ? $articles[0]['id'] : '#' ?>" class="btn-lire-hero" id="btnLireHero">
        Lire l'article
        <span class="book-icon-wrap">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
        </span>
      </a>
    </div>

    <!-- Info card : affiche l'article actif du slider -->
    <?php if(!empty($articles)): $first = $articles[0]; ?>
    <div class="hero-card">
      <div class="card-arc">
        <svg width="100" height="100" viewBox="0 0 100 100" fill="none">
          <circle cx="80" cy="20" r="55" stroke="#1a9e5c" stroke-width="3.5" fill="none" opacity=".4"/>
          <circle cx="68" cy="30" r="38" stroke="#1a9e5c" stroke-width="3.5" fill="none" opacity=".65"/>
        </svg>
      </div>

      <img class="avatar-img" src="https://i.pravatar.cc/80?img=33" alt="auteur"/>

      <div class="meta-label">Auteur</div>
      <div class="meta-val" id="cardAuteur">
        <?= htmlspecialchars($first['auteur'] ?? 'Inconnu') ?>
      </div>

      <div class="meta-label">Date de publication</div>
      <div class="meta-val" id="cardDate">
        <?= date('d/m/Y', strtotime($first['date_creation'])) ?>
      </div>

      <div class="meta-section-title">Categorie</div>
      <div class="tags-wrap" id="cardTags">
        <?php foreach($first['categories'] as $cat): ?>
          <span class="ctag"><?= htmlspecialchars($cat['libelle']) ?></span>
        <?php endforeach; ?>
      </div>

      <div class="card-vc">
        <span class="card-vc-c"></span>
        <span class="card-vc-c"></span>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Dots générés dynamiquement selon le nombre d'articles -->
  <div class="hero-dots" id="heroDots">
    <?php foreach($articles as $index => $article): ?>
      <button class="hero-dot <?= $index === 0 ? 'active' : '' ?>" onclick="goHero(<?= $index ?>)"></button>
    <?php endforeach; ?>
  </div>
</div>

<!-- ════════ ARTICLES DE LA SEMAINE ════════ -->
<section class="articles-section">
  <div class="bg-circ" style="width:220px;height:220px;top:-60px;left:200px;animation:fa 7s ease-in-out infinite;opacity:.85;"></div>
  <div class="bg-circ" style="width:140px;height:140px;top:10px;right:80px;animation:fb 9s ease-in-out infinite;opacity:.7;"></div>
  <div class="bg-circ" style="width:170px;height:170px;bottom:-40px;left:80px;animation:fc 8s ease-in-out infinite;opacity:.75;"></div>
  <div class="bg-circ" style="width:110px;height:110px;bottom:80px;right:240px;animation:fd 6s ease-in-out infinite;opacity:.6;"></div>
  <div class="bg-circ" style="width:80px;height:80px;top:55%;left:55%;animation:fa 10s ease-in-out infinite reverse;opacity:.5;"></div>

  <div class="container" style="position:relative;z-index:2;">
    <div class="sec-title fade-up">Articles de la Semaine</div>
    <div class="sec-sub fade-up">Découvrez les derniers articles ajouter en cours de semaine</div>

    <div class="slider-outer">
      <button class="slider-arrow-btn prev" onclick="slideArticles(-1)">&#8249;</button>
      <button class="slider-arrow-btn next" onclick="slideArticles(1)">&#8250;</button>

      <div class="slider-viewport">
        <div class="slider-track" id="articleTrack">

          <?php foreach($articles as $art): ?>
          <div class="flip-card">
            <div class="flip-inner">
              <div class="flip-front">
                <img src="<?= htmlspecialchars($art['image_p'] ?? 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=700&q=80') ?>"
                     alt="<?= htmlspecialchars($art['libelle']) ?>"/>
                <div class="flip-front-overlay">
                  <h3><?= htmlspecialchars($art['libelle']) ?></h3>
                  <a href="?action=article&id=<?= $art['id'] ?>" class="btn-read">
                    Lire l'article
                    <span class="book-chip"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
                  </a>
                </div>
              </div>
              <div class="flip-back">
                <h3><?= htmlspecialchars($art['libelle']) ?></h3>
                <p><?= htmlspecialchars($art['description']) ?></p>
                <a href="?action=article&id=<?= $art['id'] ?>" class="btn-read">
                  Lire l'article
                  <span class="book-chip"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
                </a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>

        </div><!-- /slider-track -->
      </div><!-- /slider-viewport -->

      <div class="sdots" id="sdots"></div>
    </div><!-- /slider-outer -->

    <div style="text-align:center;margin-top:12px;">
      <a href="?action=articles" class="btn-see-all">
        See All Articles
        <span class="book-chip"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
      </a>
    </div>
  </div>
</section>

<!-- ════════ CATÉGORIE ════════ -->
<section class="categ-section">
  <div class="categ-deco" style="width:90px;height:90px;top:16%;left:-20px;opacity:.9;"></div>
  <div class="categ-deco" style="width:140px;height:140px;top:38%;left:44%;transform:translateX(-50%);opacity:1;"></div>
  <div class="categ-deco" style="width:100px;height:100px;bottom:10%;right:-20px;opacity:.9;"></div>

  <div class="container" style="position:relative;z-index:2;">
    <div class="sec-title fade-up">Catégorie</div>
    <div class="sec-sub fade-up">Une liste de catégorie qui donne de la diversité à nos contenu</div>

    <div class="categ-grid fade-up">
      <!-- Sport -->
      <div class="categ-card">
        <img src="https://images.unsplash.com/photo-1541252260730-0412e8e2108e?w=900&q=80" alt="Sport"/>
        <div class="categ-overlay"></div>
        <div class="categ-badge" style="background:#7c3aed;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
          Sport
        </div>
        <a href="?action=categorie&id=3" class="categ-link">
          Articles associés
          <span class="book-chip"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
        </a>
      </div>

      <!-- Food -->
      <div class="categ-card">
        <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=900&q=80" alt="Food"/>
        <div class="categ-overlay"></div>
        <div class="categ-badge" style="background:linear-gradient(135deg,#f59e0b,#ea580c);">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
          Food
        </div>
        <a href="?action=categorie&id=4" class="categ-link">
          Articles associés
          <span class="book-chip"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
        </a>
      </div>

      <!-- Animals -->
      <div class="categ-card">
        <img src="https://images.unsplash.com/photo-1567431045634-a4a06f0bad52?w=900&q=80" alt="Animals"/>
        <div class="categ-overlay"></div>
        <div class="categ-badge" style="background:#22c55e;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
          Animals
        </div>
        <a href="?action=categorie&id=5" class="categ-link">
          Articles associés
          <span class="book-chip"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
        </a>
      </div>

      <!-- Technology -->
      <div class="categ-card">
        <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=900&q=80" alt="Technology"/>
        <div class="categ-overlay"></div>
        <div class="categ-badge" style="background:linear-gradient(135deg,#6366f1,#3b82f6);">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          Technology
        </div>
        <a href="?action=categorie&id=1" class="categ-link">
          Articles associés
          <span class="book-chip"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
        </a>
      </div>
    </div>

    <div style="text-align:center;margin-top:36px;">
      <a href="?action=categories" class="btn-see-all">
        See All Catégories
        <span class="book-chip"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg></span>
      </a>
    </div>
  </div>
</section>

<!-- ════════ REJOIGNEZ-NOUS ════════ -->
<section class="join-section">
  <div class="join-grid">
    <div class="join-img-panel">
      <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1000&q=90" alt="équipe de blogueurs"/>
      <div class="join-avatars">
        <img src="https://i.pravatar.cc/80?img=11" alt="a1"/>
        <img src="https://i.pravatar.cc/80?img=22" alt="a2"/>
        <img src="https://i.pravatar.cc/80?img=44" alt="a3"/>
        <div class="join-avatars-count">+2k</div>
        <span class="join-avatars-label">Auteurs actifs</span>
      </div>
      <div class="join-img-stat">
        <div class="join-img-stat-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
        </div>
        <div>
          <div class="join-img-stat-num">18 k+</div>
          <div class="join-img-stat-label">Articles publiés</div>
        </div>
      </div>
    </div>

    <div class="join-text-panel">
      <div class="join-pill-label">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Rejoignez-nous
      </div>
      <h2>Devenez auteur et partagez votre passion</h2>
      <p>Publiez vos articles, touchez des milliers de lecteurs et faites partie d'une communauté passionnée. Votre voix mérite d'être entendue.</p>
      <div class="join-features">
        <div class="join-feature-item">
          <div class="join-feature-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2.5" stroke-linecap="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
          </div>
          <p><strong>Éditeur simple et puissant</strong>Rédigez et formatez vos articles en quelques clics, sans aucune compétence technique.</p>
        </div>
        <div class="join-feature-item">
          <div class="join-feature-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2.5" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
          </div>
          <p><strong>Audience déjà constituée</strong>Profitez de nos 95 000 lecteurs mensuels dès votre première publication.</p>
        </div>
        <div class="join-feature-item">
          <div class="join-feature-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2.5" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          </div>
          <p><strong>Statistiques en temps réel</strong>Suivez vues, lectures et engagement sur chacun de vos articles.</p>
        </div>
      </div>
      <div class="join-cta-row">
        <button class="join-cta-primary">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
          S'inscrire gratuitement
        </button>
        <button class="join-cta-secondary">Se Connecter</button>
      </div>
    </div>
  </div>
</section>

<!-- ════════ FOOTER ════════ -->
<footer>
  <div class="footer-inner">
    <div>
      <a href="#" class="flogo">
        <svg width="30" height="30" viewBox="0 0 32 32" fill="none">
          <path d="M16 4C10 4 5 9 5 15.5c0 3.2 1.6 5.9 4.2 7.8 1.1.8 2.6-.3 2.1-1.6-1-2.8-1-5.8 1.2-8 3.2-3.2 8.5-3.2 11.8-1 1 .6 2.2-.3 1.6-1.5C24 9 20.3 4 16 4z" fill="#1a9e5c"/>
          <path d="M8.5 23.5c0 0 2.2-2.2 5.5-2.2s5.5 1.2 5.5 3.2c0 1.6-1.1 2.2-2.2 2.2-3.3 0-8.8-3.2-8.8-3.2z" fill="#157a47"/>
        </svg>
        <span class="flogo-text"><span class="fh">Horizon</span><span class="fb">Blog</span></span>
      </a>
      <p>A digital product agency focusing on branding, UI/UX design, and web development for forward-thinking companies.</p>
      <div class="social-row">
        <a href="#" class="sico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
        <a href="#" class="sico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
        <a href="#" class="sico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".5" fill="currentColor" stroke="none"/></svg></a>
        <a href="#" class="sico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
      </div>
    </div>
    <div>
      <h4>Legal</h4>
      <a href="#">Terms &amp; conditions</a>
      <a href="#">Privacy Policy</a>
    </div>
    <div>
      <h4>Company</h4>
      <a href="#">home</a>
      <a href="#">service</a>
      <a href="#">a propos</a>
      <a href="#">Contactez-nous</a>
    </div>
  </div>
  <div class="footer-divider">© 2026 HorizonBlog. Tous droits réservés.</div>
</footer>

<!-- ════════ JS ════════ -->
<script>
/* ── HERO : données injectées depuis PHP ── */
const slides = <?php echo json_encode(array_map(function($article) {
    return [
        'id'         => $article['id'],
        'title'      => $article['libelle'],
        'desc'       => $article['description'],
        'bg'         => !empty($article['image_p'])
                            ? $article['image_p']
                            : 'https://images.unsplash.com/photo-1570481662006-a3a1374699e8?w=1800&q=85',
        'auteur'     => $article['auteur'] ?? 'Inconnu',
        'date'       => date('d/m/Y', strtotime($article['date_creation'])),
        'categories' => array_column($article['categories'], 'libelle')
    ];
}, $articles)); ?>;

let hi = 0, htimer;
const heroBg    = document.getElementById('heroBg');
const heroTitle = document.getElementById('heroTitle');
const heroDesc  = document.getElementById('heroDesc');
heroBg.style.transition    = 'opacity .45s';
heroTitle.style.transition = 'opacity .4s';
heroDesc.style.transition  = 'opacity .4s';
heroBg.style.backgroundImage = `url('${slides[0].bg}')`;

function goHero(i) {
  clearInterval(htimer);
  hi = ((i % slides.length) + slides.length) % slides.length;
  updateHero();
  startHeroTimer();
}
function changeHero(d) { goHero(hi + d); }

function updateHero() {
  const s = slides[hi];
  heroBg.style.opacity = '0';
  heroTitle.style.opacity = '0';
  heroDesc.style.opacity  = '0';

  setTimeout(() => {
    // Texte + fond
    heroBg.style.backgroundImage = `url('${s.bg}')`;
    heroTitle.textContent = s.title;
    heroDesc.textContent  = s.desc;

    // Lien "Lire l'article"
    document.getElementById('btnLireHero').href = `?action=article&id=${s.id}`;

    // Carte info
    document.getElementById('cardAuteur').textContent = s.auteur;
    document.getElementById('cardDate').textContent   = s.date;
    const tagsWrap = document.getElementById('cardTags');
    tagsWrap.innerHTML = s.categories.map(c =>
      `<span class="ctag">${c}</span>`
    ).join('');

    heroBg.style.opacity    = '1';
    heroTitle.style.opacity = '1';
    heroDesc.style.opacity  = '1';
  }, 280);

  document.querySelectorAll('.hero-dot').forEach((d, i) =>
    d.classList.toggle('active', i === hi)
  );
}

function startHeroTimer() { htimer = setInterval(() => changeHero(1), 5500); }
startHeroTimer();
setTimeout(() => heroBg.classList.add('zoomed'), 100);

/* ── ARTICLE SLIDER (infinite loop) ── */
const track = document.getElementById('articleTrack');
const dotsEl = document.getElementById('sdots');

function getVisible() {
  if (window.innerWidth <= 640)  return 1;
  if (window.innerWidth <= 1024) return 2;
  return 3;
}
let VISIBLE = getVisible();
const ORIG  = [...track.children];
const TOTAL = ORIG.length;
let GROUPS  = Math.ceil(TOTAL / VISIBLE);
let OFFSET  = VISIBLE;
let aPos    = 0;

function buildDots() {
  dotsEl.innerHTML = '';
  GROUPS = Math.ceil(TOTAL / VISIBLE);
  for (let i = 0; i < GROUPS; i++) {
    const d = document.createElement('button');
    d.className = 'sdot' + (i === 0 ? ' active' : '');
    d.onclick = () => gotoGroup(i);
    dotsEl.appendChild(d);
  }
}

function cloneForInfinite() {
  while (track.children.length > TOTAL) track.removeChild(track.firstChild);
  while (track.children.length > TOTAL) track.removeChild(track.lastChild);
  for (let i = TOTAL - VISIBLE; i < TOTAL; i++)
    track.insertBefore(ORIG[i].cloneNode(true), track.firstChild);
  for (let i = 0; i < VISIBLE; i++)
    track.appendChild(ORIG[i].cloneNode(true));
  OFFSET = VISIBLE;
}

function rebuildSlider() {
  aPos = 0;
  track.innerHTML = '';
  ORIG.forEach(c => track.appendChild(c.cloneNode(true)));
  cloneForInfinite();
  buildDots();
  applyPos(true);
}

buildDots();
cloneForInfinite();

function cardW() {
  const gap = 28;
  return track.children[0].offsetWidth + gap;
}
function applyPos(instant) {
  const x = (aPos + OFFSET) * cardW();
  track.style.transition = instant ? 'none' : 'transform .5s cubic-bezier(.4,0,.2,1)';
  track.style.transform  = `translateX(-${x}px)`;
}
applyPos(true);

let resizeTimer;
window.addEventListener('resize', () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(() => {
    const newVis = getVisible();
    if (newVis !== VISIBLE) { VISIBLE = newVis; rebuildSlider(); }
    else applyPos(true);
  }, 150);
});

function slideArticles(dir) {
  aPos += dir;
  applyPos(false);
  track.addEventListener('transitionend', snapCheck, { once: true });
  updateDots();
}
function gotoGroup(g)  { aPos = g; applyPos(false); updateDots(); }
function snapCheck() {
  if (aPos < 0)        { aPos = GROUPS - 1; applyPos(true); }
  else if (aPos >= GROUPS) { aPos = 0; applyPos(true); }
}
function updateDots() {
  const norm = ((aPos % GROUPS) + GROUPS) % GROUPS;
  document.querySelectorAll('.sdot').forEach((d, i) =>
    d.classList.toggle('active', i === norm)
  );
}

/* ── MOBILE MENU ── */
function toggleMenu() {
  const btn    = document.getElementById('hamburger');
  const drawer = document.getElementById('mobileDrawer');
  btn.classList.toggle('open');
  drawer.classList.toggle('open');
  document.body.style.overflow = drawer.classList.contains('open') ? 'hidden' : '';
}
document.querySelectorAll('.mobile-drawer a').forEach(a => {
  a.addEventListener('click', () => {
    document.getElementById('hamburger').classList.remove('open');
    document.getElementById('mobileDrawer').classList.remove('open');
    document.body.style.overflow = '';
  });
});

/* ── SCROLL FADE ── */
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: .1 });
document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
</script>