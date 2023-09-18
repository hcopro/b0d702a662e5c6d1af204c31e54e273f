<?php
    
    /**
     * Manager de l'entité Evaluation
     *
     * @author Lansky
     *
     * @since 24/08/2021 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EvaluationEmploye;

    class ManagerEvaluationEmploye extends DbManager
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
            $queryOrderBy   = " ORDER BY libelle ASC";
            $resultats      = $this->findAll('evaluation_employe', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $evaluation    = new EvaluationEmploye($resultat);
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
            return new EvaluationEmploye();
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
            $resultat = $this->findOne('evaluation_employe', $fields, $values);
            if (!empty($resultat)) {
                $evaluation = new EvaluationEmploye($resultat);
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
            $parameters['idEvaluationEmploye'] = $this->insert('evaluation_employe', $parameters);
            return new EvaluationEmploye($parameters);
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
            $this->update('evaluation_employe', $parameters);
            return new EvaluationEmploye($parameters);
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
            return $this->delete('evaluation_employe', $parameters);
        }

        /** 
         * Recupérer les évaluation n'est pas encore validée par l'évaluateur
         *
         * @param array $parameters
         * 
         * @return array 
         */
        public function getEvaluationValide($parameters)
        {
            $query  =  " ( SELECT id_evaluation_employe 
                                FROM evaluation_evaluateur 
                                WHERE   id_evaluateur       = " . $parameters['idEvaluateur'] . "
                                        AND id_entreprise   = " . $parameters['idEntreprise'] . "
                                        AND is_archived     = " . '"NO"' .
                        ")";
            return $query;
        }
    }