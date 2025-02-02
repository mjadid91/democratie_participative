<main class="container mt-5">

    <?php if (isset($_SESSION['messageConnexion'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['messageConnexion']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['messageConnexion']);?>
    <?php endif; ?>

    <?php if (isset($_SESSION['messageDeconnexion'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['messageDeconnexion']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['messageDeconnexion']); ?>
    <?php endif; ?>

    <!-- Section d'introduction -->
    <div class="welcome-section text-center mb-5" style="background: linear-gradient(to right, rgba(0,124,212,0.76), #36449e); padding: 80px 0; border-radius: 10px;">
        <h1 class="display-4 text-white animated fadeIn">Bienvenue sur Démocratie Participative</h1>
        <p class="lead text-white animated fadeIn">Un espace où votre voix compte pour bâtir ensemble des décisions éclairées et équitables.</p>

        <div class="d-flex justify-content-center">
            <!-- Vérifie si l'utilisateur est connecté -->
            <?php if (isset($_SESSION['utilisateur'])): ?>
                <!-- Si l'utilisateur est connecté, affiche le bouton "Consulter mes groupes" -->
                <a href="routeur.php?page=groupes" class="btn btn-dark btn-lg shadow-lg animated fadeIn">Consulter mes groupes</a>
            <?php else: ?>

                <a href="routeur.php?page=connexion" class="btn btn-light btn-lg shadow-lg me-3 animated fadeIn">Se connecter</a>
                <a href="routeur.php?page=inscription" class="btn btn-dark btn-lg shadow-lg animated fadeIn">S'inscrire</a>
            <?php endif; ?>
        </div>
    </div>


    <!-- Section d'informations avec des icônes modernes et animations -->
    <section class="info-section mt-5">
        <h2 class="text-center mb-5">Pourquoi rejoindre notre plateforme ?</h2>
        <div class="row">
            <!-- Carte 1 -->
            <div class="col-md-4 mb-4">
                <div class="info-card card shadow-lg rounded-lg border-0 p-4 hover-card" style="transition: transform 0.3s;">
                    <div class="card-body text-center">
                        <h3 class="card-title">📊 Votez</h3>
                        <p class="card-text">Votez pour les propositions qui vous tiennent à cœur et qui vous semblent les plus pertinentes.</p>
                    </div>
                </div>
            </div>
            <!-- Carte 2 -->
            <div class="col-md-4 mb-4">
                <div class="info-card card shadow-lg rounded-lg border-0 p-4 hover-card" style="transition: transform 0.3s;">
                    <div class="card-body text-center">
                        <h3 class="card-title">💬 Participez</h3>
                        <p class="card-text">Participez aux discussions et aux débats pour faire entendre votre voix et vos idées.</p>
                    </div>
                </div>
            </div>
            <!-- Carte 3 -->
            <div class="col-md-4 mb-4">
                <div class="info-card card shadow-lg rounded-lg border-0 p-4 hover-card" style="transition: transform 0.3s;">
                    <div class="card-body text-center">
                        <h3 class="card-title">🌟 Influencez</h3>
                        <p class="card-text">Influencez directement les choix et les projets de votre communauté grâce à vos contributions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section sur la mission -->
    <section class="mission-section mt-5 text-center">
        <div class="container p-4">
            <h3 class="mb-4">Notre Mission</h3>
            <p class="lead text-dark">
                🌍 Chez <strong>Démocratie Participative</strong>, nous croyons que chaque voix mérite d’être entendue. Notre plateforme offre un espace inclusif où citoyens, organisations, et communautés peuvent collaborer pour construire des solutions qui répondent aux besoins de tous.
            </p>
            <p class="text-secondary">
                🤝 Ensemble, nous façonnons l’avenir grâce à des propositions concrètes, des discussions enrichissantes et un engagement actif. Rejoignez-nous pour faire la différence, car vos idées sont le moteur du changement. 🚀
            </p>
        </div>
    </section>
</main>

