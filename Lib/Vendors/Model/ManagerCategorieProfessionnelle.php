<?php
    
    /**
     * Manager de l'entité CategorieProfessionnelle
     *
     * @author Toky
     *
     * @since 06/07/20 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\CategorieProfessionnelle;

    class ManagerCategorieProfessionnelle extends DbManager
    {
       /**
         * Lister les catégories professionnelles
         *
         * @return array
         */
        public function lister($attributes=null) 
        {
            $categorieProfessionnelles        = array();
            $queryOrderBy                     = " ORDER BY idCategorieProfessionnelle ASC";
            $resultats = $this->findAll('categorie_professionnelle', $attributes, $queryOrderBy);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $categorieProfessionnelle    = new CategorieProfessionnelle($resultat);
                    $categorieProfessionnelles[] = $categorieProfessionnelle;
                }
            }            
            return $categorieProfessionnelles;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new CategorieProfessionnelle();
        }

        /** 
         * Chercher une catégorie professionnelle
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $categorieProfessionnelle = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }            
            $resultat = $this->findOne('categorie_professionnelle', $fields, $values);
            if (!empty($resultat)) {
                $categorieProfessionnelle = new CategorieProfessionnelle($resultat);
            }
            return $categorieProfessionnelle;
        }

        /** 
         * Chercher le dérnier identifiant d'une catégorie professionnelle
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('categorie_professionnelle', 'idCategorieProfessionnelle');
        }

        /**
         * Ajouter une catégorie professionnelle
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        { 
            $parameters['idCategorieProfessionnelle'] = $this->insert('categorie_professionnelle', $parameters);
            return new CategorieProfessionnelle($parameters);
        }

        /**
         * Modifier une catégorie professionnelle
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('categorie_professionnelle', $parameters);
            return new CategorieProfessionnelle($parameters);
        }

        /**
         * Supprimer une catégorie professionnelle
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('categorie_professionnelle', $parameters);
        }
    }