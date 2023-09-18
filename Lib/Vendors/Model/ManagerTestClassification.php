<?php
    
    /**
     * Manager de l'entité TestClassification
     *
     * @author Lansky
     *
     * @since 01/04/2022 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\TestClassification;

    class ManagerTestClassification extends DbManager
    {
        /** 
         * Lister les Evaluations
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $testClassifications    = array();
            $queryOrderBy   = " ORDER BY libelle ASC";
            $resultats      = $this->findAll('test_classification', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $testClassification    = new TestClassification($resultat);
                $testClassifications[] = $testClassification;
            }
            return $testClassifications;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new TestClassification();
        }

        /** 
         * Chercher une évaluation
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $testClassification = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('test_classification', $fields, $values);
            if (!empty($resultat)) {
                $testClassification = new TestClassification($resultat);
            }
            return $testClassification;
        }

        /**
         * Ajouter une évaluation
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['id_test_classification'] = $this->insert('test_classification', $parameters);
            return new TestClassification($parameters);
        }

        /**
         * Modifier une évaluation
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('test_classification', $parameters);
            return new TestClassification($parameters);
        }

        /**
         * Supprimer une évaluation
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('test_classification', $parameters);
        }
    }