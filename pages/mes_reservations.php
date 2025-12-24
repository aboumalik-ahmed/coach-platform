<?php
require_once '../config/database.php';
require_once '../classes/Sportif.php';
require_once '../classes/Reservation.php';
require_once '../includes/session.php';

requireSportif(); // Seulement accessible aux sportifs

$page_title = "Mes Réservations";

$sportif_id = getUserId();

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Récupérer les informations du sportif
$sportif = new Sportif($db);
$sportif->lireUn($sportif_id);

// Récupérer les réservations du sportif
$reservation = new Reservation($db);
$stmt = $reservation->lireParSportif($sportif_id);

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h2>📋 Mes Réservations</h2>
        <p>Bienvenue <?php echo $sportif->getNomComplet(); ?> ! Voici l'historique de vos réservations</p>
    </div>

    <?php if($stmt->rowCount() > 0): ?>
        <div class="cards-grid">
            <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="card">
                    <div class="card-header">
                        <h3><?php echo htmlspecialchars($row['coach_prenom'] . ' ' . $row['coach_nom']); ?></h3>
                        <span class="badge badge-success"><?php echo htmlspecialchars($row['discipline']); ?></span>
                    </div>
                    <div class="card-body">
                        <p><strong>📅 Date de la séance:</strong> <?php echo date('d/m/Y', strtotime($row['date_seance'])); ?></p>
                        <p><strong>🕐 Heure:</strong> <?php echo date('H:i', strtotime($row['heure'])); ?></p>
                        <p><strong>⏱️ Durée:</strong> <?php echo $row['duree']; ?> minutes</p>
                        <p><strong>🎫 Réservé le:</strong> <?php echo date('d/m/Y à H:i', strtotime($row['date_reservation'])); ?></p>
                        
                        <?php 
                        $date_seance = strtotime($row['date_seance'] . ' ' . $row['heure']);
                        $maintenant = time();
                        ?>
                        
                        <?php if($date_seance > $maintenant): ?>
                            <p class="mt-2"><span class="badge badge-success">À venir</span></p>
                        <?php else: ?>
                            <p class="mt-2"><span class="badge badge-warning">Terminée</span></p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a href="coach_detail.php?id=<?php echo $row['coach_id']; ?>" class="btn btn-secondary btn-small">Voir le coach</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Vous n'avez pas encore de réservations. 
            <a href="seances.php" style="color: var(--primary-color); font-weight: 600;">Découvrez nos séances disponibles</a>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="seances.php" class="btn btn-primary">Réserver une nouvelle séance</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
