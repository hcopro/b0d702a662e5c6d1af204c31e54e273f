<?php
    
    /**
     * Manager de l'entité ParametreHeure
     *
     * @author Toky
     *
     * @since 21/10/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ParametreHeure;

    class ManagerParametreHeure extends DbManager
    {
        /** 
         * Lister les ParametreHeures
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $parametreHeures      = array();
            $queryOrderBy   = " ORDER BY idParametreHeure DESC";
            $resultats      = $this->findAll('parametre_heure', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $parametreHeure    = new ParametreHeure($resultat);
                $parametreHeures[] = $parametreHeure;
            }
            return $parametreHeures;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new ParametreHeure();
        }

        /** 
         * Chercher un ParametreHeure
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $parametreHeure = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('parametre_heure', $fields, $values);
            if (!empty($resultat)) {
                $parametreHeure = new ParametreHeure($resultat);
            }
            return $parametreHeure;
        }

        /**
         * Ajouter un ParametreHeure
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idParametreHeure'] = $this->insert('parametre_heure', $parameters);
            return new ParametreHeure($parameters);
        }

        /**
         * Modifier un ParametreHeure
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('parametre_heure', $parameters);
            return new ParametreHeure($parameters);
        }

        /**
         * Supprimer un ParametreHeure
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('parametre_heure', $parameters);
        }
        
    }