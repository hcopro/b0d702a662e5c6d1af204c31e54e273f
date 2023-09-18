<?php
    
    /**
     * Manager de l'entité TestCandidate
     *
     * @author Lansky
     *
     * @since 01/04/2022 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\TestCandidate;

    class ManagerTestCandidate extends DbManager
    {
        /**
         * Lister les tests candidats
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $testCandidates    = array();
            $queryOrderBy   = " ORDER BY libelle ASC";
            $resultats      = $this->findAll('test_candidate', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $testCandidate    = new TestCandidate($resultat);
                $testCandidates[] = $testCandidate;
            }
            return $testCandidates;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new TestCandidate();
        }

        /** 
         * Chercher un test candidat
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $testCandidate = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('test_candidate', $fields, $values);
            if (!empty($resultat)) {
                $testCandidate = new TestCandidate($resultat);
            }
            return $testCandidate;
        }

        /**
         * Ajouter un test candidat
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idEvaluation'] = $this->insert('test_candidate', $parameters);
            return new TestCandidate($parameters);
        }

        /**
         * Modifier un test candidat
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('test_candidate', $parameters);
            return new TestCandidate($parameters);
        }

        /**
         * Supprimer un test candidat
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('test_candidate', $parameters);
        }
        
    }