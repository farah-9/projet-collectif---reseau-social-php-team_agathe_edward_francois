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
        <title>ReSoC - Les message par mot-clé</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
        <link rel="stylesheet" href="style copie.css">
    </head>
    <body>
        
    <?php 
        include_once 'header.php';
        ?>
        <!-- <header>
            <img src="resoc.jpg" alt="Logo de notre réseau social"/>
            <nav id="menu">
                <a href="news.php">Actualités</a>
                <a href="wall.php?user_id=5">Mur</a>
                <a href="feed.php?user_id=5">Flux</a>
                <a href="tags.php?tag_id=1">Mots-clés</a>
            </nav>
            <nav id="user">
                <a href="#">Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=5">Paramètres</a></li>
                    <li><a href="followers.php?user_id=5">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=5">Mes abonnements</a></li>
                </ul>

            </nav>
        </header> -->
        <div id="wrapper">
            <?php
            /**
             * Cette page est similaire à wall.php ou feed.php 
             * mais elle porte sur les mots-clés (tags)
             */
            /**
             * Etape 1: Le mur concerne un mot-clé en particulier
             */
            $tagId = intval($_GET['tag_id']);
            ?>
            <?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
            ?>

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom du mot-clé
                 */
                $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $tag = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par le label et effacer la ligne ci-dessous
                // echo "<pre>" . print_r($tag, 1) . "</pre>";
                ?>
                <img src="User1.jpg" alt="Portrait de l'utilisatrice" style="border-radius: 50%;">
                <section>
                    

                </section>
            </aside>
            <main>
                <?php
                /**
                 * Etape 3: récupérer tous les messages avec un mot clé donné
                 */
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,
                    users.id as id,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts_tags as filter 
                    JOIN posts ON posts.id=filter.post_id
                    JOIN users ON users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE filter.tag_id = '$tagId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 */
                while ($post = $lesInformations->fetch_assoc())
                {

                    // echo "<pre>" . print_r($post, 1) . "</pre>";
                    ?>                
                    <article>
                        <h3>
                            <time datetime='2020-02-01 11:12:13' ><?php echo($post['created'])?></time>
                        </h3>
                        <address><a href = "wall.php?user_id=<?php echo($post['id'])?>"><?php echo($post['author_name'])?></a></address>
                        <div>
                            <p><?php echo($post['content'])?></p>
                        </div>                                            
                        <footer>
                            <small>♡<?php echo $post['like_number'] ?> </small>
                            <form action="news.php" method="post">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <button type="submit" name="like_button">J'aime</button>
                            </form>
                            <?php $taglist = explode(",", $post['taglist']);
                            foreach ($taglist as $tag){?>
                            <a href="">#<?php echo($tag)?></a>
                            <?php } ?>
                        </footer>
                    </article>
                <?php } ?>


            </main>
        </div>
    </body>
</html>