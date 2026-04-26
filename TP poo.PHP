<?php

interface Authentifiable {
    public function seConnecter();
}

interface Affichable {
    public function afficher();
}

class Personne {
    private $id;
    private $nom;
    private $email;

    public function __construct($id, $nom, $email) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
    }

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function afficherInfos() {
        echo "ID : " . $this->id . "<br>";
        echo "Nom : " . $this->nom . "<br>";
        echo "Email : " . $this->email . "<br>";
    }
}

abstract class Utilisateur extends Personne implements Authentifiable, Affichable {
    protected $login;
    protected $motDePasse;

    public static $nombreUtilisateurs = 0;

    public function __construct($id, $nom, $email, $login, $motDePasse) {
        parent::__construct($id, $nom, $email);
        $this->login = $login;
        $this->motDePasse = $motDePasse;
        self::$nombreUtilisateurs++;
    }

    public function seConnecter() {
        echo $this->login . " est connecté.<br>";
    }

    public static function afficherNombre() {
        echo "Nombre d'utilisateurs : " . self::$nombreUtilisateurs . "<br>";
    }

    public function afficher() {
        $this->afficherInfos();
        $this->afficherRole();
    }

    abstract public function afficherRole();
}

class Client extends Utilisateur {
    private $typeClient;

    const TAUX_SIMPLE = 0.05;
    const TAUX_PREMIUM = 0.15;

    public function __construct($id, $nom, $email, $login, $motDePasse, $typeClient) {
        parent::__construct($id, $nom, $email, $login, $motDePasse);
        $this->typeClient = $typeClient;
    }

    public function calculerReduction($montant) {
        if ($this->typeClient == "premium") {
            return $montant * self::TAUX_PREMIUM;
        } else {
            return $montant * self::TAUX_SIMPLE;
        }
    }

    public function afficherInfos() {
        parent::afficherInfos();
        echo "Type client : " . $this->typeClient . "<br>";
    }

    public function afficherRole() {
        echo "Rôle : Client<br>";
    }
}

class Employe extends Utilisateur {
    private $salaire;

    public function __construct($id, $nom, $email, $login, $motDePasse, $salaire) {
        parent::__construct($id, $nom, $email, $login, $motDePasse);
        $this->salaire = $salaire;
    }

    public function calculerSalaireAnnuel() {
        return $this->salaire * 12;
    }

    public function afficherRole() {
        echo "Rôle : Employé<br>";
    }
}

class Administrateur extends Utilisateur {
    public function supprimerUtilisateur() {
        echo "Utilisateur supprimé par l'administrateur.<br>";
    }

    public function afficherRole() {
        echo "Rôle : Administrateur<br>";
    }
}

function afficherUtilisateur(Affichable $u) {
    $u->afficher();
    echo "<hr>";
}

// Programme principal

$client = new Client(1, "Ali", "ali@gmail.com", "ali123", "pass123", "premium");
$employe = new Employe(2, "Fatou", "fatou@gmail.com", "fatou456", "pass456", 250000);
$admin = new Administrateur(3, "Moussa", "moussa@gmail.com", "admin", "adminpass");

// Tests

echo "<h3>Client</h3>";
$client->afficher();
$client->seConnecter();
echo "Réduction : " . $client->calculerReduction(100000) . " FCFA<br>";

echo "<hr>";

echo "<h3>Employé</h3>";
$employe->afficher();
$employe->seConnecter();
echo "Salaire annuel : " . $employe->calculerSalaireAnnuel() . " FCFA<br>";

echo "<hr>";

echo "<h3>Administrateur</h3>";
$admin->afficher();
$admin->seConnecter();
$admin->supprimerUtilisateur();

echo "<hr>";

echo "<h3>Polymorphisme</h3>";
afficherUtilisateur($client);
afficherUtilisateur($employe);
afficherUtilisateur($admin);

echo "<h3>Statique</h3>";
Utilisateur::afficherNombre();

?>