<?php
$page_title = "Accueil";
include 'includes/header.php';
?>

<div class="container">
    <section class="hero">
        <h2>🏃‍♂️ Bienvenue sur Sportify</h2>
        <p>Trouvez le coach sportif idéal pour atteindre vos objectifs</p>
        <div class="hero-buttons">
            <a href="pages/coachs.php" class="btn btn-primary">Découvrir les Coachs</a>
            <a href="pages/seances.php" class="btn btn-secondary">Voir les Séances</a>
        </div>
    </section>

    <section class="page-header">
        <h2>Comment ça marche ?</h2>
        <p>Rejoignez notre communauté en 3 étapes simples</p>
    </section>

    <div class="cards-grid">
        <div class="card">
            <div class="card-header">
                <h3>📝 1. Inscription</h3>
            </div>
            <div class="card-body">
                <p>Créez votre compte en tant que sportif ou coach en quelques clics.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>🔍 2. Recherche</h3>
            </div>
            <div class="card-body">
                <p>Parcourez les profils des coachs et découvrez leurs spécialités.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>✅ 3. Réservation</h3>
            </div>
            <div class="card-body">
                <p>Réservez une séance avec le coach de votre choix facilement.</p>
            </div>
        </div>
    </div>

    <?php if(!estConnecte()): ?>
    <section class="text-center mt-4">
        <h3 class="mb-3">Prêt à commencer ?</h3>
        <div class="hero-buttons">
            <a href="pages/signup.php?type=sportif" class="btn btn-primary">Je suis un Sportif</a>
            <a href="pages/signup.php?type=coach" class="btn btn-secondary">Je suis un Coach</a>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
