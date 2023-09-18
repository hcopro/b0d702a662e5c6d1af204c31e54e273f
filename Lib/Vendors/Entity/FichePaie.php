<?php 
	
	/** 
	 * Entité FichePaie
	 *
	 * @author Toky
	 *
	 * @since 09/11/20
	 */

	namespace Entity;

	class FichePaie
	{
		private $idFichePaie;
		private $idEmploye;
		private $mois;
		private $annee;
		private $salaireDeBase;
		private $salaire;
		private $heureEffective;
		private $heureSupplementaireActive;
		private $heureSupplementaireImposable;
		private $quantiteHeureSupplementaire;
		private $majorationHeureSupplementaire;
		private $heureNuitImposable;
		private $quantiteHeureNuit;
		private $majorationHeureNuit;
		private $heureDimancheImposable;
		private $quantiteHeureDimanche;
		private $majorationHeureDimanche;
		private $heureFerieImposable;
		private $quantiteHeureFerie;
		private $majorationHeureFerie;
		private $salaireBrut;
		private $quantiteCnaps;
		private $deductionCnaps;
		private $quantiteOstie;
		private $deductionOstie;
		private $revenuImposable;
		private $irsa;
		private $quantiteCharge;
		private $deductionCharge;
		private $irsaNet;
		private $salaireNet;
		private $statut;
		private $soldeCongeDebut;
		private $soldeCongeFin;
		private $congePris;
		private $quantiteAllocation;
		private $allocationConge;
		private $presence;
		private $poste;
		
		/** 
		 * Initialisation d'un paramètre d'heure d'un employe
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
		public function getIdFichePaie()
		{
			return $this->idFichePaie;
		}

		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getMois()
		{
			return $this->mois;
		}

		public function getAnnee()
		{
			return $this->annee;
		}

		public function getSalaireDeBase()
		{
			return $this->salaireDeBase;
		}

		public function getHeureEffective()
		{
			return $this->heureEffective;
		}

		public function getHeureSupplementaireActive()
		{
			return $this->heureSupplementaireActive;
		}

		public function getHeureSupplementaireImposable()
		{
			return $this->heureSupplementaireImposable;
		}

		public function getQuantiteHeureSupplementaire()
		{
			return $this->quantiteHeureSupplementaire;
		}

		public function getMajorationHeureSupplementaire()
		{
			return $this->majorationHeureSupplementaire;
		}

		public function getHeureNuitImposable()
		{
			return $this->heureNuitImposable;
		}

		public function getQuantiteHeureNuit()
		{
			return $this->quantiteHeureNuit;
		}

		public function getMajorationHeureNuit()
		{
			return $this->majorationHeureNuit;
		}

		public function getHeureDimancheImposable()
		{
			return $this->heureDimancheImposable;
		}

		public function getQuantiteHeureDimanche()
		{
			return $this->quantiteHeureDimanche;
		}

		public function getMajorationHeureDimanche()
		{
			return $this->majorationHeureDimanche;
		}

		public function getHeureFerieImposable()
		{
			return $this->heureFerieImposable;
		}

		public function getQuantiteHeureFerie()
		{
			return $this->quantiteHeureFerie;
		}

		public function getMajorationHeureFerie()
		{
			return $this->majorationHeureFerie;
		}

		public function getQuantiteCnaps()
		{
			return $this->quantiteCnaps;
		}

		public function getDeductionCnaps()
		{
			return $this->deductionCnaps;
		}

		public function getQuantiteOstie()
		{
			return $this->quantiteOstie;
		}

		public function getDeductionOstie()
		{
			return $this->deductionOstie;
		}

		public function getRevenuImposable()
		{
			return $this->revenuImposable;
		}

		public function getIrsa()
		{
			return $this->irsa;
		}

		public function getQuantiteCharge()
		{
			return $this->quantiteCharge;
		}

		public function getDeductionCharge()
		{
			return $this->deductionCharge;
		}

		public function getIrsaNet()
		{
			return $this->irsaNet;
		}

		public function getSalaireNet()
		{
			return $this->salaireNet;
		}

		public function getSalaireBrut()
		{
			return $this->salaireBrut;
		}

		public function getSalaire()
		{
			return $this->salaire;
		}

		public function getStatut()
		{
			return $this->statut;
		}

		public function getSoldeCongeDebut()
		{
			return $this->soldeCongeDebut;
		}

	    public function getSoldeCongeFin()
	    {
	    	return $this->soldeCongeFin;
	    }

	    public function getCongePris()
	    {
	    	return $this->congePris;
	    }

	    public function getQuantiteAllocation()
	    {
	    	return $this->quantiteAllocation;
	    }

	    public function getAllocationConge()
	    {
	    	return $this->allocationConge;
	    }

	    public function getPresence()
	    {
	    	return $this->presence;
	    }

	    public function getDebut()
	    {
	    	return $this->debut;
	    }

	    public function getFin()
	    {
	    	return $this->fin;
	    }

	    public function getPoste()
	    {
	    	return $this->poste;
	    }

	// Seters
		public function setIdFichePaie($idFichePaie)
		{
			$this->idFichePaie = $idFichePaie;
		}

		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

		public function setMois($mois)
		{
			$this->mois = $mois;
		}

		public function setAnnee($annee)
		{
			$this->annee = $annee;
		}

		public function setSalaireDeBase($salaireDeBase)
		{
			$this->salaireDeBase = $salaireDeBase;
		}

		public function setHeureEffective($heureEffective)
		{
			$this->heureEffective = $heureEffective;
		}

		public function setSalaire($salaire)
		{
			$this->salaire = $salaire;
		}

		public function setHeureSupplementaireActive($heureSupplementaireActive)
		{
			$this->heureSupplementaireActive = $heureSupplementaireActive;
		}

		public function setHeureSupplementaireImposable($heureSupplementaireImposable)
		{
			$this->heureSupplementaireImposable = $heureSupplementaireImposable;
		}

		public function setQuantiteHeureSupplementaire($quantiteHeureSupplementaire)
		{
			$this->quantiteHeureSupplementaire = $quantiteHeureSupplementaire;
		}

		public function setMajorationHeureSupplementaire($majorationHeureSupplementaire)
		{
			$this->majorationHeureSupplementaire = $majorationHeureSupplementaire;
		}

		public function setHeureNuitImposable($heureNuitImposable)
		{
			$this->heureNuitImposable = $heureNuitImposable;
		}

		public function setQuantiteHeureNuit($quantiteHeureNuit)
		{
			$this->quantiteHeureNuit = $quantiteHeureNuit;
		}

		public function setMajorationHeureNuit($majorationHeureNuit)
		{
			$this->majorationHeureNuit = $majorationHeureNuit;
		}

		public function setHeureDimancheImposable($heureDimancheImposable)
		{
			$this->heureDimancheImposable = $heureDimancheImposable;
		}

		public function setQuantiteHeureDimanche($quantiteHeureDimanche)
		{
			$this->quantiteHeureDimanche = $quantiteHeureDimanche;
		}

		public function setMajorationHeureDimanche($majorationHeureDimanche)
		{
			$this->majorationHeureDimanche = $majorationHeureDimanche;
		}

		public function setTravailDimancheHabituel($travailDimancheHabituel)
		{
			$this->travailDimancheHabituel = $travailDimancheHabituel;
		}

		public function setHeureFerieImposable($heureFerieImposable)
		{
			$this->heureFerieImposable = $heureFerieImposable;
		}

		public function setQuantiteHeureFerie($quantiteHeureFerie)
		{
			$this->quantiteHeureFerie = $quantiteHeureFerie;
		}

		public function setMajorationHeureFerie($majorationHeureFerie)
		{
			$this->majorationHeureFerie = $majorationHeureFerie;
		}

		public function setQuantiteCnaps($quantiteCnaps)
		{
			$this->quantiteCnaps = $quantiteCnaps;
		}

		public function setDeductionCnaps($deductionCnaps)
		{
			$this->deductionCnaps = $deductionCnaps;
		}

		public function setQuantiteOstie($quantiteOstie)
		{
			$this->quantiteOstie = $quantiteOstie;
		}

		public function setDeductionOstie($deductionOstie)
		{
			$this->deductionOstie = $deductionOstie;
		}

		public function setRevenuImposable($revenuImposable)
		{
			$this->revenuImposable = $revenuImposable;
		}

		public function setIrsa($irsa)
		{
			$this->irsa = $irsa;
		}

		public function setQuantiteCharge($quantiteCharge)
		{
			$this->quantiteCharge = $quantiteCharge;
		}

		public function setDeductionCharge($deductionCharge)
		{
			$this->deductionCharge = $deductionCharge;
		}

		public function setIrsaNet($irsaNet)
		{
			$this->irsaNet = $irsaNet;
		}

		public function setSalaireNet($salaireNet)
		{
			$this->salaireNet = $salaireNet;
		}

		public function setSalaireBrut($salaireBrut)
		{
			$this->salaireBrut = $salaireBrut;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

		public function setSoldeCongeDebut($soldeCongeDebut)
		{
			$this->soldeCongeDebut = $soldeCongeDebut;
		}

		public function setSoldeCongeFin($soldeCongeFin)
		{
			$this->soldeCongeFin = $soldeCongeFin;
		}

		public function setCongePris($congePris)
		{
			$this->congePris = $congePris;
		}

		public function setQuantiteAllocation($quantiteAllocation)
		{
			$this->quantiteAllocation = $quantiteAllocation;
		}

		public function setAllocationConge($allocationConge)
		{
			$this->allocationConge = $allocationConge;
		}

		public function setPresence($presence)
		{
			$this->presence = $presence;
		}

		public function setDebut($debut)
		{
			$this->debut = $debut;
		}

		public function setFin($fin)
		{
			$this->fin = $fin;
		}

		public function setPoste($poste)
		{
			$this->poste = $poste;
		}

	}