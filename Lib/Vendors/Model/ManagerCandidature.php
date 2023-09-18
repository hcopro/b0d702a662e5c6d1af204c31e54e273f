<?php
    
    /**
     * Manager de l'entité Candidature
     *
     * @author Voahirana
     *
     * @since 21/10/19 
     */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Candidature;

	class ManagerCandidature extends DbManager
	{
        /**
         * Lister les candidatures
         *
         * @param array $attributes Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes) 
        {
            $candidatures = array();
            $string       = " ORDER BY dateCandidature DESC";
            $resultats    = $this->findAll('candidature', $attributes, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $candidature    = new Candidature($resultat);
                    $candidatures[] = $candidature; 
                }
            } 
            return $candidatures;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Candidature();
        }

        /** 
         * Chercher une candidature
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields      = array();
            $values      = array();
            $candidature = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('candidature', $fields, $values);
            if (!empty($resultat)) {
                $candidature = new Candidature($resultat);
            }
            return $candidature;
        }

        /** 
         * Chercher le dérnier identifiant d'une candidature
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('candidature', 'idCandidature');
        }

        /**
         * Insérer une candidature
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
         */
        public function ajouter($parameters) 
        { 
            $this->insert('candidature', $parameters);
            return new Candidature($parameters);
        }

        /**
         * Modifier une candidature
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('candidature', $parameters);
            return new Candidature($parameters);
        }
        
    }