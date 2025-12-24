SELECT 
    u.nom,
    u.prenom,
    COUNT(s.id) AS total_seances
FROM 
    coachs c
    INNER JOIN users u ON c.user_id = u.id
    INNER JOIN seances s ON c.user_id = s.coach_id
GROUP BY 
    c.user_id, u.nom, u.prenom;


    SELECT 
    c.user_id AS coach_id,
    u.nom,
    u.prenom,
    COUNT(r.id) AS nombre_seances_reservees
FROM coachs c
JOIN users u ON u.id = c.user_id
LEFT JOIN seances s ON s.coach_id = c.user_id
LEFT JOIN reservations r ON r.seance_id = s.id
GROUP BY c.user_id, u.nom, u.prenom;



SELECT 
    u.nom,
    u.prenom,
    ROUND((COUNT(r.id) / COUNT(s.id)) * 100, 2) AS taux_reservation_pct
FROM 
    coachs c
    INNER JOIN users u ON c.user_id = u.id
    INNER JOIN seances s ON c.user_id = s.coach_id
    LEFT JOIN reservations r ON s.id = r.seance_id
GROUP BY 
    c.user_id, u.nom, u.prenom;




SELECT 
    u.nom,
    u.prenom
FROM 
    coachs c
    INNER JOIN users u ON c.user_id = u.id
    INNER JOIN seances s ON c.user_id = s.coach_id
GROUP BY 
    c.user_id, u.nom, u.prenom
HAVING 
    COUNT(s.id) >= 3;