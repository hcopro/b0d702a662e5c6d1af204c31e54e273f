<?php
    
    /**
     * Manager de l'entité ParametrePermission
     *
     * @author Toky
     *
     * @since 24/07/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ParametrePermission;

    class ManagerParametrePermission extends DbManager
    {
        /** 
         * Lister les parametres de permission
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister()
        {
            $parametrePermissions   = array();
            $queryOrderBy            = " ORDER BY idParametrePermission ASC";
            $resultats               = $this->findAll('parametre_permission', null, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $parametrePermission    = new ParametrePermission($resultat);
                $parametrePermissions[] = $parametrePermission;
            }
            return $parametrePermissions;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new ParametrePermission();
        }

        /** 
         * Chercher un parametre d'une permission
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $parametrePermission = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('parametre_permission', $fields, $values);
            if (!empty($resultat)) {
                $parametrePermission = new ParametrePermission($resultat);
            }
            return $parametrePermission;
        }

        /**
         * Ajouter un parametre de permission
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idParametrePermission'] = $this->insert('parametre_permission', $parameters);
            return new ParametrePermission($parameters);
        }

        /**
         * Modifier un parametre de permission
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('parametre_permission', $parameters);
            return new ParametrePermission($parameters);
        }

        /**
         * Supprimer un parametre de permission
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('parametre_permission', $parameters);
        }
        
    }