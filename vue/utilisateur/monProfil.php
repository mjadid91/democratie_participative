<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header text-white" style="background-color: #072c6d;">
            <h1 class="card-title mb-0">Mon Profil</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center">
                    <h5 class="mt-3"><?= htmlspecialchars($utilisateur->get('prenomUtilisateur') . ' ' . $utilisateur->get('nomUtilisateur')) ?></h5>
                    <p class="text-muted">Pseudo : <?= htmlspecialchars($utilisateur->get('loginUtilisateur')) ?></p>
                </div>

                <div class="col-md-8">
                    <h4 class="mb-4">Informations personnelles</h4>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Email :</strong> <?= htmlspecialchars($utilisateur->get('emailUtilisateur')) ?></li>
                        <li class="list-group-item"><strong>Adresse :</strong> <?= htmlspecialchars($utilisateur->get('adresseUtilisateur')) ?></li>
                        <li class="list-group-item"><strong>Ville :</strong> <?= htmlspecialchars($utilisateur->get('villeUtilisateur')) ?></li>
                        <li class="list-group-item"><strong>Code Postal :</strong> <?= htmlspecialchars($utilisateur->get('cpUtilisateur')) ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
