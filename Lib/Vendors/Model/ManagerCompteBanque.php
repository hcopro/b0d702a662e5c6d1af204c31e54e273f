<?php
    
    /**
     * Manager de l'entité Compte banque
     *
     * @author Voahirana
     *
     * @since 16/03/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\CompteBanque;

    class ManagerCompteBanque extends DbManager
    {
        /** 
         * Lister les comptes bancaires
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister()
        {
            $compteBanques = array();
            $resultats     = $this->findAll('compte_banque', null, null);
            foreach ($resultats as $resultat) {
                $compteBanque    = new CompteBanque($resultat);
                $compteBanques[] = $compteBanque;
            }
            return $compteBanques;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new CompteBanque();
        }

        /** 
         * Chercher un compte banque
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields       = array();
            $values       = array();
            $compteBanque = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('compte_banque', $fields, $values);
            if (!empty($resultat)) {
                $compteBanque = new CompteBanque($resultat);
            }
            return $compteBanque;
        }

        /**
         * Ajouter un compte banque
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idCompteBanque'] = $this->insert('compte_banque', $parameters);
            return new CompteBanque($parameters);
        }

        /**
         * Modifier un compte banque
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('compte_banque', $parameters);
            return new CompteBanque($parameters);
        }

        /**
         * Supprimer un compte banque
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('compte_banque', $parameters);
        }
        
    }