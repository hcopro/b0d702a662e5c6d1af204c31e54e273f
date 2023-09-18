<?php
    
    /**
     * Manager de l'entité TestQuestion
     *
     * @author Lansky
     *
     * @since 01/05/2022
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\TestQuestion;

    class ManagerTestQuestion extends DbManager
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
            $testQuestions    = array();
            $queryOrderBy   = " ORDER BY question ASC";
            $resultats      = $this->findAll('test_question', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $testQuestion    = new TestQuestion($resultat);
                $testQuestions[] = $testQuestion;
            }
            return $testQuestions;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new TestQuestion();
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
            $testQuestion = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('test_question', $fields, $values);
            if (!empty($resultat)) {
                $testQuestion = new TestQuestion($resultat);
            }
            return $testQuestion;
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
            $parameters['id_test_question'] = $this->insert('test_question', $parameters);
            return new TestQuestion($parameters);
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
            $this->update('test_question', $parameters);
            return new TestQuestion($parameters);
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
            return $this->delete('test_question', $parameters);
        }
    }