<?php
require_once ROOT . "/model/auth/authModel.php";

// Démarrer la session si pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ── FORMULAIRE INSCRIPTION ── */
$register = function () {
    $error = '';
    $success = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom     = trim($_POST['nom'] ?? '');
        $prenom  = trim($_POST['prenom'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $mdp     = $_POST['mot_de_passe'] ?? '';
        $mdpConfirm = $_POST['confirm_mot_de_passe'] ?? '';
        
        // Validation nom (2-50 caractères, lettres et tirets)
        if (!preg_match('/^[a-zA-ZÀ-ÿ-]{2,50}$/', $nom)) {
            $error = "Le nom doit contenir 2 à 50 caractères (lettres et tirets uniquement).";
        }
        // Validation prénom (2-50 caractères)
        elseif (!preg_match('/^[a-zA-ZÀ-ÿ-]{2,50}$/', $prenom)) {
            $error = "Le prénom doit contenir 2 à 50 caractères (lettres et tirets uniquement).";
        }
        // Validation email
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "L'adresse email n'est pas valide.";
        }
        // Validation mot de passe (min 6 caractères)
        elseif (strlen($mdp) < 6) {
            $error = "Le mot de passe doit contenir au moins 6 caractères.";
        }
        elseif ($mdp !== $mdpConfirm) {
            $error = "Les mots de passe ne correspondent pas.";
        }
        // Vérifier unicité email
        elseif (emailExists($email)) {
            $error = "Cet email est déjà utilisé.";
        }
        else {
            if (registerLecteur($nom, $prenom, $email, $mdp)) {
                $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
            }
        }
    }
    
    loadView("auth/register", compact('error', 'success'), 'auth');
};

/* ── FORMULAIRE CONNEXION ── */
$login = function () {
    $error = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email    = trim($_POST['email'] ?? '');
        $mdp      = $_POST['mot_de_passe'] ?? '';
        $userType = $_POST['user_type'] ?? 'lecteur';
        
        if (empty($email) || empty($mdp)) {
            $error = "Veuillez remplir tous les champs.";
        } else {
            $user = false;
            
            if ($userType === 'lecteur') {
                $user = loginLecteur($email, $mdp);
                if ($user) {
                    $_SESSION['user'] = [
                        'id'    => (int) $user['id'],
                        'type'  => 'lecteur',
                        'nom'   => $user['nom'],
                        'prenom'=> $user['prenom'],
                        'email' => $user['email']
                    ];
                }
            } 
            elseif ($userType === 'auteur') {
                $user = loginAuteur($email, $mdp);
                if ($user) {
                    $_SESSION['user'] = [
                        'id'    => (int) $user['id'],
                        'type'  => 'auteur',
                        'nom'   => $user['nom'],
                        'prenom'=> $user['prenom'],
                        'email' => $user['email']
                    ];
                }
            }
            
            if ($user) {
                header("Location: " . path('lecteur', 'home'));
                exit();
            } else {
                $error = "Email ou mot de passe incorrect, ou compte inactif.";
            }
        }
    }
    
    loadView("auth/login", compact('error'), 'auth');
};

/* ── DECONNEXION ── */
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
    "logout"   => $logout
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