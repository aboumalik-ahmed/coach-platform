<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/session.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Sportify' : 'Sportify - Plateforme Coach-Sportif'; ?></title>
    <link rel="stylesheet" href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? '../public/css/style.css' : 'public/css/style.css'; ?>">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? '../index.php' : 'index.php'; ?>">
                        <h1>🏃‍♂️ Sportify</h1>
                    </a>
                </div>
                <ul class="nav-menu">
                    <li><a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? '../index.php' : 'index.php'; ?>">Accueil</a></li>
                    <li><a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? 'coachs.php' : 'pages/coachs.php'; ?>">Coachs</a></li>
                    <li><a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? 'seances.php' : 'pages/seances.php'; ?>">Séances</a></li>
                    
                    <?php if(estConnecte()): ?>
                        <?php if(estCoach()): ?>
                            <li><a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? 'coach_dashboard.php' : 'pages/coach_dashboard.php'; ?>">Mon Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? 'mes_reservations.php' : 'pages/mes_reservations.php'; ?>">Mes Réservations</a></li>
                        <?php endif; ?>
                        <li class="user-menu">
                            <span class="user-name"><?php echo getUserNomComplet(); ?></span>
                            <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? 'logout.php' : 'pages/logout.php'; ?>" class="btn-logout">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? 'login.php' : 'pages/login.php'; ?>" class="btn-primary">Connexion</a></li>
                        <li><a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? 'signup.php' : 'pages/signup.php'; ?>" class="btn-secondary">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main-content">
