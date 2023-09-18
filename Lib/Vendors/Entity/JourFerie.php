<?php 
	
	/** 
	 * Entité Jour Férié
	 *
	 * @author Toky 
	 *
	 * @since 14/07/20
	 */

	namespace Entity;

	class JourFerie
	{
		private $idJourFerie;
		private $designation;
		
		/** 
		 * Initialisation d'un JourFerie
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
	    public function getIdJourFerie()
	    {
	    	return $this->idJourFerie;
	    }

	    public function getDesignation()
	    {
	    	return $this->designation;
	    }

	    // Setters
	    public function setIdJourFerie($idJourFerie)
	    {
	    	$this->idJourFerie = $idJourFerie;
	    }

	    public function setDesignation($designation)
	    {
	    	$this->designation = $designation;
	    }
	}