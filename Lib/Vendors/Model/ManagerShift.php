<?php
    
    /**
     * Manager de l'entité Shift
     *
     * @author Lansky
     *
     * @since 28/12/2022
    */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Shift;

    class ManagerShift extends DbManager
    {
        /** 
         * Lister les shifts
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
        */
        public function lister($attributs = null)
        {
            $shifts   = array();
            $orderByQuery = " ORDER BY start_time ASC";
            // $orderByQuery = " ORDER BY (SELECT employe.nom FROM `employe` WHERE employe.idEmploye = shift.id_employe) ASC";
            $resultats = $this->findAll('shift', $attributs, $orderByQuery);
            foreach ($resultats as $resultat) {
                $shift   = new Shift($resultat);
                $shifts[] = $shift;
            }
            return $shifts;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
        */
        public function initialiser()
        {
            return new Shift();
        }

        /** 
         * Chercher une shift
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
        */
        public function chercher($parameters)
        {
            $fields    = array();
            $values    = array();
            $shift     = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    if (strstr($value, 'LIKE') || strstr($value, 'BETWEEN')) {
                        $value =  " " . $value;
                    } else {
                        $value =  "'" . $value . "'";
                    }
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('shift', $fields, $values);
            if (!empty($resultat)) {
                $shift = new Shift($resultat);
            }
            return $shift;
        }

        /**
         * Ajouter une shift
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
        */
        public function ajouter($parameters) 
        {
            $parameters['idShift'] = $this->insert('shift', $parameters);
            return new Shift($parameters);
        }

        /**
         * Modifier une shift
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
        */
        public function modifier($parameters) 
        {
            $this->update('shift', $parameters);
            return new Shift($parameters);
        }

        /**
         * Supprimer une shift
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
        */
        public function supprimer($parameters) 
        {
            return $this->delete('shift', $parameters);
        }
        
    }