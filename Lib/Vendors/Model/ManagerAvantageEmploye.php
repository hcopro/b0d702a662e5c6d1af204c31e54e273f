<?php
    
    /**
     * Manager de l'entité AvantageEmploye
     *
     * @author Toky
     *
     * @since 21/10/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\AvantageEmploye;

    class ManagerAvantageEmploye extends DbManager
    {
        /** 
         * Lister les AvantageEmployes
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $avantageEmployes      = array();
            $queryOrderBy   = " ORDER BY idAvantageEmploye DESC";
            $resultats      = $this->findAll('avantage_employe', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $avantageEmploye    = new AvantageEmploye($resultat);
                $avantageEmployes[] = $avantageEmploye;
            }
            return $avantageEmployes;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new AvantageEmploye();
        }

        /** 
         * Chercher un AvantageEmploye
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $avantageEmploye = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('avantage_employe', $fields, $values);
            if (!empty($resultat)) {
                $avantageEmploye = new AvantageEmploye($resultat);
            }
            return $avantageEmploye;
        }

        /**
         * Ajouter un AvantageEmploye
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idAvantageEmploye'] = $this->insert('avantage_employe', $parameters);
            return new AvantageEmploye($parameters);
        }

        /**
         * Modifier un AvantageEmploye
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('avantage_employe', $parameters);
            return new AvantageEmploye($parameters);
        }

        /**
         * Supprimer un AvantageEmploye
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('avantage_employe', $parameters);
        }
        
    }