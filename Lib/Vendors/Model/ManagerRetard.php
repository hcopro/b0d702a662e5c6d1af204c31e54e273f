<?php
    
    /**
     * Manager de l'entité Retard
     *
     * @author Lansky
     *
     * @since 11/10/2022
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Retard;

    class ManagerRetard extends DbManager
    {
        /** 
         * Lister les retards
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs = null)
        {
            $retards   = array();
            $orderByQuery = " ORDER BY id_retard ASC";
            $resultats = $this->findAll('retard', $attributs, $orderByQuery);
            foreach ($resultats as $resultat) {
                $retard   = new Retard($resultat);
                $retards[] = $retard;
            }
            return $retards;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Retard();
        }

        /** 
         * Chercher une retard
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $retard     = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    if (strstr($value, 'LIKE')) {
                        $value =  " " . $value;
                    } else {
                        $value =  "'" . $value . "'";
                    }
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('retard', $fields, $values);
            if (!empty($resultat)) {
                $retard = new Retard($resultat);
            }
            return $retard;
        }

        /**
         * Ajouter une retard
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idRetard'] = $this->insert('retard', $parameters);
            return new Retard($parameters);
        }

        /**
         * Modifier une retard
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('retard', $parameters);
            return new Retard($parameters);
        }

        /**
         * Supprimer une retard
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('retard', $parameters);
        }
        
    }