<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Coach.php';
require_once '../classes/Sportif.php';

$page_title = "Connexion";
$error = '';
$success = '';

// Si déjà connecté, rediriger
if(isset($_SESSION['user_id'])) {
    if($_SESSION['user_type'] === 'coach') {
        header("Location: coach_dashboard.php");
    } else {
        header("Location: mes_reservations.php");
    }
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Validation
    if(empty($email) || empty($password) || empty($user_type)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } else {
        // Connexion à la base de données
        $database = new Database();
        $db = $database->getConnection();

        if($user_type === 'coach') {
            $coach = new Coach($db);
            if($coach->login($email, $password)) {
                $_SESSION['user_id'] = $coach->getId();
                $_SESSION['user_type'] = 'coach';
                $_SESSION['user_nom'] = $coach->getNom();
                $_SESSION['user_prenom'] = $coach->getPrenom();
                header("Location: coach_dashboard.php");
                exit();
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } else {
            $sportif = new Sportif($db);
            if($sportif->login($email, $password)) {
                $_SESSION['user_id'] = $sportif->getId();
                $_SESSION['user_type'] = 'sportif';
                $_SESSION['user_nom'] = $sportif->getNom();
                $_SESSION['user_prenom'] = $sportif->getPrenom();
                header("Location: mes_reservations.php");
                exit();
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-3">🔐 Connexion</h2>
        
        <?php if(!empty($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if(!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="user_type">Type de compte *</label>
                <select name="user_type" id="user_type" required>
                    <option value="">-- Choisissez --</option>
                    <option value="sportif" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] === 'sportif') ? 'selected' : ''; ?>>Sportif</option>
                    <option value="coach" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] === 'coach') ? 'selected' : ''; ?>>Coach</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                       required placeholder="votre@email.com">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe *</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </div>
        </form>

        <p class="text-center mt-3">
            Pas encore de compte ? 
            <a href="signup.php" style="color: var(--primary-color); font-weight: 600;">Créer un compte</a>
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
