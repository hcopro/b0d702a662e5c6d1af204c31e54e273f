<?php
    
    /**
     * Manager de l'entité NotificationnotificationEmployePermission
     *
     * @author Toky
     *
     * @since 27/07/2020 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\NotificationEmployePermission;

    class ManagerNotificationEmployePermission extends DbManager
    {
        /** 
         * Lister les notifications de permission d'un employe
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function lister($attributes)
        {
            $notificationEmployePermissions  = array();
            $queryOrderBy                    = " ORDER BY idNotificationEmployePermission DESC";
            $resultats                       = $this->findAll('notification_employe_permission', $attributes, $queryOrderBy);
            foreach ($resultats as $resultat) {
                $notificationEmployePermission    = new NotificationEmployePermission($resultat);
                $notificationEmployePermissions[] = $notificationEmployePermission;
            }
            return $notificationEmployePermissions;
        }

        /** 
         * Créer un objet vide
         *
         * @return object|empty
         */
        public function initialiser()
        {
            return new NotificationEmployePermission();
        }

        /** 
         * Chercher une notification de permission d'un employe
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields = array();
            $values = array();
            $notificationEmployePermission = null;
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }
            $resultat = $this->findOne('notification_employe_permission', $fields, $values);
            if (!empty($resultat)) {
                $notificationEmployePermission = new NotificationEmployePermission($resultat);
            }
            return $notificationEmployePermission;
        }

        /**
         * Selectionner les notifications de demandes de permission dans un intervalle
         *
         * @param array @attributes critère avec égalité
         * @param array @min        intervalle minimum
         * @param array @max        interval maximum
         *
         * @return array
         */
        public function selectionner($attributes=null, $min, $max)
        {
            $notificationEmployePermissions   = array();
            $orderByQuery                     = " ORDER BY date DESC";
            $resultats = $this->selectAll('notification_employe_permission', $attributes, $min, $max, $orderByQuery);
            foreach ($resultats as $resultat) {
                $notificationEmployePermission    = new NotificationEmployePermission($resultat);
                $notificationEmployePermissions[] = $notificationEmployePermission;
            }
            return $notificationEmployePermissions;
        }

        /**
         * Ajouter une notification de permission d'un employe
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        {
            $parameters['idNotificationEmployePermission'] = $this->insert('notification_employe_permission', $parameters);
            return new NotificationEmployePermission($parameters);
        }

        /**
         * Modifier une notification de permission d'un employe
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('notification_employe_permission', $parameters);
            return new NotificationEmployePermission($parameters);
        }

        /**
         * Supprimer une notification de permission d'un employé
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('notification_employe_permission', $parameters);
        }
        
    }