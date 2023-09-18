<?php
    
    /**
     * Manager de l'entité Interim
     *
     * @author Toky
     *
     * @since 10/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Interim;

    class ManagerInterim extends DbManager
    {
        /** 
         * Lister les intérim
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs)
        {
            $interims   = array();
            $queryOrderBy   = " ORDER BY debut DESC";
            $resultats      = $this->findAll('interim', $attributs, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $interim    = new Interim($resultat);
                $interims[] = $interim;
            }
            return $interims;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Interim();
        }

        /** 
         * Chercher un intérim
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $interim = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('interim', $fields, $values);
            if (!empty($resultat)) {
                $interim = new Interim($resultat);
            }
            return $interim;
        }

        /**
         * Ajouter un intérim
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idInterim'] = $this->insert('interim', $parameters);
            return new Interim($parameters);
        }

        /**
         * Modifier un intérim
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('interim', $parameters);
            return new Interim($parameters);
        }

        /**
         * Supprimer un interim
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('interim', $parameters);
        }
        
    }