<?php
    
    /**
     * Manager du module Evaluation du Backend
     *
     * @author Lansky
     *
     * @since 14/07/2021 
     */

    use \Core\DbManager;
    use \Core\View;
    use \Model\ManagerEmploye;
    use \Model\ManagerEntreprise;
    use \Model\ManagerEvaluationCategorie;
    use \Model\ManagerEvaluationQuestionnaire;
    use \Model\ManagerMessage;
    use \Model\ManagerEvaluation;
    use \Model\ManagerEvaluationEmploye;
    use \Model\ManagerEvaluationEvaluateur;
    use \Model\ManagerContratEmploye;
    use \Model\ManagerCompte;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerServicePoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerMenuEntreprise;
    use \Model\ManagerBarometreList;
    use \Model\ManagerBarometre;

    class ManagerModuleEvaluation extends DbManager
    {
        const FILTER_GROUP_ALL          = 1;
        const FILTER_GROUP_POSTE        = 2;
        const FILTER_GROUP_EMPLOYE      = 3;
        const FILTER_GROUP_SERVICE      = 4;
        const MAX_INTERVAL              = 3;
        const TODAY                     = 1;
        const TOMORROW                  = 2;
        const YESTERDAY                 = 3;
        const THIS_WEEK                 = 4;
        const NEXT_WEEK                 = 5;
        const LAST_WEEK                 = 6;
        const THIS_MONTH                = 7;
        const NEXT_MONTH                = 8;
        const LAST_MONTH                = 9;
        const FILTER_STATUS_ALL         = 0;
        const FILTER_STATUS_REPLY       = 1;
        const FILTER_STATUS_NO_REPLY    = 2;
        const VALIDATED                 = 2;

        
        /**
         * Debut de la dimension
         */
       /**
        * Lister l'interface principale de gestion des traits de personnalités
        *
        * @param array $parameters
        *
        * @return array
        */
        public function listerCategorie($parameters)
        {
            $entreprise = $this->getEntreprise();
            $categories = $this->getCategorie($entreprise->getIdEntreprise());
            $donnees = [
                'entreprise'    => $entreprise,
                'categories'    => $categories,
            ];
            $view   = new View("listeCategorie");
            $view->send("Backend", "Evaluation", $donnees, "");
            exit();
        }
       /**
        * Récupérer toutes les données de la dimension
        *
        * @param array $parameters
        *
        * @return array
        */
        public function getCategories($parameters)
        {
            if ($parameters['idCategorie'] == 0) {
                $entreprise = $this->getEntreprise();
                $categories = $this->getCategorie($entreprise->getIdEntreprise());
                foreach ($categories as $key => $value) {
                    $categorie[$key] = $value->toArray();
                }
            } else {
                $manager        = new ManagerEvaluationCategorie();
                $categorie      = $this->getFiltre($manager, 'chercher', 'id_categorie', $parameters['idCategorie']);
                $categorie      = $categorie->toArray();
            }
            echo json_encode($categorie);
            exit();
        }
       /**
        * Récupérer toutes les données de l'évaluation modifiée
        *
        * @param array $parameters
        *
        * @return array
        */
        public function getEvaluationArray($parameters)
        {
            $manager    = new ManagerEvaluation();
            $response   = $manager->chercher([
                'id_entreprise' => $_SESSION['user']['idEntreprise'],
                'id_evaluation' => $parameters['idEvaluation']
            ]);
            $response->setCategory($response->getCategory());
            $response   = $response->toArray();
            foreach ($response['category'] as $k => $val) {
                foreach ($val as $key => $value) {
                    if (is_object($value)) {
                        $evaluation[$k][$key] =  $value->toArray();
                    } elseif (is_array($value)) {
                        foreach ($value as $keys => $category) {
                            $evaluation[$k][$key][$keys]['sousCategories'] = $category['sousCategories']->toArray(); 
                            foreach ($category['questionnaires'] as $indx => $question) {
                                $question->setInterpretation($question->getInterpretation());
                                $evaluation[$k][$key][$keys]['questionnaires'][$indx] = $question->toArray();
                            }
                        }
                    }
                }
            }
            echo json_encode($evaluation);
            exit();
        }
       /**
        * Récupérer l'entreprise
        *
        * @param int $idEntreprise
        *
        * @return array
        */
        private function getCategorie($idEntreprise)
        {
            $manager    = new ManagerEvaluationCategorie();
            $tmp        = $this->getFiltre($manager, 'lister', 'id_entreprise', $idEntreprise);
            $key        = 0;
            if ($tmp) {
                foreach ($tmp as $categorie) {
                    $categories[$key]    = $manager->chercher(['id_categorie' => $categorie->getIdCategorie()]);
                    $key++ ;
                }
            }
            return $categories; 
        }
       /**
        * Voir l'interface principale de gestion des traits de personnalités
        *
        * @param array $parameters
        *
        * @return array
        */
        public function voirCategorieDetail($parameters)
        {
            $manager        = new ManagerEvaluationCategorie();
            $entreprise     = $this->getEntreprise();
            $categorie      = $manager-> chercher([
                'id_categorie'  => $parameters['idCategorie'],
                'id_entreprise' =>$entreprise->getIdEntreprise()
            ]);
            $questions      = $this->getQuestionnaires($entreprise,$parameters['idCategorie']);
            return [
                'entreprise'    => $entreprise,
                'categorie'     => $categorie,
                'questions'     => $questions,
            ];
        }
       /**
        * Nouveau enregistrement d'une dimension
        *
        * @param array $parameters Les données du Nouveau enregistrement
        *
        * @return empty
        */
        public function ajoutCategorie($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerEvaluationCategorie();
                $retour     = $manager->ajouter([
                    'libelle'       => $parameters['libelle'],
                    'code'          => $parameters['code'],
                    'description'   => $parameters['description'],
                    'id_entreprise' => $parameters['idEntreprise']
                ]);
                if ($retour->getIdCategorie() != 0) {
                    $_SESSION['info']['success']    = "Enregistrement terminé avec succès";
                } else {
                    $_SESSION['info']['danger']     = "Echec de l'enregistrement";
                }
            }
        }
       /**
        * Mettre à jour une dimension
        *
        * @param array $parameters Les données du Nouveau enregistrement
        *
        * @return empty
        */
        public function mettreAJourCategorie($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerEvaluationCategorie();
                $retour     = $manager->modifier($parameters);
                if ($retour->getIdCategorie() != 0) {
                    $_SESSION['info']['success']    = "Enregistrement terminé avec succès";
                } else {
                    $_SESSION['info']['danger']     = "Echec de l'enregistrement";
                }
            }
        }
       /**
        * Supprimer une dimension
        *
        * @param array $parameters Les critères des données à supprimer
        *
        * @return empty
        */
        public function supprimerCategorie($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerEvaluationCategorie();
                $categorie  = $manager->chercher([
                    'id_categorie'  => $parameters['id_categorie'],
                    'id_entreprise' => $_SESSION['user']['idEntreprise']
                ]);
                if ($categorie != null) {
                    $manager = new ManagerEvaluationQuestionnaire();
                    $question   = $manager->lister([
                        'id_categorie'  => $categorie->getIdCategorie(),
                        'id_entreprise' => $_SESSION['user']['idEntreprise']
                    ]);
                    if (count($question) > 0) {
                        $suppression = false;
                    } else {
                        $manager = new ManagerEvaluationCategorie();
                        $suppression = $manager->supprimer([
                            'id_categorie' => $parameters['id_categorie']
                        ]);
                    }
                    if ($suppression) {
                        $_SESSION['info']['success']    = "Suppression est fait avec succès !";
                        
                    } else {
                        $_SESSION['info']['danger']     = "Impossible de supprimer '".$categorie->getLibelle()."'. Veuillez effacer d'abord les dimensions lient à cet enregistrement !!!";
                    }
                }
            }
        }
        /**
         * Fin de la dimension
         */

        /**
         * Debut de la question
         */
       /**
        * Lister l'interface principale de gestion des questionnaires
        *
        * @param array $parameters
        *
        * @return array
        */
        public function listerQuestionnaire($parameters)
        {
            $manager        = new ManagerEntreprise();
            $entreprise     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $categories     = $this->getCategorie($entreprise->getIdEntreprise());
            return [
                'entreprise'    => $entreprise,
                'categories'    => $categories,
                'questionnaires'=> null
            ];
        }
       /**
        * Récupérer toutes les données de la questionnaire
        *
        * @param array $entreprise Entreprise
        * @param array or int $categorie
        *
        * @return array
        */
        private function getQuestionnaires($entreprise, $categories)
        {
            $manager = new ManagerEvaluationQuestionnaire();
            if (is_array($categories)) {
                foreach ($categories['sousCategories'] as $key => $sousCategorie) {
                    foreach ($sousCategorie as $value) {
                        $questionnaires[$value->getIdCategorie()]  = $this->getFiltre($manager, 'lister', 'id_categorie', $value->getIdCategorie());
                   }
               }
            } else {
                if (intval($categories)) {
                    $questionnaires  = $manager->lister([
                        'id_entreprise' => $entreprise->getIdEntreprise(),
                        'id_categorie'  => $categories
                    ]);
                } else {
                    $questionnaires  = $this->getFiltre($manager, 'lister', 'id_entreprise', $entreprise->getIdEntreprise());
                }
            }
            return $questionnaires;
        }
       /**
        * Récupérer la questionnaire
        *
        * @param array $parameters
        *
        * @return json
        */
       public function getQuestion($parameters)
       {
           $manager     = new ManagerEvaluationQuestionnaire();
           if ($parameters['idQuestion'] && $parameters['idCategorie']) {
               $questions    = $manager->chercher([
                    'id_question'   => $parameters['idQuestion'],
                    'id_categorie'  => $parameters['idCategorie'],
                    'id_entreprise' => $_SESSION['user']['idEntreprise']
                ]);
               $questions->setPointNiveau($questions->getPointNiveau());
               $questions->setInterpretation($questions->getInterpretation());
               $response = $questions->toArray();
           } elseif ($parameters['idCategorie']) {
               $questions   = $manager->lister([
                    'id_categorie'  => $parameters['idCategorie'],
                    'id_entreprise' => $_SESSION['user']['idEntreprise']
                ]);
           }
           if (is_array($questions)) {
               foreach ($questions as $key => $question) {
                   $response[] = $question->toArray();
               }
           }
            echo json_encode($response);
            exit();
       }
       /**
        * Récupérer la questionnaire
        *
        * @param array $parameters
        *
        * @return html
        */
        public function getQuestionnaire($parameters)
        {
            $manager    = new ManagerEvaluationCategorie();
            $entreprise = $this->getEntreprise();
            if (intval($parameters['idCategorie'])) {
                $categories     = $this->getFiltre($manager, 'chercher','id_categorie', $parameters['idCategorie']);
                $questionnaires = $this->getQuestionnaires($entreprise, $parameters['idCategorie']);
            } else{
                $categories     = $this->getCategorie($entreprise->getIdEntreprise());
                $manager        = new ManagerEvaluationQuestionnaire();
                $questionnaires = $manager->lister([]);
            }
            $donnees = [
                'entreprise'        => $entreprise,
                'categories'        => $categories,
                'questionnaires'    => $questionnaires
            ];
            $view   = new View("listeQuestionnaire");
            $view->sendWithoutTemplate("Backend", "Evaluation", $donnees, "");
            exit();
        }
       /**
        * Nouveau enregistrement d'une questionnaire
        *
        * @param array $parameters Les données du Nouveau enregistrement
        *
        * @return empty
        */
        public function ajoutQuestionnaire($parameters)
        {
            if (!empty($parameters)) {
                $interpretation = serialize([
                    'Initiation 1'  => $parameters['Initiation_1'],
                    'Pratique 2'    => $parameters['Pratique_2'],
                    'Maîtrise 3'    => $parameters['Maîtrise_3'],
                    'Expertise 4'   => $parameters['Expertise_4']
                ]);
                $manager    = new ManagerEvaluationCategorie();
                $cat        = $manager->chercher(['id_categorie' => $parameters['idCategorie'], 'id_entreprise' => $parameters['idEntreprise']]);
                $newId      = $this->findLast('evaluation_questionnaire', 'id_question')['id'] + 1;
                $manager    = new ManagerEvaluationQuestionnaire();
                $retour     = $manager->ajouter([
                    'libelle'       => $parameters['libelle'],
                    'id_entreprise' => $parameters['idEntreprise'],
                    'interpretation'=> $interpretation,
                    'code'          => "{$cat->getCode()}{$newId}", // CODE + ID
                    'id_categorie'  => $parameters['idCategorie']
                ]);
                if ($retour->getIdQuestion() != 0) {
                    $_SESSION['info']['success']    = "Enregistrement terminé avec succès";
                } else {
                    $_SESSION['info']['danger']     = "Echec de l'enregistrement";
                }
            }
        }
       /**
        * Mettre à jour une questionnaire
        *
        * @param array $parameters Les données du Nouveau enregistrement
        *
        * @return empty
        */
        public function mettreAJourQuestionnaire($parameters)
        {
            if (!empty($parameters)) {
                $interpretation = serialize([
                    'Initiation 1'  => $parameters['Initiation_1'],
                    'Pratique 2'    => $parameters['Pratique_2'],
                    'Maîtrise 3'    => $parameters['Maîtrise_3'],
                    'Expertise 4'   => $parameters['Expertise_4']
                ]);
                for ($i=0; $i <4 ; $i++) { 
                    array_pop($parameters);
                }
                $parameters = array_merge($parameters,['interpretation' => $interpretation]);
                $manager    = new ManagerEvaluationQuestionnaire();
                $retour     = $manager->modifier($parameters);
                if ($retour->getIdQuestion() != 0) {
                    $_SESSION['info']['success']    = "Enregistrement terminé avec succès";
                } else {
                    $_SESSION['info']['danger']     = "Echec de l'enregistrement";
                }
            }
        }
       /**
        * Supprimer une questionnaire
        *
        * @param array $parameters Les critères des données à supprimer
        *
        * @return empty
        */
        public function supprimerQuestionnaire($parameters)
        {
            if (!empty($parameters)) {
                $manager        = new ManagerEvaluationQuestionnaire();
                $questionnaire  = $manager->chercher(['id_question' => $parameters['idQuestion']]);
                if ($questionnaire != null) {
                    $suppression = $manager->supprimer([
                        'id_question' => $parameters['idQuestion']
                    ]);
                    if ($suppression) {
                            $_SESSION['info']['success']    = "Suppression est fait avec succès!";
                    } else {
                        $_SESSION['info']['danger']         = "Impossible de supprimer '".$questionnaire->getLibelle()."'!";
                    }
                }
            }
        }
        /**
         * Fin de la question
         */

        /**
         * Debut de l'évaluation
         */
       /**
        * Récupérer l'évaluation
        *
        * @param int $idEntreprise
        *
        * @return array
        */
        private function getEvaluation($idEntreprise)
        {
            $manager    = new ManagerEvaluation();
            $evaluations= $this->getFiltre($manager, 'lister', 'id_entreprise', $idEntreprise);
            return $evaluations;
        }
       /**
        * Lister l'interface principale de gestion des évaluations
        *
        * @param array $parameters
        *
        * @return array
        */
        public function listerEvaluation($parameters)
        {
            $entreprise     = $this->getEntreprise();
            if ($_SESSION['compte']["identifiant"] == 'employe') {
                $user       = 'employe';
                $manager    = new ManagerEvaluationEvaluateur();
                $response   = $manager->lister([
                    'id_entreprise' => $entreprise->getIdEntreprise(),
                    'id_evaluateur' => $_SESSION['user']['idEmploye'],
                    'is_deleted'    => 'NO',
                    'is_archived'   => 'NO'
                ]);
                foreach ($response as $key => $value) {
                    $manager    = new ManagerEmploye();
                    $evalue     = $manager->chercher([
                        'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                        'idEmploye'     => $value->getIdEvaluee()
                    ]);
                    $value->setIdEvaluee($evalue); // Récupère les donnée sur les employés concernées
                    $manager    = new ManagerEntreprisePoste();
                    $poste      = $manager->chercher([
                        'idEntreprisePoste' => $value->getIdEntreprisePoste(),
                        'idEntreprise'      => $_SESSION['user']['idEntreprise']
                    ]);
                    $value->setIdEntreprisePoste($poste); // Récupère les donnée sur le poste d'une évaluation
                }
            } elseif ($_SESSION['compte']["identifiant"] == 'entreprise') {
                $user       = 'entreprise';
                $manager    = new ManagerEvaluationEmploye();
                $response   = $manager->lister([
                    'is_archived'   =>'NO',
                    'is_deleted'    =>'NO',
                    'id_entreprise' => $entreprise->getIdEntreprise()
                ]);
            }
            $view   = new View("listerEvaluation");
            $view->send("Backend", "Evaluation", $response, $user); 
            exit();
        }
        /**
        * Lister l'interface principale de gestion des évaluations archivées
        *
        * @param array $parameters
        *
        * @return array
        */
        public function listerEvaluationArchive($parameters)
        {
            $entreprise     = $this->getEntreprise();
            if ($_SESSION['compte']["identifiant"] == 'employe') {
                $user       = 'employe';
                $manager    = new ManagerEvaluationEvaluateur();
                $response   = $manager->lister([
                    'id_entreprise' => $entreprise->getIdEntreprise(),
                    'id_evaluateur' => $_SESSION['user']['idEmploye'],
                    'is_deleted'    => 'NO',
                    'is_archived'   => 'YES'
                ]);
                foreach ($response as $key => $value) {
                    $manager    = new ManagerEmploye();
                    $evalue     = $manager->chercher([
                        'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                        'idEmploye'     => $value->getIdEvaluee()
                    ]);
                    $value->setIdEvaluee($evalue); // Récupère les donnée sur les employés concernées
                    $manager = new ManagerEntreprisePoste();
                    $poste = $manager->chercher([
                        'idEntreprisePoste' => $value->getIdEntreprisePoste(),
                        'idEntreprise'      => $_SESSION['user']['idEntreprise']
                    ]);
                    $value->setIdEntreprisePoste($poste); // Récupère les donnée sur le poste d'une évaluation
                }
            } elseif ($_SESSION['compte']["identifiant"] == 'entreprise') {
                $user       = 'entreprise';
                $manager    = new ManagerEvaluationEmploye();
                $response   = $manager->lister([
                    'is_archived'   =>'YES',
                    'is_deleted'    =>'NO',
                    'id_entreprise' => $entreprise->getIdEntreprise()
                ]);
            }
            $view   = new View("listerEvaluation");
            $view->send("Backend", "Evaluation", $response, $user); 
            exit();
        }
       /** 
        * Afficher le formulaire d'une évaluation
        * 
        * @param array $parameters Les données à récupérer
        *
        * @return Objet
        */
        public function voirEvaluation($parameters)
        {
            if ($_SESSION['compte']["identifiant"] == 'employe') {
                $user       = 'employe';
                $manager    = new ManagerEvaluationEvaluateur();
                $evaluation = $manager->chercher(['id_evaluation_evaluateur' => $parameters['idEvaluation']]);
                $manager    = new ManagerEmploye();
                $evaluation->setIdEvaluee($this->getFiltre($manager, 'chercher', 'idEmploye', $evaluation->getIdEvaluee()));
            } elseif ($_SESSION['compte']["identifiant"] == 'entreprise') {
                $user       = 'entreprise';
                $manager    = new ManagerEvaluationEmploye();
                $evaluation = $manager->chercher(['id_evaluation_employe' => $parameters['idEvaluation']]);
                $manager    = new ManagerEvaluationEvaluateur();
                if ($evaluation) {
                    $result     = $manager->lister(['id_evaluation_employe' => $evaluation->getIdEvaluationEmploye()]);
                    $evaluation = [
                        'evaluation'    => $evaluation, 
                        'answers'       => $result
                    ];
                }
                }
            $view   = new View("voirEvaluation");
            $view->send("Backend", "Evaluation", $evaluation, $user);
            exit();
        }
       /**
        * Nouveau enregistrement d'une évaluation d'employée
        *
        * @param array $parameters Les données du Nouveau enregistrement
        *
        * @return empty
        */
        public function ajoutEvaluation($parameters)
        {
            if (!empty($parameters)) {
                $entreprise     = $this->getEntreprise();
                $manager        = new ManagerEmploye();
                $evaluee        = ['evaluee' =>$this->getFiltre($manager, 'chercher', 'idEmploye', $parameters['idEvaluee'])];
                foreach (explode(',', $parameters['idEvaluateur']) as $key => $value) {
                    if ($value) {
                        $evaluateurs[] = $this->getFiltre($manager, 'chercher', 'idEmploye', $value);
                    }
                }
                $evaluateursArray   = ['evaluateurs' => $evaluateurs];
                $manager            = new ManagerEvaluation();
                $evaluation         = $this->getFiltre($manager, 'chercher', 'id_evaluation', $parameters['idEvaluation']);
                $manager            = new ManagerEntreprisePoste();
                $poste              = $manager->chercher([
                    'idEntreprise'      => $entreprise->getIdEntreprise(),
                    'idEntreprisePoste' => $evaluation->getIdEntreprisePoste()
                ]);
                foreach ($evaluation->getCategory() as $key => $value) {
                    $tmpQuest       = array();
                    foreach ($value["category"] as $ke => $val) {
                        $questions  = array();
                        foreach ($val["questionnaires"] as $k => $question) {
                            $questions[] = array_merge(
                                ['question' => $question],
                                ['point'    => 0],
                                ['note'     => '']
                            );
                        }
                        $tmpQuest[] = [
                            'sousCategories'    => $val['sousCategories'],
                            'questionnaires'    =>$questions
                        ];
                    }  
                    $points[]       = [
                        'parent'    => $value["parent"],
                        'category'  => $tmpQuest
                    ];
                }
                $manager = new ManagerEmploye();
                foreach (explode(',',$parameters['idEvaluateur']) as $key => $value) {
                    if ($value) {
                        $evaluateur = $this->getFiltre($manager, 'chercher', 'idEmploye', $value);
                        $this->sendMailEvaluation(  $evaluee['evaluee'] ,$evaluateur,
                                                    $this->findLast('evaluation_employe', 'id_evaluation_employe')['id'] + 1);
                    }
                }
                $points     = array_merge(['poste'=>$poste], $evaluee, $evaluateursArray, ['points' => $points]);
                $manager    = new ManagerEvaluationEmploye();
                $retour     = $manager->ajouter([
                    'id_evaluation'     => $parameters['idEvaluation'],
                    'id_evaluee'        => $parameters['idEvaluee'],
                    'id_entreprise'     => $parameters['idEntreprise'],
                    'evaluateur'        => serialize($evaluateurs),
                    'libelle'           => $parameters['libelle'],
                    'is_archived'       => 'NO',
                    'date_creation'     => $parameters['dateCreation'],
                    'point'             => serialize($points)
                ]);
                if ($retour->getIdEvaluationEmploye() != 0) {
                    $_SESSION['info']['success']    = "Le formulaire d'évaluation à bien été envoyé !";
                } else {
                    $_SESSION['info']['danger']     = "Echec de l'enregistrement";
                }
            }
        }
        /**
         * Mettre à jour une évaluation pour évaluateur
         *
         * @param array $parameters Les données du Nouveau enregistrement
         *
         * @return empty
         */
        public function mettreAJourEvaluation($parameters)
        {
            $evaluationEvaluateur   = $this->getAllPoints($parameters);
            $manager                = new ManagerEvaluationEvaluateur();
            $retour                 = $manager->modifier([
                'id_evaluation_evaluateur'  => $parameters['idEvaluationEvaluateur'],
                'libelle'                   => $evaluationEvaluateur['evalEmpl']->getLibelle(),
                'id_entreprise'             => $evaluationEvaluateur['evalEmpl']->getIdEntreprise(),
                'date_repondre'             => $parameters['dateRepondre'],
                'id_evaluateur'             => $parameters['idEvaluateur'],
                'id_evaluee'                => $evaluationEvaluateur['evalEmpl']->getIdEvaluee(),
                'id_evaluation_employe'     => $evaluationEvaluateur['evalEmpl']->getIdEvaluationEmploye(),
                'is_archived'               => 'NO',
                'donnee_evaluation'         => serialize($evaluationEvaluateur['point'])
            ]);
            if ($retour->getIdEvaluationEvaluateur() != 0) {
                $manager    = new ManagerEmploye();
                $evaluateur = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise'], 'idEmploye' => $_SESSION['user']['idEmploye']]);
                $evaluee    = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise'], 'idEmploye' => $evaluationEvaluateur['evalEmpl']->getIdEvaluee()]);
                $this->sendMailNotification($evaluee, $evaluateur, $evaluationEvaluateur['evalEmpl']->getIdEvaluationEmploye() , 'modifié(e)', $retour->getIdEvaluationEvaluateur());
                $_SESSION['info']['success']    = "Le formulaire d'évaluation à bien été envoyé ";
                $this->calculer($evaluationEvaluateur['evalEmpl']);
            } else {
                $_SESSION['info']['danger']     = "Echec de l'enregistrement";
            }
        }
        /**
         * Archiver une évaluation
         *
         * @param array $parameters Les critères des données à archiver
         *
         * @return empty
         */
        public function archiverEvaluation($parameters)
        {
            if (!empty($parameters)) {
                if (explode('/', $_GET['page'])[1] == 'entreprise') {
                    $manager    = new ManagerEvaluationEmploye();
                    $evaluation = $manager->chercher(['id_evaluation_employe' => $parameters['idEvaluation']]);
                    $idName     = 'id_evaluation_employe';
                } elseif (explode('/', $_GET['page'])[1] == 'employe') {
                    $manager    = new ManagerEvaluationEvaluateur();
                    $evaluation = $manager->chercher(['id_evaluation_evaluateur' => $parameters['idEvaluation']]);
                    $idName     = 'id_evaluation_evaluateur';
                }
                if ($evaluation != null) {
                    $archiver = $manager->modifier([
                        ''.$idName      => $parameters['idEvaluation'],
                        'is_archived'   => 'YES'
                    ]);
                    if ($archiver) {
                        $_SESSION['info']['success']    = "Archivage est fait avec succès!";
                    } else {
                        $_SESSION['info']['danger']     = "Impossible d'archiver '".$evaluation->getLibelle()."'. Veuillez effacer d'abord les évaluations lient à cet enregistrement!!!";
                    }
                }
            }
        }
        /**
         * Supprimer une évaluation
         *
         * @param array $parameters Les critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerEvaluation($parameters)
        {
            if (!empty($parameters)) {
                if (explode('/', $_GET['page'])[1] == 'entreprise') {
                    $manager    = new ManagerEvaluationEmploye();
                    $evaluation = $manager->chercher(['id_evaluation_employe' => $parameters['idEvaluation']]);
                    $idName     = 'id_evaluation_employe';
                } elseif (explode('/', $_GET['page'])[1] == 'employe') {
                    $manager    = new ManagerEvaluationEvaluateur();
                    $evaluation = $manager->chercher(['id_evaluation_evaluateur' => $parameters['idEvaluation']]);
                    $idName     = 'id_evaluation_evaluateur';
                }
                if ($evaluation != null) {
                    $archiver = $manager->modifier([
                        ''.$idName      => $parameters['idEvaluation'],
                        'is_deleted'    => 'YES'
                    ]);
                    if ($archiver) {
                        $_SESSION['info']['success']    = "Suppression est fait avec succès!";
                    } else {
                        $_SESSION['info']['danger']     = "Impossible de supprimer '".$evaluation->getLibelle()."'. Veuillez effacer d'abord les évaluations lient à cet enregistrement!!!";
                    }
                }
            }
        }
        /**
         * Restaurer une évaluation
         *
         * @param array $parameters Les critères des données à restaurer
         *
         * @return empty
         */
        public function restaurerEvaluation($parameters)
        {
            if (!empty($parameters)) {
                if (explode('/', $_GET['page'])[1] == 'entreprise') {
                    $manager    = new ManagerEvaluationEmploye();
                    $evaluation = $manager->chercher(['id_evaluation_employe' => $parameters['idEvaluation']]);
                    $idName     = 'id_evaluation_employe';
                } elseif (explode('/', $_GET['page'])[1] == 'employe') {
                    $manager    = new ManagerEvaluationEvaluateur();
                    $evaluation = $manager->chercher(['id_evaluation_evaluateur' => $parameters['idEvaluation']]);
                    $idName     = 'id_evaluation_evaluateur';
                }
                if ($evaluation != null) {
                    $archiver = $manager->modifier([
                        ''.$idName      => $parameters['idEvaluation'],
                        'is_archived'   => 'NO'
                    ]);
                    if ($archiver) {
                        $_SESSION['info']['success']    = "Restauration est fait avec succès!";
                    } else {
                        $_SESSION['info']['danger']     = "Impossible de supprimer '".$evaluation->getLibelle()."'. Veuillez effacer d'abord les évaluations lient à cet enregistrement!!!";
                    }
                }
            }
        }
        /** 
         * Afficher le formulaire de la validation d'une évaluation
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
         */
        public function afficherFormEvaluation($parameters)
        {
            $manager    = new ManagerEvaluationEvaluateur();
            $evaluation = $this->getFiltre($manager, 'chercher', 'id_evaluation_evaluateur', $parameters['idEvaluation']);
            $manager    = new ManagerEmploye();
            $evaluation->setIdEvaluee($this->getFiltre($manager, 'chercher', 'idEmploye', $evaluation->getIdEvaluee()));
            $manager    = new ManagerEvaluationEmploye();
            $evaluation->setIdEvaluationEmploye($this->getFiltre($manager, 'chercher', 'id_evaluation_employe', $evaluation->getIdEvaluationEmploye()));
            return $evaluation;
        }
        /**
         * Fin de l'évaluation
         */

        /**
         * Debut d'échantillon de l'évaluation
         */
       /**
        * Voir l'interface principale de gestion des échantillons d'évaluations
        *
        * @param array $parameters
        *
        * @return array
        */
        public function listerEvaluationModele($parameters)
        {
            $entreprise = $this->getEntreprise();
            $manager    = new ManagerEntreprisePoste();
            $postes     = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $manager    = new ManagerEmploye();
            $employes   = $this->getFiltre($manager, 'lister','idEntreprise',$entreprise->getIdEntreprise());
            $employe    = [];
            foreach ($employes as $key => $value) {
                $employe[] = $value->toArray();
            }
            $manager        = new ManagerEvaluation();
            $evaluations    = $this->getEvaluation($entreprise->getIdEntreprise());
            foreach ($evaluations as $evaluation) {
                $idPoste = $evaluation->getIdEntreprisePoste();
                $manager = new ManagerEntreprisePoste();
                $evaluation->setIdEntreprisePoste(
                    $manager->chercher([
                        'idEntreprisePoste' => $idPoste,
                        'idEntreprise'      => $entreprise->getIdEntreprise()
                    ])
                );
            }
            $donnees    = [
                'entreprise'    => $entreprise,
                'employe'       => $employe,
                'employes'      => $employes,
                'postes'        => $postes,
                'evaluations'   => $evaluations
            ];
            $view   = new View("listerEvaluation");
            $view->send("Backend", "Evaluation", $donnees, "");
            exit();
        }
       /** 
        * Afficher le formulaire d'échantillon d'une évaluation
        * 
        * @param array $parameters Les données à récupérer
        *
        * @return Objet
        */
        public function voirEvaluationModele($parameters)
        {
            $manager    = new ManagerEvaluation();
            $evaluation = $this->getFiltre($manager, 'chercher', 'id_evaluation', $parameters['idEvaluation']);  
            $view       = new View("voirEvaluation");
            $view->send("Backend", "Evaluation", $evaluation, "");
            exit();
        }
        /**
         * Mettre à jour une table
         *
         * @param array $parents Les données à manipuler
         *
         * @return array
        */
        private function setEvaluationArray($parents)
        {
            $entreprise = $this->getEntreprise();
            $manager    = new ManagerEvaluationCategorie();
            foreach ($parents as $key => $parent) {
                $sousCategories = array();
                if (!empty($parent)) {
                    $category = explode(',category:', $parent);
                    foreach ($category as $k => $val) {
                        if ($k > 0) {
                            // Catégorie
                            $categs         = explode(',', $val);
                            $questionnaires = array();
                            foreach ($categs as $ke => $value) {
                                if (!empty($value)) {
                                    if ($ke > 0 ) {
                                        // Questionnaire
                                        $manager            = new ManagerEvaluationQuestionnaire();
                                        $questionnaires[]   = $manager->chercher([
                                            'id_entreprise' => $entreprise->getIdEntreprise(),
                                            'id_categorie'  => $categs[0],
                                            'id_question'   => $value
                                        ]);
                                    } else {
                                        // Sous-catégorie
                                        $manager            = new ManagerEvaluationCategorie();
                                        $sousCategorie[$k]  = $manager->chercher([
                                            'id_categorie'  => $value,
                                            'id_entreprise' => $entreprise->getIdEntreprise()
                                        ]);
                                    }
                                }
                            }
                            if (isset($sousCategorie[$k])) {
                                $sousCategorie[$k]  = ['sousCategories' => $sousCategorie[$k]];
                                $questionnaires     = ['questionnaires' => $questionnaires];
                                $sousCategories[]   = array_merge($sousCategorie[$k], $questionnaires);
                            }
                        } else {
                            // Parent
                            $manager    = new ManagerEvaluationCategorie();
                            // Récupération le parent pour avoir un objet de l'entité catégorie
                            $objet      = $this->getFiltre($manager, 'chercher', 'id_categorie', explode(',', $category[1])[0]);
                            // Assignation des valeurs de l'objet catégorie
                            $objet->setDescription("");
                            $objet->setIdCategorie(0);
                            $objet->setCode("");
                            $objet->setLibelle($category[0]);
                        }
                    }
                    $response[] = array_merge([
                        'parent'    => $objet,
                        'category'  => $sousCategories
                    ]);
                }
            }
            return $response;
        }
       /**
        * Nouveau enregistrement d'une echantillon d'évaluation
        *
        * @param array $parameters Les données du Nouveau enregistrement
        *
        * @return empty
        */
        public function ajoutEvaluationModele($parameters)
        {
            if (!empty($parameters)) {
                $parents    = explode('parent:', $parameters['question']);
                $response   = $this->setEvaluationArray($parents);
                $entreprise     = $this->getEntreprise();
               
                // echo "<pre>";
                // var_dump($parameters);
                // var_dump($response); 

                // exit();

                $manager    = new ManagerEvaluation();
                $retour     = $manager->ajouter([
                    'libelle'               => $parameters['libelle'],
                    'id_entreprise'         => $entreprise->getIdEntreprise(),
                    'id_entreprise_poste'   => $parameters["idEntreprisePoste"],
                    'date_creation'         => date("Y-m-d H:i:s"),
                    'category'              => serialize($response)
                ]);
                if ($retour->getIdEvaluation() != 0) {
                    $_SESSION['info']['success']    = "Enregistrement terminé avec succès";
                } else {
                    $_SESSION['info']['danger']     = "Echec de l'enregistrement";
                }
            }
        }
       /**
        * Mettre à jour un échantillon d'évaluation
        *
        * @param array $parameters Les données du Nouveau enregistrement
        *
        * @return empty
        */
        public function mettreAJourEvaluationModele($parameters)
        {
            if (!empty($parameters)) {

                $parents    = explode('parent:', $parameters['question']);
                $response   = $this->setEvaluationArray($parents);
                $entreprise     = $this->getEntreprise();
               
                $manager    = new ManagerEvaluation();
                $retour     = $manager->modifier([
                    'id_evaluation'         => $parameters['idEvaluation'],
                    'id_entreprise_poste'   => $parameters["idEntreprisePoste"],
                    'libelle'               => $parameters['libelle'],
                    'id_entreprise'         => $entreprise->getIdEntreprise(),
                    'date_modif'            => date("Y-m-d H:i:s"),
                    'category'              => serialize($response)
                ]);
                // echo "<pre>";
                // var_dump($retour->getIdEvaluation());
                // var_dump($retour);
                // var_dump($parameters);
                // var_dump($response); 

                // exit();
                if ($retour->getIdEvaluation() != 0) {
                    $_SESSION['info']['success']    = "Enregistrement terminé avec succès";
                } else {
                    $_SESSION['info']['danger']     = "Echec de l'enregistrement";
                }
            }
        }
       /**
        * Supprimer une exemple d'évaluation
        *
        * @param array $parameters Les critères des données à supprimer
        *
        * @return empty
        */
        public function supprimerEvaluationModele($parameters)
        {
            if (!empty($parameters)) {
                $manager    = new ManagerEvaluation();
                $evaluation = $manager->chercher(['id_evaluation' => $parameters['idEvaluation']]);
                if ($evaluation != null) {
                    $suppression = $manager->supprimer([
                        'id_evaluation' => $parameters['idEvaluation']
                    ]);
                    if ($suppression) {
                        $_SESSION['info']['success']    = "Suppression est fait avec succès!";
                    } else {
                        $_SESSION['info']['danger']     = "Impossible de supprimer '".$evaluation->getLibelle()."'. Veuillez effacer d'abord les évaluations lient à cet enregistrement!!!";
                    }
                }
            }
        }
        /**
         * Fin d'échantillon de l'évaluation
         */

        /**
         * Debut de l'évaluation à valider
         */
       /**
        * Lister l'interface principale de gestion des évaluations validées
        *
        * @param array $parameters
        *
        * @return array
        */
        public function listerEvaluationValider($parameters)
        {
            $manager    = new ManagerEvaluationEmploye();
            $entreprise = $this->getEntreprise();
            $donnees    = array();
            if (explode('/',$_SERVER['REQUEST_URI'])[2] == 'employe') {
                // La liste de l'évaluation à valider
                $evaluations = $manager->getEvaluationValide(['idEntreprise' => $_SESSION['user']['idEntreprise'], 'idEvaluateur' => $_SESSION['user']['idEmploye']]);
                // Vérifie si évaluation a été validée
                $manager = new ManagerEvaluationEmploye();
                $evaluations = $manager->lister(['is_archived' => 'NO', 'id_entreprise' => $_SESSION['user']['idEntreprise'], 'id_evaluation_employe' => 'NOT IN ' . $manager->getEvaluationValide(['idEntreprise' => $_SESSION['user']['idEntreprise'], 'idEvaluateur' => $_SESSION['user']['idEmploye']])]);
                // Filtrer le résultat par rapport à l'évaluateur
                foreach ($evaluations as $key => $evaluation) {
                    foreach ($evaluation->getEvaluateur() as $keyTwo => $evaluateur) {
                        if ($evaluateur->getIdEmploye() == $_SESSION['user']['idEmploye']) {
                            $donnees[] = $evaluation;
                        }
                    }
                }
                $view    = new View("listerEvaluationValiderEmploye");
            }
            $view->send("Backend", "Evaluation", $donnees, "");
            exit();
        }
       /**
        * Nouveau enregistrement d'une évaluation à valider
        *
        * @param array $parameters Les données du Nouveau enregistrement
        *
        * @return empty
        */
        public function ajoutEvaluationValider($parameters)
        {
            $evaluationEvaluateur   = $this->getAllPoints($parameters);
            $manager                = new ManagerEvaluationEvaluateur();
            $retour                 = $manager->ajouter([
                'libelle'               => $evaluationEvaluateur['evalEmpl']->getLibelle(),
                'id_entreprise'         => $evaluationEvaluateur['evalEmpl']->getIdEntreprise(),
                'date_repondre'         => $parameters['dateRepondre'],
                'id_evaluateur'         => $parameters['idEvaluateur'],
                'id_evaluee'            => $evaluationEvaluateur['evalEmpl']->getIdEvaluee(),
                'id_evaluation_employe' => $evaluationEvaluateur['evalEmpl']->getIdEvaluationEmploye(),
                'id_entreprise_poste'   => $evaluationEvaluateur['evalEmpl']->getPoint()['poste']->getIdEntreprisePoste(),
                'is_archived'           => 'NO',
                'is_deleted'            => 'NO',
                'donnee_evaluation'     => serialize($evaluationEvaluateur['point'])
            ]);
            if ($retour->getIdEvaluationEvaluateur() != 0) {
                $manager    = new ManagerEmploye();
                $evaluateur = $manager->chercher([
                    'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                    'idEmploye'     => $_SESSION['user']['idEmploye']
                ]);
                $evaluee    = $manager->chercher([
                    'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                    'idEmploye'     => $evaluationEvaluateur['evalEmpl']->getIdEvaluee()
                ]);
                $this->sendMailNotification($evaluee, $evaluateur, $evaluationEvaluateur['evalEmpl']->getIdEvaluationEmploye() ,
                                            ' validé(e)', $retour->getIdEvaluationEvaluateur()); // Envoyer un mail de notification à chauqe evaluateurs
                $this->calculer($evaluationEvaluateur['evalEmpl']); // Récupérer les point par calcul
                $_SESSION['info']['success']    = "Enregistrement terminé avec succès";
            } else {
                $_SESSION['info']['danger']     = "Echec de l'enregistrement";
            }
        }
        /**
        * Récupérer les points et les notes via formulaire
        *
        * @param array $parameters Les données du Nouveau enregistrement
        *
        * @return array of object
        */
        private function getAllPoints($parameters)
        {
            $indice                             = 0;
            $manager                            = new ManagerEvaluationEmploye();
            $evaluationEvaluateur['evalEmpl']   = $manager->chercher([
                'id_evaluation_employe' => $parameters['idEvaluationEmploye'],
                'id_entreprise' => $_SESSION['user']['idEntreprise']
            ]);
            $responsePoint                      = explode(',', $parameters['point']);
            $responseNote                       = explode(',', $parameters['note']);
            foreach ($evaluationEvaluateur['evalEmpl']->getPoint()['points'] as $key => $evalEmpl) {
                foreach ($evalEmpl["category"] as $keyTwo => $questionnaires) {
                    $resQuest = array();
                    if (!empty($questionnaires["questionnaires"])) {
                        foreach ($questionnaires["questionnaires"] as $keyThree => $questions) {
                            if ($questions["question"]->getIdCategorie() == $questionnaires["sousCategories"]->getIdCategorie()) {
                                $resQuest[$keyThree] = [
                                    'question'  => $questions["question"],
                                    'point'     => $responsePoint[$indice],
                                    'note'      => $responseNote[$indice]
                                ];
                                $indice++;
                            }
                        }
                        $questionnaires["questionnaires"]   = $resQuest;
                    }
                    if ($resQuest) {
                        $evalEmpl['category'][$keyTwo] = $questionnaires;
                    }
                }
                $evaluationEvaluateur['point'][$key] = $evalEmpl;
            }
            // Nombre de réponse d'une évaluation est ajoutée + 1 à chaque validation une évaluation
            $manager->modifier([
                'id_evaluation_employe' => $evaluationEvaluateur['evalEmpl']->getIdEvaluationEmploye(),
                'nombre_reponse'        => $evaluationEvaluateur['evalEmpl']->getNombreReponse() + 1
            ]);
            return $evaluationEvaluateur;
        }
        /**
        * Calculer la moyenne de points par question par rapport aux évaluateurs
        *
        * @param object $evaluationEmploye
        *
        * @return empty
        */
        private function calculer($evaluationEmploye)
        {
            $newPoints          = array();
            $moyTotal           = 0.0;
            $manager            = new ManagerEvaluationEvaluateur();
            $evaluationValide   = $manager->lister([
                'id_evaluation_employe' => $evaluationEmploye->getIdEvaluationEmploye(),
                'id_entreprise'         => $_SESSION['user']['idEntreprise'],
                'id_evaluee'            => $evaluationEmploye->getIdEvaluee()
            ]);
            if (count($evaluationEmploye->getEvaluateur()) == count($evaluationValide)) {
                if (count($evaluationEmploye->getEvaluateur()) == 1 && $evaluationEmploye->getEvaluateur()) {
                    foreach ($evaluationValide[0]->getDonneeEvaluation() as $key => $datas) {
                        $moyenne = 0.0;
                        foreach ($datas["category"] as $keyTwo => $results) {
                            if ($results['questionnaires']) {
                                $moy = 0.0;
                                foreach ($results["questionnaires"] as $keyThree => $result) {
                                    // Calcule la moyenne par question
                                    $datas["category"][$keyTwo]["questionnaires"][$keyThree]["note"] = "";
                                    $moy += $datas["category"][$keyTwo]["questionnaires"][$keyThree]["point"];
                                }
                                $moy            = $moy / count($results["questionnaires"]); // Calcule la moyenne par dimension
                                $moyenne        += $moy;
                                $tmp[$keyTwo]   = array_merge($results, ["moyenne" => $moy]);
                            }
                        }
                        $subResult = [ 
                            'parent'        => $datas["parent"],
                            'category'      => $tmp
                        ];
                        $moyenne            = $moyenne / count($datas["category"]); // Calcule la moyenne par trait de personnalité
                        $newPoints[$key]    = array_merge($subResult, ["moyenneCategory" => $moyenne]);
                        $moyTotal           += $moyenne; // Calcule la somme de la moyenne total
                    }
                    $moyTotal += $moyTotal / count($evaluationValide[0]->getDonneeEvaluation()); // Calcule la moyenne total
                }
                else {
                    foreach ($evaluationValide as $key => $datas) {
                        foreach ($datas->getDonneeEvaluation() as $keyTwo => $data) {
                            if ($key > 0) {
                                $moyenne    = 0.0;
                                foreach ($data["category"] as $keyThree => $results) {
                                    $moy    = 0.0;
                                    foreach ($results["questionnaires"] as $keyFour => $result) {
                                        $evaluationPoint[$keyTwo]["category"][$keyThree]["questionnaires"][$keyFour]["point"] = $evaluationPoint[$keyTwo]["category"][$keyThree]["questionnaires"][$keyFour]["point"] + $result["point"]; // Somme des questions
                                        // Calcule de la moyenne par question
                                        if ($key == count($evaluationValide) - 1) {
                                            $evaluationPoint[$keyTwo]["category"][$keyThree]["questionnaires"][$keyFour]["point"] = $evaluationPoint[$keyTwo]["category"][$keyThree]["questionnaires"][$keyFour]["point"] / count($evaluationEmploye->getEvaluateur());
                                            $evaluationPoint[$keyTwo]["category"][$keyThree]["questionnaires"][$keyFour]["note"] = "";
                                            $moy += $evaluationPoint[$keyTwo]["category"][$keyThree]["questionnaires"][$keyFour]["point"];
                                        }
                                    }
                                    // Calcule de la moyenne par dimension
                                    if ($moy > 0) {
                                        $moy                                                = $moy / count($results["questionnaires"]);
                                        $moyenne                                            += $moy;
                                        $newPoints[$keyTwo]["category"][$keyThree]    = array_merge(
                                            $evaluationPoint[$keyTwo]["category"][$keyThree],
                                            ["moyenne" => $moy]
                                        ); // Affectation et récupération des données de la moyenne dimension
                                    }
                                }
                                // Calcule la moyenne par trait de personnalité
                                if ($moyenne > 0) {
                                    $moyenne                = $moyenne / count($data["category"]);
                                    $newPoints[$keyTwo]     = array_merge(
                                        ['parent'           => $data["parent"]], 
                                        $newPoints[$keyTwo], 
                                        ["moyenneCategory"  => $moyenne]
                                    ); // Affectation et récupération des données de la moyenne trait de personnalité
                                    $moyTotal               += $moyenne; // Calcule la somme de la moyenne total
                                }                              
                            } else {
                                $evaluationPoint    = $datas->getDonneeEvaluation();
                            }
                        }
                        if (count($evaluationValide) == $key + 1) {
                            $moyTotal = $moyTotal / count($datas->getDonneeEvaluation()); // Calcule la moyenne total
                        }
                    }
                }
                $newEvaluationPoint = array_merge(
                    ['poste'        => $evaluationEmploye->getPoint()["poste"]],
                    ['evaluee'      => $evaluationEmploye->getPoint()["evaluee"]],
                    ['evaluateurs'  => $evaluationEmploye->getPoint()["evaluateurs"]],
                    ['points'       => $newPoints]
                );
                $manager    = new ManagerEvaluationEmploye();
                $retour     = $manager->modifier([
                    'id_evaluation_employe' => $evaluationEmploye->getIdEvaluationEmploye(),
                    'is_answered'           => 'YES',
                    'moyenne'               => floatval($moyTotal),
                    'point'                 => serialize($newEvaluationPoint)
                ]);
            }
        }
       /** 
        * Afficher le formulaire de la validation d'une évaluation
        * 
        * @param array $parameters Les données à récupérer
        *
        * @return Objet
        */
        public function afficherFormEvaluationValider($parameters)
        {
            $manager    = new ManagerEvaluationEmploye();
            $evaluation = $this->getFiltre($manager, 'chercher', 'id_evaluation_employe', $parameters['idEvaluation']);
            return $evaluation;
        }
        /**
         * Fin de l'évaluation à valider
         */
       
       /**
        * Récupérer l'identification d'entreprise
        *
        *
        * @return array
        */
        private function getEntreprise()
        {
            $manager = new ManagerEntreprise();
            return $this->getFiltre($manager, 'chercher', 'idEntreprise', $_SESSION['user']['idEntreprise']);
        }
       /**
        * Récupérer les données pour le filtre
        *
        * @param object $manager
        * @param string $fonction 
        * @param string $attribut
        * @param int, string $whereClause
        *
        * @return array
        */
        private function getFiltre($manager, $fonction, $attribut, $whereClause)
        {
            return $manager->$fonction([$attribut => $whereClause]);
        }
        /**
         * Envoyer un email à un employé après avoir créée une évaluation
         * 
         * @param object $evaluateur l'employé concerné
         * @param object $evaluee L'employé concerné à évaluer
         * @param int $idEvaluationEmploye Id de la table evaluation_employe
         *
         * @return empty
         */
        private function sendMailEvaluation($evaluee, $evaluateur, $idEvaluationEmploye)
        {
            $to         = $evaluateur->getEmail();
            $headers[]  = "MIME-Version: 1.0";
            $headers[]  = 'Content-type: text/html; charset=iso-8859-1';
            $headers[]  = 'From: ' . $_SESSION['user']['email'];
            $subject    = "évaluation pour " . strtoupper($evaluee->getPrenom());
            $message    = "<html><body>
                            <div class='container'>
                                <label>Bonjour " . $evaluateur->getPrenom() . ",</label><br><br>
                                <label>Nous vous informons que vous avez une évaluation à valider.</label><br><br>
                                <label>Voici le lien d'accès à la validation de l'évaluation : <a href=" . HOST . "manage/employe/valide-evaluation_valider?idEvaluation=" . $idEvaluationEmploye . ">valider cette évaluation</a></label>
                                <br><br><br>
                                <label>Cordialement, </label><br><br>
                                <label> L'équipe <a href=" . HOST . ">Human Cart'Office</a></label>
                            </div>
                        </body></html>";
            mail($to, $subject, $message, implode("\r\n", $headers));
        }
        /**
         * Envoyer un email de notification à l'entreprise après avoir validée une évaluation
         * 
         * @param object $evaluateur l'employé concerné
         * @param object $evaluee L'employé concerné à évaluer
         * @param int $idEvaluationEmploye Id de la table evaluation_employe
         *
         * @return empty
         */
        private function sendMailNotification($evaluee, $evaluateur, $idEvaluationEmploye, $motif, $id)
        {
            $to         = $this->getEntreprise()->getEmail();
            $headers[]  = "MIME-Version: 1.0";
            $headers[]  = 'Content-type: text/html; charset=iso-8859-1';
            $headers[]  = 'From: ' . $_SESSION['user']['email'];
            $subject    = "Évaluation pour " . strtoupper($evaluee->getPrenom());
            $message    = "<html><body>
                            <div class='container'>
                                <label>Bonjour ,  </label><br><br>
                                <label>" . $evaluateur->getPrenom() . " a " . $motif . " cette évaluation</label><br><br>
                                <label>Voici le lien pour voir la validation d'évaluation : <a href=" . HOST . "manage/entreprise/detail-evaluation?idEvaluation=" . $idEvaluationEmploye . ">voir la réponse</a></label><br><br><br>
                                <label>Cordialement, </label>
                                <br><br><br>
                                <label> L'équipe <a href=" . HOST . ">Human Cart'Office</a></label>
                            </div>
                        </body></html>";
            mail($to, $subject, $message, implode("\r\n", $headers));
        }
        /**
         * Dessiner le graphe d'une personne à évaluer
         * 
         * @param arary $parameters Les critères des données du graphe
         *
         * @return empty
         */
        public function drawGraph($parameters)
        {
            $manager = new ManagerEvaluationEmploye();
            $donnees = $this->getFiltre($manager, 'chercher', 'id_evaluation_employe', $parameters['idEvaluation']);
            $points  = $donnees->getPoint()["points"];
            $response= array();
            if ( isset($parameters['idEvaluateur']) ) {
                $manager = new ManagerEvaluationEvaluateur();
                $result = $manager->chercher([
                    'id_evaluateur'         => $parameters['idEvaluateur'],
                    'id_evaluation_employe' => $parameters['idEvaluation'],
                    'id_evaluee'            => $donnees->getIdEvaluee(),
                    'id_entreprise'         => $_SESSION['user']['idEntreprise']
                ]) ;
                // Convertir objet en tableau
                $moyTotal = 0.0;
                foreach ($result->getDonneeEvaluation() as $key => $value) {
                    $moyenne = 0.0;
                    foreach ($value["category"] as $keyTwo => $valueTwo) {
                        $moy = 0.0;
                        foreach ($valueTwo["questionnaires"] as $keyThree => $valueThree) {
                            $response[$key]['category'][$keyTwo]['questionnaires'][$keyThree] = [
                                'question'  => $valueThree["question"]->toArray(),
                                'point'     => $valueThree['point']
                            ];
                            $moy += $valueThree['point'];
                        }
                        if (count($valueTwo["questionnaires"]) > 0 && $moy > 0) {
                            $moy = $moy / count($valueTwo["questionnaires"]);
                        } else {
                            $moy = 0.0;
                        }
                        $response[$key]['category'][$keyTwo] = [
                            'sousCategories' => array_merge(
                                $valueTwo["sousCategories"]->toArray(),
                                ['moyenne' => $moy]
                            ),
                            'questionnaires' =>  $response[$key]['category'][$keyTwo]['questionnaires']
                        ];
                        $moyenne += $moy;
                    }
                    $response[$key]['parent'] = array_merge($value["parent"]->toArray(),['moyenne' => $moyenne / count($value["category"]) ]);
                    if (count($value["category"]) > 0 && $moyenne > 0) {
                        $moyTotal += $moyenne / count($value["category"]);
                    }
                }
                $response['moyenne'] = $moyTotal / count($result->getDonneeEvaluation());
            }
            else {
                // Convertir objet en tableau
                foreach ($points as $key => $value) {
                    $response[$key]['parent'] = array_merge($value["parent"]->toArray(),['moyenne' => $value["moyenneCategory"]]);
                    foreach ($value["category"] as $keyTwo => $valueTwo) {
                        $response[$key]['category'][$keyTwo] = [
                            'sousCategories' => array_merge($valueTwo["sousCategories"]->toArray(), ['moyenne' => $valueTwo["moyenne"]])
                        ];
                        foreach ($valueTwo["questionnaires"] as $keyThree => $valueThree) {
                            $response[$key]['category'][$keyTwo]['questionnaires'][$keyThree] = [
                                'question'  => $valueThree["question"]->toArray(),
                                'point'     => $valueThree['point']
                            ];
                        }
                    }
                }
                $response['moyenne'] = $donnees->getMoyenne();
            }
            $response['evaluee'] = $donnees->getPoint()["evaluee"]->getNom() .' ' . $donnees->getPoint()["evaluee"]->getPrenom() ;
            $donnees->setPoint(serialize($response));
            $view = new View("showGraph");
            $view->send("Backend", "Evaluation", $donnees, "entreprise"); 
            exit();
        }
        /**
         * Importer un fichier csv dans la base de données
         * 
         * @param arary $parameters 
         *
         * @return empty
         */
        public function uploadCsvFile($parameters)
        {
            if (isset($parameters['upload'])) {
                $file           = $_FILES['file']['tmp_name'];
                $idCategorie    = 0;
                if (($handle = fopen($file, "r")) !== FALSE && end(explode('.', $_FILES['file']['name'])) == 'csv') {
                    if (end(explode('/', $_SERVER['HTTP_REFERER'])) == 'categorie'
                        || end(explode('/', $_SERVER['HTTP_REFERER'])) =='questionnaire') {
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            $interpreter = [
                                'Initiation 1'   => '',
                                'Pratique 2'     => '',
                                'Maîtrise 3'     => '',
                                'Expertise 4'    => '' 
                            ];
                            $manager    = new ManagerEvaluationCategorie();
                            if (strstr($data[0]," ")) {
                                preg_match('#\((.*?)\)#', explode(":", $data[0])[0], $code); // Récupère mot dans parenthèse
                                $doublon    = $manager->chercher([
                                    'code'      => $code[1],
                                    'libelle'   => utf8_encode(str_replace("'","\'",explode(":", $data[0])[0]))
                                ]);
                                if (!$doublon) {
                                    $retour     = $manager->ajouter([
                                        'code'          => $code[1],
                                        'libelle'       => explode(":", $data[0])[0],
                                        'description'   => explode(":", $data[0])[1],
                                        'id_entreprise' => $_SESSION['user']['idEntreprise']
                                    ]);
                                    $idCategorie = $retour->getIdCategorie();
                                }
                                else {
                                    $idCategorie = $doublon->getIdCategorie();
                                }
                            } else {
                                if (count($data) == 6) {
                                    $interpreter = array_merge(
                                        ['Initiation 1'   => $data[2]],
                                        ['Pratique 2'     => $data[3]],
                                        ['Maîtrise 3'     => $data[4]],
                                        ['Expertise 4'    => $data[5]]
                                    );
                                }
                                $cat        = $manager->chercher([
                                    'id_categorie'  => $idCategorie,
                                    'id_entreprise' => $parameters['idEntreprise']
                                ]);
                                $newId      = $this->findLast('evaluation_questionnaire', 'id_question')['id'] + 1;
                                $manager    = new ManagerEvaluationQuestionnaire();
                                if (!$manager->chercher(['libelle' => utf8_encode($data[1]), 'id_categorie' => $idCategorie])) {
                                    $retour     = $manager->ajouter([
                                        'code'          => "{$cat->getCode()}{$newId}",
                                        'libelle'       => $data[1],
                                        'interpretation'=> serialize($interpreter),
                                        'id_categorie'  => $idCategorie
                                    ]);
                                }
                            }
                        }
                    } elseif (end(explode('/', $_SERVER['HTTP_REFERER'])) =='evaluation_modele') {
                        $inc    = -1;
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                            if (isset($data)) {
                                // var_dump($data);
                                if (!empty($data[2]) && $poste && !(trim(strtolower($data[1])) == 'code')) {
                                    // Récupérer une dimension
                                    $manager    = new ManagerEvaluationCategorie();
                                    $code = $data[1];
                                    while (strlen($code) > 0) {
                                        $code   = substr($code, 0, strlen($code) - 1);
                                        $categ  = $manager->chercher([
                                            'id_entreprise' => $_SESSION['user']['idEntreprise'],
                                            'code'          => $code
                                        ]);
                                        if ($categ) {
                                            // Récupérer une question depuis la base
                                            $manager    = new ManagerEvaluationQuestionnaire();
                                            $quest      = $manager->chercher([
                                                'id_entreprise' => $_SESSION['user']['idEntreprise'],
                                                'id_categorie'  => $categ->getIdCategorie(),
                                                'libelle'       => str_replace("'","\'",$data[2])
                                            ]);
                                            break;
                                        }
                                    }
                                    if (!empty($data[0])) {
                                        // trait
                                        $indice++;
                                        $indx = 0;
                                        // Affectation d'objet
                                        $manager    = new ManagerEvaluationCategorie();
                                        $parent = $manager->chercher([
                                            'id_categorie'  => $categ->getIdCategorie(),
                                            'id_entreprise' => $_SESSION['user']['idEntreprise']
                                        ]); 
                                        $parent->setDescription('');
                                        $parent->setIdCategorie(0);
                                        $parent->setCode('');
                                        $parent->setLibelle($data[0]);
                                        $resultat[$inc]['data'][$indice] = ['parent' => $parent];
                                        $resultat[$inc]['data'][$indice] = array_merge($resultat[$inc]['data'][$indice],['category' => array() ]) ;
                                    }
                                    if ($quest) {
                                        if (sizeof($resultat[$inc]['data'][$indice]['category']) > 0) {
                                            $compare = false;
                                            foreach ($resultat[$inc]['data'][$indice]['category'] as $key => $value) {
                                                if (isset($value['sousCategories'])) {
                                                    if ($categ->getIdCategorie() == $value['sousCategories']->getIdCategorie()) {
                                                        $compare = true;
                                                        $clef = $key;
                                                        break;
                                                    }
                                                }
                                            }
                                            if (!$compare) {
                                                $indx++;
                                                $resultat[$inc]['data'][$indice]['category'][$indx]['sousCategories'] = $categ;
                                            }
                                        } else {
                                            $resultat[$inc]['data'][$indice]['category'][$indx]['sousCategories'] = $categ;
                                        }
                                        if ($compare) {
                                            $compare = !$compare;
                                            $resultat[$inc]['data'][$indice]['category'][$clef]['questionnaires'][] = $quest;
                                        } else {
                                            $resultat[$inc]['data'][$indice]['category'][$indx]['questionnaires'][] = $quest;
                                        }
                                    }  
                                } elseif (!empty($data[0]) && strtolower(trim(explode(':',$data[0])[0])) == 'poste') { // POSTE
                                    // Récupérer le poste existant
                                    $inc++;
                                    $indice     = -1;
                                    $indx       = -1;
                                    $manager    = new ManagerEntreprisePoste ();
                                    $poste      = $manager->chercher([
                                        'idEntreprise'  => $_SESSION['user']['idEntreprise'],
                                        'poste'         => trim(explode(':',$data[0])[1])
                                    ]);
                                    if ($poste) {
                                        $resultat[$inc]['poste'] = $poste;
                                    } else {
                                        $_SESSION['info']['danger']     = "Le poste" . trim(explode(':',$data[0])[1]) . "n'existe pas encore dans notre base !";
                                    }
                                } elseif (trim(strtolower($data[1])) == 'code') {
                                    // Récupérer les entêtes du point moyenne par question par niveau
                                    if (isset($data[3]) && !empty($data[3])) {
                                        $entete = array();
                                        for ($ind=3; $ind < sizeof($data); $ind++) { 
                                            $entete = array_merge($entete, [$data[$ind]]);
                                        }
                                    }
                                }
                            }                            
                        }


                         // Ajout dans la base le modèle obtenu 
                        $manager = new ManagerEvaluation();
                        foreach ($resultat as $key => $value) {
                            $new = $this->findLast('evaluation', 'id_evaluation')['id'] + 1;
                            if (is_array($value)) {
                                $retour     = $manager->ajouter([
                                    'libelle'               => "Modèle N° {$new} d'évaluation pour poste " . $value['poste']->getPoste(),
                                    'id_entreprise'         => $_SESSION['user']['idEntreprise'],
                                    'id_entreprise_poste'   => $value['poste']->getIdEntreprisePoste(),
                                    'date_creation'         => date("Y-m-d H:i:s"),
                                    'category'              => serialize($value['data'])
                                ]);
                            }
                        }
                        /*echo "<pre>";
                        var_dump($value);
                        exit();*/
                    }
                    fclose($handle);
                    $_SESSION['info']['success']    = "Import fait avec succès .";
                } else {
                    $_SESSION['info']['danger']     = "Import intérrompu .";
                }
            }
        }
        /**
         * Lister la liste du baromètre
         * 
         * @param arary $parameters 
         *
         * @return empty
         */
        public function listerBarometreList($parameters)
        {
            $entreprise = $this->getEntreprise();
            $manager    = new ManagerEntreprisePoste();
            $postes     = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $manager    = new ManagerEmploye();
            $employes   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $manager    = new ManagerEntrepriseService();
            $services   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            if (is_null($parameters)) {
                return [
                    'postesFilter'      => $postes,
                    'employesFilter'    => $employes,
                    'servicesFilter'    => $services
                ];
            } else {
                $manager    = new ManagerBarometreList();
                $barometres = $manager->lister(['id_entreprise' => $entreprise->getIdEntreprise(), 'is_archived' => 'NO']);
                if ($barometres) {
                    foreach ($barometres as $barometre) {
                        $manager    = new ManagerContratEmploye();
                        $contratEmploye = $manager->chercher(['idEmploye' => $barometre->getIdEmploye()]);
                        $manager    = new ManagerServicePoste();
                        $servicePoste = $manager->chercher(['idServicePoste' => $contratEmploye->getIdServicePoste()]);
                        $manager    = new ManagerEntreprisePoste();
                        $servicePoste->setIdEntreprisePoste($manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]));
                        $manager    = new ManagerEntrepriseService();
                        $servicePoste->setIdEntrepriseService($manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]));
                        $contratEmploye->setIdServicePoste($servicePoste);
                        $manager    = new ManagerEmploye();
                        $contratEmploye->setIdEmploye($manager->chercher([
                            'idEntreprise'  => $entreprise->getIdEntreprise(),
                            'idEmploye'     => $barometre->getIdEmploye()
                        ]));
                        $barometre->setIdEmploye($contratEmploye);
                    }
                    $barometres = $this->filterNeed($barometres, $parameters);
                    $donnees = [
                        'barometres'        => $barometres
                    ];
                }
                $view   = new View("listeBarometre");
                $view->sendWithoutTemplate("Backend", "Evaluation", $donnees, "entreprise"); 
                exit();
            }
        }
        /**
         * Récupere la liste du baromètre
         * 
         * @param string $str
         *
         * @return array
         */
        private function getBarometerList ($str) {
            $manager    = new ManagerBarometreList();
            $barom      = $manager->lister([
                'id_entreprise' => $this->getEntreprise()->getIdEntreprise(),
                'id_employe'    => $_SESSION['user']['idEmploye'],
                'is_answered'   => $str
            ]);
            $manager = new ManagerBarometre();
            foreach ($barom as $barometer) {
                $barometer->setIdBarometre($manager->chercher(['id_barometre' => $barometer->getIdBarometre(), 'id_entreprise' => $this->getEntreprise()->getIdEntreprise()]));
            }
            return $barom;
        }
        /**
         * Récupere les filtre du baromètre
         * 
         * @param array $param Le paramètre de filtre
         * @param array $data La donnée à filtrer
         *
         * @return array
         */
        private function filterNeed ($data, $param) {
            extract($param);
            if (!empty($groupe)) {
                $data = $this->filterByGroup($data, $groupe, $id);
            }
            if (!empty($etat)) {
                $data = $this->filterByState($data, $etat);
            }
            if (!empty($periode)) {
                $data = $this->filterBarometerBy($data, $periode, $mois, $debut, $fin, $etat);
            }
            return $data;
        }
        /**
         * Convertir un mois lettre en un entier
         *
         * @param string $date Date à convfertir
         *
         * @return Date 
         */
        private function getDateToInt($date)
        {
            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            foreach ($months as $key => $value) {
                if (mb_strtolower($value) === mb_strtolower(explode(' ', $date)[1])) {
                    return explode(' ', $date)[2] . '-' . ($key + 1) . '-' . explode(' ', $date)[0];
                }
            }
        }
        /** 
         * Filtrer un tableau par rapport au mois
         * 
         * @param array $data Données à filtrer
         * @param int $month Mois filtre
         * @param int $year année filtre
         * 
         * @return array
         */
        private function FilterDataByMonth ($data, $year, $month, $state = null) {
            $filters = array();
            $state == self::FILTER_STATUS_REPLY ? $filter = 'getDateReply' : $filter = 'getDate';
            foreach ($data as $donnee) {
                if (explode('-',self::getDateToInt($donnee->$filter()))[1] == $month
                    && explode('-',self::getDateToInt($donnee->$filter()))[0] == $year) {
                    $filters[] = $donnee;
                }
            }
            return $filters;
        }
        /** 
         * Filtrer un tableau par rapport à la date
         * 
         * @param array $data Données à filtrer
         * @param date $date Date filtre
         * @param date $fin Date interval fin filtre
         * 
         * @return array
         */
        private function FilterDataByDate ($data, $date, $fin = null, $state = null) {
            $filters = array();
            if (is_array($date)) {
                $fin = end($date); 
                $date = $date[0]; 
            }
            $state == self::FILTER_STATUS_REPLY ? $filter = 'getDateReply' : $filter = 'getDate';
            foreach ($data as $donnee) {
                if ($fin) {
                    $dateFilter = self::getDateToInt($donnee->$filter());
                    if ($dateFilter && explode('-', $dateFilter)[1] < 10) {
                        $dateFilter = explode('-', $dateFilter)[0] . '-0' . explode('-', $dateFilter)[1] . '-' . explode('-', $dateFilter)[2];
                    }
                    if ($dateFilter >= $date && $dateFilter <= $fin) {
                        $filters[] = $donnee;
                    }
                } else {
                    if (self::getDateToInt($donnee->$filter()) == $date) {
                        $filters[] = $donnee;
                    }
                }
            }
            return $filters;
        }
        /** 
         * Filtrer le baromètre
         * 
         * @param array $data Données à filtrer
         * @param date $periode Période de la date filtre
         * @param date $mois Mois de la date filtre
         * @param date $debut Début de la date filtre
         * @param date $fin Date interval fin filtre
         * 
         * @return array
         */
        static function filterBarometerBy ($data, $periode = null, $mois = null, $debut = null, $fin = null, $state) {
            if (!empty($data)) {
                $listFilterDate = array();
                if ($mois) {
                    $listFilterDate = self::FilterDataByMonth($data, date('Y'), $mois, $state);
                } elseif ($debut && $fin) {
                    if (strstr($debut, '/')) {
                        $temporary      = explode('/', $debut);
                        $debut          = $temporary[2].'-'.$temporary[1].'-'.$temporary[0];
                    }
                    if (strstr($fin, '/')) {
                        $temporary      = explode('/', $fin);
                        $fin            = $temporary[2].'-'.$temporary[1].'-'.$temporary[0];
                    }
                    $listFilterDate = self::FilterDataByDate($data, $debut, $fin, $state);
                } elseif ($periode) {
                    switch ($periode) {
                        case self::TODAY :
                            $state == self::FILTER_STATUS_REPLY ? $filter = 'getDateReply' : $filter = 'getDate';
                            foreach ($data as $barom) {
                                if (self::getDateToInt($barom->$filter()) == date('Y-m-d')) {
                                    $listFilterDate[] = $barom;
                                }
                            }
                            break;
                        case self::TOMORROW :
                            $date = new DateTime();
                            $date->add(new DateInterval("P1D"));
                            $listFilterDate = self::FilterDataByDate($data, $date->format('Y-m-d'), $state);
                            break;
                        case self::YESTERDAY :
                            $date = new DateTime();
                            $date->sub(new DateInterval("P1D"));
                            $listFilterDate = self::FilterDataByDate($data, $date->format('Y-m-d'), $state);
                            break;
                        case self::LAST_WEEK :
                            $date = new DateTime();
                            $arrayDate = [
                                '0' => date('Y-m-d', strtotime('Monday last week'.$date->format('Y-m-d').'')),
                                '1' => date('Y-m-d', strtotime('Tuesday last week'.$date->format('Y-m-d').'')),
                                '2' => date('Y-m-d', strtotime('Wednesday last week'.$date->format('Y-m-d').'')),
                                '3' => date('Y-m-d', strtotime('Thursday last week'.$date->format('Y-m-d').'')),
                                '4' => date('Y-m-d', strtotime('Friday last week'.$date->format('Y-m-d').'')),
                                '5' => date('Y-m-d', strtotime('Saturday last week'.$date->format('Y-m-d').'')),
                                '6' => date('Y-m-d', strtotime('Sunday last week'.$date->format('Y-m-d').''))
                            ];
                            $listFilterDate = self::FilterDataByDate($data, $arrayDate, $state);
                            break;
                        case self::THIS_WEEK :
                            $date       = new DateTime();
                            $arrayDate  = [
                                '0' => date('Y-m-d', strtotime('Monday this week'.$date->format('Y-m-d').'')),
                                '1' => date('Y-m-d', strtotime('Tuesday this week'.$date->format('Y-m-d').'')),
                                '2' => date('Y-m-d', strtotime('Wednesday this week'.$date->format('Y-m-d').'')),
                                '3' => date('Y-m-d', strtotime('Thursday this week'.$date->format('Y-m-d').'')),
                                '4' => date('Y-m-d', strtotime('Friday this week'.$date->format('Y-m-d').'')),
                                '5' => date('Y-m-d', strtotime('Saturday this week'.$date->format('Y-m-d').'')),
                                '6' => date('Y-m-d', strtotime('Sunday this week'.$date->format('Y-m-d').'')),
                            ];
                            $listFilterDate = self::FilterDataByDate($data, $arrayDate, $state);
                            break;
                        case self::NEXT_WEEK :
                            $date       = new DateTime();
                            $arrayDate  = [
                                '0' => date('Y-m-d', strtotime('Monday next week'.$date->format('Y-m-d').'')),
                                '1' => date('Y-m-d', strtotime('Tuesday next week'.$date->format('Y-m-d').'')),
                                '2' => date('Y-m-d', strtotime('Wednesday next week'.$date->format('Y-m-d').'')),
                                '3' => date('Y-m-d', strtotime('Thursday next week'.$date->format('Y-m-d').'')),
                                '4' => date('Y-m-d', strtotime('Friday next week'.$date->format('Y-m-d').'')),
                                '5' => date('Y-m-d', strtotime('Saturday next week'.$date->format('Y-m-d').'')),
                                '6' => date('Y-m-d', strtotime('Sunday next week'.$date->format('Y-m-d').'')),
                            ];
                            $listFilterDate = self::FilterDataByDate($data, $arrayDate, $state);
                            break;
                        case self::LAST_MONTH :
                            if (date('m') - 1 == 0) {
                                $month  = 12;
                                $year   = date('Y') - 1;
                            } else {
                                $month  = date('m') - 1;
                                $year   = date('Y');
                            }
                            $listFilterDate = self::FilterDataByMonth($data, $year,$month, $state);
                            break;
                        case self::THIS_MONTH :
                            $listFilterDate = self::FilterDataByMonth($data, date('Y'), date('m'), $state);
                            break;
                        case self::NEXT_MONTH :
                            if (date('m') + 1 > 12) {
                                $month  = 1;
                                $year   = date('Y') + 1;
                            } else {
                                $month  = date('m') + 1;
                                $year   = date('Y');
                            }
                            $listFilterDate = self::FilterDataByMonth($data, $year, $month, $state);
                            break;
                        default:
                            $listFilterDate = $data;
                            break;
                    }
                }
                $data = $listFilterDate;
            }
            return $data;
        }

        static function filterByState ($data, $stat) {
            $response = array();
            switch ((int)$stat) {
                case self::FILTER_STATUS_ALL:
                    $response = $data;
                    break;
                case self::FILTER_STATUS_REPLY:
                    foreach ($data as $value) {
                        if ($value->getIsAnswered() == 'YES') {
                            $response[] = $value;
                        }
                    }
                    break;
                case self::FILTER_STATUS_NO_REPLY:
                    foreach ($data as $value) {
                        if ($value->getIsAnswered() == 'NO') {
                            $response[] = $value;
                        }
                    }
                    break;
                default:
                    break;
            }
            return $response;
        }

        static function filterByGroup ($data, $group, $id=null) {
            $response = array();
            switch ($group) {
                case self::FILTER_GROUP_ALL:
                    $response = $data;
                    break;
                case self::FILTER_GROUP_POSTE:
                    foreach ($data as $value) {
                        if ($value->getIdEmploye()->getIdServicePoste()->getIdEntreprisePoste()->getIdEntreprisePoste() == $id) {
                            $response[] = $value;
                        }
                    }
                    break;
                case self::FILTER_GROUP_EMPLOYE:
                    foreach ($data as $value) {
                        if ($value->getIdEmploye()->getIdEmploye()->getIdEmploye() == $id) {
                            $response[] = $value;
                        }
                    }
                    break;
                case self::FILTER_GROUP_SERVICE:
                    foreach ($data as $value) {
                        if ($value->getIdEmploye()->getIdServicePoste()->getIdEntrepriseService()->getIdEntrepriseService() == $id) {
                            $response[] = $value;
                        }
                    }
                    break;
                default:
                    break;
            }
            return $response;
        }
        /**
         * Lister la liste du baromètre
         * 
         * @param arary $parameters 
         *
         * @return empty
         */
        public function listerBarometre($parameters)
        {
            $entreprise = $this->getEntreprise();
            if ($_SESSION['compte']['identifiant'] == 'entreprise') {
                $manager    = new ManagerEmploye();
                $employes   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                $manager    = new ManagerBarometre();
                return [
                    'listes'    => $manager->lister(['id_entreprise' => $entreprise->getIdEntreprise()]),
                    'employes'  => $employes
                ];
            } elseif ($_SESSION['compte']['identifiant'] == 'employe') {
                return [ 'barometres' => $this->getBarometerList($parameters['reply']) ];
            }
        }

        /**
         * Envoyer un baromètre
         * 
         * @param arary $parameters 
         *
         * @return empty
         */
        public function envoyerBarometre($parameters)
        {
            $entreprise     = $this->getEntreprise();
            $manager        = new ManagerBarometre();
            $barometre      = $manager->chercher([
                'id_entreprise' => $entreprise->getIdEntreprise(),
                'id_barometre'  => $parameters['idBarometre']
            ]);
            $manager        = new ManagerEmploye();
            $listIdEmployes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise(), 'idEmploye' => 'IN ('.$parameters['listIdEmploye'].')' ]);
            // Envoyer une notification E-mail aux employés
            $manager = new ManagerBarometreList();
            foreach ($listIdEmployes as $employe) {
                $retour     = $manager->ajouter([
                    'id_barometre' => $barometre->getIdBarometre(),
                    'id_entreprise' => $_SESSION['user']['idEntreprise'],
                    'id_employe' => $employe->getIdEmploye(),
                    'date' => date('Y-m-d'),
                    'contents'  => serialize($barometre->getContents())
                ]);
                if ($retour->getIdBarometreList() != 0) {
                    $to         = $employe->getEmail();
                    $headers[]  = "MIME-Version: 1.0";
                    $headers[]  = 'Content-type: text/html; charset=iso-8859-1';
                    $headers[]  = 'From: ' . $_SESSION['user']['email'];
                    $subject    = "Baromètre du mois" . ucfirst(date('m')) ;
                    $message    = "<html><body>
                                    <div class='container'>
                                        <label>Bonjour " . $employe->getPrenom() . " ,  </label><br><br>
                                        <label>Nous vous invitons de répondre soignieusement tout la série de question au baromètre </label><br><br>
                                        <label>Voici le lien pour répondre le baromètre du mois : <a href=" . HOST . "manage/employe/barometre?idBarometreList=" . $retour->getIdBarometreList() . "></a></label><br><br><br>
                                        <label>Cordialement, </label>
                                        <br><br><br>
                                        <label> L'équipe <a href=" . HOST . ">Human Cart'Office</a></label>
                                    </div>
                                </body></html>";
                    $mail = mail($to, $subject, $message, implode("\r\n", $headers));
                    if ($mail) {
                        $_SESSION['info']['success']    = "E-mail a été bien envoyer avec succès";
                    } else {
                        $_SESSION['info']['danger']     = "Echec de l'envoie E-mail";
                    }
                }
            }
            $this->listerBarometre(null);
        }
        /**
         * Ajout nouveau enregistrement du baromètre
         * 
         * @param arary $parameters 
         *
         * @return empty
         */
        public function ajoutBarometre($parameters)
        {   
            $response = array();
            if (!is_null($parameters['contentForm'])) {
                foreach (explode(',periode:', $parameters['contentForm']) as $period) {
                    if ($period) {
                        $resp = array();
                        foreach (explode(',question:', explode(',questions:', $period)[1]) as $question) {
                            if ($question) {
                                $resp[] = [
                                    'question'  => explode(',choise:',$question)[0],
                                    'choise'    => explode(',', explode(',choise:',$question)[1])
                                ];
                            }
                        }
                        $response[] = [
                            'periode'   => explode(',questions:', $period)[0],
                            'questions' => $resp
                        ];
                    }
                }
                if (!empty($response)) {
                    $manager = new ManagerBarometre();
                    $retour = $manager->ajouter([
                        'libelle'       => $parameters['libelle'],
                        'is_archived'   => 'NO',
                        'contents'      => serialize($response),
                        'id_entreprise' => $_SESSION['user']['idEntreprise']
                    ]);
                    if ($retour->getIdBarometre() != 0) {
                        $_SESSION['info']['success']    = "Enregistrement terminé avec succès";
                    } else {
                        $_SESSION['info']['danger']     = "Echec de l'enregistrement";
                    }
                }
            } else {
                $_SESSION['info']['danger']     = "Une erreur est survenue";
            }
        }
        /**
         * Répondre le baromètre depuis l'empoyé
         * 
         * @param arary $parameters 
         *
         * @return empty
         */
        public function answerBarometre($parameters)
        {
            $inc        = 0;
            $response   = explode(',', $parameters['myResponse']);
            foreach ($response as $key => $value) {
                $choix[] = end(explode('-', explode('flexRadio-', $value)[1]));
            }
            $manager    = new ManagerBarometreList();
            $barometre  = $manager->chercher(['id_barometre_list' => $parameters['idBarometre']]);
            $response   = array();
            foreach ($barometre->getContents() as $keyPeriod => $valPeriod) {
                $resp   = array();
                foreach ($valPeriod['questions'] as $keyQuestions => $valQuestions) {
                    $valQuestions['answer'] = $valQuestions['choise'][$choix[$inc]];
                    // Point par rapport aux choix
                    $point = 0;
                    if (count($valQuestions['choise']) == 5) {
                       $point = 5 - $choix[$inc] ; 
                    } elseif (count($valQuestions['choise']) ==4 ) {
                        $point = 5 - ($choix[$inc] < 2 ? $choix[$inc] : $choix[$inc] + 1);
                    } elseif (count($valQuestions['choise']) == 3){
                        $point = 5 - ($choix[$inc] == 0 ? 0 : $choix[$inc] * 2) ;
                    } elseif (count($valQuestions['choise']) >0 && count($valQuestions['choise']) <=2) {
                        $point = $choix[$inc] + 1;
                    }
                    $valQuestions['point']  = $point;
                    $resp[]                 = $valQuestions; 
                    $valQuestions['point']  = $choix[$inc];
                    $inc++;
                }
                $valPeriod['questions']     = $resp;
                $response[]                 = $valPeriod;
            }
            $response['suggestion']     = $parameters['suggestion'];
            $retour                     = $manager->modifier([
                'id_barometre_list' => $barometre->getIdBarometreList(),
                'date_reply'        => date('Y-m-d H:i:s'),
                'is_answered'       => 'YES',
                'contents'          => serialize($response)
            ]);
            if ($retour->getIdBarometreList() != 0) {
                $_SESSION['info']['success']    = "Votre réponse a été bien envoyée !";
            } else {
                $_SESSION['info']['danger']     = "Erreur a été produite ! </br> Veuillez refaire plus tard .";
            }
        }
        /**
         * Récupérer le baromètre depuis l'Ajax
         * 
         * @param arary $parameters 
         *
         * @return empty
         */
        public function getBarometer($parameters)
        {   
            $manager = new ManagerBarometreList();
            $response = $manager->chercher(['id_barometre_list' => $parameters['idBarometre']]);
            $response->setContents($response->getContents());
            echo json_encode($response->toArray());
            exit();
        }


        /**
         * Retourner le poste d'un employé
         *
         * @param object $employe
         *
         * @return object
         */
        private function getPosteEmploye($employe)
        {
            $manager  = new ManagerContratEmploye();
            $contrat  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            if ($contrat != null) {
                $manager  = new ManagerServicePoste();
                $servicePoste = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                $manager      = new ManagerEntreprisePoste();
                $poste = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
                return $poste;
            }  else {
                return false;
            }
        }

        /**
         * Retourner le service d'un employé
         *
         * @param object $employe
         *
         * @return object
         */
        private function getServiceEmploye($employe)
        {
            $manager  = new ManagerContratEmploye();
            $contrat  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            if ($contrat != null) {
                $manager  = new ManagerServicePoste();
                $servicePoste = $manager->chercher(['idServicePoste' => $contrat->getIdServicePoste()]);
                $manager      = new ManagerEntrepriseService();
                $service = $manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]);
                return $service;
            } else {
                return false;
            }
        }

        /**
         * Dessiner le graphe d'une personne à évaluer
         * 
         * @param arary $parameters Les critères des données du graphe
         *
         * @return empty
         */
        public function drawBarometer($parameters)
        {
            $manager = new ManagerBarometreList();
            $barometre = $manager->chercher(['id_barometre_list' => $parameters['idBarometre']]);
            $manager = new ManagerEmploye();
            $employe = $manager->chercher(['idEmploye' => $barometre->getIdEmploye()]);
            $manager = new ManagerBarometre();
            $barometreEnvoye = $manager->chercher(['id_barometre' => $barometre->getIdBarometre()]);
            $donnees = [
                'employe'           => $employe,
                'barometreEnvoye'   => $barometreEnvoye,
                'barometre'         => $barometre,
                'service'           => $this->getServiceEmploye($employe),
                'poste'             => $this->getPosteEmploye($employe)
            ];
            $view = new View("barometerGraph");
            $view->send("Backend", "Evaluation", $donnees, "entreprise");
            exit();
        }

    }