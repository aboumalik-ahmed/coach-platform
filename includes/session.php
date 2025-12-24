<?php
/**
 * Gestion de la session
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Vérifier si l'utilisateur est connecté
 * @return bool
 */
function estConnecte() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_type']);
}

/**
 * Vérifier si l'utilisateur est un coach
 * @return bool
 */
function estCoach() {
    return estConnecte() && $_SESSION['user_type'] === 'coach';
}

/**
 * Vérifier si l'utilisateur est un sportif
 * @return bool
 */
function estSportif() {
    return estConnecte() && $_SESSION['user_type'] === 'sportif';
}

/**
 * Obtenir l'ID de l'utilisateur connecté
 * @return int|null
 */
function getUserId() {
    return estConnecte() ? $_SESSION['user_id'] : null;
}

/**
 * Obtenir le type d'utilisateur
 * @return string|null
 */
function getUserType() {
    return estConnecte() ? $_SESSION['user_type'] : null;
}

/**
 * Obtenir le nom complet de l'utilisateur
 * @return string
 */
function getUserNomComplet() {
    if(estConnecte()) {
        return $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
    }
    return '';
}

/**
 * Déconnecter l'utilisateur
 */
function deconnecter() {
    session_unset();
    session_destroy();
}

/**
 * Rediriger vers la page de connexion si non connecté
 */
function requireLogin() {
    if(!estConnecte()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Rediriger vers la page de connexion si non coach
 */
function requireCoach() {
    requireLogin();
    if(!estCoach()) {
        header("Location: ../index.php");
        exit();
    }
}

/**
 * Rediriger vers la page de connexion si non sportif
 */
function requireSportif() {
    requireLogin();
    if(!estSportif()) {
        header("Location: ../index.php");
        exit();
    }
}
?>
