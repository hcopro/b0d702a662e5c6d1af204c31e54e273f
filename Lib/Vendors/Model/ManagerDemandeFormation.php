<?php
    
    /**
     * Manager de l'entité DemandeFormation
     *
     * @author Toky
     *
     * @since 28/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\DemandeFormation;

    class ManagerDemandeFormation extends DbManager
    {
        /** 
         * Lister les demandes de formation
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs)
        {
            $DemandeFormations   = array();
            $queryOrderBy   = " ORDER BY idDemandeFormation DESC";
            $resultats      = $this->findAll('demande_formation', $attributs, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $DemandeFormation    = new DemandeFormation($resultat);
                $DemandeFormations[] = $DemandeFormation;
            }
            return $DemandeFormations;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new DemandeFormation();
        }

        /**
         * Selectionner des demandes de formation dans un intervalle
         *
         * @param array @attributes critère avec égalité
         * @param array @min        intervalle minimum
         * @param array @max        intervalle maximum
         *
         * @return array
         */
        public function selectionner($attributes=null, $min, $max)
        {
            $formations     = array();
            $orderByQuery = " ORDER BY idDemandeFormation DESC";
            $resultats = $this->selectAll('demande_formation', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $formation    = new DemandeFormation($resultat);
                $formations[] = $formation;
            }
            return $formations;
        }

        /** 
         * Chercher une demande de formation
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $DemandeFormation = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('demande_formation', $fields, $values);
            if (!empty($resultat)) {
                $DemandeFormation = new DemandeFormation($resultat);
            }
            return $DemandeFormation;
        }

        /**
         * Ajouter une demande de formation
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idDemandeFormation'] = $this->insert('demande_formation', $parameters);
            return new DemandeFormation($parameters);
        }

        /**
         * Modifier une demande de formation
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('demande_formation', $parameters);
            return new DemandeFormation($parameters);
        }

        /**
         * Supprimer une Demande de Formation
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('demande_formation', $parameters);
        }
        
    }