<div class="container mt-5">
    <button class="btn btn-primary position-fixed  end-0 rounded-circle" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMembres" aria-controls="offcanvasMembres">
        <i class="bi bi-people-fill"></i>
    </button>
    <h2 class="text-center mb-4"><?= htmlspecialchars($nomGroupe) ?></h2>
    <p class="text-center text-muted">Liste des propositions soumises par les membres du groupe.</p>

    <?php if (isset($_SESSION['msgErreurAjout'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['msgErreurAjout']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msgErreurAjout']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['msgProposition'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['msgProposition']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msgProposition']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['msgAjoutMembre'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['msgAjoutMembre']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msgAjoutMembre']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['msgModification'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['msgModification']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msgModification']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['msgErreurModification'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['msgErreurModification']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msgErreurModification']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['msgSuppressionMembre'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['msgSuppressionMembre']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msgSuppressionMembre']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['msgErreurSuppression'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['msgErreurSuppression']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msgErreurSuppression']); ?>
    <?php endif; ?>



    <?php if (isset($budgetGroupe)): ?>
        <div class="text-center mb-4">
            <h4 class="mb-3">Budget du Groupe</h4>
            <div class="progress" style="height: 30px; border: 1px solid #ddd; border-radius: 5px;">
                <?php
                $montantUtilise = $budgetGroupe['montantTotalBudget'] - $budgetGroupe['montantDisponible'];
                $pourcentageUtilise = ($budgetGroupe['montantTotalBudget'] > 0)
                    ? ($montantUtilise / $budgetGroupe['montantTotalBudget']) * 100
                    : 0;
                ?>
                <div class="progress-bar bg-primary" role="progressbar"
                     style="width: <?= $pourcentageUtilise ?>%;"
                     aria-valuenow="<?= $pourcentageUtilise ?>"
                     aria-valuemin="0"
                     aria-valuemax="100">
                    <?= $pourcentageUtilise > 0 ? "Utilisé : " . number_format($montantUtilise, 2, ',', ' ') . " €" : "" ?>
                </div>
            </div>
            <div class="mt-2">
            <span class="badge bg-secondary">
                Budget Disponible : <?= number_format($budgetGroupe['montantDisponible'], 2, ',', ' ') ?> €
            </span>
                <span class="badge bg-dark">
                Budget Maximal : <?= number_format($budgetGroupe['montantTotalBudget'], 2, ',', ' ') ?> €
            </span>
            </div>
        </div>
    <?php endif; ?>



    <?php if (isset($_SESSION['utilisateur'])): ?>
        <div class="text-center mb-4">
            <a href="routeur.php?page=propositions&action=afficherFormulaireProposition&IDGroupe=<?= htmlspecialchars($IDGroupe) ?>" class="btn btn-primary px-4 py-2">
                <i class="bi bi-plus-circle me-2"></i>Créer une Proposition
            </a>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
                <?php if (isset($propositions) && count($propositions) > 0): ?>
                    <div class="row g-4">
                        <?php foreach ($propositions as $proposition): ?>
                            <div class="col-md-6">
                                <div class="card shadow-sm h-100">
                                    <div class="card-header" style="background-color: #072c6d; color: white; border-top-left-radius: .375rem; border-top-right-radius: .375rem;">
                                        <h5 class="card-title mb-0"><?= nl2br(htmlspecialchars($proposition['titreProposition'])) ?></h5>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <p class="card-text text-muted"><?= nl2br(htmlspecialchars($proposition['descriptionProposition'])) ?></p>
                                        <small class="text-muted mt-auto">Soumise le : <?= date('d M Y', strtotime($proposition['dateSoumissionProposition'])) ?></small>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center bg-light">
                                        <span class="badge btn bg-dark text-white">Fin : <?= date('d M Y', strtotime($proposition['dateFinProposition'])) ?></span>
                                        <a href="routeur.php?page=propositions&action=afficherDetailsProposition&IDProposition=<?= $proposition['IDProposition'] ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i> Consulter
                                        </a>
                                        <?php if ($_SESSION['utilisateur']->get('loginUtilisateur') === $proposition['loginUtilisateur']): ?>
                                            <a href="routeur.php?page=propositions&action=afficherFormulaireModifierProposition&IDProposition=<?= $proposition['IDProposition'] ?>" class="btn btn-outline-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Modifier
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                <?php else: ?>
                    <p class="text-center text-muted">Aucune proposition disponible pour ce groupe.</p>
                <?php endif; ?>
            </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasMembres" aria-labelledby="offcanvasMembresLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasMembresLabel" class="">Membres du Groupe</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <?php if (!empty($membres)): ?>
                        <ul class="list-group mb-4">
                            <?php foreach ($membres as $membre): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($membre['loginUtilisateur']) ?>
                                    <span class="badge bg-black">Membre</span>
                                    <?php if (roleDansGroupe::isAdmin($_SESSION['utilisateur']->get('loginUtilisateur'), $IDGroupe)
                                        && $membre['loginUtilisateur'] !== $_SESSION['utilisateur']->get('loginUtilisateur')): ?>
                                        <form action="routeur.php" method="post" class="d-inline">
                                            <input type="hidden" name="page" value="invitation">
                                            <input type="hidden" name="action" value="supprimerMembre">
                                            <input type="hidden" name="IDGroupe" value="<?= htmlspecialchars($IDGroupe) ?>">
                                            <input type="hidden" name="loginUtilisateur" value="<?= htmlspecialchars($membre['loginUtilisateur']) ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-center text-muted mb-4">Aucun membre dans ce groupe pour le moment.</p>
                    <?php endif; ?>

                    <?php if (roleDansGroupe::isAdmin($_SESSION['utilisateur']->get('loginUtilisateur'), $IDGroupe)): ?>
                        <div class="card shadow-sm border border-primary mx-auto" style="max-width: 20rem;">
                            <div class="card-header text-white text-center" style="background-color: #072c6d;">
                                <h6 class="card-title mb-0">Ajouter un Membre</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small text-center">Ajoutez un utilisateur existant par son pseudo.</p>
                                <form action="routeur.php" method="post">
                                    <input type="hidden" name="page" value="invitation">
                                    <input type="hidden" name="action" value="ajouterMembre">
                                    <input type="hidden" name="IDGroupe" value="<?= htmlspecialchars($IDGroupe) ?>">
                                    <div class="mb-2">
                                        <input type="text" class="form-control form-control-sm" id="loginUtilisateur" name="loginUtilisateur" placeholder="Pseudo" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bi bi-person-plus"></i> Ajouter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
    </div>
</div>

