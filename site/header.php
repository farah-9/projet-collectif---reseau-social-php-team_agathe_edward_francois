<header>
            <img src="logo.png" alt="Logo de notre réseau social"/>
            <nav id="menu">
                <a href="news.php">Actualités</a>
                <a href="wall.php?user_id=<?php echo ($_SESSION['connected_id']) ?>">Mur</a>
                <a href="feed.php?user_id=<?php echo ($_SESSION['connected_id']) ?>">Flux</a>
                <a href="tags.php?tag_id=1">Mots-clés</a>
            </nav>
            <!-- <button id="logout-button" disabled><a href="login.php?user_id=5">Logout</button> -->
            <form img src="User1.jpg" alt="Portrait de l'utilisatrice" style="border-radius: 50%;">
            <button type="submit" id="logout-button">Logout</button>
</form>
            <nav id="user">
                <a href="#">Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=<?php echo ($_SESSION['connected_id']) ?>">Paramètres</a></li>
                    <li><a href="followers.php?user_id=<?php echo ($_SESSION['connected_id']) ?>">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=<?php echo ($_SESSION['connected_id']) ?>">Mes abonnements</a></li>
                    <li><a href="login.php">login</a></li>
                    <li><a href="logout.php">Logout</a></li>

                </ul>
            </nav>
        </header>
        <video  autoplay muted loop> 
        <source src="banner.mp4" type="video/mp4">
        Votre navigateur ne prend pas en charge la lecture de vidéos HTML5.
        </video>