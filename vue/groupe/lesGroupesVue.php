<main class="container py-5" style="max-width: 1000px;">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">📋 Mes Groupes</h1>
        <p class="text-muted fs-5">Gérez vos groupes et explorez ceux auxquels vous appartenez.</p>
        <a href="routeur.php?page=groupes&action=afficherFormulaireGroupe"
           class="btn btn-primary px-4 py-2">
            <i class="bi bi-plus-circle me-2"></i>Créer un groupe
        </a>
    </div>
    <?php if (isset($_SESSION['messageGroupeCreer'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['messageGroupeCreer']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['messageGroupeCreer']); ?>
    <?php endif; ?>


    <!-- Section Groupes Administrés -->
    <section class="mb-5">
        <h2 class="h4 mb-4"><i class="bi bi-gear-fill me-2"></i>Groupes Administrés</h2>
        <div class="row g-4">
            <?php if (!empty($tabGroupesAdmin)) : ?>
                <?php foreach ($tabGroupesAdmin as $groupe) : ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header custom-bg text-white text-center">
                                <h5 class="card-title mb-0"><?= nl2br(htmlspecialchars($groupe->get('nomGroupe'))) ?></h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-secondary"><?= nl2br(htmlspecialchars($groupe->get('descriptionGroupe'))) ?></p>
                                <a href="routeur.php?page=propositions&action=afficherPropositions&IDGroupe=<?= $groupe->get('IDGroupe') ?>"
                                   class="btn btn-outline-primary btn-sm w-100">Voir les Propositions</a>
                                <a href="routeur.php?page=groupes&action=afficherFormulaireModifierGroupe&IDGroupe=<?= $groupe->get('IDGroupe') ?>"
                                   class="btn btn-outline-danger btn-sm w-100 mt-2">Modifier</a>

                            </div>
                            <div class="card-footer text-center">
                                <span class="badge btn bg-danger text-white">Admin</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="text">
                    <p class="text-muted fst-italic">Vous ne gérez aucun groupe pour le moment. Créez en un !</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Section Tous les Groupes -->
    <section>
        <h2 class="h4 mb-4"><i class="bi bi-people-fill me-2"></i>Tout mes groupes</h2>
        <div class="row g-4">
            <?php if (!empty($groupesUniques)) : ?>
                <?php foreach ($groupesUniques as $groupe) : ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header custom-bg text-white text-center">
                                <h5 class="card-title mb-0"><?= nl2br(htmlspecialchars($groupe->get('nomGroupe'))) ?></h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-secondary"><?= nl2br(htmlspecialchars($groupe->get('descriptionGroupe'))) ?></p>
                                <a href="routeur.php?page=propositions&action=afficherPropositions&IDGroupe=<?= $groupe->get('IDGroupe') ?>"
                                   class="btn btn-outline-dark btn-sm w-100">Consulter le groupe</a>
                            </div>
                            <div class="card-footer text-center">
                                <span class="badge btn bg-dark text-white"><?= in_array($groupe, $tabGroupesAdmin) ? "Admin" : "Membre" ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="text">
                    <p class="text-muted fst-italic">Vous n'appartenez à aucun groupe pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

