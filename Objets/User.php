<?php
class User{
    private string $userid;
    private string $nom;
    private string $prenom;
    private DateTime $datenaissance;
    private string $password;
    private string $mail;
    private string $tel;
    private string $conseiller;
    private int $permissions;

    //////////////////////////////////////////////
    
    //Constructeur

    //////////////////////////////////////////////


    public function __construct(string $uid, string $n,string $p, string $dn, string $pwd,string $mel,string $tel,string $cons,int $perms){
        $this->userid = $uid;
        $this->nom = $n;
        $this->prenom = $p;
        $this->datenaissance = date_create($dn);
        $this->password = $pwd;
        $this->mail = $mel;
        $this->tel = $tel;
        $this->conseiller = $cons;
        $this->permissions = $perms;
    }

    //////////////////////////////////////////////
    
    //Getters

    //////////////////////////////////////////////
    public function getUserID() : string{
        return $this->userid;
    }
    public function getNom() : string{
        return $this->nom;
    }

    public function getPrenom() : string{
        return $this->prenom;
    }

    public function getDateNaissance() : DateTime{
        return $this->datenaissance;
    }

    public function getPassword() : string{
        return $this->password;
    }

    public function getMail() : string{
        return $this->mail;
    }

    public function getTel() : string{
        return $this->tel;
    }
    public function getConseiller() : string{
        return $this->conseiller;
    }

    public function getPermissions() : int{
        return $this->permissions;
    }

    //////////////////////////////////////////////
    
    //Setters

    //////////////////////////////////////////////

    public function setUserID($uid) : void{
        $this->userid = $uid;
    }
    public function setNom(string $n) : void{
        $this->nom = $n;
    }

    public function setPrenom(string $p) : void{
        $this->prenom = $p;
    }

    public function setDateNaissance(string $dn) : void{
        $dn = date_create($dn);
        $this->datenaissance = $dn;
    }

    public function setPassword(string $pwd) : void{
        $this->password = $pwd;
    }

    public function setMail(string $mel) : void{
        $this->mail = $mel;
    }

    public function setTel(string $t) : void{
        $this->tel = $t;
    }
    public function setConseiller(string $c) : void{
        $this->conseiller = $c;
    }

    public function setPermissions(int $perms) : void{
        $this->permissions = $perms;
    }

    public function __toString(): string {
        return "Nom: " . $this->getNom() . ", Prenom: " . $this->getPrenom() . ", Date de naissance: " . $this->getDateNaissance() . ", Mot de passe: " . $this->getPassword() . ", Mail: " . $this->getMail() . ", Telephone: " . $this->getTel() . ", ID du conseiller: " . $this->getConseiller() . ", Permissions: " .  $this->getPermissions() .  "<br>";
    }
};
?>