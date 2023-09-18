<?php 
	
	/** 
	 * Entité TacheAutomatique
	 *
	 * @author Toky
	 *
	 * @since 28/08/20
	 */

	namespace Entity;

	class TacheAutomatique
	{
		private $idTacheAutomatique;
		private $nom;
		private $description;
		private $script;
		private $periode;
		private $codePeriode;
		
		/** 
		 * Initialisation d'une Validation de Congé
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
	    public function getIdTacheAutomatique()
	    {
	    	return $this->idTacheAutomatique;
	    }

		public function getNom()
		{
			return $this->nom;
		}

		public function getDescription()
		{
			return $this->description;
		}

		public function getScript()
		{
			return $this->script;
		}

		public function getPeriode()
		{
			return $this->periode;
		}

		public function getCodePeriode()
		{
			return $this->codePeriode;
		}

	// Seters
		public function setIdTacheAutomatique($idTacheAutomatique)
		{
			$this->idTacheAutomatique = $idTacheAutomatique;
		}

		public function setNom($nom)
		{
			$this->nom = $nom;
		}

		public function setDescription($description)
		{
			$this->description = $description;
		}

		public function setScript($script)
		{
			$this->script = $script;
		}

		public function setPeriode($periode)
		{
			$this->periode = $periode;
		}

		public function setCodePeriode($codePeriode)
		{
			$this->codePeriode = $codePeriode;
		}
		
	}