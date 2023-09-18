<?php 
	
	/** 
	 * Entité Pointage
	 *
	 * @author Toky 
	 *
	 * @since 16/07/20
	 */

	namespace Entity;

	class Pointage
	{
		private $idPointage;
		private $idPresence;
		private $idTache;
		private $debut;
		private $fin;
		private $statut;
		
		/** 
		 * Initialisation d'une tache
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
	    public function getIdPointage()
	    {
	    	return $this->idPointage;
	    } 

	    public function getIdTache()
	    {
	    	return $this->idTache;
	    }

	    public function getIdPresence()
	    {
	    	return $this->idPresence;
	    }

	    public function getDebut()
	    {
	    	return $this->debut;
	    }

	    public function getFin()
	    {
	    	return $this->fin;
	    }

	    public function getStatut()
	    {
	    	return $this->statut;
	    }

	    // Setters
	    public function setIdPointage($idPointage)
	    {
	    	$this->idPointage = $idPointage;
	    }
	    public function setIdPresence($idPresence)
	    {
	    	$this->idPresence = $idPresence;
	    }

	    public function setIdTache($idTache)
	    {
	    	$this->idTache = $idTache;
	    }

	    public function setDebut($debut)
	    {
	    	$this->debut = $debut;
	    }

	    public function setFin($fin)
	    {
	    	$this->fin = $fin;
	    }

	    public function setStatut($statut)
	    {
	    	$this->statut = $statut;
	    }

	}