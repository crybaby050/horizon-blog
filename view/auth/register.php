<?php
  $pageTitle = 'Inscription';
  $leftTitle = 'Rejoignez-nous gratuitement';
  $leftDesc  = 'Créez votre compte lecteur en quelques secondes et commencez à explorer les meilleurs articles de HorizonBlog.';
?>

<div class="auth-header">
  <div class="auth-header-icon">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="22" height="22" stroke="#1a9e5c">
      <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
      <circle cx="8.5" cy="7" r="4"/>
      <line x1="20" y1="8" x2="20" y2="14"/>
      <line x1="23" y1="11" x2="17" y2="11"/>
    </svg>
  </div>
  <h1>Créer un compte</h1>
  <p>Rejoignez la communauté HorizonBlog</p>
</div>

<?php if (!empty($error)): ?>
  <div class="auth-alert auth-alert-err">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15" stroke="#dc2626">
      <circle cx="12" cy="12" r="10"/>
      <line x1="12" y1="8" x2="12" y2="12"/>
      <line x1="12" y1="16" x2="12.01" y2="16"/>
    </svg>
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
  <div class="auth-alert auth-alert-ok">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="15" height="15" stroke="#0f6e40">
      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
      <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <?= htmlspecialchars($success) ?>
  </div>
<?php endif; ?>

<form method="POST" action="" class="auth-form">
  <input type="hidden" name="controller" value="auth"/>
  <input type="hidden" name="action"     value="register"/>

  <!-- Nom + Prénom -->
  <div class="auth-row">
    <div class="auth-field">
      <label for="nom">Nom <span class="auth-req">*</span></label>
      <div class="auth-input-wrap">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#9ca3af">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          <circle cx="12" cy="7" r="4"/>
        </svg>
        <input type="text" id="nom" name="nom" required
               placeholder="Votre nom"
               value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"/>
      </div>
    </div>
    <div class="auth-field">
      <label for="prenom">Prénom <span class="auth-req">*</span></label>
      <div class="auth-input-wrap">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#9ca3af">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
          <circle cx="12" cy="7" r="4"/>
        </svg>
        <input type="text" id="prenom" name="prenom" required
               placeholder="Votre prénom"
               value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"/>
      </div>
    </div>
  </div>

  <!-- Email -->
  <div class="auth-field">
    <label for="email">Adresse e-mail <span class="auth-req">*</span></label>
    <div class="auth-input-wrap">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#9ca3af">
        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
        <polyline points="22,6 12,13 2,6"/>
      </svg>
      <input type="email" id="email" name="email" required
             placeholder="vous@exemple.com"
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"/>
    </div>
  </div>

  <!-- Mot de passe -->
  <div class="auth-field">
    <label for="mot_de_passe">Mot de passe <span class="auth-req">*</span></label>
    <div class="auth-input-wrap">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#9ca3af">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
      </svg>
      <input type="password" id="mot_de_passe" name="mot_de_passe" required
             placeholder="Minimum 6 caractères"/>
      <button type="button" class="auth-toggle-pwd" onclick="togglePassword('mot_de_passe', this)" tabindex="-1">
        <svg class="eye-show" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15" stroke="#9ca3af">
          <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
        </svg>
        <svg class="eye-hide" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15" stroke="#9ca3af" style="display:none">
          <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
          <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
          <line x1="1" y1="1" x2="23" y2="23"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Confirmer mot de passe -->
  <div class="auth-field">
    <label for="confirm_mot_de_passe">Confirmer le mot de passe <span class="auth-req">*</span></label>
    <div class="auth-input-wrap">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#9ca3af">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
      </svg>
      <input type="password" id="confirm_mot_de_passe" name="confirm_mot_de_passe" required
             placeholder="Répétez votre mot de passe"/>
      <button type="button" class="auth-toggle-pwd" onclick="togglePassword('confirm_mot_de_passe', this)" tabindex="-1">
        <svg class="eye-show" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15" stroke="#9ca3af">
          <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
        </svg>
        <svg class="eye-hide" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15" stroke="#9ca3af" style="display:none">
          <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
          <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
          <line x1="1" y1="1" x2="23" y2="23"/>
        </svg>
      </button>
    </div>
  </div>

  <button type="submit" class="auth-submit">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="15" height="15" stroke="white">
      <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
      <circle cx="8.5" cy="7" r="4"/>
      <line x1="20" y1="8" x2="20" y2="14"/>
      <line x1="23" y1="11" x2="17" y2="11"/>
    </svg>
    Créer mon compte
  </button>
</form>

<div class="auth-footer-links">
  <p>Déjà inscrit ? <a href="<?= path('auth','login') ?>">Se connecter</a></p>
  <a href="<?= path('lecteur','home') ?>" class="auth-back-link">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="12" height="12">
      <path d="M15 18l-6-6 6-6"/>
    </svg>
    Retour au blog
  </a>
</div>