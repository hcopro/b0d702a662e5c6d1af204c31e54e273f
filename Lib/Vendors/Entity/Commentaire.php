<?php
	/**
	 *	@author Billy Bam
	 * 
	 *  
	 * 
	 * 
	*/
	namespace Entity;

	class Commentaire
	{
		private $idCommentaire; 
		private $idCandidat;
		private $idCompte;
		private $idCandidature;
		private $commentaire;
		private $statut;
		private $dateCommentaire;

		/** 
		 * Initialisation d'un Commentaire
		 *
		 * @param array $data Données à intialiser 
		 *
		 * @return empty
		*/
	    public function __construct($data = array())
	    {
	        if(!empty($data)) {
	        	$this->hydrate($data);
	        }           
	    }
	    /** 
		 * Remplir la structure d'un objet 
		 *
		 * @param array $data Données à remplir
		 *
		 * @return empty
		*/
	    public function hydrate($data)
	    {
	        foreach ($data as $attribut => $data) {
	            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
	            if (is_callable(array($this, $method))) {
	                $this->$method($data);
	            }
	        }
	    }
	    /** 
	     * Convertir un objet en tableau 
	     *
	     * @return array
	    */
	    public function toArray()
	    {
	    	return get_object_vars($this);
	    }
	    //GETTER
	    public function getIdCommentaire()
	    {
	    	return $this->idCommentaire;
	    }

	    public function getIdCandidat()
	    {
	    	return $this->idCandidat;
	    }

	    public function getIdCompte()
	    {
	    	return $this->idCompte;
	    }

	    public function getIdCandidature()
	    {
	    	return $this->idCandidature;
	    }

	    public function getCommentaire()
	    {
	    	return $this->commentaire;
	    }

	    public function getStatut()
	    {
	    	return $this->statut;
	    }
	    
	    public function getDateCommentaire()
	    {
	    	return $this->dateCommentaire;
	    }
	    //SETTER
	    public function setIdCommentaire($idCommentaire)
	    {
	    	$this->idCommentaire = $idCommentaire;
	    }

	    public function setIdCandidat($idCandidat)
	    {
	    	$this->idCandidat = $idCandidat;
	    }

	    public function setIdCompte($idCompte)
	    {
	    	$this->idCompte = $idCompte;
	    }

	    public function setIdCandidature($idCandidature)
	    {
	    	$this->idCandidature = $idCandidature;
	    }

	    public function setCommentaire($commentaire)
	    {
	    	$this->commentaire = $commentaire;
	    }

	    public function setStatut($statut)
	    {
	    	$this->statut = $statut;
	    }

	    public function setDateCommentaire($dateCommentaire)
	    {
	    	$this->dateCommentaire = $dateCommentaire;
	    }
	}    