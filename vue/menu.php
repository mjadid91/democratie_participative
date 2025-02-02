<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <img src="./img/logo.png" alt="" width="50" height="50">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= ($_GET['page'] ?? '') === 'main' ? 'active' : '' ?>" href="routeur.php?page=main">Accueil</a>
                    </li>
                    <?php if (isset($_SESSION['utilisateur'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'groupes' ? 'active' : '' ?>" href="routeur.php?page=groupes">Mes Groupes</a>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['utilisateur']) && roleDansGroupe::isModo($_SESSION['utilisateur']->get('loginUtilisateur'))): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="routeur.php?page=signalements&action=afficherSignalements">Signalements</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['utilisateur'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person fs-3"></i>

                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="nav-link <?= ($_GET['page'] ?? '') === 'profil' ? 'active' : '' ?>" href="routeur.php?page=utilisateur&action=afficherProfil">Mon Profil</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="routeur.php?page=connexion">Déconnexion</a>
                                </li>
                            </ul>
                        </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($_GET['page'] ?? '') === 'connexion' ? 'active' : '' ?>" href="routeur.php?page=connexion">Connexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
