<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>HorizonBlog - Authentification</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?= WEBROOT ?>css/style.css"/>
</head>
<body class="auth-page">

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-image">
            <div class="auth-image-overlay"></div>
            <div class="auth-image-content">
                <div class="auth-logo">
                    <svg width="48" height="48" viewBox="0 0 32 32" fill="none">
                        <path d="M16 4C10 4 5 9 5 15.5c0 3.2 1.6 5.9 4.2 7.8 1.1.8 2.6-.3 2.1-1.6-1-2.8-1-5.8 1.2-8 3.2-3.2 8.5-3.2 11.8-1 1 .6 2.2-.3 1.6-1.5C24 9 20.3 4 16 4z" fill="#1a9e5c"/>
                        <path d="M8.5 23.5c0 0 2.2-2.2 5.5-2.2s5.5 1.2 5.5 3.2c0 1.6-1.1 2.2-2.2 2.2-3.3 0-8.8-3.2-8.8-3.2z" fill="#157a47"/>
                    </svg>
                    <span class="auth-logo-text">HorizonBlog</span>
                </div>
                <h2>Bienvenue</h2>
                <p>Rejoignez une communauté passionnée et partagez vos idées.</p>
            </div>
        </div>
        <div class="auth-form-wrapper">
            <?= $content ?>
        </div>
    </div>
</div>

</body>
</html>