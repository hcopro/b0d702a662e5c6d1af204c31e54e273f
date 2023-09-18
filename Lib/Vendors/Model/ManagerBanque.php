<?php
    
    /**
     * Manager de l'entité Banque
     *
     * @author Voahirana
     *
     * @since 13/03/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Banque;

    class ManagerBanque extends DbManager
    {
        /** 
         * Lister les banques
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister()
        {
            $banques   = array();
            $string    = " ORDER BY nomBanque ASC";
            $resultats = $this->findAll('banque', null, $string);
            foreach ($resultats as $resultat) {
                $banque    = new Banque($resultat);
                $banques[] = $banque;
            }
            return $banques;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Banque();
        }

        /** 
         * Chercher une banque
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $banque = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('banque', $fields, $values);
            if (!empty($resultat)) {
                $banque = new Banque($resultat);
            }
            return $banque;
        }

        /**
         * Ajouter une banque
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idBanque'] = $this->insert('banque', $parameters);
            return new Banque($parameters);
        }

        /**
         * Modifier une banque
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('banque', $parameters);
            return new Banque($parameters);
        }

        /**
         * Supprimer une banque
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('banque', $parameters);
        }
        
    }