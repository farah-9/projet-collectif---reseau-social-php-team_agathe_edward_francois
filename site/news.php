<?php
session_start();
if (!isset($_SESSION['connected_id'])){
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Actualités</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
        <link rel="stylesheet" href="style copie.css">
    </head>
    <body>
    <?php 
        include_once 'header.php';
        ?>
        <!-- <header>
            <a href='admin.php'><img src="resoc.jpg" alt="Logo de notre réseau social"/></a>
            <nav id="menu">
                <a href="news.php">Actualités</a>
                <a href="wall.php?user_id=5">Mur</a>
                <a href="feed.php?user_id=5">Flux</a>
                <a href="tags.php?tag_id=1">Mots-clés</a>
            </nav>
            <nav id="user">
                <a href="#">▾ Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=5">Paramètres</a></li>
                    <li><a href="followers.php?user_id=5">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=5">Mes abonnements</a></li>
                </ul>
            </nav>
        </header> -->
        <div id="wrapper">
            <aside>
            <img src="User1.jpg" alt="Portrait de l'utilisatrice" style="border-radius: 50%;">
           
                <section>
                    
                </section>
            </aside>
            <main>
                <!-- L'article qui suit est un exemple pour la présentation et 
                @todo: doit etre retiré -->
                <!-- <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13' >31 février 2010 à 11h12</time>
                    </h3>
                    <address>par AreTirer</address>
                    <div>
                        <p>Ceci est un paragraphe</p>
                        <p>Ceci est un autre paragraphe</p>
                        <p>... de toutes manières il faut supprimer cet 
                            article et le remplacer par des informations en 
                            provenance de la base de donnée (voir ci-dessous)</p>
                    </div>                                            
                    <footer>
                        <small>♥1012 </small>
                        <a href="">#lorem</a>,
                        <a href="">#piscitur</a>,
                    </footer>
                </article>                -->

                <?php
                /*
                  // C'est ici que le travail PHP commence
                  // Votre mission si vous l'acceptez est de chercher dans la base
                  // de données la liste des 5 derniers messsages (posts) et
                  // de l'afficher
                  // Documentation : les exemples https://www.php.net/manual/fr/mysqli.query.php
                  // plus généralement : https://www.php.net/manual/fr/mysqli.query.php
                 */

                // Etape 1: Ouvrir une connexion avec la base de donnée.
                $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                //verification
                if ($mysqli->connect_errno)
                {
                    echo "<article>";
                    echo("Échec de la connexion : " . $mysqli->connect_error);
                    echo("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                    echo "</article>";
                    exit();
                }

                // Etape 2: Poser une question à la base de donnée et récupérer ses informations
                // cette requete vous est donnée, elle est complexe mais correcte, 
                // si vous ne la comprenez pas c'est normal, passez, on y reviendra
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.id as post_number,
                    posts.created,
                    users.alias as author_name,  
                    users.id as id,
                    tags.id as tagId,
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id, tags.id
                    ORDER BY posts.created DESC  
                    LIMIT 5
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Vérification
                if ( ! $lesInformations)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }
                $post = $lesInformations->fetch_assoc();

                if (isset($_POST['like_button'])) {
                    // Récupérer l'ID du post à liker depuis le formulaire
                    $post_id = $_POST['post_id'];

                    // Mettre à jour le nombre de likes dans la base de données
                    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                    if ($mysqli->connect_errno) {
                        echo "Échec de la connexion : " . $mysqli->connect_error;
                        exit();
                    }

                    $updateQuery = "INSERT INTO likes (`user_id`, `post_id`) VALUES ('" . $_SESSION['connected_id'] . "', '" . $post['post_number'] . "')";
                    if ($mysqli->query($updateQuery)) {
                        echo "Le post a été liké avec succès.";
                    } else {
                        echo "Une erreur s'est produite lors du like du post : " . $mysqli->error;
                    }
                    $mysqli->close();                                
                    }
                            
                // Etape 3: Parcourir ces données et les ranger bien comme il faut dans du html
                // NB: à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
                while ($post = $lesInformations->fetch_assoc())
                {
                    //la ligne ci-dessous doit etre supprimée mais regardez ce 
                    //qu'elle affiche avant pour comprendre comment sont organisées les information dans votre 
                    //echo "<pre>" . print_r($post, 1) . "</pre>";

                    // @todo : Votre mission c'est de remplacer les AREMPLACER par les bonnes valeurs
                    // ci-dessous par les bonnes valeurs cachées dans la variable $post 
                    // on vous met le pied à l'étrier avec created
                    // 
                    // avec le ? > ci-dessous on sort du mode php et on écrit du html comme on veut... mais en restant dans la boucle
                    ?>
                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                        <address><a href = "wall.php?user_id=<?php echo($post['id'])?>"><?php echo($post['author_name'])?></a></address>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>
                        <footer>
                            <small>♡<?php echo $post['like_number'] ?> </small>
                            <form action="news.php" method="post">
                            <input type="hidden" name="post_id" value="<?php echo $post['post_number']; ?>">
                            <button type="submit" name="like_button">J'aime</button>
                        </form>
                                    
                            <?php $taglist = explode(",", $post['taglist']);
                            foreach ($taglist as $tag){?>
                            <a href="tags.php?tag_id=<?php echo $post['tagId'] ?>">#<?php echo($tag)?></a>
                            <?php } ?>
                        </footer>
                    </article>
                    <?php

                    //break;
                
                }
                ?>

            </main>
        </div>
    </body>
</html>
