<?php
    
    /**
     * Manager de l'entité TestPersonality
     *
     * @author Lansky
     *
     * @since 01/04/2022 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\TestPersonality;

    class ManagerTestPersonality extends DbManager
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
            $queryOrderBy   = " ORDER BY libelle ASC";
            $resultats      = $this->findAll('test_personality', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $testPersonality    = new TestPersonality($resultat);
                $testPersonalities[] = $testPersonality;
            }
            return $testPersonalities;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new TestPersonality();
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
            $testPersonality = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('test_personality', $fields, $values);
            if (!empty($resultat)) {
                $testPersonality = new TestPersonality($resultat);
            }
            return $testPersonality;
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
            $parameters['id_test_personality'] = $this->insert('test_personality', $parameters);
            return new TestPersonality($parameters);
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
            $this->update('test_personality', $parameters);
            return new TestPersonality($parameters);
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
            return $this->delete('test_personality', $parameters);
        }
        
    }