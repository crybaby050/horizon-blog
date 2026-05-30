<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>HorizonBlog — Connexion Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?= WEBROOT ?>css/style.css"/>
</head>
<body class="adm-login-body">

<div class="adm-login-wrap">

  <!-- Panneau gauche décoratif -->
  <div class="adm-login-left">
    <div class="adm-login-left-inner">
      <div class="adm-login-brand">
        <svg width="48" height="48" viewBox="0 0 32 32" fill="none">
          <path d="M16 4C10 4 5 9 5 15.5c0 3.2 1.6 5.9 4.2 7.8 1.1.8 2.6-.3 2.1-1.6-1-2.8-1-5.8 1.2-8 3.2-3.2 8.5-3.2 11.8-1 1 .6 2.2-.3 1.6-1.5C24 9 20.3 4 16 4z" fill="#fff"/>
          <path d="M8.5 23.5c0 0 2.2-2.2 5.5-2.2s5.5 1.2 5.5 3.2c0 1.6-1.1 2.2-2.2 2.2-3.3 0-8.8-3.2-8.8-3.2z" fill="rgba(255,255,255,.7)"/>
        </svg>
        <div>
          <div class="adm-login-brand-name">HorizonBlog</div>
          <div class="adm-login-brand-sub">Espace Administration</div>
        </div>
      </div>

      <div class="adm-login-left-text">
        <h2>Gérez votre plateforme</h2>
        <p>Accédez au tableau de bord pour gérer les articles, les utilisateurs et les signalements de HorizonBlog.</p>
      </div>

      <div class="adm-login-stats">
        <div class="adm-login-stat">
          <div class="adm-login-stat-num">10+</div>
          <div class="adm-login-stat-lbl">Articles</div>
        </div>
        <div class="adm-login-stat-sep"></div>
        <div class="adm-login-stat">
          <div class="adm-login-stat-num">3</div>
          <div class="adm-login-stat-lbl">Auteurs</div>
        </div>
        <div class="adm-login-stat-sep"></div>
        <div class="adm-login-stat">
          <div class="adm-login-stat-num">3</div>
          <div class="adm-login-stat-lbl">Lecteurs</div>
        </div>
      </div>

      <!-- Cercles décoratifs -->
      <div class="adm-login-deco adm-login-deco1"></div>
      <div class="adm-login-deco adm-login-deco2"></div>
      <div class="adm-login-deco adm-login-deco3"></div>
    </div>
  </div>

  <!-- Panneau droit formulaire -->
  <div class="adm-login-right">
    <div class="adm-login-form-wrap">

      <div class="adm-login-header">
        <div class="adm-login-icon">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="24" height="24" stroke="#1a9e5c">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </div>
        <h1>Connexion administrateur</h1>
        <p>Entrez vos identifiants pour accéder au panneau d'administration.</p>
      </div>

      <?php if (!empty($error)): ?>
        <div class="adm-login-error">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#dc2626">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="<?= path('admin','login') ?>" class="adm-login-form">
        <input type="hidden" name="controller" value="admin"/>
        <input type="hidden" name="action"     value="login"/>

        <div class="adm-login-field">
          <label for="adm-email">Adresse e-mail</label>
          <div class="adm-login-input-wrap">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#9ca3af">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
            <input type="email" id="adm-email" name="email"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   placeholder="admin@horizonblog.com"
                   required autofocus/>
          </div>
        </div>

        <div class="adm-login-field">
          <label for="adm-mdp">Mot de passe</label>
          <div class="adm-login-input-wrap">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16" stroke="#9ca3af">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <input type="password" id="adm-mdp" name="mot_de_passe"
                   placeholder="••••••••"
                   required/>
            <button type="button" class="adm-toggle-pwd" onclick="admTogglePwd(this)" tabindex="-1">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="15" height="15" stroke="#9ca3af">
                <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        <button type="submit" class="adm-login-submit">
          <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="15" height="15" stroke="white">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/>
            <line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
          Se connecter
        </button>
      </form>

      <a href="<?= path('lecteur','home') ?>" class="adm-login-back">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="13" height="13">
          <path d="M15 18l-6-6 6-6"/>
        </svg>
        Retour au blog
      </a>

    </div>
  </div>
</div>

<script>
function admTogglePwd(btn) {
  const input = btn.closest('.adm-login-input-wrap').querySelector('input');
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>