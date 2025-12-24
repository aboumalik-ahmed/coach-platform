    </main>
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>🏃‍♂️ Sportify</h3>
                    <p>Votre plateforme de mise en relation avec des coachs professionnels</p>
                </div>
                <div class="footer-section">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? '../index.php' : 'index.php'; ?>">Accueil</a></li>
                        <li><a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? 'coachs.php' : 'pages/coachs.php'; ?>">Coachs</a></li>
                        <li><a href="<?php echo strpos($_SERVER['PHP_SELF'], '/pages/') !== false ? 'seances.php' : 'pages/seances.php'; ?>">Séances</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Email: contact@sportify.com</p>
                    <p>Tél: +212 6 00 00 00 00</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Sportify. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
