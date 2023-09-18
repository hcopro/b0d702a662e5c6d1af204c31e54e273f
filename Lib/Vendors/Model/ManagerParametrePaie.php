<?php

    /**
     * Manager de l'entité ParametrePaie
     *
     * @author Toky
     *
     * @since 21/10/2020
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\ParametrePaie;

    class ManagerParametrePaie extends DbManager
    {
        /**
         * Lister les ParametrePaies
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $parametrePaies      = array();
            $queryOrderBy   = " ORDER BY idParametrePaie DESC";
            $resultats      = $this->findAll('parametre_paie', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $parametrePaie    = new ParametrePaie($resultat);
                $parametrePaies[] = $parametrePaie;
            }
            return $parametrePaies;
        }

        /**
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new ParametrePaie();
        }

        /**
         * Chercher un ParametrePaie
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $parametrePaie = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('parametre_paie', $fields, $values);
            if (!empty($resultat)) {
                $parametrePaie = new ParametrePaie($resultat);
            }
            return $parametrePaie;
        }

        /**
         * Ajouter un ParametrePaie
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters)
        {
            $parameters['idParametrePaie'] = $this->insert('parametre_paie', $parameters);
            return new ParametrePaie($parameters);
        }

        /**
         * Modifier un ParametrePaie
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters)
        {
            $this->update('parametre_paie', $parameters);
            return new ParametrePaie($parameters);
        }

        /**
         * Supprimer un ParametrePaie
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters)
        {
            return $this->delete('parametre_paie', $parameters);
        }

    }
