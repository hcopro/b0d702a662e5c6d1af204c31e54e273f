<?php
    
    /**
     * Manager de l'entité Avance
     *
     * @author Toky
     *
     * @since 18/11/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Avance;

    class ManagerAvance extends DbManager
    {
        /** 
         * Lister les Avances
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $avances      = array();
            $queryOrderBy   = " ORDER BY idAvance DESC";
            $resultats      = $this->findAll('avance', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $avance    = new Avance($resultat);
                $avances[] = $avance;
            }
            return $avances;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Avance();
        }

        /** 
         * Chercher un Avance
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $avance = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('avance', $fields, $values);
            if (!empty($resultat)) {
                $avance = new Avance($resultat);
            }
            return $avance;
        }

        /**
         * Ajouter un Avance
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idAvance'] = $this->insert('avance', $parameters);
            return new Avance($parameters);
        }

        /**
         * Modifier un Avance
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('avance', $parameters);
            return new Avance($parameters);
        }

        /**
         * Supprimer un Avance
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('avance', $parameters);
        }
        
    }