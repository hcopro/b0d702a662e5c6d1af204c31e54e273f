<?php
    
    /**
     * Manager de l'entité Avantage
     *
     * @author Toky
     *
     * @since 21/10/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Avantage;

    class ManagerAvantage extends DbManager
    {
        /** 
         * Lister les avantages
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $avantages      = array();
            $queryOrderBy   = " ORDER BY idAvantage DESC";
            $resultats      = $this->findAll('avantage', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $avantage    = new Avantage($resultat);
                $avantages[] = $avantage;
            }
            return $avantages;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Avantage();
        }

        /** 
         * Chercher un avantage
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $avantage = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('avantage', $fields, $values);
            if (!empty($resultat)) {
                $avantage = new Avantage($resultat);
            }
            return $avantage;
        }

        /**
         * Ajouter un avantage
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idAvantage'] = $this->insert('avantage', $parameters);
            return new Avantage($parameters);
        }

        /**
         * Modifier un avantage
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('avantage', $parameters);
            return new Avantage($parameters);
        }

        /**
         * Supprimer un avantage
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('avantage', $parameters);
        }
        
    }