<?php
    
    /**
     * Manager de l'entité AvanceQuinzaine
     *
     * @author Toky
     *
     * @since 24/11/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\AvanceQuinzaine;

    class ManagerAvanceQuinzaine extends DbManager
    {
        /** 
         * Lister les AvanceQuinzaines
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $avanceQuinzaines      = array();
            $queryOrderBy   = " ORDER BY idAvanceQuinzaine DESC";
            $resultats      = $this->findAll('avance_quinzaine', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $avanceQuinzaine    = new AvanceQuinzaine($resultat);
                $avanceQuinzaines[] = $avanceQuinzaine;
            }
            return $avanceQuinzaines;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new AvanceQuinzaine();
        }

        /** 
         * Chercher un AvanceQuinzaine
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $avanceQuinzaine = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('avance_quinzaine', $fields, $values);
            if (!empty($resultat)) {
                $avanceQuinzaine = new AvanceQuinzaine($resultat);
            }
            return $avanceQuinzaine;
        }

        /**
         * Selectionner des Avances quinzaine dans un intervalle
         *
         * @param array @attributes critère avec égalité
         * @param array @min        intervalle minimum
         * @param array @max        interval maximum
         *
         * @return array
         */
        public function selectionner($attributes=null, $min, $max)
        {
            $avanceQuinzaines = array();
            $orderByQuery     = " ORDER BY idAvanceQuinzaine DESC";
            $resultats = $this->selectAll('avance_quinzaine', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $avanceQuinzaine    = new AvanceQuinzaine($resultat);
                $avanceQuinzaines[] = $avanceQuinzaine;
            }
            return $avanceQuinzaines;
        }

        /**
         * Ajouter un AvanceQuinzaine
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idAvanceQuinzaine'] = $this->insert('avance_quinzaine', $parameters);
            return new AvanceQuinzaine($parameters);
        }

        /**
         * Modifier un AvanceQuinzaine
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('avance_quinzaine', $parameters);
            return new AvanceQuinzaine($parameters);
        }

        /**
         * Supprimer un AvanceQuinzaine
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('avance_quinzaine', $parameters);
        }
        
    }