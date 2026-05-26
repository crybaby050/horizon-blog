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
        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required 
               placeholder="••••••"/>
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