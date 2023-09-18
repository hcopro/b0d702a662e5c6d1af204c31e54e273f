<?php 
	
	/** 
	 * Entité Configuration
	 *
	 * @author Toky
	 *
	 * @since 27/06/20
	 */

	namespace Entity;

	class Template
	{
		private $idTemplate;
		private $idEntreprise;
		private $idContrat;
		private $fichier;
		
		/** 
		 * Initialisation d'une template
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

	    /**
	     *	Getters
	     */
	    public function getIdTemplate()
	    {
	    	return $this->idTemplate;
	    }

	    public function getIdEntreprise()
	    {
	    	return $this->idEntreprise;
	    }

	    public function getIdContrat()
	    {
	    	return $this->idContrat;
	    }

	    public function getFichier()
	    {
	    	return $this->fichier;
	    }

	    /**
	     * Setters
	     */
	    public function setIdTemplate($idTemplate)
	    {
	    	$this->idTemplate = $idTemplate;
	    }

	    public function setIdEntreprise($idEntreprise)
	    {
	    	$this->idEntreprise = $idEntreprise;
	    }

	    public function setidContrat($idContrat)
	    {
	    	$this->idContrat = $idContrat;
	    }

	    public function setfichier($fichier)
	    {
	    	$this->fichier = $fichier;
	    }
	}