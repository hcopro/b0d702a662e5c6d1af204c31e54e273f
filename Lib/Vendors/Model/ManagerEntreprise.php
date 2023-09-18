<?php
    
    /**
     * Manager de l'entité Entreprise
     *
     * @author Voahirana
     *
     * @since 26/09/19 
     */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Entreprise;

	class ManagerEntreprise extends DbManager
	{
        /**
         * Lister les entreprises
         *
         * @return array
         */
        public function lister() 
        {
            $entreprises = array();
            $string      = " ORDER BY nom ASC";
            $resultats   = $this->findAll('entreprise', null, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $entreprise    = new Entreprise($resultat);
                    $entreprises[] = $entreprise; 
                }
            } 
            return $entreprises;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Entreprise();
        }

        /** 
         * Chercher le dérnier identifiant d'un entreprise
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('entreprise', 'idEntreprise');
        }

        /** 
         * Chercher une entreprise
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields     = array();
            $values     = array();
            $entreprise = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('entreprise', $fields, $values);
            if (!empty($resultat)) {
                $entreprise = new Entreprise($resultat);
            }
            return $entreprise;
        }

        /**
         * Insérer une entreprise
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idEntreprise'] = $this->insert('entreprise', $parameters);
            return new Entreprise($parameters);
        }

        /**
         * Modifier une entreprise
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('entreprise', $parameters);
            return new Entreprise($parameters);
        }
        
    }