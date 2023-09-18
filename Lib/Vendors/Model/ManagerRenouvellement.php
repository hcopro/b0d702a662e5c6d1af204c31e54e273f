<?php
    
    /**
     * Manager de l'entité Renouvellement
     *
     * @author Toky
     *
     * @since 16/06/20 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Renouvellement;

    class ManagerRenouvellement extends DbManager
    {
       /**
         * Lister les renouvellements
         *
         *@param array $attributes critères des données à lister
         *
         * @return array
         */
        public function lister($attributes) 
        {
            $renouvellements = array();
            $queryOrderBy = " ORDER BY idRenouvellement DESC";
            $resultats     = $this->findAll('renouvellement', $attributes, $queryOrderBy);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $renouvellement    = new Renouvellement($resultat);
                    $renouvellements[] = $renouvellement;
                }
            }            
            return $renouvellements;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Renouvellement();
        }

        /** 
         * Chercher un renouvellement
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $renouvellement = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('renouvellement', $fields, $values);
            if (!empty($resultat)) {
                $renouvellement = new Renouvellement($resultat);
            }
            return $renouvellement;
        }

        /**
         * Ajouter un renouvellement
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idRenouvellement'] = $this->insert('renouvellement', $parameters);
            return new Renouvellement($parameters);
        }

        /**
         * Modifier un renouvellement
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('renouvellement', $parameters);
            return new Renouvellement($parameters);
        }

        /**
         * Supprimer un renouvellement
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('renouvellement', $parameters);
        }
    }