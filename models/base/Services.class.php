<?php
namespace models\base;

/**
 * Abstract class Services : c'est la classe Services de base que tous les nouveaux Services doivent en hériter.
 */
abstract class Services
{
    private $query;
    private $main_table;
    protected $db;

    public function __construct() 
    {
        $this->db = DbConnection::getInstance()->getPdo();
    }

    /**
     * Fonction de vérification des entrées de l'utilisateur
     * @param mixed $data entrée à vérifier
     * @return mixed sortie vérifié
     */
    public function verifyInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    /**
     * Fonction qui joint deux tables à un champ spécifie
     * @param mixed $table table pour la jointure
     * @param array $fields champs pour la jointure
     * @return self INNER JOIN $table ON $table.$field = $this->main_table.$field
     */
    public function join($table, $fields) : self
    {
        $table = $this->verifyInput($table);

        $this->query .= " INNER JOIN {$table} ON {$this->main_table}.{$fields[0]} = {$table}.{$fields[1]}";

        return $this;
    }

    /**
     * Fonction qui renvoi un requête SELECT avec champ tous les champs d'une table
     * @param string $table table à sélectionner
     * @return self SELECT * FROM $table
     */
    public function select($table, $fields = ['*']) : self
    {
        $this->main_table = $this->verifyInput($table);
        $fields = implode(', ', $fields);
        
        $this->query .= "SELECT {$fields} FROM {$this->main_table} ";

        return $this;
    }

    /**
     * Fonction qui spécifie un condition WHERE d'un requête SQL
     * @param mixed $fields champs à vérifier
     * @param mixed $operand l'opérateur à choisir tel que =, >, <, LIKE ...
     * @return self WHERE $fields $operand ?
     */
    public function where($fields, $operand) : self
    {
        if ($operand === 'LIKE')
        {
            $this->query .= " WHERE {$fields} {$operand} :key ";
        }
        else
        {
            $this->query .= " WHERE {$fields} {$operand} ? ";
        }

        return $this;
    }

    /**
     * Fonction permettant de faire une requête d'insertion en SQL
     * @param mixed $table table pour l'insertion
     * @param mixed $fields champs pour l'insertion
     * @param mixed $values valeurs pour l'insertion
     * @return self INSERT INTO $table ($fields) VALUES ($values)
     */
    public function insert($table, $fields, $values) : self
    {
        for ($i = 0; $i < count($values); $i++)
        {
            $values[$i] = $this->verifyInput($values[$i]);
        }

        $fields = implode(', ', $fields);
        $values = implode("', '", $values);

        $this->query .= "INSERT INTO {$table} ({$fields}) VALUES ('{$values}')";

        return $this;
    }

    /**
     * Fonction permettant de faire une requête d'update en SQL
     * Cette fonction est à combiner avec une clause WHERE
     * @param mixed $table table pour l'update
     * @param mixed $fieldsAndValues champs pour l'update
     * @return self UPDATE $table SET $fieldsAndValues
     */
    public function update($table, $fields, $values) : self
    {
        for ($i = 0; $i < count($values); $i++)
        {
            $values[$i] = $this->verifyInput($values[$i]);
        }

        $fieldValues = '';
        for($i = 0; $i < count($fields); $i++)
        {
            $fieldValues .= "{$fields[$i]} = '{$values[$i]}', ";
        }

        $this->query .= "UPDATE {$table} SET {$fieldValues}";

        return $this;
    }

    /**
     * Fonction permettant de faire une requête de suppression en SQL
     * Cette fonction est à combiner avec une clause WHERE
     * @param mixed $table table pour la suppression
     * @return self DELETE FROM $table
     */
    public function delete($table) : self
    {
        $this->query .= "DELETE FROM {$table}";

        return $this;
    }

    /**
     * Get the value of query
     */
    public function getQuery()
    {
        return $this->query;
    }
}