<?php
    
    /**
     * Manager de l'entité Poste
     *
     * @author Voahirana
     *
     * @since 11/03/20 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Poste;

    class ManagerPoste extends DbManager
    {
       /**
         * Lister les postes
         *
         * @return array
         */
        public function lister() 
        {
            $postes = array();
            $string = " ORDER BY nomPoste ASC";
            $tab    = $this->findAll('poste', null,$string);
            if (!empty($tab)) {
                foreach ($tab as $data) {
                    $poste    = new Poste($data);
                    $postes[] = $poste;
                }
            }            
            return $postes;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Poste();
        }

        /** 
         * Chercher un poste
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $poste = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('poste', $fields, $values);
            if (!empty($resultat)) {
                $poste = new Poste($resultat);
            }
            return $poste;
        }

        /**
         * Ajouter un poste
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idposte'] = $this->insert('poste', $parameters);
            return new Poste($parameters);
        }

        /**
         * Modifier un poste
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('poste', $parameters);
            return new Poste($parameters);
        }

        /**
         * Supprimer un poste
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('poste', $parameters);
        }
    }