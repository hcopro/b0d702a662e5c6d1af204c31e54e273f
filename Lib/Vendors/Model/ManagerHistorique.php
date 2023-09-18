<?php
    
    /**
     * Manager de l'entité Historique
     *
     * @author Voahirana
     *
     * @since 21/11/19 
     */

    namespace Model;

	use \Core\DbManager;
    use \Entity\Historique;

	class ManagerHistorique extends DbManager
	{
        /**
         * Lister les historiques
         *
         * @return array
         */
        public function lister($parameters) 
        {
            $historiques = array();
            $string      = " ORDER BY date DESC";
            $resultats   = $this->findAll('historique', $parameters, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $historique    = new Historique($resultat);
                    $historiques[] = $historique;
                }
            } 
            return $historiques;
        }

        /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Historique();
        }

        /** 
         * Chercher une historique
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields     = array();
            $values     = array();
            $historique = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('historique', $fields, $values);
            if (!empty($resultat)) {
                $historique = new Historique($resultat);
            }
            return $historique;
        }

        /** 
         * Chercher le dérnier identifiant d'une historique
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('historique', 'idHistorique');
        } 

        /**
         * Insérer une historique
         *
         * @param array $parameters Les données à insérer
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $this->insert('historique', $parameters);
            return new Historique($parameters);
        }

        /**
         * Modifier une historique
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('historique', $parameters);
            return new Historique($parameters);
        } 

        /**
         * Supprimer une historique
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('historique', $parameters);
        } 
    }