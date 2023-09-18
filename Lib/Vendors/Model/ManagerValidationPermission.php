<?php
    
    /**
     * Manager de l'entité ValidationPermission
     *
     * @author Lansky
     *
     * @since 2023-06-07 
    */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ValidationPermission;

    class ManagerValidationPermission extends DbManager
    {
        /** 
         * Lister les Validations de Permissions
         *
         * @param array $parameters     Critères des données à lister
         *
         * @return array
        */
        public function lister($attributes)
        {
            $validationPermissions  = array();
            $queryOrderBy           = " ORDER BY id_validation_permission DESC";
            $resultats              = $this->findAll('validation_permission', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $validationPermission    = new ValidationPermission($resultat);
                $validationPermissions[] = $validationPermission;
            }
            return $validationPermissions;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
        */
        public function initialiser()
        {
            return new ValidationPermission();
        }

        /** 
         * Chercher une validation de Congé
         *
         * @param array $parameters     Critères des données à chercher
         *
         * @return object|empty
        */
        public function chercher($parameters)
        {
            $fields                 = array();
            $values                 = array();
            $validationPermission   = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('validation_permission', $fields, $values);
            if (!empty($resultat)) {
                $validationPermission = new ValidationPermission($resultat);
            }
            return $validationPermission;
        }

        /**
         * Selectionner des ValidationConges dans un intervalle
         *
         * @param array @attributes     Critère avec égalité
         * @param array @min            Intervalle minimum
         * @param array @max            Interval maximum
         *
         * @return array
        */
        public function selectionner($attributes=null, $min, $max)
        {
            $validationPermissions  = array();
            $orderByQuery           = " ORDER BY id_validation_permission DESC";
            $resultats              = $this->selectAll('validation_permission', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $validationPermission    = new ValidationPermission($resultat);
                $validationPermissions[] = $validationPermission;
            }
            return $validationPermissions;
        }

        /**
         * Ajouter un ValidationPermission
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
        */
        public function ajouter($parameters) 
        {
            $parameters['idValidationPermission'] = $this->insert('validation_permission', $parameters);
            return new ValidationPermission($parameters);
        }

        /**
         * Modifier un ValidationPermission
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
        */
        public function modifier($parameters) 
        {
            $this->update('validation_permission', $parameters);
            return new ValidationPermission($parameters);
        }

        /**
         * Supprimer un ValidationPermission
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimer($parameters) 
        {
            return $this->delete('validation_permission', $parameters);
        }
        
    }