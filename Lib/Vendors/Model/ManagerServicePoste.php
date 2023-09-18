<?php
    
    /**
     * Manager de l'entité ServicePoste
     *
     * @author Voahirana
     *
     * @since 11/03/20 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ServicePoste;

    class ManagerServicePoste extends DbManager
    {
       /**
         * Lister les postes d'un service
         *
         * @param array $attributes Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes) 
        {
            $servicePostes = array();
            $resultats     = $this->findAll('service_poste', $attributes);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $servicePoste    = new ServicePoste($resultat);
                    $servicePostes[] = $servicePoste;
                }
            }            
            return $servicePostes;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new ServicePoste();
        }

        /** 
         * Chercher un poste d'un service
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields            = array();
            $values            = array();
            $servicePoste = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }            
            $resultat = $this->findOne('service_poste', $fields, $values);
            if (!empty($resultat)) {
                $servicePoste = new ServicePoste($resultat);
            }
            return $servicePoste;
        }

        /**
         * Ajouter un poste d'un service
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        { 
            $parameters['idServicePoste'] = $this->insert('service_poste', $parameters);
            return new ServicePoste($parameters);
        }

        /**
         * Modifier un poste d'un service
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('service_poste', $parameters);
            return new ServicePoste($parameters);
        }

        /**
         * Supprimer un poste d'un service
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('service_poste', $parameters);
        }
    }