<?php
require_once('Objets/Compte.php');
    class BDDManager{

        private PDO $pdo;

        public function __construct(){
            try{
            $this->pdo = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');
            }
            catch(exception $e){
                    die('Erreur connexion: '. $e->getMessage());
                }
        }




        public function getUserbyID(int $id) : User{
            $requetedata = "SELECT * FROM users WHERE userid = ?;";
            $requetedata = $this->pdo->prepare($requetedata); 
            $requetedata->execute(array($id));
            $data = $requetedata->fetch();
            $user = new User($data['userid'], $data['nom'], $data['prenom'], $data['date_naissance'], $data['password'], $data['mail'], $data['tel'], $data['idconseiller'], $data['permissions']);// On paramètre les valeurs de l'objet que l'on vient de créer
            return $user;
        }

        public function getUserComptes(){
            $tabComptes = [];
            $requetedata = "SELECT * FROM comptes WHERE userid = ?";
            $requetedata = $this->pdo->prepare($requetedata); 
            $requetedata->execute();
            while($data = $requetedata->fetch())
            {
                $compte = new Compte($data['userid'], $data['RIB'], $data['comptenom'], $data['solde'], $data['decouvert_autorise']);
                $tabComptes[] = $compte;
            }
            return $tabComptes;
        }

        /*public function getPokemons($d){
            $tabPokemon = [];
            $requetedata = "SELECT * FROM pokemon p, detientpokemon dp, dresseur d WHERE d.id_dress = ? AND dp.id_pok = p.id_pok AND d.id_dress = dp.id_dress;";
            $requetedata = $this->pdo->prepare($requetedata); 
            $requetedata->execute(array($d));
            while($data = $requetedata->fetch())
            {
                $pokemon = new Pokemon($data['id_pok'], $data['nom_pok']);
                $tabPokemon[] = $pokemon;
            }
            return $tabPokemon;
        }

        public function getPokemonsStock($d){
            $tabPokemon = [];
            $requetedata = "SELECT * FROM pokemon p, stockepokemon s WHERE s.id_dress = ? AND s.id_pok = p.id_pok;";
            $requetedata = $this->pdo->prepare($requetedata); 
            $requetedata->execute(array($d));
            while($data = $requetedata->fetch())
            {
                $pokemon = new Pokemon($data['id_pok'], $data['nom_pok']);
                $tabPokemon[] = $pokemon;
            }
            return $tabPokemon;
        }*/

    }