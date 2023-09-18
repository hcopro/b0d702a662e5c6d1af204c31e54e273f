<?php 
	
	/** 
	 * Entité Historique
	 *
	 * @author Voahirana 
	 *
	 * @since 21/11/19
	 */

	namespace Entity;

	class Historique
	{
		private $idHistorique;
		private $date; 
		private $action; 
		private $idCompte; 
		private $idSuperadmin; 

		/** 
		 * Initialisation d'un Candidat
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
		public function getIdHistorique()
		{
			return $this->idHistorique;
		}

		public function getDate()
		{
			return $this->date;
		}

		public function getAction()
		{
			return $this->action;
		}

		public function getIdSuperadmin()
		{
			return $this->idSuperadmin;
		}

		public function getIdCompte()
		{
			return $this->idCompte;
		}
		
	// Seters
		public function setIdHistorique($idHistorique)
		{
			$this->idHistorique = $idHistorique;
		}

		public function setDate($date)
		{
			$this->date = $date;
		}

		public function setAction($action)
		{
			$this->action = $action;
		}

		public function setIdCompte($idCompte)
		{
			$this->idCompte = $idCompte;
		}

		public function setIdSuperadmin($idSuperadmin)
		{
			$this->idSuperadmin = $idSuperadmin;
		}

	}