<?php
// includes/footer.php
?>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>🌱 EcoColeta</h3>
                    <p>Sistema de coleta de recicláveis que conecta moradores e coletores para um planeta mais sustentável.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook">📘</a>
                        <a href="#" aria-label="Instagram">📷</a>
                        <a href="#" aria-label="Twitter">🐦</a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Links Rápidos</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="cadastro.php">Cadastrar</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="rotas.php">Rotas de Coleta</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Tipos de Usuários</h4>
                    <ul>
                        <li><a href="cadastro.php?tipo=morador">Morador</a></li>
                        <li><a href="cadastro.php?tipo=coletor">Coletor Individual</a></li>
                        <li><a href="cadastro.php?tipo=empresa">Empresa</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contato</h4>
                    <ul>
                        <li>📧 contato@ecocoleta.com</li>
                        <li>📞 (11) 99999-9999</li>
                        <li>📍 São Paulo, SP</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 EcoColeta. Todos os direitos reservados. ♻️</p>
                <p>Desenvolvido com ❤️ para um planeta mais verde.</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <?php if (file_exists('js/' . basename($_SERVER['PHP_SELF'], '.php') . '.js')): ?>
        <script src="js/<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>.js"></script>
    <?php endif; ?>
</body>
</html>