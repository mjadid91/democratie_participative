<main class="container py-5" style="max-width: 800px;">
    <h2 class="text-center mb-4">Modifier le Groupe</h2>
    <form method="post" action="routeur.php">
        <input type="hidden" name="page" value="groupes">
        <input type="hidden" name="action" value="modifierGroupe">
        <input type="hidden" name="IDGroupe" value="<?= htmlspecialchars($groupe->get('IDGroupe')) ?>">

        <div class="mb-3">
            <label for="nomGroupe" class="form-label">Nom du Groupe</label>
            <input type="text" class="form-control" id="nomGroupe" name="nomGroupe"
                   value="<?= htmlspecialchars($groupe->get('nomGroupe')) ?>" required>
        </div>

        <div class="mb-3">
            <label for="descriptionGroupe" class="form-label">Description</label>
            <textarea class="form-control" id="descriptionGroupe" name="descriptionGroupe" rows="5" required><?= htmlspecialchars($groupe->get('descriptionGroupe')) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="montantTotalBudget" class="form-label">Budget Total</label>
            <input type="number" class="form-control" id="montantTotalBudget" name="montantTotalBudget" step="0.01"
                   value="<?= htmlspecialchars($budget['montantTotalBudget']) ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="routeur.php?page=groupes" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</main>
