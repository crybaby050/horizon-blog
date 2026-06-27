<?php
require_once ROOT . "/model/auth/authModel.php";

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
            $email    = trim($_POST['email']);
            $mdp      = $_POST['mot_de_passe'];
            $userType = $_POST['user_type'] ?? 'lecteur';
            $user     = false;

            if ($userType === 'lecteur') {
                $user = loginLecteur($email, $mdp);
                if ($user) {
                    $_SESSION['user'] = [
                        'id'     => (int)$user['id'],
                        'type'   => 'lecteur',
                        'nom'    => $user['nom'],
                        'prenom' => $user['prenom'],
                        'email'  => $user['email'],
                        'photo'  => $user['photo'] ?? null,
                    ];
                }
            } elseif ($userType === 'auteur') {
                $user = loginAuteur($email, $mdp);
                if ($user) {
                    $_SESSION['user'] = [
                        'id'     => (int)$user['id'],
                        'type'   => 'auteur',
                        'nom'    => $user['nom'],
                        'prenom' => $user['prenom'],
                        'email'  => $user['email'],
                    ];
                }
            }

            if ($user) {
                header("Location: " . path($userType === 'auteur' ? 'auteur' : 'lecteur', $userType === 'auteur' ? 'dashboard' : 'home'));
                exit();
            } else {
                $errors['global'] = "Email ou mot de passe incorrect.";
            }
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