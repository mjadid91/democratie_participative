<main class="container mt-5" style="max-width: 800px;">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); // Supprimez le message après affichage ?>
    <?php endif; ?>

    <form action="routeur.php" method="post" class="p-4 bg-light rounded">
        <input type="hidden" name="page" value="connexion">
        <h1 class="text-center">Connexion</h1>
        <?php
        foreach ($champs as $champ => $details) {
            echo "<div class=\"mb-3\">";
            echo "<label for=\"$champ\" class=\"form-label\">$details[1]</label>";
            echo "<input type=\"$details[0]\" class=\"form-control\" id=\"$champ\" name=\"$champ\" placeholder=\"$details[1]\" required>";
            echo "</div>";
        }
        ?>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </div>
        Vous n'avez pas de compte ? <a href="routeur.php?page=inscription">S'inscrire</a>
    </form>

    <?php if (isset($_SESSION['erreurConnexion'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['erreurConnexion']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['erreurConnexion']); ?>
    <?php endif; ?>
</main>
