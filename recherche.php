<?php
     
    // Connexion à la base donnée
     
    $db_server = 'localhost'; // Adresse du serveur MySQL
    $db_name = 'site';            // Nom de la base de données
    $db_user_login = 'root';  // Nom de l'utilisateur
    $db_user_pass = '';       // Mot de passe de l'utilisateur

    // Ouvre une connexion au serveur MySQL
    $conn = mysqli_connect($db_server,$db_user_login, $db_user_pass, $db_name);


    if ( isset($_POST['requete']) )
    $requete = htmlentities($conn->real_escape_string($_POST['requete']));


    if (!empty($requete))
    {

        $req = "SELECT * FROM produit WHERE title  LIKE '%$requete%'"; 
        $exec = $conn->query($req);                            
// exécuter la requête
        $nb_resultats = $exec->num_rows;              // compter les résultats


    if($nb_resultats != 0) 
    {
       echo '<center>';   
       echo '
           <form action="" method="Post">
           <input type="text" name="requete" size="60px">
           <input type="submit" value="Ok">
           </form>';
    echo'<br/><br/>';
      echo '<font color="blue">Résultat de votre recherche </font><br/>
            <font size="2px">'.$nb_resultats.'</font>';


    if($nb_resultats > 1)
    {
        echo ' <font size="2px" color="red">résultats</font> ';
    }
        else
        {
            echo ' <font size="2px" color="red">résultats trouvé</font>  ';
        } 

       echo  '<font size="2px">dans notre base de données :</font><br/><br/>';
  echo '</center>';


    while($donnees = mysqli_fetch_array($exec))
    {
    ?>
<link rel="stylesheet" type="text/css" href="css2.css">	 
 <p><img  src="imgs/<?php echo $donnees['title']; ?>.jpg"/></p>
    <?php
          
          echo '<center>'; 
		 
          echo '<font size="2px">'.$donnees['title'].'</font><br/>';
          echo  '<font size="2px">'.$donnees['description'].'</font><br/>';
          echo '<font size="2px">'.$donnees['price'].'</font><br/>';
		  echo '<font size="2px">'.$donnees['category'].'</font><br/>';
          echo '</center>';
    ?>
	

    <?php
    } // fin de la boucle
    ?>


    <?php
    }


    else {
        echo '<center>';   
        echo '
           <form action="" method="Post">
           <input type="text" name="requete" size="60px">
           <input type="submit" value="Ok">
           </form>';
        echo '</center>';
        echo '<h5>Pas de résultats</h3>';
        echo '<pre>Nous n avons trouver aucun résultats pour votre requête
              <font color="blue">' .$_POST['requete'].'</font></pre>';
      
     }
    }

    else
    { 


     echo '<center>';   
     echo '
           <form action="" method="Post">
           <input type="text" name="requete" size="60px">
           <input type="submit" value="Ok">
           </form>';
     echo '</center>';      

    }
?>

