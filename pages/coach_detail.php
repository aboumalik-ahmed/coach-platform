<?php
require_once '../config/database.php';
require_once '../classes/Coach.php';
require_once '../classes/Seance.php';

$page_title = "Détail Coach";

// Vérifier si l'ID est fourni
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: coachs.php");
    exit();
}

$coach_id = intval($_GET['id']);

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Récupérer les informations du coach
$coach = new Coach($db);
if(!$coach->lireUn($coach_id)) {
    header("Location: coachs.php");
    exit();
}

// Récupérer les séances du coach
$seance = new Seance($db);
$stmt_seances = $seance->lireParCoach($coach_id);

include '../includes/header.php';
?>

<div class="container">
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h2><?php echo htmlspecialchars($coach->getNomComplet()); ?></h2>
            <span class="badge badge-success"><?php echo htmlspecialchars($coach->getDiscipline()); ?></span>
        </div>
        
        <div class="card-body">
            <p><strong>📧 Email:</strong> <?php echo htmlspecialchars($coach->getEmail()); ?></p>
            <p><strong>⭐ Expérience:</strong> <?php echo $coach->getAnneesExperience(); ?> ans</p>
            
            <h3 class="mt-3 mb-2">À propos</h3>
            <p><?php echo nl2br(htmlspecialchars($coach->getDescription())); ?></p>
        </div>
    </div>

    <div class="mt-4">
        <h3 class="mb-3">📅 Séances proposées par ce coach</h3>
        
        <?php if($stmt_seances->rowCount() > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Durée</th>
                            <th>Statut</th>
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
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Ce coach n'a pas encore de séances programmées.
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center mt-4">
        <a href="coachs.php" class="btn btn-secondary">Retour à la liste</a>
        <a href="seances.php" class="btn btn-primary">Voir toutes les séances</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
