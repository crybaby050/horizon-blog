<?php
  $pageTitle = 'Connexion';
  $leftTitle = 'Bon retour parmi nous !';
  $leftDesc  = 'Connectez-vous pour accéder à vos articles favoris et interagir avec la communauté HorizonBlog.';
?>

<div class="auth-header">
  <div class="auth-header-icon">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="22" height="22" stroke="#1a9e5c">
      <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
      <polyline points="10 17 15 12 10 7"/>
      <line x1="15" y1="12" x2="3" y2="12"/>
    </svg>
  </div>
  <h1>Connexion</h1>
  <p>Accédez à votre compte HorizonBlog</p>
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

<form method="POST" action="" class="auth-form">
  <input type="hidden" name="controller" value="auth"/>
  <input type="hidden" name="action"     value="login"/>

  <!-- Type de compte -->
  <div class="auth-type-tabs">
    <label class="auth-type-tab">
      <input type="radio" name="user_type" value="lecteur" checked
             onchange="document.querySelector('.auth-type-tab.active').classList.remove('active');this.closest('.auth-type-tab').classList.add('active')"/>
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
      </svg>
      Lecteur
    </label>
    <label class="auth-type-tab">
      <input type="radio" name="user_type" value="auteur"
             onchange="document.querySelector('.auth-type-tab.active').classList.remove('active');this.closest('.auth-type-tab').classList.add('active')"/>
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15">
        <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
      </svg>
      Auteur
    </label>
  </div>

  <!-- Email -->
  <div class="auth-field">
    <label for="email">Adresse e-mail</label>
    <div class="auth-input-wrap">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#9ca3af">
        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
        <polyline points="22,6 12,13 2,6"/>
      </svg>
      <input type="email" id="email" name="email"
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
             placeholder="vous@exemple.com"
             required autofocus/>
    </div>
  </div>

  <!-- Mot de passe -->
  <div class="auth-field">
    <label for="mot_de_passe">Mot de passe</label>
    <div class="auth-input-wrap">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#9ca3af">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
      </svg>
      <input type="password" id="mot_de_passe" name="mot_de_passe"
             placeholder="••••••••" required/>
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

  <button type="submit" class="auth-submit">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="15" height="15" stroke="white">
      <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
      <polyline points="10 17 15 12 10 7"/>
      <line x1="15" y1="12" x2="3" y2="12"/>
    </svg>
    Se connecter
  </button>
</form>

<div class="auth-footer-links">
  <p>Pas encore de compte ? <a href="<?= path('auth','register') ?>">S'inscrire</a></p>
  <a href="<?= path('lecteur','home') ?>" class="auth-back-link">
    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="12" height="12">
      <path d="M15 18l-6-6 6-6"/>
    </svg>
    Retour au blog
  </a>
</div>