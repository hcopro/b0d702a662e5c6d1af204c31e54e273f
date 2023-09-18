<?php
    
    /**
     * Manager de l'entité ParametrePointage
     *
     * @author Toky
     *
     * @since 29/10/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ParametrePointage;

    class ManagerParametrePointage extends DbManager
    {
        /** 
         * Lister les ParametrePointages
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $parametrePointages  = array();
            $queryOrderBy        = " ORDER BY idParametrePointage DESC";
            $resultats           = $this->findAll('parametre_pointage', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $parametrePointage    = new ParametrePointage($resultat);
                $parametrePointages[] = $parametrePointage;
            }
            return $parametrePointages;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new ParametrePointage();
        }

        /** 
         * Chercher un ParametrePointage
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $parametrePointage = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('parametre_pointage', $fields, $values);
            if (!empty($resultat)) {
                $parametrePointage = new ParametrePointage($resultat);
            }
            return $parametrePointage;
        }

        /**
         * Ajouter un ParametrePointage
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idParametrePointage'] = $this->insert('parametre_pointage', $parameters);
            return new ParametrePointage($parameters);
        }

        /**
         * Modifier un ParametrePointage
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('parametre_pointage', $parameters);
            return new ParametrePointage($parameters);
        }

        /**
         * Supprimer un ParametrePointage
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('parametre_pointage', $parameters);
        }
        
    }