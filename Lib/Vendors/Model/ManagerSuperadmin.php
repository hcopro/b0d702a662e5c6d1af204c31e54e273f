<?php
    
    /**
     * Manager de l'entité Superadmin
     *
     * @author Voahirana
     *
     * @since 30/09/19 
     */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Superadmin;

	class ManagerSuperadmin extends DbManager
	{
        /**
         * Lister les superadmins
         *
         * @return array
         */
        public function lister() 
        {
            $superadmins = array();
            $string      = " ORDER BY nom ASC";
            $resultats   = $this->findAll('superadmin', null, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $superadmin    = new Superadmin($resultat);
                    $superadmins[] = $superadmin;
                }
            }            
            return $superadmins;
        }
        
        /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Superadmin();
        }

        /** 
         * Chercher un superadmin
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields     = array();
            $values     = array();
            $superadmin = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('superadmin', $fields, $values);
            if (!empty($resultat)) {
                $superadmin = new Superadmin($resultat);
            }
            return $superadmin;
        }

        /**
         * Insérer un superadmin
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $this->insert('superadmin', $parameters);
            return new Superadmin($parameters);
        }

        /**
         * modifier un superadmin
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('superadmin', $parameters);
            return new Superadmin($parameters);
        }

        /**
         * Supprimer un admin
         *
         * @param array $parameters Critères données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('superadmin', $parameters);
        }
	}