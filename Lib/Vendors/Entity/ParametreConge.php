<?php 
	
	/** 
	 * Entité ParametreConge
	 *
	 * @author Toky
	 *
	 * @since 02/09/20
	 */

	namespace Entity;

	class ParametreConge
	{
		private $idParametreConge;
		private $idEntreprise;
		private $attente;
		private $processus;
		private $niveau;
		private $calcul;
		private $deductWeekend;
		private $notifyAbsence;
		
		/** 
		 * Initialisation d'un paramètre de permission
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
		public function getIdParametreConge()
		{
			return $this->idParametreConge;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getAttente()
		{
			return $this->attente;
		}

		public function getProcessus()
		{
			return $this->processus;
		}

		public function getNiveau()
		{
			return $this->niveau;
		}

		public function getCalcul()
		{
			return $this->calcul;
		}

		public function getDeductWeekend()
		{
			return $this->deductWeekend;
		}

		public function getNotifyAbsence()
		{
			return $this->notifyAbsence;
		}

	// Seters
		public function setIdParametreConge($idParametreConge)
		{
			$this->idParametreConge = $idParametreConge;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setAttente($attente)
		{
			$this->attente = $attente;
		}

		public function setProcessus($processus)
		{
			$this->processus = $processus;
		}

		public function setNiveau($niveau)
		{
			$this->niveau = $niveau;
		}

		public function setCalcul($calcul)
		{
			$this->calcul = $calcul;
		}

		public function setDeductWeekend($deductWeekend)
		{
			$this->deductWeekend = $deductWeekend;
		}

		public function setNotifyAbsence($notifyAbsence)
		{
			$this->notifyAbsence = $notifyAbsence;
		}
		
	}