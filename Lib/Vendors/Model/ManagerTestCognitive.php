<?php
    
    /**
     * Manager de l'entité TestCognitive
     *
     * @author Lansky
     *
     * @since 01/04/2022 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\TestCognitive;

    class ManagerTestCognitive extends DbManager
    {
        /**
         * Lister les tests cognitifs
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $testCognitives    = array();
            $queryOrderBy   = " ORDER BY libelle ASC";
            $resultats      = $this->findAll('test_cognitive', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $testCognitive    = new TestCognitive($resultat);
                $testCognitives[] = $testCognitive;
            }
            return $testCognitives;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new TestCognitive();
        }

        /** 
         * Chercher un test cognitif
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $testCognitive = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('test_cognitive', $fields, $values);
            if (!empty($resultat)) {
                $testCognitive = new TestCognitive($resultat);
            }
            return $testCognitive;
        }

        /**
         * Ajouter un test cognitif
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['id_test_cognitive'] = $this->insert('test_cognitive', $parameters);
            return new TestCognitive($parameters);
        }

        /**
         * Modifier un test cognitif
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('test_cognitive', $parameters);
            return new TestCognitive($parameters);
        }

        /**
         * Supprimer un test cognitif
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('test_cognitive', $parameters);
        }
        
    }