<?php if (isset($_SESSION['erreurDate'])): ?>
    <div class="alert alert-danger text-center" role="alert">
        <?= $_SESSION['erreurDate']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['erreurDate']); // Supprimez le message après affichage ?>
<?php endif; ?>

<main class="container mt-5" style="max-width: 800px;">
    <h2 class="mb-4 text-center">Créer un Vote</h2>
    <form method="post" action="routeur.php" class="needs-validation" novalidate>
        <input type="hidden" name="page" value="propositions">
        <input type="hidden" name="action" value="creerVote">
        <input type="hidden" name="IDProposition" value="<?php echo htmlspecialchars($_GET['IDProposition'] ?? 0); ?>">

        <div class="mb-3">
            <label for="typeVote" class="form-label">Type de Vote</label>
            <select class="form-select" id="typeVote" name="typeVote" required>
                <option value="">-- Choisir un type de vote --</option>
                <option value="Majoritaire">Majoritaire</option>
                <option value="Consultatif">Consultatif</option>
                <option value="Decisif">Unanime</option>
            </select>
            <div class="invalid-feedback">Veuillez sélectionner un type de vote.</div>
        </div>

        <div class="mb-3">
            <label for="dateFinVote" class="form-label">Date et heure de fin</label>
            <input type="datetime-local" class="form-control" id="dateFinVote" name="dateFinVote" required>
            <div class="invalid-feedback">Veuillez entrer une date et une heure de fin.</div>
        </div>


        <div class="text-center">
            <button type="submit" class="btn btn-primary">Créer le Vote</button>
        </div>
    </form>
</main>
