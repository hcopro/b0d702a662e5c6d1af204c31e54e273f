<?php 
	
	/** 
	 * Entité Interim
	 *
	 * @author Toky
	 *
	 * @since 10/09/20
	 */

	namespace Entity;

	class Interim
	{
		private $idInterim;
		private $idEmploye;
		private $idEntreprisePoste;
		private $chef;
		private $debut;
		private $fin;
		
		/** 
		 * Initialisation d'un interim
		 *
		 * @param array $data Données à intialiser 
		 *
		 * @return empty
		 */
	    public function __construct($data = array())
	    {
	        if (!empty($data)) {
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
		public function getIdInterim()
		{
			return $this->idInterim;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getIdEntreprisePoste()
		{
			return $this->idEntreprisePoste;
		}

		public function getChef()
		{
			return $this->chef;
		}

		public function getDebut()
		{
			return $this->debut;
		}

		public function getFin()
		{
			return $this->fin;
		}

	// Seters
		public function setIdInterim($idInterim)
		{
			$this->idInterim = $idInterim;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setIdEntreprisePoste($idEntreprisePoste)
		{
			$this->idEntreprisePoste = $idEntreprisePoste;
		}

		public function setChef($chef)
		{
			$this->chef = $chef;
		}
		
		public function setDebut($debut)
		{
			$this->debut = $debut;
		}

		public function setFin($fin)
		{
			$this->fin = $fin;
		}
	}