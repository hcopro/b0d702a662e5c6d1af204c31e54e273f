<?php
    
    /**
     * Manager de l'entité EvaluationCategorie
     *
     * @author Lansky
     *
     * @since 09/07/2021 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EvaluationCategorie;

    class ManagerEvaluationCategorie extends DbManager
    {
        /** 
         * Lister les categories
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes = null)
        {
            $categories     = array();
            $queryOrderBy   = " ORDER BY libelle ASC";
            $resultats      = $this->findAll('evaluation_categorie', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $categorie    = new EvaluationCategorie($resultat);
                $categories[] = $categorie;
            }
            return $categories;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new EvaluationCategorie();
        }

        /** 
         * Chercher une categorie
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $categorie   = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('evaluation_categorie', $fields, $values);
            if (!empty($resultat)) {
                $categorie = new EvaluationCategorie($resultat);
            }
            return $categorie;
        }

        /**
         * Ajouter une categorie
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idCategorie'] = $this->insert('evaluation_categorie', $parameters);
            return new EvaluationCategorie($parameters);
        }

        /**
         * Modifier une categorie
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        { 
            $this->update('evaluation_categorie', $parameters);
            return new EvaluationCategorie($parameters);
        }

        /**
         * Supprimer une categorie
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('evaluation_categorie', $parameters);
        }
    }