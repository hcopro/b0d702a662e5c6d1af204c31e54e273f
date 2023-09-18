<?php
    
    /**
     * Manager de l'entité EntreprisePoste
     *
     * @author Voahirana
     *
     * @since 12/03/20 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\EntreprisePoste;

    class ManagerEntreprisePoste extends DbManager
    {
       /**
         * Lister les postes d'une entreprise
         *
         * @param array $attributes Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes) 
        {
            $entreprisePostes = array();
            $string           = " ORDER BY poste ASC";
            $resultats        = $this->findAll('entreprise_poste', $attributes, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $entreprisePoste    = new EntreprisePoste($resultat);
                    $entreprisePostes[] = $entreprisePoste;
                }
            }            
            return $entreprisePostes;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new EntreprisePoste();
        }

        /** 
         * Chercher un poste d'une entreprise
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields             = array();
            $values             = array();
            $entreprisePoste    = new EntreprisePoste();
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }            
            $resultat = $this->findOne('entreprise_poste', $fields, $values);
            if (!empty($resultat)) {
                $entreprisePoste = new EntreprisePoste($resultat);
            }
            return $entreprisePoste;
        }

        /**
         * Ajouter un poste d'une entreprise
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        { 
            $parameters['idEntreprisePoste'] = $this->insert('entreprise_poste', $parameters);
            return new EntreprisePoste($parameters);
        }

        /**
         * Modifier un poste d'une entreprise
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('entreprise_poste', $parameters);
            return new EntreprisePoste($parameters);
        }

        /**
         * Supprimer un poste d'une entreprise
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('entreprise_poste', $parameters);
        }
    }