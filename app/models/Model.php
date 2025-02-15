<?php

abstract class Model
{

    protected static $table;
    protected static $id;
    protected static $class;

    public function __construct($attributes = [])
    {
        foreach ($attributes as $attribute => $value) {
            $this->__set($attribute, $value);
        }
    }

    /**
     * Permet de récupérer dynamiquement un attribut
     * @param $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        return $this->$attribute;
    }

    /**
     * Permet de définir dynamiquement un attribut
     * @param $attribute
     * @param $value
     */
    public function __set($attribute, $value)
    {
        $this->$attribute = $value;
    }

    /**
     * Récupère tous les Modeles
     * @return mixed
     */
    public static function getAll()
    {
        $pdo = Connexion::pdo();
        $query = $pdo->query('SELECT * FROM ' . static::$table . ';');
        $query->setFetchmode(PDO::FETCH_CLASS, static::$class);
        return $query->fetchAll();
    }

    /**
     * Récupère un Modele par son identifiant
     * @param $id
     * @return Mixed
     */
    public static function getById($id)
    {
        if (!static::exists($id)) {
            return false;
        }

        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . static::$id . ' = :id;');
        $query->bindParam(':id', $id);
        $query->setFetchmode(PDO::FETCH_CLASS, static::$class);
        $query->execute();
        return $query->fetch();
    }

    /**
     * Vérifie si un Modele existe
     * @param $id
     * @return bool
     */
    public static function exists($id)
    {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . static::$id . ' = :id;');
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->rowCount() > 0;
    }

    /**
     * Supprime un Modele par son identifiant
     * @param $id
     * @return bool
     */
    public static function deleteById($id) {
        $pdo = Connexion::pdo();
        $query = $pdo->prepare('DELETE FROM ' . static::$table . ' WHERE ' . static::$id . ' = :id;');
        $query->bindParam(':id', $id);
        return $query->execute();
    }

}