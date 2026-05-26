<div class="auth-header">
    <h1>Connexion</h1>
    <p>Accédez à votre compte HorizonBlog</p>
</div>

<form method="POST" action="" class="auth-form">
    <div class="form-group">
        <label for="email">Adresse email</label>
        <input type="email" id="email" name="email" required 
               placeholder="exemple@email.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"/>
    </div>
    
    <div class="form-group">
        <!--<label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required 
               placeholder="••••••"/>-->

               <label for="mot_de_passe">Mot de passe</label>
  <div class="input-password-wrap">
    <input type="password" id="mot_de_passe" name="mot_de_passe" required placeholder="••••••"/>
    <button type="button" class="toggle-password" onclick="togglePassword('mot_de_passe', this)">
      <svg class="eye-show" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round">
        <path d="M1 12S5 5 12 5s11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
      </svg>
      <svg class="eye-hide" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="display:none">
        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>
      </svg>
    </button>
  </div>

    </div>
    
    <div class="form-group">
        <label>Type de compte</label>
        <div class="radio-group">
            <label class="radio-label">
                <input type="radio" name="user_type" value="lecteur" checked/>
                <span>Lecteur</span>
            </label>
            <label class="radio-label">
                <input type="radio" name="user_type" value="auteur"/>
                <span>Auteur</span>
            </label>
        </div>
    </div>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <button type="submit" class="btn-submit">Se connecter</button>
</form>

<div class="auth-footer-link">
    <p>Pas encore de compte ? <a href="<?= path('auth', 'register') ?>">Inscrivez-vous</a></p>
</div>