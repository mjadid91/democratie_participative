<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-header text-white text-center" style="background-color: #072c6d;">
            <h1 class="mb-0 text-light">Signalements de Commentaires</h1>
        </div>
        <div class="card-body">
            <?php if (!empty($nomGroupe)): ?>
                <div class="alert alert-primary text-center">
                    Vous êtes modérateur du groupe : <strong><?= htmlspecialchars($nomGroupe) ?></strong>.
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['messageSignalementSupp'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= $_SESSION['messageSignalementSupp'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['messageSignalementSupp']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['messageErreurSuppSignalement'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['messageErreurSuppSignalement'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['messageErreurSuppSignalement']); ?>
            <?php endif; ?>

            <?php if (!empty($signalements)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                        <tr>
                            <th>Proposition</th>
                            <th>Commentaire</th>
                            <th>Motif</th>
                            <th>Date du Signalement</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($signalements as $signalement): ?>
                            <tr>
                                <td><?= htmlspecialchars($signalement['titreProposition']) ?></td>
                                <td class="text-start"><?= htmlspecialchars($signalement['texteCommentaire']) ?></td>
                                <td class="text-start"><?= htmlspecialchars($signalement['motifSignalement']) ?></td>
                                <td><?= date('d M Y, H:i', strtotime($signalement['dateSignalement'])) ?></td>
                                <td>
                                    <form action="routeur.php" method="post">
                                        <input type="hidden" name="page" value="signalements">
                                        <input type="hidden" name="action" value="supprimerCommentaire">
                                        <input type="hidden" name="IDCommentaire" value="<?= htmlspecialchars($signalement['IDCommentaire']) ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">Aucun signalement pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
