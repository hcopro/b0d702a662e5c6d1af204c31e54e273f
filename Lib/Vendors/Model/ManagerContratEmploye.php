<?php
    
    /**
     * Manager de l'entité ContratEmploye
     *
     * @author Voahirana
     *
     * @since 13/03/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ContratEmploye;

    class ManagerContratEmploye extends DbManager
    {
        /** 
         * Lister les contrats d'un employé
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes = null)
        {
            $contrats  = array();
            $queryOrderBy    = " ORDER BY idContratEmploye DESC";
            $resultats = $this->findAll('contrat_employe', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $contrat    = new ContratEmploye($resultat);
                $contrats[] = $contrat;
            }
            return $contrats;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new ContratEmploye();
        }

        /** 
         * Chercher un contrat d'un employé
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $contrat = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('contrat_employe', $fields, $values);
            if (!empty($resultat)) {
                $contrat = new ContratEmploye($resultat);
            }
            return $contrat;
        }

        /**
         * Ajouter un contrat d'un employé
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idContratEmploye'] = $this->insert('contrat_employe', $parameters);
            return new ContratEmploye($parameters);
        }

        /**
         * Modifier un contrat d'un employé
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('contrat_employe', $parameters);
            return new ContratEmploye($parameters);
        }

        /**
         * Supprimer une banque
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('contrat_employe', $parameters);
        }
        
    }