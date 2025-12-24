<?php
require_once '../config/database.php';
require_once '../classes/Coach.php';
require_once '../classes/Seance.php';
require_once '../classes/Reservation.php';
require_once '../includes/session.php';

requireCoach(); // Seulement accessible aux coachs

$page_title = "Dashboard Coach";
$success = '';
$error = '';

$coach_id = getUserId();

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Récupérer les informations du coach
$coach = new Coach($db);
$coach->lireUn($coach_id);

// Traitement de l'ajout de séance
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_seance'])) {
    $date_seance = $_POST['date_seance'];
    $heure = $_POST['heure'];
    $duree = intval($_POST['duree']);
    
    if(empty($date_seance) || empty($heure) || $duree <= 0) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $seance = new Seance($db);
        $seance->setCoachId($coach_id);
        $seance->setDateSeance($date_seance);
        $seance->setHeure($heure);
        $seance->setDuree($duree);
        $seance->setStatut('disponible');
        
        if($seance->creer()) {
            $success = "Séance ajoutée avec succès !";
        } else {
            $error = "Erreur lors de l'ajout de la séance.";
        }
    }
}

// Traitement de la suppression de séance
if(isset($_GET['supprimer']) && !empty($_GET['supprimer'])) {
    $seance_id = intval($_GET['supprimer']);
    
    $seance = new Seance($db);
    if($seance->lireUn($seance_id)) {
        // Vérifier que la séance appartient au coach
        if($seance->getCoachId() == $coach_id) {
            if($seance->supprimer()) {
                $success = "Séance supprimée avec succès !";
            } else {
                $error = "Erreur lors de la suppression.";
            }
        }
    }
}

// Récupérer les séances du coach
$seance = new Seance($db);
$stmt_seances = $seance->lireParCoach($coach_id);

// Récupérer les réservations
$reservation = new Reservation($db);
$stmt_reservations = $reservation->lireParCoach($coach_id);

// Statistiques
$total_seances = $stmt_seances->rowCount();
$total_reservations = $stmt_reservations->rowCount();

include '../includes/header.php';
?>

<div class="container">
    <div class="dashboard-header">
        <h2>👨‍🏫 Dashboard Coach - <?php echo $coach->getNomComplet(); ?></h2>
        <p>Gérez vos séances et consultez vos réservations</p>
    </div>

    <?php if(!empty($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if(!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Statistiques -->
    <div class="dashboard-stats">
        <div class="stat-card">
            <h4>Total Séances</h4>
            <div class="stat-number"><?php echo $total_seances; ?></div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, var(--secondary-color), #059669);">
            <h4>Réservations</h4>
            <div class="stat-number"><?php echo $total_reservations; ?></div>
        </div>
    </div>

    <!-- Formulaire d'ajout de séance -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>➕ Ajouter une nouvelle séance</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div class="form-group">
                        <label for="date_seance">Date *</label>
                        <input type="date" name="date_seance" id="date_seance" required 
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="heure">Heure *</label>
                        <input type="time" name="heure" id="heure" required>
                    </div>
                    <div class="form-group">
                        <label for="duree">Durée (minutes) *</label>
                        <input type="number" name="duree" id="duree" min="15" step="15" value="60" required>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" name="ajouter_seance" class="btn btn-primary">Ajouter la séance</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des séances -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>📅 Mes Séances</h3>
        </div>
        <div class="card-body">
            <?php 
            // Réexécuter la requête pour l'affichage
            $stmt_seances = $seance->lireParCoach($coach_id);
            
            if($stmt_seances->rowCount() > 0): 
            ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Durée</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $stmt_seances->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($row['date_seance'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($row['heure'])); ?></td>
                                    <td><?php echo $row['duree']; ?> min</td>
                                    <td>
                                        <?php if($row['statut'] === 'disponible'): ?>
                                            <span class="badge badge-success">Disponible</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Réservée</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($row['statut'] === 'disponible'): ?>
                                            <a href="?supprimer=<?php echo $row['id']; ?>" 
                                               class="btn btn-danger btn-small"
                                               onclick="return confirm('Voulez-vous vraiment supprimer cette séance ?')">
                                                Supprimer
                                            </a>
                                        <?php else: ?>
                                            <span style="color: var(--text-color); font-size: 0.9rem;">Déjà réservée</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>Vous n'avez pas encore créé de séances.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Liste des réservations -->
    <div class="card">
        <div class="card-header">
            <h3>📋 Réservations de mes séances</h3>
        </div>
        <div class="card-body">
            <?php 
            // Réexécuter la requête pour l'affichage
            $stmt_reservations = $reservation->lireParCoach($coach_id);
            
            if($stmt_reservations->rowCount() > 0): 
            ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Sportif</th>
                                <th>Date séance</th>
                                <th>Heure</th>
                                <th>Durée</th>
                                <th>Date réservation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $stmt_reservations->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['sportif_prenom'] . ' ' . $row['sportif_nom']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['date_seance'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($row['heure'])); ?></td>
                                    <td><?php echo $row['duree']; ?> min</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['date_reservation'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>Aucune réservation pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
