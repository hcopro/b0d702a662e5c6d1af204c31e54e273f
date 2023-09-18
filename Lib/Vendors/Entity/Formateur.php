<?php 
	
	/** 
	 * Entité Formateur
	 *
	 * @author Toky
	 *
	 * @since 15/09/20
	 */

	namespace Entity;

	class Formateur
	{
		private $idFormateur;
		private $idEntreprise;
		private $civilite;
		private $nom;
		private $prenom;
		private $nif;
		private $stat;
		private $rcs;
		private $contact;
		private $email;
		private $statut;
		
		/** 
		 * Initialisation d'un formateur
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
		public function getIdFormateur()
		{
			return $this->idFormateur;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getCivilite()
		{
			return $this->civilite;
		}

		public function getNom()
		{
			return $this->nom;
		}

		public function getPrenom()
		{
			return $this->prenom;
		}

		public function getNif()
		{
			return $this->nif;
		}

		public function getStat()
		{
			return $this->stat;
		}

		public function getRcs()
		{
			return $this->rcs;
		}

		public function getContact()
		{
			return $this->contact;
		}

		public function getEmail()
		{
			return $this->email;
		}

		public function getStatut()
		{
			return $this->statut;
		}

	// Seters
		public function setIdFormateur($idFormateur)
		{
			$this->idFormateur = $idFormateur;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setCivilite($civilite)
		{
			$this->civilite = $civilite;
		}

		public function setNom($nom)
		{
			$this->nom = strtoupper($nom);
		}

		public function setPrenom($prenom)
		{
			$this->prenom = ucwords($prenom);
		}

		public function setNif($nif)
		{
			$this->nif = $nif;
		}

		public function setStat($stat)
		{
			$this->stat = $stat;
		}

		public function setRcs($rcs)
		{
			$this->rcs = $rcs;
		}

		public function setContact($contact)
		{
			$this->contact = $contact;
		}

		public function setEmail($email)
		{
			$this->email = $email;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}
	}