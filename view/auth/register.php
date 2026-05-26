<div class="auth-header">
    <h1>Inscription</h1>
    <p>Créez votre compte lecteur gratuitement</p>
</div>

<form method="POST" action="" class="auth-form">
    <div class="form-row">
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" required 
                   placeholder="Votre nom"
                   value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"/>
        </div>
        
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" required 
                   placeholder="Votre prénom"
                   value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"/>
        </div>
    </div>
    
    <div class="form-group">
        <label for="email">Adresse email</label>
        <input type="email" id="email" name="email" required 
               placeholder="exemple@email.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"/>
    </div>
    
    <div class="form-group">
        <label for="mot_de_passe">Mot de passe (minimum 6 caractères)</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required 
               placeholder="••••••"/>
    </div>
    
    <div class="form-group">
        <label for="confirm_mot_de_passe">Confirmer le mot de passe</label>
        <input type="password" id="confirm_mot_de_passe" name="confirm_mot_de_passe" required 
               placeholder="••••••"/>
    </div>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <button type="submit" class="btn-submit">S'inscrire</button>
</form>

<div class="auth-footer-link">
    <p>Déjà inscrit ? <a href="<?= path('auth', 'login') ?>">Connectez-vous</a></p>
</div>