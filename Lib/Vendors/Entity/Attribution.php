<?php 

/** 
	 * Entité Attribution
	 *
	 * @author Billy Bam 
	 *
	 * @since 27/03/2023
	 */


namespace Entity;

class Attribution{
	private $idAttribution;
	private $idCandidat;
	private $idCandidature;
	private $idOffre;
	private $statut;
	private $idInterlocuteur;

	public function __construct($data = array())
	{
		if(!empty($data)){
			$this->hydrate($data);
		}
	}

	public function hydrate($data)
	{
		foreach ($data as $attribut => $data) {
            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
            if (is_callable(array($this, $method))) {
                $this->$method($data);
            }
        }
	}

	public function toArray()
	{
		return get_object_vars($this);
	}

	//Getter

	public function getIdAttribution()
	{
		return $this->idAttribution;
	}

	public function getIdCandidat()
    {
    	return $this->idCandidat;
    }

	public function getIdCandidature()
	{
		return $this->idCandidature;
	}

	public function getIdOffre()
	{
		return $this->idOffre;
	}

	public function getStatut()
	{
		return $this->statut;
	}

	public function getIdInterlocuteur()
	{
		return $this->idInterlocuteur;
	}


	//Setter

	public function setIdAttribution($idAttribution)
	{
		$this->idAttribution = $idAttribution;
	}

	public function setIdCandidat($idCandidat)
    {
    	$this->idCandidat = $idCandidat;
    }

	public function setIdCandidature($idCandidature)
	{
		$this->idCandidature = $idCandidature;
	}

	public function setIdOffre($idOffre)
	{
		$this->idOffre = $idOffre;
	}

	public function setStatut($statut)
	{
		$this->statut = $statut;
	}

	public function setIdInterlocuteur($idInterlocuteur)
	{
		$this->idInterlocuteur = $idInterlocuteur;
	}
}