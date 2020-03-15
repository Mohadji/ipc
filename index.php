<?php 
  /*variable de demarage des sessions*/
  session_start();

  /*connexion a la base de donnee*/
  require_once('Connexions/bdd.php');

  /*operation apres avoir cliquer sur le bouton connexion*/
  if (isset($_POST['envoi'])) 
  {
    if (!empty($_POST['log']) AND !empty($_POST['password'])) 
    {
      $login = htmlspecialchars($_POST['log']);
      $password = htmlspecialchars($_POST['password']);

      $sql = $bdd->prepare("SELECT * FROM admin WHERE login=? AND mot_de_passe=?");
      $sql->execute(array($login,$password));
      $verification = $sql->rowCount();
      /*si la verification est vrai on renvoi 1 sinon 0*/
      if ($verification == 1) 
      {
          /*affichage des information de personne qui vien de se connecter*/
          $affichage = $sql->fetch();
          // die(var_dump($affichage));

          $_SESSION['LOGIN']        = $affichage['login'];
          $_SESSION['MOT_DE_PASSE'] = $affichage['mot_de_passe'];
          $_SESSION['NOM']          = $affichage['nom'];
          $_SESSION['PRENOM']       = $affichage['prenom'];
          $_SESSION['ID']           = $affichage['id_admin'];
          $_SESSION['PROFIL']       = 'admin';

          header("location:admin/accueil.php");
      }
        else
        {

          /*on reverifie les information si c'est n'est pas pour un caissier*/
          $sql = $bdd->prepare("SELECT * FROM caisse WHERE login=? AND mot_de_passe=?");
          $sql->execute(array($login,$password));

          $verification = $sql->rowCount();
          if ($verification == 1) 
          {
            $affichage = $sql->fetch();
            // die(var_dump($affichage));

            /*recuperation des information du caissier dans des variable de session*/
            $_SESSION['ID'] = $affichage['id_caissier'];
            $_SESSION['NOM'] = $affichage['nom'];
            $_SESSION['PRENOM'] = $affichage['prenom'];
            $_SESSION['LOGIN'] = $affichage['login'];
            $_SESSION['MOT_DE_PASSE'] = $affichage['mot_de_passe'];
            $_SESSION['PROFIL'] = 'caisse';
            /*redirection apres verification des information du caissier*/
            header('location:caisse/accueil.php');

          }
            else
            {

              /*cela siginifie que ces information de concerne aucun des utilisateurs*/
              /*on redirige la personne vers la page de connexion avec une erreur*/
              header("location:index.php?msg= Login et/ou Mot de passe incorrect.");

            } //fin else 

        }// fin else
    }
    else
    {
      /*si part erreur aucun des champs n'est renseigner pour est vide */
      /*cette inscription n'est executer que si les blocage on echouer (required)*/
      header("location: index.php?msg= Login et/ou Mot de passe ne doit pas etre vide.");
    }// fin else
  
  } // fin if isset 


 ?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>IPC_STOCK - Login</title>

    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

  </head>

  <body class="bg-dark">

    <div class="container">
      <div class="col-md-12 mx-auto mt-5">
         <?php 

            if (isset($_GET['msg'])) 
            {
              echo '<p style="color:#f05; text-align: center">'.$_GET['msg'].'</p><br>';  
            }
         ?>
      </div>
      <div class="card card-login mx-auto mt-5">
        <div class="card-header">Authentification</div>
        <div class="card-body">
          <form action="" method="post" id="idform">
            <div class="form-group">
              <div class="form-label-group">
                <input type="text" name="log" id="log" class="form-control" placeholder="Saisir l'identifiant" required="required" autofocus="autofocus">
                <label for="log">Login</label>
              </div>
            </div>
            <div class="form-group">
              <div class="form-label-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="required">
                <label for="password">Mot de passe</label>
              </div>
            </div>
            <button class="btn btn-primary btn-block" name="envoi">Connexion</button>
          </form>
          <div class="text-center"><br>
            <a class="d-small small" href="">Mot de passe oublié ?</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  </body>

</html>
