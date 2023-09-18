<?php 
	
	/** 
	 * Entité Interlocuteur
	 *
	 * @author Voahirana 
	 *
	 * @since 27/11/19
	 */

	namespace Entity;

	class Interlocuteur
	{
		private $idInterlocuteur;
		private $civilite;
		private $nom;
		private $email;
		
		/** 
		 * Initialisation d'un Interlocuteur
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
		
		public function getIdInterlocuteur()
		{
			return $this->idInterlocuteur;
		}

		public function getCivilite()
		{
			return $this->civilite;
		}
		
		public function getNom()
		{
			return $this->nom;
		}
		
		public function getEmail()
		{
			return $this->email;
		}

	// Seters

		public function setIdInterlocuteur($idInterlocuteur)
		{
			$this->idInterlocuteur = $idInterlocuteur;
		}

		public function setCivilite($civilite)
		{
			$this->civilite = $civilite;
		}

		public function setNom($nom)
		{
			$this->nom = $nom;
		}

		public function setEmail($email)
		{
			$this->email = $email;
		}
	}