<?php 
	
	/** 
	 * Entité Presence
	 *
	 * @author Toky 
	 *
	 * @since 16/07/20
	 */

	namespace Entity;

	class Presence
	{
		private $idPresence;
		private $idEmploye;
		private $date;
		private $statut;
		
		/** 
		 * Initialisation d'une presence 
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
	    public function getIdPresence()
	    {
	    	return $this->idPresence;
	    }

	    public function getIdEmploye()
	    {
	    	return $this->idEmploye;
	    }

	    public function getDate()
	    {
	    	return $this->date;
	    }

	    public function getStatut()
	    {
	    	return $this->statut;
	    }

	    // Setters
	    public function setIdPresence($idPresence)
	    {
	    	$this->idPresence = $idPresence;
	    }

	    public function setIdEmploye($idEmploye)
	    {
	    	$this->idEmploye = $idEmploye;
	    }

	    public function setDate($date)
	    {
	    	$this->date = $date;
	    }

	    public function setStatut($statut)
	    {
	    	$this->statut = $statut;
	    }
	}