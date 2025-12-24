<?php
require_once '../config/database.php';
require_once '../classes/Seance.php';
require_once '../classes/Reservation.php';
require_once '../includes/session.php';

$page_title = "Séances disponibles";
$success = '';
$error = '';

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Traitement de la réservation
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver'])) {
    requireSportif(); // Seuls les sportifs peuvent réserver
    
    $seance_id = intval($_POST['seance_id']);
    $sportif_id = getUserId();
    
    $reservation = new Reservation($db);
    $reservation->setSeanceId($seance_id);
    $reservation->setSportifId($sportif_id);
    
    // Vérifier si déjà réservée
    if($reservation->dejaReservee($seance_id, $sportif_id)) {
        $error = "Vous avez déjà réservé cette séance.";
    } elseif($reservation->creer()) {
        $success = "Réservation effectuée avec succès !";
    } else {
        $error = "Erreur lors de la réservation. La séance n'est peut-être plus disponible.";
    }
}

// Récupérer toutes les séances disponibles
$seance = new Seance($db);
$stmt = $seance->lireDisponibles();

include '../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h2>📅 Séances Disponibles</h2>
        <p>Réservez une séance avec votre coach préféré</p>
    </div>

    <?php if(!empty($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if(!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if(!estConnecte()): ?>
        <div class="alert alert-warning">
            Vous devez être connecté en tant que sportif pour réserver une séance. 
            <a href="login.php" style="color: var(--primary-color); font-weight: 600;">Se connecter</a>
        </div>
    <?php elseif(estCoach()): ?>
        <div class="alert alert-info">
            Les coachs ne peuvent pas réserver de séances. Cette page est réservée aux sportifs.
        </div>
    <?php endif; ?>

    <?php if($stmt->rowCount() > 0): ?>
        <div class="cards-grid">
            <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="card">
                    <div class="card-header">
                        <h3><?php echo htmlspecialchars($row['coach_prenom'] . ' ' . $row['coach_nom']); ?></h3>
                        <span class="badge badge-success"><?php echo htmlspecialchars($row['discipline']); ?></span>
                    </div>
                    <div class="card-body">
                        <p><strong>📅 Date:</strong> <?php echo date('d/m/Y', strtotime($row['date_seance'])); ?></p>
                        <p><strong>🕐 Heure:</strong> <?php echo date('H:i', strtotime($row['heure'])); ?></p>
                        <p><strong>⏱️ Durée:</strong> <?php echo $row['duree']; ?> minutes</p>
                        <p><strong>📍 Statut:</strong> <span class="badge badge-success">Disponible</span></p>
                    </div>
                    <div class="card-footer">
                        <?php if(estSportif()): ?>
                            <form method="POST" action="" style="margin: 0;">
                                <input type="hidden" name="seance_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="reserver" class="btn btn-primary btn-small">Réserver</button>
                            </form>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary btn-small">Se connecter pour réserver</a>
                        <?php endif; ?>
                        <a href="coach_detail.php?id=<?php echo $row['coach_id']; ?>" class="btn btn-secondary btn-small">Voir le coach</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Aucune séance disponible pour le moment. Revenez plus tard !
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
