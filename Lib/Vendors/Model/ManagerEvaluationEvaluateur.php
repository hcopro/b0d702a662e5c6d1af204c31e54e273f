<?php
    
    /**
     * Manager de l'entité Evaluation
     *
     * @author Lansky
     *
     * @since 31/08/2021 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EvaluationEvaluateur;

    class ManagerEvaluationEvaluateur extends DbManager
    {
        /** 
         * Lister les évaluations employée
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $evaluations    = array();
            $queryOrderBy   = " ORDER BY id_evaluateur DESC";
            $resultats      = $this->findAll('evaluation_evaluateur', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $evaluation    = new EvaluationEvaluateur($resultat);
                $evaluations[] = $evaluation;
            }
            return $evaluations;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new EvaluationEvaluateur();
        }

        /** 
         * Chercher une évaluation employée
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $evaluation = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('evaluation_evaluateur', $fields, $values);
            if (!empty($resultat)) {
                $evaluation = new EvaluationEvaluateur($resultat);
            }
            return $evaluation;
        }

        /**
         * Ajouter une évaluation employée
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idEvaluationEvaluateur'] = $this->insert('evaluation_evaluateur', $parameters);
            return new EvaluationEvaluateur($parameters);
        }

        /**
         * Modifier une évaluation employée
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('evaluation_evaluateur', $parameters);
            return new EvaluationEvaluateur($parameters);
        }

        /**
         * Supprimer une évaluation employée
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('evaluation_evaluateur', $parameters);
        }
    }