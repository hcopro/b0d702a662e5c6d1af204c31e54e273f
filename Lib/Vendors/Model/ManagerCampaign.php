<?php
    
    /**
     * Manager de l'entité Campaign
     *
     * @author Lansky
     *
     * @since 2023-05-26
    */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Campaign;

    class ManagerCampaign extends DbManager
    {
        /** 
         * Lister les campagnes
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
        */
        public function lister($attributs = null)
        { 
            $campaigns      = array();
            $orderByQuery   = " ORDER BY CASE WHEN days_diff >= 0 THEN days_diff ELSE days_diff * -1 END, start_date ASC, start_date ASC, start_time DESC";
            $addSelect      = ", DATEDIFF(start_date, CURDATE()) AS days_diff";
            $resultats      = $this->findAll('campaign', $attributs, $orderByQuery, $addSelect);
            foreach ($resultats as $resultat) {
                $campaign   = new Campaign($resultat);
                $campaigns[] = $campaign;
            }
            return $campaigns;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
        */
        public function initialiser()
        {
            return new Campaign();
        }

        /** 
         * Chercher une campagne
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
        */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $campaign     = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    if (strstr($value, 'LIKE')) {
                        $value =  " " . $value;
                    } else {
                        $value =  "'" . $value . "'";
                    }
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('campaign', $fields, $values);
            if (!empty($resultat)) {
                $campaign = new Campaign($resultat);
            }
            return $campaign;
        }

        /**
         * Ajouter une campagne
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
        */
        public function ajouter($parameters) 
        {
            $parameters['idCampaign'] = $this->insert('campaign', $parameters);
            return new Campaign($parameters);
        }

        /**
         * Modifier une campagne
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
        */
        public function modifier($parameters) 
        {
            $this->update('campaign', $parameters);
            return new Campaign($parameters);
        }

        /**
         * Supprimer une campagne
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimer($parameters) 
        {
            return $this->delete('campaign', $parameters);
        }
        
    }