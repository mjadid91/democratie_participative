<main class="container py-5" style="max-width: 800px;">
    <h2 class="text-center mb-4">Modifier la Proposition</h2>
    <form method="post" action="routeur.php">
        <input type="hidden" name="page" value="propositions">
        <input type="hidden" name="action" value="modifierProposition">
        <input type="hidden" name="IDProposition" value="<?= htmlspecialchars($proposition['IDProposition']) ?>">

        <div class="mb-3">
            <label for="titreProposition" class="form-label">Nom de la Proposition</label>
            <input type="text" class="form-control" id="titreProposition" name="titreProposition"
                   value="<?= htmlspecialchars($proposition['titreProposition']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="descriptionProposition" class="form-label">Description</label>
            <textarea class="form-control" id="descriptionProposition" name="descriptionProposition" rows="5" required><?= htmlspecialchars($proposition['descriptionProposition']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="montantProposition" class="form-label">Montant</label>
            <input type="number" class="form-control" id="montantProposition" name="montantProposition" step="0.01"
                   value="<?= htmlspecialchars($proposition['montantProposition']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="dateFinProposition" class="form-label">Date et Heure de Fin</label>
            <input type="datetime-local" class="form-control" id="dateFinProposition" name="dateFinProposition"
                   value="<?= date('Y-m-d\TH:i', strtotime($proposition['dateFinProposition'])) ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="routeur.php?page=propositions&action=afficherPropositions&IDGroupe=<?= htmlspecialchars($proposition['IDGroupe']) ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</main>
