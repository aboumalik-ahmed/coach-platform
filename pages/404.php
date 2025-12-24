<?php
$page_title = "Page non trouvée";
include '../includes/header.php';
?>

<div class="container">
    <div class="error-page">
        <h1>404</h1>
        <h2>🔍 Oups ! Page non trouvée</h2>
        <p>La page que vous recherchez semble introuvable.</p>
        <p>Elle a peut-être été déplacée ou n'existe plus.</p>
        
        <div class="hero-buttons mt-4">
            <a href="../index.php" class="btn btn-primary">🏠 Retour à l'accueil</a>
            <a href="coachs.php" class="btn btn-secondary">👥 Voir les coachs</a>
            <a href="seances.php" class="btn btn-secondary">📅 Voir les séances</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
