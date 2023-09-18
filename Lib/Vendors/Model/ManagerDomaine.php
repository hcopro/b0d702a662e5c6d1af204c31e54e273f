<?php
    
    /**
     * Manager de l'entité Domaine
     *
     * @author Voahirana
     *
     * @since 03/10/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Domaine;

    class ManagerDomaine extends DbManager
    {
       /**
         * Lister les domaines
         *
         * @return array
         */
        public function lister() 
        {
            $domaines = array();
            $string   = " ORDER BY nomDomaine ASC";
            $tab      = $this->findAll('domaine', null,$string);
            if (!empty($tab)) {
                foreach ($tab as $data) {
                    $domaine    = new Domaine($data);
                    $domaines[] = $domaine;
                }
            }            
            return $domaines;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Domaine();
        }

        /** 
         * Chercher un domaine
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $domaine = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('domaine', $fields, $values);
            if (!empty($resultat)) {
                $domaine = new Domaine($resultat);
            }
            return $domaine;
        }

        /** 
         * Chercher le dérnier identifiant d'un domaine
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('domaine', 'idDomaine');
        }

        /**
         * Ajouter un domaine
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idDomaine'] = $this->insert('domaine', $parameters);
            return new Domaine($parameters);
        }

        /**
         * Modifier un domaine
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('domaine', $parameters);
            return new Domaine($parameters);
        }

        /**
         * Supprimer un domaine
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('domaine', $parameters);
        }
    }