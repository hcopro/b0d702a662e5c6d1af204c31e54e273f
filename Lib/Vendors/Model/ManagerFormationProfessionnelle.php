<?php
    
    /**
     * Manager de l'entité FormationProfessionnelle
     *
     * @author Toky
     *
     * @since 10/09/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\FormationProfessionnelle;

    class ManagerFormationProfessionnelle extends DbManager
    {
        /** 
         * Lister les formations
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributs)
        {
            $formationProfessionnelles   = array();
            $queryOrderBy   = " ORDER BY idFormationProfessionnelle DESC";
            $resultats      = $this->findAll('formation_professionnelle', $attributs, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $formationProfessionnelle    = new FormationProfessionnelle($resultat);
                $formationProfessionnelles[] = $formationProfessionnelle;
            }
            return $formationProfessionnelles;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new FormationProfessionnelle();
        }

        /**
         * Selectionner des formations dans un intervalle
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
            $orderByQuery = " ORDER BY idFormationProfessionnelle DESC";
            $resultats = $this->selectAll('formation_professionnelle', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $formation    = new FormationProfessionnelle($resultat);
                $formations[] = $formation;
            }
            return $formations;
        }

        /** 
         * Chercher une formation
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $formationProfessionnelle = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('formation_professionnelle', $fields, $values);
            if (!empty($resultat)) {
                $formationProfessionnelle = new FormationProfessionnelle($resultat);
            }
            return $formationProfessionnelle;
        }

        /**
         * Ajouter une formation
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idFormationProfessionnelle'] = $this->insert('formation_professionnelle', $parameters);
            return new FormationProfessionnelle($parameters);
        }

        /**
         * Modifier une formation
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('formation_professionnelle', $parameters);
            return new FormationProfessionnelle($parameters);
        }

        /**
         * Supprimer une FormationProfessionnelle
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('formation_professionnelle', $parameters);
        }
        
    }