<?php
	/**
	 * Manager de l'entité Logiciel
	 *
	 * @author Billy Bam
	 *
	 * @since 21/11/2022 
	*/

	namespace Model;

	use \Core\DbManager;
	use \Entity\CentreInteret;

	class ManagerCentreInteret extends DbManager {
		
		/** 
	     * Lister les centres d'interets
	     *
	     * @param array $parameters Critères des données à lister
	     *
	     * @return array
	    */
		public function lister($attributes = null)
		{
			$centreInterets      = array();
	        $queryOrderBy   = " ORDER BY categorie_centre_interet ASC";
	        $resultats      = $this->findAll('centre_interet', $attributes, $queryOrderBy);
	        foreach ($resultats as $resultat) {
	            $centreInteret    = new CentreInteret($resultat);
	            $centreInterets[] = $centreInteret;
	        }
	        return $centreInterets;
		}
		/** 
	     * Créer un objet vide
	     *
	     * @return object|empty
	    */
		public function initialiser()
		{
			return new Logiciel();
		}
		/** 
	     * Chercher un centre d'interet
	     *
	     * @param array $parameters Critères des données à chercher
	     *
	     * @return object|empty
	    */
		public function chercher($parameters)
		{
			$fields = array();
	        $values = array();
	        $CentreInteret   = null;
	        foreach ($parameters as $key => $value) {
	            if (filter_var($value, FILTER_VALIDATE_INT) == true) {
	                $value = $value;
	            } else {
	                $value =  "'" . $value . "'";
	            }
	            $fields [] = $key;
	            $values [] = $value;
	        }
	        $resultat = $this->findOne('centre_interet', $fields, $values);
	        if (!empty($resultat)) {
	            $centreInteret = new CentreInteret($resultat);
	        }
	        return $centreInteret;
		}
		/**
	     * Ajouter un centre d'interet
	     *
	     * @param array $parameters Les données à ajouter
	     *
	     * @return object
	    */
		public function ajouter($parameters)
		{
			$parameters['idCentreInteret'] = $this->insert('centre_interet', $parameters);
	        return new CentreInteret($parameters);
		}
		/**
	     * Modifier un centre d'interet
	     *
	     * @param array $parameters Les données à modifier
	     *
	     * @return object
	    */
		public function modifier($parameters)
		{
			$update = $this->update('centre_interet', $parameters);
	        return new CentreInteret($parameters);
		}
		/**
	     * Supprimer un centre d'interet
	     *
	     * @param array $parameters Critères des données à supprimer
	     *
	     * @return empty
	    */
		public function supprimer($parameters)
		{
			return $this->delete('centre_interet', $parameters);
		}
	}
