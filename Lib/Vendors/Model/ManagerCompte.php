<?php
    
    /**
     * Manager de l'entité Compte
     *
     * @author Voahirana 
     *
     * @since 02/10/19 
     */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Compte;

	class ManagerCompte extends DbManager
	{
        /**
         * Lister les comptes
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($parameters) 
        {
            $comptes   = array();
            $resultats = $this->findAll('compte', $parameters);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $compte    = new Compte($resultat);
                    $comptes[] = $compte; 
                }
            } 
            return $comptes;
        }
        /** 
         * Creer un compte
         *
         * @param array $parameters Les données à créer
         *
         * @return object
         */
        public function creerCompte($parameters)
        {
            $parameters['idCompte'] = $this->insert('compte', $parameters);
            return new Compte($parameters);
        }

        /** 
         * Chercher un compte
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $compte   = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('compte', $fields, $values);
            if (!empty($resultat)) {
                $compte = new Compte($resultat);
            }
            return $compte;
        }

        /** 
         * Chercher le dérnier identifiant d'un compte
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('compte', 'idCompte');
        }

        /**
         * Modifier un compte
         *
         * @param array Desc: Les données à modifier
         *
         * @return Object
         */
        public function modifier($parameters) 
        {
            $this->update('compte', $parameters);
            return new Compte($parameters);
        }

         /**
         * Supprimer un compte
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('compte', $parameters);
        }
    }