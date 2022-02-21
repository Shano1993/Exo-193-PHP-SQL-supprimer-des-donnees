<?php

/**
 * Handle MySQL Connection with PDO.
 * Class DB
 */
class DB
{
    private string $server = 'localhost';
    private string $db = 'exo193';
    private string $user = 'root';
    private string $pwd = '';

    private static PDO $dbInstance;

    /**
     * DbStatic constructor.
     */
    public function __construct() {
        try {
            self::$dbInstance = new PDO("mysql:host=$this->server;dbname=$this->db;charset=utf8", $this->user, $this->pwd);
            self::$dbInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'Connexion réussie à la base de donnée' . '<br>';

            $sql1 = "DELETE FROM user WHERE id = 4";

            if (self::$dbInstance->exec($sql1) !== false) {
                echo "Le dernier utilisateur a été supprimé" . "<br>";
            }

            $sql2 = "TRUNCATE TABLE user";

            if (self::$dbInstance->exec($sql2) !== false) {
                echo "Contenu de la table supprimé !" . "<br>";
            }

            $stm = self::$dbInstance->prepare("
                INSERT INTO user (nom, prenom, rue, numero, code_postal, ville, pays, mail)
                VALUES (:nom, :prenom, :rue, :numero, :code_postal, :ville, :pays, :mail )
            ");

            $stm->execute([
                ':nom' => 'Hanotiau',
                ':prenom' => 'Stefan',
                ':rue' => 'Mon adresse',
                ':numero' => 56,
                ':code_postal' => 6470,
                ':ville' => 'Chimay',
                ':pays' => 'Belgique',
                ':mail' => 'monadresse@mail.com',
            ]);

            $sql3 = "DROP TABLE user";

            if (self::$dbInstance->exec($sql3) !== false) {
                echo "Table user complètement supprimée" . "<br>";
            }
        }
        catch(PDOException $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * Return PDO instance.
     */
    public static function getInstance(): ?PDO {
        if( is_null(self::$dbInstance) ) {
            new self();
        }
        return self::$dbInstance;
    }

    /**
     * Avoid instance to be cloned.
     */
    public function __clone() {}
}
