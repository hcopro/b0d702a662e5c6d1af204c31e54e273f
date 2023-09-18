<?php
    
    /**
     * Manager de l'entité template
     *
     * @author Toky
     *
     * @since 27/06/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Template;

    class ManagerTemplate extends DbManager
    {
        /** 
         * Lister les templates
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($parameters)
        {
            $templates  = array();
            $queryOrderBy   = " ORDER BY idTemplate ASC";
            $resultats      = $this->findAll('template', $parameters, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $template    = new Template($resultat);
                $templates[] = $template;
            }
            return $templates;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new Template();
        }

        /** 
         * Chercher une template
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields        = array();
            $values        = array();
            $template = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('template', $fields, $values);
            if (!empty($resultat)) {
                $template = new Template($resultat);
            }
            return $template;
        }

        /**
         * Ajouter une template
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idtemplate'] = $this->insert('template', $parameters);
            return new Template($parameters);
        }

        /**
         * Modifier une template
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('template', $parameters);
            return new Template($parameters);
        }

        /**
         * Supprimer une template
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('template', $parameters);
        }
        
    }