<?php 
	
	/** 
	 * Entité Message
	 *
	 * @author Toky
	 *
	 * @since 13/08/20
	 */

	namespace Entity;

	class Message
	{
		private $idMessage;
		private $idCompte;
		private $objet;
		private $statut;
		private $contenu;
		private $date;
		private $aFaire;
		
		/** 
		 * Initialisation d'un message
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

	// Getters
	    public function getIdMessage()
	    {
	    	return $this->idMessage;
	    }

		public function getIdCompte()
		{
			return $this->idCompte;
		}

		public function getObjet()
		{
			return $this->objet;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getContenu()
		{
			return $this->contenu;
		}

		public function getDate()
		{
			return $this->date;
		}

		public function getAFaire()
		{
			return $this->aFaire;
		}

	// Seters
		public function setIdMessage($idMessage)
		{
			$this->idMessage = $idMessage;
		}

		public function setIdCompte($idCompte)
		{
			$this->idCompte = $idCompte;
		}

		public function setObjet($objet)
		{
			$this->objet = $objet;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setContenu($contenu)
		{
			$this->contenu = $contenu;
		}

		public function setDate($date)
		{
			$this->date = $date;
		}

		public function setAFaire($aFaire)
		{
			$this->aFaire = $aFaire;
		}
		
	}