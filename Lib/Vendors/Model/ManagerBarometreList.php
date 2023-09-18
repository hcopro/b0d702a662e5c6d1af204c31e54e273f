<?php
    
    /**
     * Manager de l'entité BarometreList
     *
     * @author Lansky
     *
     * @since 10/01/2022 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\BarometreList;

    class ManagerBarometreList extends DbManager
    {
        /**
         * Lister les tests personnalités
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $testPersonalities    = array();
            $queryOrderBy   = " ORDER BY date DESC";
            $resultats      = $this->findAll('barometre_list', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $barometre    = new BarometreList($resultat);
                $testPersonalities[] = $barometre;
            }
            return $testPersonalities;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser($data=array())
        {
            return new BarometreList($data);
        }

        /** 
         * Chercher un test personnalité
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $barometre = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('barometre_list', $fields, $values);
            if (!empty($resultat)) {
                $barometre = new BarometreList($resultat);
            }
            return $barometre;
        }

        /**
         * Ajouter un test personnalité
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idBarometreList'] = $this->insert('barometre_list', $parameters);
            return new BarometreList($parameters);
        }

        /**
         * Modifier un test personnalité
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('barometre_list', $parameters);
            return new BarometreList($parameters);
        }

        /**
         * Supprimer un test personnalité
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('barometre_list', $parameters);
        }

        /**
         * Récupérer les trois dernieres réponses du baromètre
         *
         * @param int $idEmploye        L'identifiant du salarié
         * @param int $idBarometre      L'identifiant du baromètre
         * @param int $idBarometreList  L'identifiant de la liste du baromètre
         * @param int $limit            Le nombre de ligne maximal à récupérer
         *
         * @return array
         */
        public function lastThreeBarometer($idEmploye, $idBarometre, $idBarometreList, $idEntreprise, $limit=1)
        {
            $BarometreList = [];
            $query = "SELECT bl.*
            FROM barometre_list bl
            INNER JOIN (
                SELECT DISTINCT date
                FROM barometre_list
                WHERE id_barometre_list NOT IN ($idBarometreList)
                AND id_employe      = $idEmploye
                AND id_entreprise   = $idEntreprise
                AND id_barometre    = $idBarometre
                AND is_answered     = 'YES'
                ORDER BY date DESC LIMIT $limit
            ) latest_dates
            ON bl.date = latest_dates.date WHERE bl.id_barometre_list NOT IN ($idBarometreList)
            AND bl.id_employe       = $idEmploye
            AND bl.id_entreprise    = $idEntreprise
            AND bl.id_barometre     = $idBarometre
            AND bl.is_answered      = 'YES'";
            $pdo = $this->pdo();
            $requete    = $pdo->prepare($query);
            $requete->execute();
            $response   = $requete->fetchAll();
            if (!empty($response)) {
                foreach ($response as $resultat) {
                    $barometre    = new BarometreList($resultat);
                    $BarometreList[] = $barometre; 
                }
            }
            return $BarometreList;
        }
        
    }