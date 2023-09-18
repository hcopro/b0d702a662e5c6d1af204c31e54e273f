<?php
    
    /**
     * Manager de l'entité EvaluationFormation
     *
     * @author Toky
     *
     * @since 10/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EvaluationFormation;

    class ManagerEvaluationFormation extends DbManager
    {
        /** 
         * Lister les évaluations  concernées par la formation
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs)
        {
            $evaluationFormations   = array();
            $queryOrderBy   = " ORDER BY idEvaluationFormation DESC";
            $resultats      = $this->findAll('evaluation_formation', $attributs, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $evaluationFormation    = new EvaluationFormation($resultat);
                $evaluationFormations[] = $evaluationFormation;
            }
            return $evaluationFormations;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new EvaluationFormation();
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
            $evaluationFormation = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('evaluation_formation', $fields, $values);
            if (!empty($resultat)) {
                $evaluationFormation = new EvaluationFormation($resultat);
            }
            return $evaluationFormation;
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
            $parameters['idEvaluationFormation'] = $this->insert('evaluation_formation', $parameters);
            return new EvaluationFormation($parameters);
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
            $this->update('evaluation_formation', $parameters);
            return new EvaluationFormation($parameters);
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
            return $this->delete('evaluation_formation', $parameters);
        }
        
    }