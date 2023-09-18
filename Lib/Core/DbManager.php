<?php

    /**
     * Connexion à la base de données
     *
     * @author Voahirana
     *
     * @since 30/09/19
     */

	namespace Core;

	use \PDO;

	abstract class DbManager
	{
        /**
         * Se connecter à la base
         *
         * @return empty
         */
        public function pdo() 
        {
	    	// Set the timeout limit to 40 seconds
            set_time_limit(40);
            try {
                // try {
	    		    return new PDO("mysql:host=localhost;dbname=hcomg_dBpre-prodSIRH;char=utf-8", "hcomg_dBpre-prodSIRH", "SVShlkBxS4");
                // } catch (Throwable $e) {
                //     echo "Temps d'exécution maximum dépassé... <br>Veuillez retourner à la page précédante !";
                // }
	    	} catch (PDOException $e) {
	    		// echo $e->getMessage;
                echo 'La base ne répond pas... <br>Veuillez retourner à la page précédante !';
	    	}
	    }

        /**
         * Lister les données d'une table
         *
         * @param string $table Nom d'une table
         * @param string $attributes les conditions de la requête
         * @param string $string la suite de la requête
         *
         * @return array 
         */
        public function findAll($table, $attributes = null, $string = null, $addSelect = '') 
    	{
    		$db    = $this->pdo();
            $str   = "";
            if (empty($attributes)) {
                $query = "SELECT * FROM ". $table;
            } else {
                foreach ($attributes as $key => $value) {
                    if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                        $value = $value;
                    } else {
                        $value =  "'" . $value . "'";
                    }
                    /** @changelog 12/05/2021 [EVOL] (Lansky) Ajouter quelques fonctions SQL */
                    if ($value =="'IS NULL'" || $value =="'IS NOT NULL'" || strstr($value,"'NOT " ) || strstr($value,"'IN " )|| strstr($value,"'LIKE ") || strstr($value,"BETWEEN ") || substr(preg_replace("/'+/", '', $value), 0, 1) == chr(60) || substr(preg_replace("/'+/", '', $value), 0, 1) == chr(62)) {
                        $value= explode("'", $value);
                        $str .= "(" . $key . " " . $value[1] . ") AND ";
                    } else {
                        $str .= $key . " = " . $value . " AND ";
                    }
                }
                $conditions = substr($str, 0, -5);
                $query      = "SELECT * " . $addSelect . " FROM ". $table . " WHERE " . $conditions;
            }
            $requete = $db->prepare($query . $string);
            $requete->execute();
            $resultat = $requete->fetchAll();
            $requete->closeCursor();
            return $resultat ;
    	}

        /**
         * Selectionner les données d'une table
         *
         * @param string $table       le nom de la table
         * @param string $attributes  les conditions de la requête
         * @param string $min         intervalle minimum
         * @param string $max         intervalle maximum
         *
         * @return array
         */
        public function selectAll($table, $attributes = null, $min, $max, $string = null)
        {
            $db    = $this->pdo();
            $str   = "";
            if (empty($attributes)) {
                foreach ($min as $key => $value) {
                    if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                        $value = $value;
                    } else {
                        $value =  "'" . $value . "'";
                    } 
                    $str .= $key . " >= " . $value . " AND ";
                }
                foreach ($max as $key => $value) {
                    if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                        $value = $value;
                    } else {
                        $value =  "'" . $value . "'";
                    } 
                    $str .= $key . " <= " . $value . " AND ";
                }
                $conditions = substr($str, 0, -5);
                $query      = "SELECT * FROM ". $table . " WHERE " . $conditions;
            } else {
                foreach ($attributes as $key => $value) {
                    if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                        $value = $value;
                    } else {
                        $value =  "'" . $value . "'";
                    } 
                    $str .= $key . " = " . $value . " AND ";
                }
                foreach ($min as $key => $value) {
                    if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                        $value = $value;
                    } else {
                        $value =  "'" . $value . "'";
                    } 
                    $str .= $key . " >= " . $value . " AND ";
                }
                foreach ($max as $key => $value) {
                    if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                        $value = $value;
                    } else {
                        $value =  "'" . $value . "'";
                    } 
                    $str .= $key . " <= " . $value . " AND ";
                }
                $conditions = substr($str, 0, -5);
                $query      = "SELECT * FROM ". $table . " WHERE " . $conditions;
            }
            $requete = $db->prepare($query . $string);
            $requete->execute();
            $resultat = $requete->fetchAll();
            $requete->closeCursor();
            return $resultat ;
        }

        /**
         * insérer une ligne dans une table
         *
         * @param string $table Nom d'une table
         * @param array $attributes les données à insérer
         *
         * @return empty 
         */
        public function insert($table, $attributes)
        {
            $db    = $this->pdo();
            $query = 'INSERT INTO '. $table .' (';
            foreach ($attributes as $key => $value) {
                $query .= '`' . $key . '`, '; 
            }
            $query  = substr($query, 0, -2);
            $query .= ') VALUES (';
            foreach ($attributes as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    // @Ravao: Ici, on n'a pas besoin d'appeler la méthode 'quote' car ici, $value est de type integer
                    $query .= $value . ", ";
                } else {
                    // @Ravao:  C'est ici qu'on aura besoin d'appeler la méthode 'quote' pour un string
                    //          Cette méthode est censé rajouter les caractères apostrophes avant et après 
                    //          la chaîne de caractère en plus de protéger la chaîne pour l'utiliser dans une requête SQL PDO; 
                    //          Ce qui a causé l'erreur c'est que nous avons encore mis deux apostrophes avant et après
                    //          Ça devrait être bon maintenant ;)  
                    $query .= $db->quote($value) . ", ";
                    //$query .=  "'" . $value . "', ";
                }                 
            }
            $query   = substr($query, 0, -2);
            $query  .= ');';
            $contenu = $query;
            $requete = $db->prepare($query);
            $requete->execute();
            return $db->lastInsertId();
        }

        /**
         * modifier une ligne dans une table
         *
         * @param string $table Nom d'une table
         * @param array $attributes les données à modifier
         *
         * @return empty 
         */
        public function update($table, $attributes)
        {
            $db    = $this->pdo();
            $query = "UPDATE ". $table ." SET ";
            foreach ($attributes as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $query .= $key . " = " . $value . ", "; 
                } else {
                    $query .= $key . " = " . $db->quote($value) . ", "; 
                }                
            }
            $query   = substr($query, 0, -2);
            $query  .= " WHERE " . key($attributes) . " = " . reset($attributes);
            $requete = $db->prepare($query);
            $requete->execute();
            $requete->closeCursor();
        }

        /**
         * Chercher une ligne dans une table
         *
         * @param string $table Nom d'une table
         * @param string $fields Champs spécifiques
         * @param string $values Données à chercher
         *
         * @return array 
         */
        public function findOne($table, $fields, $values) 
        {
            $str = "";
            $db  = $this->pdo();
            for ($i = 0; $i < count($fields); $i++) { 
                if (strstr($values[$i], 'LIKE')) {
                    // $str .= $fields[$i] . $values[$i] . " AND ";
                    $str .= $fields[$i] . " LIKE " . str_replace('LIKE ', '', $values[$i]) . " AND ";
                } elseif (strstr($values[$i], 'BETWEEN')) {
                    $str .= $fields[$i] . $values[$i] . " AND ";
                } else {
                    $str .= $fields[$i] . " = " . $values[$i] . " AND ";
                }
            }
            $conditions = substr($str, 0, -5);
            $query      = "SELECT * FROM " . $table . " WHERE " . $conditions;
            $requete    = $db->prepare($query);
            $requete->execute();
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);
            $requete->closeCursor();
            return $resultat;
        }

        /**
         * Chercher le dérinier identifiant
         *
         * @param string $table Nom d'une table
         * @param string $id Champs spécifiques
         *
         * @return array 
         */
        public function findLast($table, $id) 
        {
            $db      = $this->pdo();
            $query   = "SELECT MAX($id) AS id FROM " . $table;
            $requete = $db->prepare($query);
            $requete->execute();
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);
            $requete->closeCursor();
            return $resultat ;
        }

        /**
         * Supprimer une ligne dans une table
         *
         * @param string $table Nom d'une table
         * @param string $attributes Les attributs des données à supprimer
         *
         * @return boolean 
         */
        public function delete($table, $attributes) 
        {
            $db    = $this->pdo();
            $query = "DELETE FROM ". $table ." WHERE ";
            foreach ($attributes as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $query .= $key . " = " . $value . ", AND "; 
                } else {
                    $query .= $key . " = '" . $value . "', AND "; 
                }                
            }
            $query   = substr($query, 0, -6);
            $requete = $db->prepare($query);
            $retour  = $requete->execute();
            return $retour;
        }

	}