<?php
    /**
     * Manager de l'entité Langue
     *
     * @author Billy Bam
     *
     * @since 17/11/2022 
    */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Langue;

    class ManagerLangue extends DbManager
    {
   		/** 
         * Lister les langues
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
        */
        public function lister($attributes = null)
        {
            $langues          = array();
            $queryOrderBy   = " ORDER BY nom_langue ASC";
            $resultats      = $this->findAll('langue', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $langue    = new Langue($resultat);
                $langues[] = $langue;
            }
            return $langues;
        }
        /** 
         * Créer un objet vide
         *
         * @return object|empty
        */
        public function initialiser()
        {
            return new Langue();
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
            $Langue   = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('langue', $fields, $values);
            if (!empty($resultat)) {
                $langue = new Langue($resultat);
            }
            return $langue;
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
            $parameters['idLangue'] = $this->insert('langue', $parameters);
            return new Langue($parameters);
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
            $update = $this->update('langue', $parameters);
            return new Langue($parameters);
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
            return $this->delete('langue', $parameters);
        }
    }