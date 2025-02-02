<main class="container mt-5" style="max-width: 800px;">
    <form action="routeur.php" method="post" class="p-4 bg-light rounded">
        <input type="hidden" name="page" value="inscription">
        <h1 class="text-center">Inscription</h1>
        <?php
        foreach($champs as $champ => $details) {
            echo "<div class=\"mb-3\">";
            echo "<label for=\"$champ\" class=\"form-label\">$details[1]</label>";
            echo "<input type=\"$details[0]\" class=\"form-control\" id=\"$champ\" name=\"$champ\" placeholder=\"$details[1]\" required>";
            echo "</div>";
        }
        ?>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </div>
        Déjà un compte ? <a href="routeur.php?page=connexion"> Se connecter</a>
    </form>
</main>