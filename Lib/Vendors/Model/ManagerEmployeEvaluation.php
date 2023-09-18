<?php
    
    /**
     * Manager de l'entité EmployeEvaluation
     *
     * @author Lansky
     *
     * @since 10/07/2021 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EmployeEvaluation;

    class ManagerEmployeEvaluation extends DbManager
    {
        /** 
         * Lister les Evaluations d'employées
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $employe_evaluations      = array();
            $queryOrderBy   = " ORDER BY idEmplEval DESC";
            $resultats      = $this->findAll('employe_evaluation', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $emplEval    = new EmployeEvaluation($resultat);
                $emplEvals[] = $emplEval;
            }
            return $employe_evaluations;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new EmployeEvaluation();
        }

        /** 
         * Chercher un EmployeEvaluation
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $emplEval = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('employe_evaluation', $fields, $values);
            if (!empty($resultat)) {
                $emplEval = new EmployeEvaluation($resultat);
            }
            return $emplEval;
        }

        /**
         * Ajouter un EmployeEvaluation
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idEmplEval'] = $this->insert('employe_evaluation', $parameters);
            return new EmployeEvaluation($parameters);
        }

        /**
         * Modifier un EmployeEvaluation
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('employe_evaluation', $parameters);
            return new EmployeEvaluation($parameters);
        }

        /**
         * Supprimer un EmployeEvaluation
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('employe_evaluation', $parameters);
        }
        
    }