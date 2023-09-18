<?php 
	
	/** 
	 * Entité EmployeRepos
	 *
	 * @author Toky
	 *
	 * @since 07/08/20
	 */

	namespace Entity;

	class EmployeRepos
	{
		private $idEmployeRepos;
		private $idEmploye;
		private $raison;
		private $statut;
		private $date;
		private $duree;
		private $settled;
		private $justify;
		
		/** 
		 * Initialisation d'un repos d'un employe
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
		public function getIdEmployeRepos()
		{
			return $this->idEmployeRepos;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getRaison()
		{
			return $this->raison;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getDate()
		{
			return $this->date;
		}

		public function getDuree()
		{
			return $this->duree;
		}

		public function getSettled()
		{
			return $this->settled;
		}

		public function getJustify()
		{
			return $this->justify;
		}

	// Seters
		public function setIdEmployeRepos($idEmployeRepos)
		{
			$this->idEmployeRepos = $idEmployeRepos;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setRaison($raison)
		{
			$this->raison = $raison;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setDate($date)
		{
			$this->date = $date;
		}

		public function setDuree($duree)
		{
			$this->duree = $duree;
		}

		public function setSettled($settled)
		{
			$this->settled = $settled;
		}

		public function setJustify($justify)
		{
			$this->justify = $justify;
		}
		
	}