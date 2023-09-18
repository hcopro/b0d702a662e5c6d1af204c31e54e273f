<?php
    
    /**
     * Manager de l'entité ParametreAvantage
     *
     * @author Toky
     *
     * @since 18/11/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ParametreAvantage;

    class ManagerParametreAvantage extends DbManager
    {
        /** 
         * Lister les ParametreAvantages
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $parametreAvantages      = array();
            $queryOrderBy   = " ORDER BY idParametreAvantage DESC";
            $resultats      = $this->findAll('parametre_avantage', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $parametreAvantage    = new ParametreAvantage($resultat);
                $parametreAvantages[] = $parametreAvantage;
            }
            return $parametreAvantages;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new ParametreAvantage();
        }

        /** 
         * Chercher un ParametreAvantage
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $parametreAvantage = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('parametre_avantage', $fields, $values);
            if (!empty($resultat)) {
                $parametreAvantage = new ParametreAvantage($resultat);
            }
            return $parametreAvantage;
        }

        /**
         * Ajouter un ParametreAvantage
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idParametreAvantage'] = $this->insert('parametre_avantage', $parameters);
            return new ParametreAvantage($parameters);
        }

        /**
         * Modifier un ParametreAvantage
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('parametre_avantage', $parameters);
            return new ParametreAvantage($parameters);
        }

        /**
         * Supprimer un ParametreAvantage
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('parametre_avantage', $parameters);
        }
        
    }