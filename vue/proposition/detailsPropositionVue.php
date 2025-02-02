<div class="container mt-5">
    <div class="mb-4">
        <a href="routeur.php?page=propositions&action=afficherPropositions&IDGroupe=<?= htmlspecialchars($proposition['IDGroupe']) ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Revenir aux propositions
        </a>
    </div>
    
    <!-- Affichage des détails de la proposition -->
    <div class="card shadow-lg rounded-lg border-0 mb-5">
        <div class="card-header" style="background-color: #072c6d; color: white; border-top-left-radius: .375rem; border-top-right-radius: .375rem;">
            <h1 class="card-title mb-0 text-center">
                <i class="bi bi-lightbulb me-2"></i><?= htmlspecialchars($proposition['titreProposition']) ?>
            </h1>
        </div>
        <div class="card-body bg-light">
            <p class="fs-5"><strong>Description :</strong> <?= nl2br(htmlspecialchars($proposition['descriptionProposition'])) ?></p>
            <p><strong>Date de Soumission :</strong> <?= date('d M Y, H:i', strtotime($proposition['dateSoumissionProposition'])) ?></p>
            <p><strong>Se termine le :</strong> <?= date('d M Y, H:i', strtotime($proposition['dateFinProposition'])) ?></p>
            <p><strong>État :</strong> <span class="badge bg-primary"><?= htmlspecialchars($proposition['etatProposition']) ?></span></p>
            <p><strong>Montant proposé :</strong> <span class="text-success fw-bold"><?= number_format($proposition['montantProposition'], 2, ',', ' ') ?> €</span></p>
        </div>
        <div class="card-footer text-muted text-center bg-secondary bg-opacity-10">
            <small>Proposée par : <strong><?= htmlspecialchars($proposition['loginUtilisateur']) ?></strong></small>
        </div>
    </div>

    <?php if (isset($_SESSION['messageVoteSoumis'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['messageVoteSoumis']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['messageVoteSoumis']); ?>
    <?php endif; ?>

    <div class="row">
        <!-- Section Commentaires -->
        <div class="col-md-8">
            <div class="mb-5">
                <h2 class="mb-4" style="color: #072c6d;"><i class="bi bi-chat-left-text me-2"></i>Commentaires</h2>
                <?php if (!empty($commentaires)): ?>
                    <div class="list-group">
                        <?php foreach ($commentaires as $commentaire): ?>
                            <!-- Affichage des commentaires -->
                            <div class="list-group-item d-flex flex-column mb-3 p-3 shadow-sm rounded" style="background-color: #f0f4f8; border-left: 4px solid #072c6d;">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="fw-bold" style="color: #072c6d;"><?= htmlspecialchars($commentaire['loginUtilisateur']) ?></h6>
                                        <p class="text-muted mb-1"><?= nl2br(htmlspecialchars($commentaire['texteCommentaire'])) ?></p>
                                        <small class="text-muted">Le <?= date('d M Y, H:i', strtotime($commentaire['dateCommentaire'])) ?></small>
                                    </div>
                                    <?php if (!empty($commentaire['reactions'])): ?>
                                        <div class="text-end">
                                            <ul class="list-inline">
                                                <?php
                                                $reactions = explode(';', $commentaire['reactions']);
                                                foreach ($reactions as $reactionDetail):
                                                    list($typeReaction, $loginUtilisateur) = explode(':', $reactionDetail);
                                                    ?>
                                                    <li class="list-inline-item">
                                                        <span class="badge bg-light text-dark">
                                                            <?php if ($typeReaction === 'Jaime'): ?>
                                                                <i class="bi bi-hand-thumbs-up-fill text-primary"></i>
                                                            <?php elseif ($typeReaction === 'Jaime pas'): ?>
                                                                <i class="bi bi-hand-thumbs-down-fill text-danger"></i>
                                                            <?php elseif ($typeReaction === 'Jadore'): ?>
                                                                <i class="bi bi-heart-fill text-danger"></i>
                                                            <?php elseif ($typeReaction === 'Mort de rire'): ?>
                                                                <i class="bi bi-emoji-laughing-fill text-warning"></i>
                                                            <?php elseif ($typeReaction === 'Triste'): ?>
                                                                <i class="bi bi-emoji-frown-fill text-secondary"></i>
                                                            <?php else: ?>
                                                                <?= htmlspecialchars($typeReaction) ?>
                                                            <?php endif; ?>
                                                            <small><?= htmlspecialchars($loginUtilisateur) ?></small>
                                                        </span>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-end">
                                            <small class="text-muted fst-italic">Aucune réaction.</small>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($commentaire['loginUtilisateur'] === $_SESSION['utilisateur']->get('loginUtilisateur')): ?>
                                        <!-- Menu déroulant pour actions -->
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm" type="button" id="dropdownMenuButton<?= $commentaire['IDCommentaire'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton<?= $commentaire['IDCommentaire'] ?>">
                                                <li>
                                                    <form action="routeur.php" method="post">
                                                        <input type="hidden" name="page" value="propositions">
                                                        <input type="hidden" name="action" value="supprimerCommentaire">
                                                        <input type="hidden" name="IDCommentaire" value="<?= htmlspecialchars($commentaire['IDCommentaire']) ?>">
                                                        <input type="hidden" name="IDProposition" value="<?= htmlspecialchars($proposition['IDProposition']) ?>">
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash"></i> Supprimer le commentaire
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-start mt-2">
                                    <?php
                                    $typesReactions = ['Jaime', 'Jaime pas', 'Jadore', 'Mort de rire', 'Triste'];
                                    foreach ($typesReactions as $type) {
                                        $estActive = !empty($commentaire['reactions']) && strpos($commentaire['reactions'], $type . ':' . $_SESSION['utilisateur']->get('loginUtilisateur')) !== false;
                                        ?>
                                        <form action="routeur.php" method="post" class="me-2">
                                            <input type="hidden" name="page" value="propositions">
                                            <input type="hidden" name="action" value="ajouterReaction">
                                            <input type="hidden" name="IDCommentaire" value="<?= $commentaire['IDCommentaire'] ?>">
                                            <input type="hidden" name="IDProposition" value="<?= $proposition['IDProposition'] ?>">
                                            <input type="hidden" name="typeReaction" value="<?= $type ?>">
                                            <button type="submit" class="btn btn-light btn-sm <?= $estActive ? 'active' : '' ?>">
                                                <?php if ($type === 'Jaime'): ?>
                                                    <i class="bi bi-hand-thumbs-up <?= $estActive ? 'text-primary' : '' ?>"></i>
                                                <?php elseif ($type === 'Jaime pas'): ?>
                                                    <i class="bi bi-hand-thumbs-down <?= $estActive ? 'text-danger' : '' ?>"></i>
                                                <?php elseif ($type === 'Jadore'): ?>
                                                    <i class="bi bi-heart <?= $estActive ? 'text-danger' : '' ?>"></i>
                                                <?php elseif ($type === 'Mort de rire'): ?>
                                                    <i class="bi bi-emoji-laughing <?= $estActive ? 'text-warning' : '' ?>"></i>
                                                <?php elseif ($type === 'Triste'): ?>
                                                    <i class="bi bi-emoji-frown <?= $estActive ? 'text-secondary' : '' ?>"></i>
                                                <?php endif; ?>
                                            </button>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted fst-italic">Aucun commentaire pour cette proposition.</p>
                <?php endif; ?>
            </div>

            <div class="card shadow-sm p-4">
                <h5 class="text-black mb-3">Ajouter un commentaire</h5>
                <?php
                $dateFin = new DateTime($proposition['dateFinProposition']);
                $dateActuelle = new DateTime();
                $statutProposition = $proposition['etatProposition'];
                $propositionTerminee = $dateFin < $dateActuelle;
                if ($statutProposition === 'Validée' || $statutProposition === 'Rejetée' || $dateActuelle >= $dateFin): ?>
                    <p class="text-muted">
                        La publication des commentaires est désactivée pour cette proposition.
                        Les commentaires ne peuvent être ajoutés que pour les propositions en cours.
                    </p>
                <?php else: ?>
                    <form action="routeur.php" method="post">
                        <input type="hidden" name="page" value="propositions">
                        <input type="hidden" name="action" value="ajouterCommentaire">
                        <input type="hidden" name="IDProposition" value="<?= htmlspecialchars($proposition['IDProposition']) ?>">

                        <div class="mb-3">
                            <textarea id="texteCommentaire" class="form-control" name="texteCommentaire" rows="5" placeholder="Exprimez-vous..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2">
                            <i class="bi bi-send me-2"></i>Envoyer
                        </button>
                    </form>
                <?php endif; ?>
            </div>

        </div>
        <div class="col-md-4">
            <h2 class="mb-4" style="color: #072c6d;"><i class="bi bi-box-arrow-up-right me-2"></i>Votes</h2>

            <?php if (!empty($votes)): ?>
                <?php foreach ($votes as $vote): ?>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #072c6d;">Type de vote : <?= htmlspecialchars($vote['typeVote']) ?></h5>
                            <p class="text-muted mb-1">Début : <?= htmlspecialchars($vote['dateDebutVote']) ?></p>
                            <p class="text-muted mb-1">Fin : <?= date('d M Y, H:i', strtotime($vote['dateFinVote'])) ?></p>

                            <?php
                            $dateFin = new DateTime($vote['dateFinVote']);
                            $dateActuelle = new DateTime();
                            $voteTermine = $dateFin < $dateActuelle;
                            ?>

                            <?php if (!$voteTermine): ?>
                                <?php
                                $interval = $dateActuelle->diff($dateFin);
                                $tempsRestant = [];
                                if ($interval->d > 0) {
                                    $tempsRestant[] = $interval->d . ' jour(s)';
                                }
                                if ($interval->h > 0 || $interval->d > 0) {
                                    $tempsRestant[] = $interval->h . ' heure(s)';
                                }
                                $tempsRestant[] = $interval->i . ' minute(s)';
                                $tempsRestantTexte = implode(', ', $tempsRestant);
                                ?>
                                <p class="text-danger fw-bold">Temps restant : <?= $tempsRestantTexte ?></p>

                                <?php if (!empty($utilisateurVote)): ?>
                                    <div class="alert alert-info">
                                        Vous avez voté : <strong><?= htmlspecialchars($utilisateurVote['sensVote']) ?></strong>
                                    </div>
                                <?php endif; ?>

                                <form action="routeur.php" method="post">
                                    <input type="hidden" name="page" value="propositions">
                                    <input type="hidden" name="action" value="soumettreVote">
                                    <input type="hidden" name="IDProposition" value="<?= htmlspecialchars($proposition['IDProposition']) ?>">
                                    <div class="d-flex justify-content-around">
                                        <button type="submit" name="valeurVote" value="1" class="btn btn-success w-45">
                                            <i class="bi bi-check-circle"></i> Pour
                                        </button>
                                        <button type="submit" name="valeurVote" value="0" class="btn btn-danger w-45">
                                            <i class="bi bi-x-circle"></i> Contre
                                        </button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <p class="text-muted">Vote terminé.</p>
                                <?php
                                $majorite = htmlspecialchars($vote['majoriteVote']);
                                $texteMajorite = ($majorite === 'Pour') ? 'La majorité est POUR' : (($majorite === 'Contre') ? 'La majorité est CONTRE' : 'Résultat non déterminé');
                                ?>
                                <p class="text-primary fw-bold text-center"><?= $texteMajorite ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif (!$propositionTerminee && $_SESSION['utilisateur']->get('loginUtilisateur') === $proposition['loginUtilisateur']): ?>
                <div class="text-center">
                    <a href="routeur.php?page=propositions&action=afficherFormulaireVote&IDProposition=<?= htmlspecialchars($proposition['IDProposition']) ?>" class="btn btn-primary">
                        Créer un vote
                    </a>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">
                    La création de vote n'est plus possible car la proposition a atteint sa date de fin.
                </p>
            <?php endif; ?>
        </div>

    </div>
</div>
