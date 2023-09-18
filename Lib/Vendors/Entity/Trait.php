<?php 
	
	/** 
	 * Entité Trait
	 *
	 * @author Lansky
	 *
	 * @since 09/07/21
	 */

	namespace Entity;

	class Trait
	{
		private $idTrait;
		private $description;
		private $point;
		private $idEvaluation;
		private $idItem;
		
		/** 
		 * Initialisation d'une Trait
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
		public function getIdTrait()
		{
			return $this->idTrait;
		}

		public function getDescription()
		{
			return $this->description;
		}

		public function getPoint()
		{
			return $this->point;
		}

		public function getIdEvaluation()
		{
			return $this->idEvaluation;
		}

		public function getIdItem()
		{
			return $this->idItem;
		}


	// Seters
		public function setIdTrait($idTrait)
		{
			$this->idTrait = $idTrait;
		}

		public function setDescription($description)
		{
			$this->description = $description;
		}

		public function setIdEvaluation($idEvaluation)
		{
			$this->idEvaluation = $idEvaluation;
		}

		public function setPoint($point)
		{
			$this->point = $point;
		}

		public function setItem($item)
		{
			$this->item = $item;
		}
	}