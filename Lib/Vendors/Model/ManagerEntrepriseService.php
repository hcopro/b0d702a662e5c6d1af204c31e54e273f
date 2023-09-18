<?php
    
    /**
     * Manager de l'entité EntrepriseService
     *
     * @author Voahirana
     *
     * @since 11/03/20 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EntrepriseService;

    class ManagerEntrepriseService extends DbManager
    {
       /**
         * Lister les services d'une entreprise
         *
         * @param array $attributes Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes) 
        {
            $entrepriseServices = array();
            $string             = " ORDER BY service ASC";
            $resultats          = $this->findAll('entreprise_service', $attributes, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $entrepriseService    = new entrepriseService($resultat);
                    $entrepriseServices[] = $entrepriseService;
                }
            }            
            return $entrepriseServices;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new EntrepriseService();
        }

        /** 
         * Chercher un service d'une entreprise
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields            = array();
            $values            = array();
            $entrepriseService = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }            
            $resultat = $this->findOne('entreprise_service', $fields, $values);
            if (!empty($resultat)) {
                $entrepriseService = new EntrepriseService($resultat);
            }
            return $entrepriseService;
        }

        /**
         * Ajouter un service d'une entreprise
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        { 
            $parameters['idEntrepriseService'] = $this->insert('entreprise_service', $parameters);
            return new EntrepriseService($parameters);
        }

        /**
         * Modifier un service d'une entreprise
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('entreprise_service', $parameters);
            return new EntrepriseService($parameters);
        }

        /**
         * Supprimer un service d'une entreprise
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('entreprise_service', $parameters);
        }
    }