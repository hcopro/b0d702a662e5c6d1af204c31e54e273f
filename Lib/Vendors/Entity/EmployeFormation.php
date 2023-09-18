<?php 
	
	/** 
	 * Entité EmployeFormation
	 *
	 * @author Toky
	 *
	 * @since 15/09/20
	 */

	namespace Entity;

	class EmployeFormation
	{
		private $idEmployeFormation;
		private $idEmploye;
		private $idFormationProfessionnelle;
		
		/** 
		 * Initialisation d'une EmployeFormation
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
		public function getIdEmployeFormation()
		{
			return $this->idEmployeFormation;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getIdFormationProfessionnelle()
		{
			return $this->idFormationProfessionnelle;
		}

	// Seters
		public function setIdEmployeFormation($idEmployeFormation)
		{
			$this->idEmployeFormation = $idEmployeFormation;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setIdFormationProfessionnelle($idFormationProfessionnelle)
		{
			$this->idFormationProfessionnelle = $idFormationProfessionnelle;
		}
	}