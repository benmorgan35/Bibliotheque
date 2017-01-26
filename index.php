<?php

function chargerClasse($classname)
{
    require $classname . '.class.php';
}

spl_autoload_register('chargerClasse');

$db = new PDO('mysql:host=localhost;dbname=cours', 'root', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.

$manager = new LivresManager($db);


if (isset($_POST['creer']) && isset($_POST['titre']) && isset($_POST['auteur']) && isset($_POST['genre']) && isset($_POST['resume']) && isset($_POST['note'])) // Si on a voulu enregistrer un livre.
{
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $genre = $_POST['genre'];
    $resume = $_POST['resume'];
    $note = $_POST['note'];

    $livre = new Livre(['id' => 2, 'titre' => $titre, 'auteur' => $auteur, 'genre' => $genre, 'resume' => $resume, 'note' => $note]); // On ajoute un nouveau livre.

    if (!$livre->titreValide()) {
        $message = ' Le titre choisi est invalide.';
        unset($livre);
    } elseif ($manager->exists($livre->getTitre() . $livre->getAuteur())) {
        $message = 'Ce livre est déjà enregistré.';
        unset($livre);
    } else {
        $manager->add($livre);
    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Bibliothèque</title>

    <meta charset="utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" type="text/css">

</head>

<body>

<div class="container">

    <header class="page-header">
        <h1>Bibliothèque</h1>
    </header>

    <div class="row">

        <form action="index.php" method="post" class="form-inline">
            <fieldset class="cadre">
                <legend class="legend">Ajouter un livre</legend>
                <label>Titre </label><br/>
                <input type="text" name="titre" maxlenght="100"/><br/><br/>

                <label>Auteur</label><br/>
                <input type="text" name="auteur" maxlenght="100"/><br/><br/>

                <label>Genre</label><br/>
                <input type="text" name="genre" maxlenght="100"/><br/><br/>

                <label>Résumé</label><br/>
                <textarea name="resume" row="8" cols="100"></textarea><br/><br/>

                <label>Note de 0 à 10</label><br/>
                <select name="note">
                    <option value="option 1">0</option>
                    <option value="option 2">1</option>
                    <option value="option 3">2</option>
                    <option value="option 4">3</option>
                    <option value="option 5">4</option>
                    <option value="option 6">5</option>
                    <option value="option 7">6</option>
                    <option value="option 8">7</option>
                    <option value="option 9">8</option>
                    <option value="option 10">9</option>
                    <option value="option 11" selected="selected">10</option>
                </select><br/><br/>

                <input type="submit" value="Enregistrer" class="btn btn-default" name="creer"/>
            </fieldset>
            <hr>
        </form>

        <div class='row'>
            <form action="index.php" method="post">
                <table id="tableau" class="table table-bordered">
                    <caption>Liste des livres</caption>

                    <thead>
                    <tr>

                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Genre</th>
                        <th>Résumé</th>
                        <th>Note [0;10]</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php $articles = $manager->getList('');
                    if (empty($articles)) {
                        echo '<tr><td>Aucun livre enregistré</td></tr>';
                    } else {
                        foreach ($articles as $unLivre) {
                            echo '<td>' . $unLivre->getTitre() . '</td><td>' . $unLivre->getAuteur() . '</td><td>' . $unLivre->getGenre() . '</td><td>' . $unLivre->getResume() . '</td><td>' . $unLivre->getNote() . '</td></tr>';
                        }
                    }
                    ?>
                    </tbody>

                </table>
            </form>
        </div>
    </div>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script>$(document).ready(function () {
            $('#tableau').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.13/i18n/French.json'
                }
            });
        });</script>

</body>
</html>