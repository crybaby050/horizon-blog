<?php
require_once ROOT . "/model/auth/authModel.php";
require_once ROOT . "/model/admin/adminModel.php";  // ← ajouter cette ligne
/* ── INSCRIPTION ── */
$register = function () {
    $errors  = [];
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = validate($_POST, [
            'nom'            => ['required', 'min:2', 'max:50', 'alpha'],
            'prenom'         => ['required', 'min:2', 'max:50', 'alpha'],
            'email'          => ['required', 'email', 'unique:lecteur:email'],
            'mot_de_passe'   => ['required', 'min:6', 'confirmed'],
        ], $_FILES);

        // Upload photo (optionnel)
        $photoUrl = null;
        if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $photoUrl = uploadImage($_FILES['photo']);
            if ($photoUrl === false) {
                $errors['photo'] = 'Format image non supporté ou fichier trop lourd (max 5 Mo).';
            }
        }

        if (empty($errors)) {
            $nom    = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email  = trim($_POST['email']);
            $mdp    = $_POST['mot_de_passe'];

            if (registerLecteur($nom, $prenom, $email, $mdp, $photoUrl)) {
                $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                $_POST   = []; // vider le formulaire
            } else {
                $errors['global'] = "Une erreur est survenue. Veuillez réessayer.";
            }
        }
    }

    loadView("auth/register", compact('errors', 'success'), 'auth');
};

/* ── CONNEXION ── */
$login = function () {
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = validate($_POST, [
            'email'        => ['required', 'email'],
            'mot_de_passe' => ['required'],
        ]);

        if (empty($errors)) {
            $email = trim($_POST['email']);
            $mdp   = $_POST['mot_de_passe'];
            $user  = false;
            $type  = null;

            // Détection automatique : admin en premier, puis auteur, puis lecteur
            $admin = loginAdmin($email, $mdp);
            if ($admin) {
                $_SESSION['admin'] = [
                    'id'     => (int)$admin['id'],
                    'prenom' => $admin['prenom'],
                    'nom'    => $admin['nom'],
                    'email'  => $admin['email'],
                ];
                header('Location: ' . path('admin', 'dashboard'));
                exit();
            }

            $auteur = loginAuteur($email, $mdp);
            if ($auteur) {
                $_SESSION['user'] = [
                    'id'     => (int)$auteur['id'],
                    'type'   => 'auteur',
                    'nom'    => $auteur['nom'],
                    'prenom' => $auteur['prenom'],
                    'email'  => $auteur['email'],
                    'photo'  => $auteur['photo'] ?? null,
                ];
                header('Location: ' . path('auteur', 'dashboard'));
                exit();
            }

            $lecteur = loginLecteur($email, $mdp);
            if ($lecteur) {
                $_SESSION['user'] = [
                    'id'     => (int)$lecteur['id'],
                    'type'   => 'lecteur',
                    'nom'    => $lecteur['nom'],
                    'prenom' => $lecteur['prenom'],
                    'email'  => $lecteur['email'],
                    'photo'  => $lecteur['photo'] ?? null,
                ];
                header('Location: ' . path('lecteur', 'home'));
                exit();
            }

            $errors['global'] = 'Email ou mot de passe incorrect.';
        }
    }

    loadView("auth/login", compact('errors'), 'auth');
};

/* ── DÉCONNEXION ── */
$logout = function () {
    $_SESSION = [];
    session_destroy();
    header("Location: " . path('lecteur', 'home'));
    exit();
};

/* ── DISPATCH ── */
$actions = [
    "register" => $register,
    "login"    => $login,
    "logout"   => $logout,
];

$action = $_REQUEST["action"] ?? "login";
$GLOBALS['currentAction'] = $action;

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    http_response_code(404);
    echo "Page introuvable";
    exit();
}