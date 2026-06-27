<!-- ════════ PAGE HEADER ════════ -->
<div class="page-header">
  <div class="page-header-inner">
    <div>
      <div class="breadcrumb">
        <a href="<?= path('lecteur','home') ?>">Accueil</a>
        <span class="breadcrumb-sep">›</span>
        <span>Contact</span>
      </div>
      <div class="page-header-title">Nous contacter</div>
      <div class="page-header-sub">Une question, une suggestion ? On vous répond rapidement.</div>
    </div>
  </div>
</div>

<!-- ════════ SECTION CONTACT ════════ -->
<section class="contact-section">
  <div class="contact-inner">

    <!-- ── Infos ── -->
    <div class="contact-info fade-up">

      <h2 class="contact-info-title">Parlons-nous</h2>
      <p class="contact-info-sub">
        L'équipe HorizonBlog est disponible pour répondre à toutes vos questions,
        suggestions ou demandes de partenariat.
      </p>

      <ul class="contact-cards">

        <li class="contact-card">
          <div class="contact-card-icon" style="background:#e6f7ee;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="#1a9e5c" stroke-width="2" stroke-linecap="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1
                       0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
          </div>
          <div>
            <div class="contact-card-label">E-mail</div>
            <div class="contact-card-val">contact@horizonblog.com</div>
          </div>
        </li>

        <li class="contact-card">
          <div class="contact-card-icon" style="background:#eef2ff;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="#4f6ef7" stroke-width="2" stroke-linecap="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                       19.79 19.79 0 0 1-8.63-3.07
                       19.5 19.5 0 0 1-6-6
                       19.79 19.79 0 0 1-3.07-8.67
                       A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72
                       12.84 12.84 0 0 0 .7 2.81
                       2 2 0 0 1-.45 2.11L8.09 9.91
                       a16 16 0 0 0 6 6l1.27-1.27
                       a2 2 0 0 1 2.11-.45
                       12.84 12.84 0 0 0 2.81.7
                       A2 2 0 0 1 22 16.92z"/>
            </svg>
          </div>
          <div>
            <div class="contact-card-label">Téléphone</div>
            <div class="contact-card-val">+221 77 000 00 00</div>
          </div>
        </li>

        <li class="contact-card">
          <div class="contact-card-icon" style="background:#fff7ed;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="#f97316" stroke-width="2" stroke-linecap="round">
              <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
          </div>
          <div>
            <div class="contact-card-label">Adresse</div>
            <div class="contact-card-val">Dakar, Sénégal</div>
          </div>
        </li>

      </ul>

      <!-- Réseaux sociaux -->
      <div class="contact-socials">
        <span class="contact-socials-label">Suivez-nous</span>
        <div class="contact-socials-links">

          <a href="#" class="contact-social-btn" aria-label="Twitter/X">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M18.244 2.25h3.308l-7.227 8.26
                       7.392 9.979H16.17l-5.214-6.817L4.99
                       20.489H1.68l7.73-8.835L1.254
                       2.25H8.08l4.713 6.231zm-1.161
                       16.02h1.833L7.084 4.126H5.117z"/>
            </svg>
          </a>

          <a href="#" class="contact-social-btn" aria-label="LinkedIn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2
                       2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2
                       9h4v12H2z"/>
              <circle cx="4" cy="4" r="2"/>
            </svg>
          </a>

          <a href="#" class="contact-social-btn" aria-label="Instagram">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
            </svg>
          </a>

        </div>
      </div>
    </div>

    <!-- ── Formulaire ── -->
    <div class="contact-form-wrap fade-up" style="transition-delay:.1s">
      <form class="contact-form" method="POST" action="">
        <input type="hidden" name="controller" value="lecteur"/>
        <input type="hidden" name="action" value="contact"/>

        <div class="cf-row">
          <div class="cf-field">
            <label for="cf-nom">Nom complet <span class="cf-req">*</span></label>
            <input type="text" id="cf-nom"
                   placeholder="Votre nom"
                   value=""/>
          </div>
          <div class="cf-field">
            <label for="cf-email">Adresse e-mail <span class="cf-req">*</span></label>
            <input type="email" id="cf-email"
                   placeholder="vous@exemple.com"
                   value=""/>
          </div>
        </div>

        <div class="cf-field">
          <label for="cf-sujet">Sujet <span class="cf-req">*</span></label>
          <select id="cf-sujet" required>
            <option value="" disabled>Choisissez un sujet…</option>
            <option value="question"   >Question générale</option>
            <option value="partenariat">Partenariat</option>
            <option value="suggestion" >Suggestion d'article</option>
            <option value="signalement">Signalement</option>
            <option value="autre"      >Autre</option>
          </select>
        </div>

        <div class="cf-field">
          <label for="cf-message">Message <span class="cf-req">*</span></label>
          <textarea id="cf-message" rows="6"
                    placeholder="Décrivez votre demande en détail…"
                    ></textarea>
        </div>


        <button type="submit" class="cf-submit">
          Envoyer le message
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <line x1="22" y1="2" x2="11" y2="13"/>
            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
          </svg>
        </button>

      </form>
    </div>

  </div>
</section>