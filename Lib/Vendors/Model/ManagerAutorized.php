<?php
    
    /**
     * Manager de l'entité Autorized
     *
     * @author Lansky
     *
     * @since 28/12/2022
    */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Autorized;

    class ManagerAutorized extends DbManager
    {
        /** 
         * Lister les autorisés
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
        */
        public function lister($attributs = null)
        {
            $autorizeds   = array();
            $orderByQuery = " ORDER BY id_employe ASC";
            $resultats = $this->findAll('autorized', $attributs, $orderByQuery);
            foreach ($resultats as $resultat) {
                $autorized   = new Autorized($resultat);
                $autorizeds[] = $autorized;
            }
            return $autorizeds;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
        */
        public function initialiser()
        {
            return new Autorized();
        }

        /** 
         * Chercher une autorisation
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
        */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $autorized     = null;
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
            $resultat = $this->findOne('autorized', $fields, $values);
            if (!empty($resultat)) {
                $autorized = new Autorized($resultat);
            }
            return $autorized;
        }

        /**
         * Ajouter une autorisation
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
        */
        public function ajouter($parameters) 
        {
            $parameters['idAutorized'] = $this->insert('autorized', $parameters);
            return new Autorized($parameters);
        }

        /**
         * Modifier une autorisation
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
        */
        public function modifier($parameters) 
        {
            $this->update('autorized', $parameters);
            return new Autorized($parameters);
        }

        /**
         * Supprimer une autorisation
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimer($parameters) 
        {
            return $this->delete('autorized', $parameters);
        }
        
    }