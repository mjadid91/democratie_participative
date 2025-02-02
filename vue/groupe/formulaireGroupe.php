<main class="container mt-5" style="max-width: 800px;">
    <form action="routeur.php" method="post" class="p-4 bg-light rounded" enctype="multipart/form-data">
        <input type="hidden" name="page" value="groupes">
        <input type="hidden" name="action" value="creerGroupe">

        <h1 class="text-center">Créer un Nouveau Groupe</h1>

        <?php
        foreach ($champs as $champ => $details) {
            echo "<div class=\"mb-3\">";
            echo "<label for=\"$champ\" class=\"form-label\">$details[1]</label>";

            if ($details[0] === "file") {
                echo "<input type=\"file\" class=\"form-control\" id=\"$champ\" name=\"$champ\">";
            } else {
                echo "<input type=\"$details[0]\" class=\"form-control\" id=\"$champ\" name=\"$champ\" placeholder=\"$details[1]\" required>";
            }
            echo "</div>";
        }
        ?>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Créer le groupe</button>
        </div>
    </form>
</main>
