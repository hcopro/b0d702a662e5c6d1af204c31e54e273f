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
	use \Entity\Commentaire;

	class ManagerCommentaire extends DbManager {
		/** 
	     * Lister les Commentaires 
	     *
	     * @param array $parameters Critères des données à lister
	     *
	     * @return array
	     */
		public function lister($attributes = null)
		{
			$commentaires   = array();
	        $queryOrderBy   = " ORDER BY dateCommentaire ASC";
	        $resultats      = $this->findAll('commentaire', $attributes, $queryOrderBy);
	        foreach ($resultats as $resultat) {
	            $commentaire    = new Commentaire($resultat);
	            $commentaires[] = $commentaire;
	        }
	        return $commentaires;
		}

		 /** 
	     * Créer un objet vide
	     *
	     * @return object|empty
	     */
		public function initialiser()
		{
			return new Commentaire();
		}

		/** 
	     * Chercher un Commentaire
	     *
	     * @param array $parameters Critères des données à chercher
	     *
	     * @return object|empty
	     */
		public function chercher($parameters)
		{
			$fields 		= array();
	        $values 		= array();
	        $commentaire   	= null;
	        foreach ($parameters as $key => $value) {
	            if (filter_var($value, FILTER_VALIDATE_INT) == true) {
	                $value = $value;
	            } else {
	                $value =  "'" . $value . "'";
	            }
	            $fields [] = $key;
	            $values [] = $value;
	        }
	        $resultat = $this->findOne('commentaire', $fields, $values);
	        if (!empty($resultat)) {
	            $commentaire = new Commentaire($resultat);
	        }
	        return $commentaire;
		}

		/**
	     * Ajouter un Commentaire
	     *
	     * @param array $parameters Les données à ajouter
	     *
	     * @return object
	     */
		public function ajouter($parameters)
		{
			$parameters['idCommentaire'] = $this->insert('commentaire', $parameters);
	        return new Commentaire($parameters);
		}

		/**
	     * Modifier un Commentaire
	     *
	     * @param array $parameters Les données à modifier
	     *
	     * @return object
	     */
		public function modifier($parameters)
		{
			$update = $this->update('commentaire', $parameters);
	        return new Commentaire($parameters);
		}

		/**
	     * Supprimer un Commentaire
	     *
	     * @param array $parameters Critères des données à supprimer
	     *
	     * @return empty
	     */
		public function supprimer($parameters)
		{
			return $this->delete('commentaire', $parameters);
		}
	}
