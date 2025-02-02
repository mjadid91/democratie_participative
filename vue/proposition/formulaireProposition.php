<main class="container mt-5" style="max-width: 800px;">
    <form action="routeur.php" method="post" class="p-4 bg-light rounded" enctype="multipart/form-data">
        <input type="hidden" name="page" value="propositions">
        <input type="hidden" name="action" value="creerProposition">
        <input type="hidden" name="IDGroupe" value="<?= htmlspecialchars($_GET['IDGroupe']) ?>">
        <h1 class="text-center mb-4">Créer une nouvelle proposition</h1>

        <?php
        foreach ($champs as $champ => $details):
            $inputType = $champ === 'dateFinProposition' ? 'datetime-local' : $details[0]; // Ajout du type datetime-local
            ?>
            <div class="mb-3">
                <label for="<?= $champ ?>" class="form-label"><?= $details[1] ?></label>
                <input
                        type="<?= $inputType ?>"
                        class="form-control"
                        id="<?= $champ ?>"
                        name="<?= $champ ?>"
                        placeholder="Entrez <?= $details[1] ?>"
                        required>
            </div>
        <?php endforeach; ?>

        <div class="mb-3 text-center">
            <button type="submit" class="btn btn-primary btn-lg">Créer la Proposition</button>
        </div>
    </form>
</main>
