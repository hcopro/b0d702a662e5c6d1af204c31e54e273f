<?php
    
    /**
     * Manager de l'entité Questionnaire
     *
     * @author Lansky
     *
     * @since 09/07/2021 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Questionnaire;

    class ManagerQuestionnaire extends DbManager
    {
        /** 
         * Lister les Questionnaires
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $questionnaires = array();
            $queryOrderBy   = " ORDER BY question ASC";
            $resultats      = $this->findAll('evaluation_questionnaire', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $questionnaire    = new Questionnaire($resultat);
                $questionnaires[] = $questionnaire;
            }
            return $questionnaires;
        }
        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Questionnaire();
        }
        /** 
         * Chercher un Questionnaire
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $questionnaire = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('evaluation_questionnaire', $fields, $values);
            if (!empty($resultat)) {
                $questionnaire = new Questionnaire($resultat);
            }
            return $questionnaire;
        }
        /**
         * Ajouter un Questionnaire
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idQuestionnaire'] = $this->insert('evaluation_questionnaire', $parameters);
            return new Questionnaire($parameters);
        }
        /**
         * Modifier un Questionnaire
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            
            $this->update('evaluation_questionnaire', $parameters);
            return new Questionnaire($parameters);
        }
        /**
         * Supprimer un Questionnaire
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('evaluation_questionnaire', $parameters);
        }
    }