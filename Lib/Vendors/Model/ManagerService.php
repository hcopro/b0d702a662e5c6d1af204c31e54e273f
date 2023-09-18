<?php
    
    /**
     * Manager de l'entité Service
     *
     * @author Voahirana
     *
     * @since 11/03/20 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Service;

    class ManagerService extends DbManager
    {
       /**
         * Lister les services
         *
         * @return array
         */
        public function lister() 
        {
            $services = array();
            $string   = " ORDER BY nomService ASC";
            $tab      = $this->findAll('service', null,$string);
            if (!empty($tab)) {
                foreach ($tab as $data) {
                    $service    = new Service($data);
                    $services[] = $service;
                }
            }            
            return $services;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Service();
        }

        /** 
         * Chercher un service
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $service = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('service', $fields, $values);
            if (!empty($resultat)) {
                $service = new Service($resultat);
            }
            return $service;
        }

        /**
         * Ajouter un service
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idService'] = $this->insert('service', $parameters);
            return new Service($parameters);
        }

        /**
         * Modifier un service
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('service', $parameters);
            return new Service($parameters);
        }

        /**
         * Supprimer un service
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('service', $parameters);
        }
    }