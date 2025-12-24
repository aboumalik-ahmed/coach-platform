<?php
require_once '../config/database.php';
require_once '../classes/Coach.php';

$page_title = "Nos Coachs";

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Récupérer tous les coachs
$coach = new Coach($db);
$stmt = $coach->lireTous();

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h2>👥 Nos Coachs Professionnels</h2>
        <p>Découvrez nos coachs expérimentés et trouvez celui qui vous convient</p>
    </div>

    <?php if($stmt->rowCount() > 0): ?>
        <div class="cards-grid">
            <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="card">
                    <div class="card-header">
                        <h3><?php echo htmlspecialchars($row['prenom'] . ' ' . $row['nom']); ?></h3>
                        <span class="badge badge-success"><?php echo htmlspecialchars($row['discipline']); ?></span>
                    </div>
                    <div class="card-body">
                        <p><strong>Expérience:</strong> <?php echo htmlspecialchars($row['annees_experience']); ?> ans</p>
                        <p><strong>Description:</strong></p>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="coach_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-small">Voir le profil</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Aucun coach disponible pour le moment.
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
