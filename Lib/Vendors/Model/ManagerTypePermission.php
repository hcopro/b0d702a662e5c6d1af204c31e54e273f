<?php
    
    /**
     * Manager de l'entité TypePermission
     *
     * @author Toky
     *
     * @since 23/07/2020 
    */

    namespace Model;

    use \Core\DbManager;
    use \Entity\TypePermission;

    class ManagerTypePermission extends DbManager
    {
        /** 
         * Lister les types de Permission
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
        */
        public function lister()
        {
            $typePermissions   = array();
            $queryOrderBy      = " ORDER BY idTypePermission ASC";
            $resultats = $this->findAll('type_permission', null, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $typePermission    = new TypePermission($resultat);
                $typePermissions[] = $typePermission;
            }
            return $typePermissions;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
        */
        public function initialiser()
        {
            return new TypePermission();
        }

        /** 
         * Chercher un type de Permission
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
        */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $typePermission = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('type_permission', $fields, $values);
            if (!empty($resultat)) {
                $typePermission = new TypePermission($resultat);
            }
            return $typePermission;
        }

        /**
         * Ajouter un type de Permission
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
        */
        public function ajouter($parameters) 
        {
            $parameters['idTypePermission'] = $this->insert('type_permission', $parameters);
            return new TypePermission($parameters);
        }

        /**
         * Modifier un type de Permission
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
        */
        public function modifier($parameters) 
        {
            $this->update('type_permission', $parameters);
            return new TypePermission($parameters);
        }

        /**
         * Supprimer un type de Permission
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimer($parameters) 
        {
            return $this->delete('type_permission', $parameters);
        }

        /**
         * Récupérer les types de Permissions de l'entreprise
         *
         * @changeLog 2023-08-31 [EVOL] (Lansky) Ajout de la méthode
         *
         * @return array
        */
        public function getAllEntreprisePermission() 
        {
            $typePermissions = [];
            $query = "SELECT tp.*
                        FROM type_permission tp
                        INNER JOIN entreprise_permission ep
                        ON tp.idTypePermission = ep.idTypePermission
                            AND ep.idEntreprise = " . $_SESSION['user']['idEntreprise'];
            $pdo = $this->pdo();
            $requete    = $pdo->prepare($query);
            $requete->execute();
            $response   = $requete->fetchAll();
            if (!empty($response)) {
                foreach ($response as $resultat) {
                    $typePermission    = new TypePermission($resultat);
                    $typePermissions[] = $typePermission;
                }
            }
            return $typePermissions;
        }
        
    }