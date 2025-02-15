<?php

class Theme extends Model {

    protected static $id = 'id_theme';
    protected static $table = 'theme';
    protected static $class = 'Theme';

    protected $id_theme;
    protected $nom_theme;

    /**
     * Crée un thème dans la base de données
     * @param $proposition Proposition
     * @return true|string
     */
    public function create($nom_theme, $proposition) {


        $pdo = Connexion::pdo();
        $theme = self::existsInProposition($nom_theme, $proposition);
        if (self::existsInProposition($nom_theme, $proposition)) {
            return $this->associate($proposition);
        }

        $query = $pdo->prepare('INSERT INTO ' . static::$table . ' (id_theme, nom_theme) VALUES (:id_theme, :nom_theme);');
        $query->bindParam(':id_theme', $this->id_theme);
        $query->bindParam(':nom_theme', $this->nom_theme);

        if ($query->execute() === false) {
            return "Erreur inconnue lors de la création du thème";
        }

        return true;
    }

    /**
     * Associe un thème à une proposition
     * @param $proposition
     * @return true|string
     */
    public function associate($proposition) {

        $pdo = Connexion::pdo();
        $query = $pdo->prepare('INSERT INTO theme_proposition (id_proposition, id_theme) VALUES (:id_proposition, :id_theme);');
        $query->bindParam(':id_proposition', $proposition->id_proposition);
        $query->bindParam(':id_theme', $this->id_theme);

        if ($query->execute() === false) {
            return "Erreur inconnue lors de l'association du thème à la proposition";
        }

        return true;
    }

    /**
     * @param $proposition
     * @return array
     */
    public static function getThemesByProposition($proposition) {

        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM theme WHERE id_theme IN (SELECT id_theme FROM theme_proposition WHERE id_proposition = :id_proposition);');
        $query->bindParam(':id_proposition', $proposition->id_proposition);
        $query->setFetchmode(PDO::FETCH_CLASS, static::$class);
        $query->execute();
        return $query->fetchAll();

    }

    /**
     * Vérifie si un thème existe dans une proposition
     * @param $nom_theme
     * @param $proposition
     * @return false|Theme
     */
    public static function existsInProposition($nom_theme, $proposition) {

        $themes = self::getThemesByProposition($proposition);
        foreach ($themes as $theme) {
            if ($theme->nom_theme == $nom_theme) {
                return $theme;
            }
        }
        return false;
    }


}