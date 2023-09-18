<?php
    
    /**
     * Manager de l'entité EvaluationEvaluationInterim
     *
     * @author Toky
     *
     * @since 10/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EvaluationInterim;

    class ManagerEvaluationInterim extends DbManager
    {
        /** 
         * Lister les intérim
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs)
        {
            $evaluationInterims   = array();
            $queryOrderBy         = " ORDER BY idEvaluationInterim DESC";
            $resultats            = $this->findAll('evaluation_interim', $attributs, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $evaluationInterim    = new EvaluationInterim($resultat);
                $evaluationInterims[] = $evaluationInterim;
            }
            return $evaluationInterims;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new EvaluationInterim();
        }

        /** 
         * Chercher un intérim
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $evaluationInterim = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('evaluation_interim', $fields, $values);
            if (!empty($resultat)) {
                $evaluationInterim = new EvaluationInterim($resultat);
            }
            return $evaluationInterim;
        }

        /**
         * Ajouter un intérim
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idEvaluationInterim'] = $this->insert('evaluation_interim', $parameters);
            return new EvaluationInterim($parameters);
        }

        /**
         * Modifier un intérim
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('evaluation_interim', $parameters);
            return new EvaluationInterim($parameters);
        }

        /**
         * Supprimer un EvaluationInterim
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('evaluation_interim', $parameters);
        }
        
    }