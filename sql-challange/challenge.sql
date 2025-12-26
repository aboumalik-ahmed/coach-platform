--challenge 1

SELECT 
    u.id AS coach_id,
    CONCAT(u.prenom, ' ', u.nom) AS coach_nom_complet,
    c.discipline,
    COUNT(s.id) AS nombre_seances
FROM 
    users u
    INNER JOIN coachs c ON u.id = c.user_id
    LEFT JOIN seances s ON c.user_id = s.coach_id
WHERE 
    u.role = 'coach'
GROUP BY 
    u.id, u.prenom, u.nom, c.discipline
ORDER BY 
    nombre_seances DESC;


--======================================================================

SELECT 
    CONCAT(u.prenom, ' ', u.nom) AS coach,
    c.discipline,
    COUNT(r.id) AS nombre_seances_reservees
FROM 
    users u
    INNER JOIN coachs c ON u.id = c.user_id
    INNER JOIN seances s ON c.user_id = s.coach_id
    INNER JOIN reservations r ON s.id = r.seance_id
WHERE 
    u.role = 'coach'
GROUP BY 
    u.id, u.prenom, u.nom, c.discipline
ORDER BY 
    nombre_seances_reservees DESC;


--====================================================================
SELECT 
    CONCAT(u.prenom, ' ', u.nom) AS coach,
    ROUND(
        (SUM(CASE WHEN s.statut = 'reservee' THEN 1 ELSE 0 END) * 100.0) / 
        COUNT(s.id), 
        2
    ) AS taux_reservation_pourcent
FROM users u
    INNER JOIN coachs c ON u.id = c.user_id
    LEFT JOIN seances s ON c.user_id = s.coach_id
WHERE u.role = 'coach'
GROUP BY u.id, u.prenom, u.nom
ORDER BY taux_reservation_pourcent DESC;



--====================================================================
SELECT 
    CONCAT(u.prenom, ' ', u.nom) AS coach,
    COUNT(s.id) AS total_seances
FROM users u
    INNER JOIN coachs c ON u.id = c.user_id
    LEFT JOIN seances s ON c.user_id = s.coach_id
WHERE u.role = 'coach'
GROUP BY u.id, u.prenom, u.nom
HAVING COUNT(s.id) >= 3
ORDER BY total_seances DESC;