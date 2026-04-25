<?php

class Personne {
    private int $id;
    private string $nom;
    private string $email;

    public function __construct(int $id, string $nom, string $email) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
    }

    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getEmail(): string { return $this->email; }
    
    public function setId(int $id): void { $this->id = $id; }
    public function setNom(string $nom): void { $this->nom = $nom; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function afficherInfos(): void {
        echo "ID: $this->id<br>Nom: $this->nom<br>Email: $this->email<br>";
    }
}

interface Authentifiable {
    public function seConnecter(): void;
}

interface Affichable {
    public function afficher(): void;
}

abstract class Utilisateur extends Personne implements Authentifiable, Affichable {
    protected string $login;
    protected string $motDePasse;
    public static int $nombreUtilisateurs = 0;

    public function __construct(int $id, string $nom, string $email, string $login, string $motDePasse) {
        parent::__construct($id, $nom, $email);
        $this->login = $login;
        $this->motDePasse = $motDePasse;
        self::$nombreUtilisateurs++;
    }

    public function seConnecter(): void {
        echo "Utilisateur '{$this->login}' connecté.<br>";
    }

    abstract public function afficherRole(): void;

    public static function afficherNombre(): void {
        echo "Nombre total d'utilisateurs : " . self::$nombreUtilisateurs . "<br>";
    }

    public function afficherInfos(): void {
        parent::afficherInfos();
        echo "Login: {$this->login}<br>";
    }
}

class Client extends Utilisateur {
    private string $typeClient;
    const TAUX_SIMPLE = 0.05;
    const TAUX_PREMIUM = 0.15;

    public function __construct(int $id, string $nom, string $email, string $login, string $motDePasse, string $typeClient) {
        parent::__construct($id, $nom, $email, $login, $motDePasse);
        $this->typeClient = $typeClient;
    }

    public function getTypeClient(): string { return $this->typeClient; }
    public function setTypeClient(string $typeClient): void { $this->typeClient = $typeClient; }

    public function calculerReduction(float $montant): float {
        return $montant * ($this->typeClient === 'premium' ? self::TAUX_PREMIUM : self::TAUX_SIMPLE);
    }

    public function afficherInfos(): void {
        parent::afficherInfos();
        echo "Type de client: {$this->typeClient}<br>";
    }

    public function afficherRole(): void {
        echo "Rôle : Client {$this->typeClient}<br>";
    }

    public function afficher(): void {
        $this->afficherInfos();
        $this->afficherRole();
    }
}

class Employe extends Utilisateur {
    private float $salaire;

    public function __construct(int $id, string $nom, string $email, string $login, string $motDePasse, float $salaire) {
        parent::__construct($id, $nom, $email, $login, $motDePasse);
        $this->salaire = $salaire;
    }

    public function getSalaire(): float { return $this->salaire; }
    public function setSalaire(float $salaire): void { $this->salaire = $salaire; }

    public function calculerSalaireAnnuel(): float {
        return $this->salaire * 12;
    }

    public function afficherRole(): void {
        echo "Rôle : Employé<br>";
    }

    public function afficher(): void {
        parent::afficherInfos();
        $this->afficherRole();
        echo "Salaire mensuel: {$this->salaire} €<br>";
        echo "Salaire annuel: " . $this->calculerSalaireAnnuel() . " €<br>";
    }
}

class Administrateur extends Utilisateur {
    public function __construct(int $id, string $nom, string $email, string $login, string $motDePasse) {
        parent::__construct($id, $nom, $email, $login, $motDePasse);
    }

    public function supprimerUtilisateur(Utilisateur $utilisateur): void {
        echo "L'administrateur {$this->getNom()} a supprimé {$utilisateur->getNom()}<br>";
        Utilisateur::$nombreUtilisateurs--;
    }

    public function afficherRole(): void {
        echo "Rôle : Administrateur<br>";
    }

    public function afficher(): void {
        parent::afficherInfos();
        $this->afficherRole();
        echo "⚠️ Peut tout supprimer<br>";
    }
}

function afficherUtilisateur(Affichable $u): void {
    echo "<div style='border:1px solid #ccc; margin:10px; padding:10px;'>";
    $u->afficher();
    echo "</div>";
}

echo "<h1>🧪 Système de gestion - Version tout-en-un</h1>";

$client = new Client(1, "Alice", "alice@email.com", "alice123", "pass", "premium");
$employe = new Employe(2, "Bob", "bob@email.com", "bob69", "pass", 2500);
$admin = new Administrateur(3, "Charlie", "charlie@email.com", "admin", "pass");

Utilisateur::afficherNombre();

echo "<h2>Test afficher() :</h2>";
$client->afficher();
echo "<hr>";
$employe->afficher();
echo "<hr>";
$admin->afficher();

echo "<h2>Test connexion :</h2>";
$client->seConnecter();

echo "<h2>Réduction client :</h2>";
echo "Réduction sur 100€ : " . $client->calculerReduction(100) . "€<br>";

echo "<h2>Polymorphisme :</h2>";
afficherUtilisateur($client);
afficherUtilisateur($admin);
?>