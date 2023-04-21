<?php
require_once('Objets/User.php');

class Compte{
    private string $userid;
    private string $RIB;
    private string $BIC;
    private string $comptenom;
    private float $solde;
    private int $decouvert_autorise;

    //////////////////////////////////////////////
    
    //Constructeur

    //////////////////////////////////////////////

    public function __construct(string $uid, string $r, string $cn, float $s, int $da){
        $this->userid = $uid;
        $this->RIB = $r;
        $this->BIC = "WSBNFRXX";
        $this->comptenom = $cn;
        $this->solde = $s;
        $this->decouvert_autorise = $da; 
    }

    //////////////////////////////////////////////
    
    //Getters

    //////////////////////////////////////////////

    public function getUserid(): string {
        return $this->userid;
    }
    
    public function getRIB(): string {
        return $this->RIB;
    }
    
    public function getBIC(): string {
        return $this->BIC;
    }
    
    public function getComptenom(): string {
        return $this->comptenom;
    }
    
    public function getSolde(): float {
        return $this->solde;
    }
    
    public function getDecouvertAutorise(): int {
        return $this->decouvert_autorise;
    }
    
    //////////////////////////////////////////////
    
    //Setters

    //////////////////////////////////////////////

    public function setUserid(string $uid): void {
        $this->userid = $uid;
    }
    
    public function setRIB(string $r): void {
        $this->RIB = $r;
    }
    
    public function setBIC(string $b): void {
        $this->BIC = $b;
    }
    
    public function setComptenom(string $cn): void {
        $this->comptenom = $cn;
    }
    
    public function setSolde(float $s): void {
        $this->solde = $s;
    }
    
    public function setDecouvertAutorise(int $da): void {
        $this->decouvert_autorise = $da;
    }

};

?>