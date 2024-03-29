<?php
    session_start();

    if(!isset($_SESSION))
    {
        header('Location: connexion');
    }

    if ($_SERVER['SERVER_NAME'] == "127.0.0.1") {
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', '');
    } elseif ($_SERVER['SERVER_NAME'] == "10.206.237.111" || $_SERVER['SERVER_NAME'] == "10.206.237.112" || $_SERVER['SERVER_NAME'] == "www.wisetreebanque.sio") {
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', 'wisetree');
    } elseif ($_SERVER['SERVER_NAME'] == "zonca.alwaysdata.net") {
        $bdd = new PDO('mysql:host=mysql-zonca.alwaysdata.net;dbname=zonca_wisebankdb;charset=utf8', 'zonca_adminbank', 'wisetreebanque');
    }
    try{
        $bdd;

    }catch(exception $e){
        die('Erreur connexion: '. $e->getMessage());
    }

    $sql = "SELECT * FROM users WHERE userid = ?";
    $request = $bdd->prepare($sql);
    $request->execute(array($_SESSION['userid']));
    $user = $request->fetch();

    if($user['permissions'] < 2){
        header('Location: accueil');
    }

    unset($_SESSION['usermessage']);

    function displayusers ($bdd) {
        $sql = "SELECT * FROM users WHERE userid = ?";
        $request = $bdd->prepare($sql);
        $request->execute(array($_GET['id']));
    
        echo '<table>';
        echo '<tr style="border-style: solid;">
            <th>UserID</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Mail</th>
            <th>Téléphone</th>
            <th>Adresse</th>
            <th>Date de naissance</th>
            <th>ID conseiller</th>
            <th>Permissions</th>
            <th>Actions</th>
        </tr>';
    
        $data = $request->fetch();
            echo '<tr>';
            echo '<td>' . $data['userid'] . '</td>';
            echo '<td>' . $data['nom'] . '</td>';
            echo '<td>' . $data['prenom'] . '</td>';
            echo '<td>' . $data['mail'] . '</td>';
            echo '<td>' . $data['tel'] . '</td>';
            echo '<td>' . $data['adresse'] . '</td>';
            echo '<td>' . $data['date_naissance'] . '</td>';
            echo '<td>' . $data['idconseiller'] . '</td>';
            echo '<td>' . $data['permissions'] . '</td>';
            echo '<td>';
                // Si l'utilisateur a une permission de 4, afficher le formulaire de confirmation de mot de passe
                if($data['permissions'] >=3){
                    echo '<form method="POST" action="utilisateur">
                        <input type="hidden" name="id" value="'. $data['userid'] .'">
                        <input type="password" name="password" placeholder="Mot de passe">
                        <input class="btn btn-secondary" type="submit" name="delete" value="Supprimer">
                    </form>';
                } else {
                    echo '<form method="POST" action="utilisateur">
                        <input type="hidden" name="id" value="'. $data['userid'] .'">
                        <input class="btn btn-secondary" type="submit" name="delete" value="Supprimer" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce compte ? Wallali ?\')">
                    </form>';
                }
        
                echo '</td>';
            echo '</tr>';
        echo'</table>';
    
        if(isset($_POST['delete'])) {
            $id = $_POST['id'];
            $password = isset($_POST['password']) ? $_POST['password'] : '';
        
            $sql = 'SELECT * FROM users WHERE userid = :id';
            $request = $bdd->prepare($sql);
            $request->bindParam(':id', $id);
            $request->execute();
            $data = $request->fetch();
            
            if(!$data) {
                echo '<p style="color:red;">Utilisateur non trouvé !</p>';
                return;
            }
        
            // Vérifier le mot de passe si l'utilisateur a une permission de 4
            $authorized = false;
        
            if($data['permissions'] == '4' || $data['permissions'] == '3' ){
                if(password_verify($password, $data['password'])) {
                    $authorized = true;
                } else {
                    echo '<p style="color:red;">Mot de passe incorrect !</p>';
                }
            } else {
                $authorized = true;
            }
        
            if($authorized) {
                $sql = 'DELETE FROM comptes WHERE userid = :id';
                $request = $bdd->prepare($sql);
                $request->bindParam(':id', $id);
                $request->execute();
        
                $sql = 'DELETE FROM  users WHERE userid = :id';
                $request = $bdd->prepare($sql);
                $request->bindParam(':id', $id);
                $request->execute();
            }
        }
    }
    
    if (isset($_POST['lescomptes'])) {
        header('Location: accueil');
    }
    if (isset($_POST['comptes'])) {
        header('Location: administration');
    }
    if (isset($_POST['Deco'])) {
        header('Location: deconnexion');
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="logo.jpg" />
    <title>Vos crédits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body{
            background-image: url("img/background.png");
            height: 100%;
            background-position-y: 20%;
            background-position-x: 50%;
            color: white;
        }

        table{
            text-align: center;
            margin: auto;
        }

        th{
            padding: 5px;
            width: fit-content;
            margin:3px
        }
        td{
            padding: 1vw;
            border: 1px;
            border-style: solid;
        }

        .normal{
            background-color: transparent;
        }

        .alert{
            background-color: red;
        }

        
    </style>
</head>
  <body>

  <header>
    <div class="navbar-nav">
        <form method="POST" action="utilisateur">
            <button name="comptes" class="btn btn-primary">Retour</button>
            <button name="lescomptes" class="btn btn-secondary">Vos comptes</button>
            <button name="Deco" class="btn btn-secondary">Deconnexion</button>
        </form>
    </div>
  </header>
    <h1>Utilisateur n° <?php echo $_GET['id']?> </h1>
    <?php
        displayusers($bdd);
    ?>
    <script type="text/javascript" src='loading.js'></script>
  </body>
</html>