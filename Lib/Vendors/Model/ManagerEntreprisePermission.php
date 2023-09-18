<?php
    
    /**
     * Manager de l'entité entreprisePermission
     *
     * @author Toky
     *
     * @since 23/07/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EntreprisePermission;

    class ManagerEntreprisePermission extends DbManager
    {
        /** 
         * Lister les permissions d'une entreprise
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $entreprisePermissions   = array();
            $queryOrderBy            = " ORDER BY idEntreprisePermission ASC";
            $resultats               = $this->findAll('entreprise_permission', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $entreprisePermission    = new EntreprisePermission($resultat);
                $entreprisePermissions[] = $entreprisePermission;
            }
            return $entreprisePermissions;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new EntreprisePermission();
        }

        /** 
         * Chercher une permission d'une entreprise
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $entreprisePermission = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('entreprise_permission', $fields, $values);
            if (!empty($resultat)) {
                $entreprisePermission = new EntreprisePermission($resultat);
            }
            return $entreprisePermission;
        }

        /**
         * Ajouter une permission d'entreprise
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idEntreprisePermission'] = $this->insert('entreprise_permission', $parameters);
            return new EntreprisePermission($parameters);
        }

        /**
         * Modifier une permission d'une entreprise
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('entreprise_permission', $parameters);
            return new EntreprisePermission($parameters);
        }

        /**
         * Supprimer une permission d'entreprise
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('entreprise_permission', $parameters);
        }
        
    }