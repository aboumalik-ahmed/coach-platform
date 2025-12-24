<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Coach.php';
require_once '../classes/Sportif.php';

$page_title = "Inscription";
$error = '';
$success = '';

// Si déjà connecté, rediriger
if(isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Déterminer le type d'utilisateur depuis l'URL
$user_type = isset($_GET['type']) ? $_GET['type'] : '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type'];

    // Validation de base
    if(empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($user_type)) {
        $error = "Tous les champs obligatoires doivent être remplis.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } elseif(strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Connexion à la base de données
        $database = new Database();
        $db = $database->getConnection();

        if($user_type === 'coach') {
            $discipline = trim($_POST['discipline']);
            $annees_experience = intval($_POST['annees_experience']);
            $description = trim($_POST['description']);

            if(empty($discipline) || $annees_experience < 0) {
                $error = "Veuillez remplir tous les champs spécifiques au coach.";
            } else {
                $coach = new Coach($db);
                
                // Vérifier si l'email existe
                if($coach->emailExiste($email)) {
                    $error = "Cet email est déjà utilisé.";
                } else {
                    $coach->setNom($nom);
                    $coach->setPrenom($prenom);
                    $coach->setEmail($email);
                    $coach->setPassword($password);
                    $coach->setDiscipline($discipline);
                    $coach->setAnneesExperience($annees_experience);
                    $coach->setDescription($description);

                    if($coach->creer()) {
                        $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                        // Rediriger après 2 secondes
                        header("refresh:2;url=login.php");
                    } else {
                        $error = "Erreur lors de l'inscription. Veuillez réessayer.";
                    }
                }
            }
        } else {
            $sportif = new Sportif($db);
            
            // Vérifier si l'email existe
            if($sportif->emailExiste($email)) {
                $error = "Cet email est déjà utilisé.";
            } else {
                $sportif->setNom($nom);
                $sportif->setPrenom($prenom);
                $sportif->setEmail($email);
                $sportif->setPassword($password);

                if($sportif->creer()) {
                    $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    // Rediriger après 2 secondes
                    header("refresh:2;url=login.php");
                } else {
                    $error = "Erreur lors de l'inscription. Veuillez réessayer.";
                }
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-3">📝 Inscription</h2>
        
        <?php if(!empty($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if(!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="signupForm">
            <div class="form-group">
                <label for="user_type">Type de compte *</label>
                <select name="user_type" id="user_type" required onchange="toggleCoachFields()">
                    <option value="">-- Choisissez --</option>
                    <option value="sportif" <?php echo ($user_type === 'sportif') ? 'selected' : ''; ?>>Sportif</option>
                    <option value="coach" <?php echo ($user_type === 'coach') ? 'selected' : ''; ?>>Coach</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" name="nom" id="nom" 
                       value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" 
                       required placeholder="Votre nom">
            </div>

            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" name="prenom" id="prenom" 
                       value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>" 
                       required placeholder="Votre prénom">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                       required placeholder="votre@email.com">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe * (min. 6 caractères)</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe *</label>
                <input type="password" name="confirm_password" id="confirm_password" required placeholder="••••••••">
            </div>

            <!-- Champs spécifiques aux coachs -->
            <div id="coachFields" style="display: <?php echo ($user_type === 'coach') ? 'block' : 'none'; ?>;">
                <div class="form-group">
                    <label for="discipline">Discipline sportive *</label>
                    <input type="text" name="discipline" id="discipline" 
                           value="<?php echo isset($_POST['discipline']) ? htmlspecialchars($_POST['discipline']) : ''; ?>" 
                           placeholder="Ex: Yoga, Musculation, Running...">
                </div>

                <div class="form-group">
                    <label for="annees_experience">Années d'expérience *</label>
                    <input type="number" name="annees_experience" id="annees_experience" min="0" 
                           value="<?php echo isset($_POST['annees_experience']) ? intval($_POST['annees_experience']) : ''; ?>" 
                           placeholder="0">
                </div>

                <div class="form-group">
                    <label for="description">Description courte *</label>
                    <textarea name="description" id="description" 
                              placeholder="Parlez de vous, votre approche, vos certifications..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </div>
        </form>

        <p class="text-center mt-3">
            Déjà un compte ? 
            <a href="login.php" style="color: var(--primary-color); font-weight: 600;">Se connecter</a>
        </p>
    </div>
</div>

<script>
function toggleCoachFields() {
    const userType = document.getElementById('user_type').value;
    const coachFields = document.getElementById('coachFields');
    const coachInputs = coachFields.querySelectorAll('input, textarea');
    
    if(userType === 'coach') {
        coachFields.style.display = 'block';
        coachInputs.forEach(input => input.required = true);
    } else {
        coachFields.style.display = 'none';
        coachInputs.forEach(input => input.required = false);
    }
}

// Exécuter au chargement de la page
document.addEventListener('DOMContentLoaded', toggleCoachFields);
</script>

<?php include '../includes/footer.php'; ?>
