<?php 
	
	/** 
	 * Entité Configuration
	 *
	 * @author Toky
	 *
	 * @since 24/06/20
	 */

	namespace Entity;

	class Configuration
	{
		private $idConfiguration;
		private $idEntreprise;
		private $emailAlerte;
		private $nombreAlerte;
		private $premiereAlerte;
		private $deuxiemeAlerte;
		private $troisiemeAlerte;
		
		/** 
		 * Initialisation d'une Configuration
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
	    public function getIdConfiguration()
	    {
	    	return $this->idConfiguration;
	    }

	    public function getIdEntreprise()
	    {
	    	return $this->idEntreprise;
	    }

	    public function getEmailAlerte()
	    {
	    	return $this->emailAlerte;
	    }

	    public function getNombreAlerte()
	    {
	    	return $this->nombreAlerte;
	    }

	    public function getPremiereAlerte()
	    {
	    	return $this->premiereAlerte;
	    }

	    public function getDeuxiemeAlerte()
	    {
	    	return $this->deuxiemeAlerte;
	    }

	    public function getTroisiemeAlerte()
	    {
	    	return $this->troisiemeAlerte;
	    }

	    /**
	     * Setters
	     */
	    public function setIdConfiguration($idConfiguration)
	    {
	    	$this->idConfiguration = $idConfiguration;
	    }

	    public function setIdEntreprise($idEntreprise)
	    {
	    	$this->idEntreprise = $idEntreprise;
	    }

	    public function setEmailAlerte($emailAlerte)
	    {
	    	$this->emailAlerte = $emailAlerte;
	    }

	    public function setNombreAlerte($nombreAlerte)
	    {
	    	$this->nombreAlerte = $nombreAlerte;
	    }

	    public function setPremiereAlerte($premiereAlerte)
	    {
	    	$this->premiereAlerte = $premiereAlerte;
	    }

	    public function setDeuxiemeAlerte($deuxiemeAlerte)
	    {
	    	$this->deuxiemeAlerte = $deuxiemeAlerte;
	    }

	    public function setTroisiemeAlerte($troisiemeAlerte)
	    {
	    	$this->troisiemeAlerte = $troisiemeAlerte;
	    }

	    /**
	     * Retourner la dernière Alerte
	     *
	     * @return int 
	     */
	    public function getDerniereAlerte()
	    {
	    	switch ($this->getNombreAlerte()) {
	    		case 1:
	    			return $this->premiereAlerte;
	    			break;
	    		case 2:
	    			return $this->deuxiemeAlerte;
	    			break;
	    		case 3:
	    			return $this->troisiemeAlerte;
	    			break;
	    		default:
	    			return null;
	    			break;
	    	}
	    }

	}