<?php
    
    /**
     * Manager de l'entité SousDomaine
     *
     * @author Voahirana
     *
     * @since 03/10/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\SousDomaine;

    class ManagerSousDomaine extends DbManager
    {
        /** 
         * Lister les sous domaines
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($parameters)
        {
            $sousDomaines = array();
            $string       = " ORDER BY nomSousDomaine ASC";
            $resultats    = $this->findAll('sous_domaine', $parameters, $string);
            foreach ($resultats as $resultat) {
                $sousDomaine    = new SousDomaine($resultat);
                $sousDomaines[] = $sousDomaine;
            }
            return $sousDomaines;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new SousDomaine();
        }

        /** 
         * Chercher un sous domaine
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields      = array();
            $values      = array();
            $sousDomaine = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('sous_domaine', $fields, $values);
            if (!empty($resultat)) {
                $sousDomaine = new SousDomaine($resultat);
            }
            return $sousDomaine;
        }

        /** 
         * Chercher le dérnier identifiant d'un sous domaine
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('sous_domaine', 'idSousDomaine');
        }

        /**
         * Ajouter un sous domaine
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idSousDomaine'] = $this->insert('sous_domaine', $parameters);
            return new SousDomaine($parameters);
        }

        /**
         * Modifier un sous domaine
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('sous_domaine', $parameters);
            return new SousDomaine($parameters);
        }

        /**
         * Supprimer un sous domaine
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('sous_domaine', $parameters);
        }
        
    }