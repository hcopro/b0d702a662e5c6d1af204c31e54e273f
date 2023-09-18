<?php
    
    /**
     * Manager de l'entité Evaluation
     *
     * @author Lansky
     *
     * @since 09/07/2021 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Evaluation;

    class ManagerEvaluation extends DbManager
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
            $evaluations    = array();
            $queryOrderBy   = " ORDER BY libelle ASC";
            $resultats      = $this->findAll('evaluation', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $evaluation    = new Evaluation($resultat);
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
            return new Evaluation();
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
            $resultat = $this->findOne('evaluation', $fields, $values);
            if (!empty($resultat)) {
                $evaluation = new Evaluation($resultat);
            }
            return $evaluation;
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
            $parameters['idEvaluation'] = $this->insert('evaluation', $parameters);
            return new Evaluation($parameters);
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
            $this->update('evaluation', $parameters);
            return new Evaluation($parameters);
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
            return $this->delete('evaluation', $parameters);
        }
        
    }