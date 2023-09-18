<?php 
	
	/** 
	 * Entité Compte
	 *
	 * @author Voahirana 
	 *
	 * @since 25/09/19
	 */

	namespace Entity;

	class Compte
	{
		private $idCompte;
		private $identifiant; 
		private $login; 
		private $motDePasse; 
		private $statut;
		private $lastLoggedin;
		private $lastLoggedout;
		private $history;
		
		/** 
		 * Initialisation d'un Compte
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
		public function getIdCompte()
		{
			return $this->idCompte;
		}

		public function getIdentifiant()
		{
			return $this->identifiant;
		}

		public function getLogin()
		{
			return $this->login;
		}

		public function getMotDePasse()
		{
			return $this->motDePasse;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getLastLoggedin()
		{
			return $this->lastLoggedin;
		}

		public function getLastLoggedout()
		{
			return $this->lastLoggedout;
		}

		public function getHistory()
		{
			return unserialize($this->history);
		}

	// Seters
		public function setIdCompte($idCompte)
		{
			$this->idCompte = $idCompte;
		}

		public function setIdentifiant($identifiant)
		{
			$this->identifiant = $identifiant;
		}

		public function setLogin($login)
		{
			$this->login = $login;
		}

		public function setMotDePasse($motDePasse)
		{
			$this->motDePasse = $motDePasse;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setLastLoggedin($lastLoggedin)
		{
			$this->lastLoggedin = $lastLoggedin;
		}

		public function setLastLoggedout($lastLoggedout)
		{
			$this->lastLoggedout = $lastLoggedout;
		}

		public function setHistory($history)
		{
			$this->history = $history;
		}
		
	}