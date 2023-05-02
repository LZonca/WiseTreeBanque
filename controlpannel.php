<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header('Location: Connexion');
}

if (isset($_POST['lescomptes'])) {
    header('Location: Accueil');
}
$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', 'wisetree');
try {
    //$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
    $bdd;  //Localhost 

} catch (exception $e) {
    die('Erreur: ' . $e->getMessage());
}

function displayusers($bdd)
{
    $sql = "SELECT * FROM users ";
    $request = $bdd->prepare($sql);
    $request->execute();

    echo '<div class="table-responsive">';
    echo '<table class="table">';
    echo '<thead>
        <tr>
            <th>UserID</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Mail</th>
            <th>Téléphone</th>
            <th style="width: fit-content;">Actions</th>
        </tr>
    </thead>';

    echo '<tbody>';

    while ($data = $request->fetch()) {
        echo '<tr>';
        echo '<td>' . $data['userid'] . '</td>';
        echo '<td>' . $data['nom'] . '</td>';
        echo '<td>' . $data['prenom'] . '</td>';
        echo '<td>' . $data['mail'] . '</td>';
        echo '<td>' . $data['tel'] . '</td>';
        echo '<td>';

        // Si l'utilisateur a une permission de 4, afficher le formulaire de confirmation de mot de passe
        if ($data['permissions'] >= 3) {
            echo '<td style="width: fit-content; height: fit-content >';
            echo '<form method="POST" action="Administration">
                <input type="hidden" name="id" value="' . $data['userid'] . '">
                <input type="password" name="password" placeholder="Mot de passe">
                <input class="btn btn-secondary" type="submit" name="delete" value="Supprimer">
            </form>';
        } else {
            echo '<form method="POST" action="Administration">
                <input type="hidden" name="id" value="' . $data['userid'] . '">
                <input class="btn btn-secondary" type="submit" name="delete" value="Supprimer" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce compte ? Wallali ?\')">
            </form>';
        }

        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $sql = 'SELECT * FROM users WHERE userid = :id';
        $request = $bdd->prepare($sql);
        $request->bindParam(':id', $id);
        $request->execute();
        $data = $request->fetch();

        if (!$data) {
            echo '<p style="color:red;">Utilisateur non trouvé !</p>';
            return;
        }

        // Vérifier le mot de passe si l'utilisateur a une permission de 4
        $authorized = false;

        if ($data['permissions'] == '4' || $data['permissions'] == '3') {
            if (password_verify($password, $data['password'])) {
                $authorized = true;
            } else {
                echo '<p style="color:red;">Mot de passe incorrect !</p>';
            }
        } else {
            $authorized = true;
        }

        if ($authorized) {
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

unset($_SESSION['nompret']);
unset($_SESSION['prenompret']);
unset($_SESSION['compteactuel']);
function generateRIB($bdd, $numeroCompte)
{
    $codeBanque = "69420";

    // Ajouter des zéros à gauche du numéro de compte pour avoir une longueur de 24 caractères
    $numeroComptePadded = str_pad($numeroCompte, 11, "0", STR_PAD_LEFT) . "00000";

    // Transformer le code de la banque et le numéro de compte en une chaîne de chiffres
    $chiffres = str_replace(
        array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"),
        array("10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35"),
        $codeBanque . $numeroComptePadded
    );

    // Calculer le reste de la division euclidienne de $chiffres par 97
    $reste = 0;
    for ($i = 0; $i < strlen($chiffres); $i++) {
        $reste = ($reste * 10 + (int)$chiffres[$i]) % 97;
    }

    // Calculer la clé RIB
    $cleRib = str_pad(97 - $reste, 2, "0", STR_PAD_LEFT);
    $rib = "FR76 " . $codeBanque . " " . $numeroCompte . " " . $cleRib;
    // Vérifier si le RIB existe déjà dans la base de données
    $requete = $bdd->prepare("SELECT COUNT(*) FROM comptes WHERE RIB = ?");
    $requete->execute(array($rib));
    $count = $requete->fetchColumn();


    // Si le RIB existe déjà, appeler à nouveau la fonction generateRIB()
    if ($count > 0) {
        return generateRIB($bdd, $numeroCompte);
    } else {
        return $rib;
    }
}

function checkconseillers($bdd)
{

    $requetedata = "SELECT * FROM users WHERE permissions = 2";
    $requetedata = $bdd->prepare($requetedata);
    $requetedata->execute();

    echo "<select name='conseiller' class='form-control' required>";
    echo "<option value = '' selected></option>";
    while ($data = $requetedata->fetch()) {
        echo "<option value = " . $data['userid'] . "\">" . $data['nom'] . " " . $data['prenom'] . "</option>";
    }
    echo "</select><br><br>";
}

function checkranks($bdd)
{
    $user = $_SESSION['userid'];
    $requete = "SELECT permissions FROM users WHERE userid = ? LIMIT 1;";
    $requete = $bdd->prepare($requete);
    $requete->execute(array($user));
    $dataperms = $requete->fetch(PDO::FETCH_ASSOC);
    if (!$dataperms) {
        echo "Erreur: utilisateur non trouvé.";
        return;
    }
    $permissions = intval($dataperms['permissions']);
    if ($permissions < 1 || $permissions > 4) {
        echo "Erreur: permission invalide.";
        return;
    }
    $requete = "SELECT * FROM permissions WHERE permissionid <= ?;";
    $requete = $bdd->prepare($requete);
    $requete->execute(array($permissions));
    $data = $requete->fetchAll(PDO::FETCH_ASSOC);
    if (!$data) {
        $_SESSION['usermessage'] = "Erreur: aucune permission trouvée.";
        return;
    }
    echo "<select name='perms' class='form-control' required>";
    foreach ($data as $row) {
        echo "<option value='" . $row['permissionid'] . "'>" . $row['permissionnom'] . "</option>";
    }
    echo "</select><br><br>";
}

function generateid($bdd)
{
    $idrand = mt_rand(10000000000, 99999999999);

    $id = strval($idrand);

    $idrequete = "SELECT COUNT(*) FROM users WHERE userid = ?";
    $idrequete = $bdd->prepare($idrequete);
    $idrequete->execute(array($id));
    $countid = $idrequete->fetchColumn();
    if ($countid > 0) {
        return $id = generateid($bdd);
    }

    return $id;
}

function create_user($bdd)
{

    $id = generateid($bdd);
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password = "123456";
    $password = password_hash($password, PASSWORD_DEFAULT);
    $datenaissance = $_POST['datenaissance'];
    $mail = $_POST['email'];
    $tel = $_POST['tel'];
    $conseillier = $_POST['conseiller'];
    $perms = $_POST['perms'];
    try {
        $bdd;
    } catch (exception $e) {
        die('Erreur creation: ' . $e->getMessage());
    }
    // Se connecter à la base de données avec PDO

    // Vérifier si l'utilisateur existe déjà dans la base de données
    $nomrequete = "SELECT COUNT(*) FROM users WHERE nom = ?";
    $nomrequete = $bdd->prepare($nomrequete);
    $nomrequete->execute(array($nom));
    $countnom = $nomrequete->fetchColumn();

    $prenomrequete = "SELECT COUNT(*) FROM users WHERE prenom = ?";
    $prenomrequete = $bdd->prepare($prenomrequete);
    $prenomrequete->execute(array($prenom));
    $countpren = $prenomrequete->fetchColumn();

    $mailrequete = "SELECT COUNT(*) FROM users WHERE mail = ?";
    $mailrequete = $bdd->prepare($mailrequete);
    $mailrequete->execute(array($mail));
    $countmail = $mailrequete->fetchColumn();

    $telrequete = "SELECT COUNT(*) FROM users WHERE mail = ?";
    $telrequete = $bdd->prepare($telrequete);
    $telrequete->execute(array($tel));
    $counttel = $telrequete->fetchColumn();

    $naissrequete = "SELECT COUNT(*) FROM users WHERE date_naissance = ?";
    $naissrequete = $bdd->prepare($naissrequete);
    $naissrequete->execute(array($datenaissance));
    $countnaissance = $naissrequete->fetchColumn();

    if (verifnewuser()) {

        if (($countnom > 0 && $countpren > 0 && $countnaissance > 0 && $countmail > 0 && $counttel > 0) || $countmail > 0 || $counttel > 0) {
            // Afficher un message d'erreur si l'utilisateur existe déjà
            $_SESSION['usermessage'] = "<p class='alert alert-danger'>Cet utilisateur existe déjà.<p>";
        } else {

            $requete = "INSERT INTO users (userid, nom, prenom, date_naissance, password, mail, tel, idconseiller, permissions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $requete = $bdd->prepare($requete);
            $requete->execute(array($id, $nom, $prenom, $datenaissance, $password, $mail, $tel, $conseillier, $perms));
            $data = $requete->fetch();

            $_SESSION['usermessage'] = "<p class='alert alert-success'>L'utilisateur a été ajouté avec succès.<p>";
        }
        if ($perms == 4) {
            $logrequete = "INSERT INTO actionlogs (typaction, actionuser) VALUES (?,?)";
            $logrequete = $bdd->prepare($logrequete);
            $logrequete->execute(array(4, $_SESSION['userid']));
        } else {
            $logrequete = "INSERT INTO actionlogs (typaction, actionuser) VALUES (?,?)";
            $logrequete = $bdd->prepare($logrequete);
            $logrequete->execute(array(2, $_SESSION['userid']));
        }
    }
}

function create_compte($bdd)
{
    $nom = $_POST['nomclient'];
    $prenom = $_POST['prenomclient'];

    $nomrequete = "SELECT COUNT(*) FROM users WHERE nom = ?";
    $nomrequete = $bdd->prepare($nomrequete);
    $nomrequete->execute(array($nom));
    $countnom = $nomrequete->fetchColumn();

    $prenomrequete = "SELECT COUNT(*) FROM users WHERE prenom = ?";
    $prenomrequete = $bdd->prepare($prenomrequete);
    $prenomrequete->execute(array($prenom));
    $countpren = $prenomrequete->fetchColumn();

    if ($countnom == 0 && $countpren == 0) {
        // Afficher un message d'erreur si l'utilisateur n'existe pas.
        $_SESSION['usermessage'] = "<p class='alert alert-danger'>Pas d'utilisateur à ce nom!<p>";
    } else {
        $requeteinfo = "SELECT * FROM users WHERE nom = ? AND prenom = ?";
        $requeteinfo = $bdd->prepare($requeteinfo);
        $requeteinfo->execute(array($nom, $prenom));
        $data = $requeteinfo->fetch();
        $RIB = generateRIB($bdd, $data['userid']);
        $decouvert = $_POST['decouvert'];
        $comptenom = $_POST['nomcompte'];

        $requete = "INSERT INTO comptes (userid, comptenom, RIB, decouvert_autorise) VALUES (?, ?, ?, ?);";
        $requete = $bdd->prepare($requete);
        $requete->execute(array($data['userid'], $comptenom, $RIB, $decouvert));

        $logrequete = "INSERT INTO actionlogs (typaction, actionuser) VALUES (?,?)";
        $logrequete = $bdd->prepare($logrequete);
        $logrequete->execute(array(3, $_SESSION['userid']));


        $_SESSION['usermessage'] = "<p class='alert alert-success'>Le compte a été créé avec succès.<p>";
    }
}

function checkusercomptes($bdd)
{
    $nom = $_POST['nompret'];
    $prenom = $_POST['prenompret'];
    $_SESSION['nompret'] = $nom;
    $_SESSION['prenompret'] = $prenom;
    $requeteinfo = "SELECT *, COUNT(*) AS compteur FROM users WHERE nom = ? AND prenom = ?";
    $requeteinfo = $bdd->prepare($requeteinfo);
    $requeteinfo->execute(array($nom, $prenom));
    $datauserid = $requeteinfo->fetch();

    if ($datauserid['compteur'] == 0) {
        // Afficher un message d'erreur si l'utilisateur n'existe pas.
        $_SESSION['usermessage'] = "<p class='alert alert-danger'>Pas d'utilisateur à ce nom!<p>";
    } else {
        $requetedata = "SELECT * FROM comptes WHERE userid = ? ";
        $requetedata = $bdd->prepare($requetedata);
        $requetedata->execute(array($datauserid['userid']));
        echo '<div class="comptes_container">';
        while ($data = $requetedata->fetch()) {
            echo "<div class='compte'>";
            echo "<h2><b>Compte " . $data['comptenom'] . "</b></h2>";
            echo "<form method='POST' action='Crédit'>";
            echo "<input class='btn btn-primary' type='submit' name='compteactuelnom' value='" . $data['comptenom'] . "'>";
            echo "<input type='text' hidden name='compteactuel' value='" . $data['RIB'] . " '>";
            echo "</form>";
            echo "<h5>Votre solde: <u>" . $data['solde'] . "€</u></h5>";
            echo "</div>";
        }
        echo '</div>';
    }
}
function checkmail($mail)
{
    for ($i = 0; $i < strlen($mail); $i++) {
        if ($mail != '') {
            if ($mail[$i] == '@') {
                return true;
            }
        }
    }
}

function verifnewuser()
{
    if (isset($_POST['adduser'])) {
        if (isset($_POST['nom']) && $_POST['nom'] != '' && !is_numeric($_POST['nom'])) {
            if (isset($_POST['prenom']) && $_POST['prenom'] != '' && !is_numeric($_POST['prenom'])) {
                if (isset($_POST['mail']) && $_POST['mail'] != '' && checkmail($_POST['mail'])) {
                    if (isset($_POST['datenaissance'])) {
                        if (isset($_POST['tel']) && $_POST['tel'] != '') {
                            return True;
                        } else {
                            $_SESSION['usermessage'] = "<p class='alert alert-danger'>Numero de téléphone non rempli !";
                        }
                    } else {
                        $_SESSION['usermessage'] = "<p class='alert alert-danger'>Date de naissance non remplie !";
                    }
                } else {
                    $_SESSION['usermessage'] = "<p class='alert alert-danger'>Numero de téléphone non rempli !</p>";
                }
            } else {
                $_SESSION['usermessage'] = "<p class='alert alert-danger'>Le prénom est mal rempli !";
            }
        } else {
            $_SESSION['usermessage'] = "<p class='alert alert-danger'> Le nom est mal rempli !";
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="logo.jpg" />
    <title>Pannel conseillers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body {
            background-image: url("background.png");
            height: 100%;
            background-position-y: 20%;
            background-position-x: 50%;
        }

        h2 {
            font-size: 1.5vw;
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <div class="navbar-nav">
        <form method="POST" action="Administration">
            <button name="lescomptes" class="btn btn-secondary">Vos comptes</button>
        </form>
    </div>
    <div class="container">
        <div class='login-container'>
            <div class="titre">
                <h1>Panneau de controle</h1>
            </div>

            <div class="loginform">
                <?php if (isset($_SESSION['usermessage'])) {
                    echo $_SESSION['usermessage'];
                } ?>
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                <h2>Ajouter un utilisateur</h2>
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <form action="Administration" method="post">
                                    <label for="nom">Nom*:</label><br><br>
                                    <input type="text" id="nom" name="nom" placeholder="Votre nom" class="form-control" required><br><br>
                                    <label for="prenom">Prenom*:</label><br><br>
                                    <input type="text" id="prenom" name="prenom" placeholder="Votre prenom" class="form-control" required><br><br>
                                    <label for="email">Mail*:</label><br><br>
                                    <input type="mail" id="mail" name="email" placeholder="Wise@Tree.com" class="form-control" required><br><br>
                                    <label for="datenaissance">Date de naissance*:</label><br><br>
                                    <input type="date" id="date" name="datenaissance" placeholder="11/10/2003" class="form-control" required><br><br>
                                    <label for="tel">N°télephone*:</label><br><br>
                                    <input type="text" id="tel" name="tel" placeholder="+33***********" class="form-control" required><br><br>
                                    <?php
                                    //$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
                                    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', '');  //Localhost 
                                    checkconseillers($bdd);
                                    ?>
                                    <label for="perms">Permissions:*</label><br><br>
                                    <?php
                                    checkranks($bdd);
                                    ?>

                                    <button name="adduser" class="btn btn-primary">Ajouter un compte</button>
                                </form>

                                <?php

                                if (isset($_POST['adduser'])) {
                                    create_user($bdd);
                                }
                                displayusers($bdd);
                                ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="loginform">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    <h2>Créer un compte</h2>
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <form action="Administration" method="post">
                                    <label for="nomclient">Nom:*</label><br><br>
                                    <input type="text" id="nomclient" name="nomclient" placeholder="Nom du propriétaire de compte" class="form-control" required><br><br>
                                    <label for="prenomclient">Prenom:*</label><br><br>
                                    <input type="text" id="prenomclient" name="prenomclient" placeholder="Prenom du propriétaire de compte" class="form-control" required><br><br>
                                    <label for="nomcompte">Type de compte:*</label><br><br>
                                    <select name='nomcompte' class="form-control" required>
                                        <option value="Courant" selected>Compte courant</option>
                                        <option value="Epargne">Compte epargne</option>
                                        <option value="Etudiant">Compte étudiant</option>
                                    </select><br><br>
                                    <label for="decouvert">Decouvert autorisé:*</label><br><br>
                                    <select name="decouvert" class="form-control" required>
                                        <option value="0" selected>0</option>
                                        <option value="100">100</option>
                                        <option value="200">200</option>
                                        <option value="300">300</option>
                                        <option value="400">400</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                        <option value="2000">2000</option>
                                        <option value="3000">3000</option>
                                        <option value="4000">4000</option>
                                        <option value="5000">5000</option>
                                        <option value="10000">10000</option>
                                    </select><br><br>

                                    <button name="addcompte" class="btn btn-primary">Ajouter un compte</button>
                                    <?php
                                    if (isset($_POST['addcompte'])) //&& verifnewuser())
                                    {
                                        create_compte($bdd);
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="accordion" id="accordionPanelsStayOpenExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                <h2>Créer un prêt pour un utilisateur: </h2>
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingTwo">
                            <div class="accordion-body">
                                <form action='controlpannel' method="POST">
                                    <label for="nompret">Nom du créancier:</label>
                                    <input type='text' name='nompret' class="form-control" placeholder='<?php
                                                                                                        if (isset($_POST['nompret'])) {
                                                                                                            echo $_POST['nompret'];
                                                                                                        }
                                                                                                        ?>'><br><br>
                                    <label for="prenompret">Prénom du créancier:</label>
                                    <input type='text' name='prenompret' class="form-control" placeholder='<?php
                                                                                                            if (isset($_POST['prenompret'])) {
                                                                                                                echo $_POST['prenompret'];
                                                                                                            } ?>'><br><br>
                                    <button name="addpret" class="btn btn-primary">Chercher utilisateur</button>
                                </form>
                                <?php
                                if (isset($_POST['addpret'])) {
                                    echo "<h2>Comptes de l'utilisateur " . $_POST['nompret'] . " " . $_POST['prenompret'] . ": </h2>";
                                    checkusercomptes($bdd);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>