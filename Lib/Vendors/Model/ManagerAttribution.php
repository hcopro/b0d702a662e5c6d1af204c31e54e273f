<?php
    /**
     * Manager de l'entité Attribution
     *
     * @author Billy Bam
     *
     * @since 27/03/2023 
    */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Attribution;

    /**
     * 
    */
    class ManagerAttribution extends DbManager
    {
   		/** 
         * Lister les attributions
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
        */
        public function lister($attributes = null)
        {
            $attributions          = array();
            $queryOrderBy   = " ORDER BY statut ASC";
            $resultats      = $this->findAll('attribution', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $attribution    = new Attribution($resultat);
                $attributions[] = $attribution;
            }
            return $attributions;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
        */
        public function initialiser()
        {
            return new Attribution();
        }

        /** 
         * Chercher une Langue
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
        */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $Attribution   = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('attribution', $fields, $values);
            if (!empty($resultat)) {
                $attribution = new Langue($resultat);
            }
            return $attribution;
        }

        /**
         * Ajouter une langue
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
        */
        public function ajouter($parameters) 
        {
            $parameters['idAttribution'] = $this->insert('attribution', $parameters);
            return new Attribution($parameters);
        }

        /**
         * Modifier une langue
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
        */
        public function modifier($parameters) 
        {            
            $update = $this->update('attribution', $parameters);
            return new Attribution($parameters);
        }

        /**
         * Supprimer une langue
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimer($parameters) 
        {
            return $this->delete('attribution', $parameters);
        }
        
    }