<?php
    
    /**
     * Manager de l'entité ParametreAvance
     *
     * @author Toky
     *
     * @since 18/11/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ParametreAvance;

    class ManagerParametreAvance extends DbManager
    {
        /** 
         * Lister les ParametreAvances
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $parametreAvances      = array();
            $queryOrderBy   = " ORDER BY idParametreAvance DESC";
            $resultats      = $this->findAll('parametre_avance', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $parametreAvance    = new ParametreAvance($resultat);
                $parametreAvances[] = $parametreAvance;
            }
            return $parametreAvances;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new ParametreAvance();
        }

        /** 
         * Chercher un ParametreAvance
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $parametreAvance = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('parametre_avance', $fields, $values);
            if (!empty($resultat)) {
                $parametreAvance = new ParametreAvance($resultat);
            }
            return $parametreAvance;
        }

        /**
         * Ajouter un ParametreAvance
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idParametreAvance'] = $this->insert('parametre_avance', $parameters);
            return new ParametreAvance($parameters);
        }

        /**
         * Modifier un ParametreAvance
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('parametre_avance', $parameters);
            return new ParametreAvance($parameters);
        }

        /**
         * Supprimer un ParametreAvance
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('parametre_avance', $parameters);
        }
    }