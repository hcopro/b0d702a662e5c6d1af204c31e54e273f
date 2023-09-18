<?php
    
    /**
     * Manager du module Barometre du Backend
     *
     * @author Lansky
     *
     * @since 14/07/2021 
    */

    use \Core\DbManager;
    use \Core\View;
    use \Core\PublicFonctions;
    use \Model\ManagerEmploye;
    use \Model\ManagerEntreprise;
    use \Model\ManagerMessage;
    use \Model\ManagerContratEmploye;
    use \Model\ManagerCompte;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerServicePoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerMenuEntreprise;
    use \Model\ManagerBarometreList;
    use \Model\ManagerBarometre;
    use \Model\ManagerEmailContact;

    class ManagerModuleBarometre extends DbManager
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
        const PROPOSED                  = 1;
        const YES                       = 1;
        const NO                        = 0;

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
         * Lister la liste du baromètre
         * 
         * @param arary $parameters 
         *
         * @return empty
        */
        public function listerBarometreList($parameters)
        {
            extract($this->getData());
            if (is_null($parameters)) {
                return [
                    'postesFilter'      => $postes,
                    'employesFilter'    => $employes,
                    'servicesFilter'    => $services
                ];
            } else {
                $donnees = [
                    'barometres' => $this->generateBarometer($parameters, $employes)
                ];
                $view   = new View("listeBarometre");
                $view->sendWithoutTemplate("Backend", "Barometre", $donnees, $_SESSION['compte']['identifiant']); 
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
        private function filterNeed($data, $param)
        {
            extract($param);
            if (!empty($groupe)) {
                $data = $this->filterByGroup($data, $groupe);
            }
            if (!empty($etat)) {
                $data = $this->filterByState($data, $etat);
            }
            if (!isset($bebut) || $bebut === '') {
                $debut = null;
            }
            if (!isset($fin) || $fin === '') {
                $fin = null;
            }
            if (!isset($mois) || $mois === '') {
                $mois = null;
            }
            if (!isset($periode) || $periode === '') {
                $periode = null;
            }
            $data = $this->filterBarometerBy($data, $periode, $mois, $debut, $fin, $etat);
            $globalRemark   = array();
            if (!empty($groupe) && ($groupe == self::FILTER_GROUP_POSTE || $groupe == self::FILTER_GROUP_SERVICE)) {
                foreach ($data as $key => $datas) {
                    $temporary      = $datas[0];
                    $lastestDate    = $temporary->getDate();
                    $arrayTmp       = $temporary->getContents();
                    $answered       = 'NO';
                    $nombreAnswered = 0;
                    $suggestion     = "";
                    foreach ($datas as $keys => $value) {
                        if (isset($value->getContents()['suggestion'])) {
                            $suggestion .= "- " . $value->getContents()['suggestion'] . "\r\n";
                        }
                        if ($value->getDateReply()) {
                            $lastestDate = $value->getDateReply() > $lastestDate ? $value->getDateReply() : $lastestDate;
                        }
                        if ($value->getIsAnswered() == 'YES') {
                            $answered       = 'YES';
                            $nombreAnswered++;
                            if ($temporary->getIdBarometreList() != $value->getIdBarometreList()) {
                                foreach ($value->getContents() as $k => $contents) {
                                    if (isset($contents['questions']) && is_int($k)) {
                                        foreach ($contents['questions'] as $ke => $questions) {
                                            if (array_key_exists('point', $questions)) {
                                                if(isset($arrayTmp[$k]['questions'][$ke]['point'])) {
                                                    $arrayTmp[$k]['questions'][$ke]['point'] += array_key_exists('point', $questions) ? $questions['point'] : 0;
                                                    if ($keys == count($datas) - 1) {
                                                        $arrayTmp[$k]['questions'][$ke]['point'] = (int)round($arrayTmp[$k]['questions'][$ke]['point'] / count($datas));
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if (!empty($contents['remarque'])) {
                                        if (!isset($globalRemark['remarque'][$k]['rmq'])) {
                                            $globalRemark['remarque'][$k]['rmq'] = $contents['remarque']['rmq'];
                                            while (is_array($globalRemark['remarque'][$k]['rmq'])) {
                                                $globalRemark['remarque'][$k]['rmq'] = $globalRemark['remarque'][$k]['rmq']['rmq'];
                                            }
                                            if (!isset($globalRemark['remarque'][$k]['answer'])) {
                                                $globalRemark['remarque'][$k]['answer'] = '';
                                            }
                                            $globalRemark['remarque'][$k]['answer']  .= '*' . $contents['remarque']['answer'] . "\r\n";
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $arrayTmp["suggestion"] = nl2br($suggestion);// Ici, on ajout une interprétation à la version suivante si nessecaire
                    $arrayTmp["remarque"]   = $globalRemark;
                    $newId                  = explode(':',$key);
                    unset($newId[0]);
                    $newId                  = array_values($newId);
                    $temporary->setIdBarometreList(implode(':',$newId));
                    $temporary->setContents(serialize($arrayTmp));
                    $temporary->setIdEmploye($nombreAnswered . '/' . count($datas));
                    $temporary->setIsAnswered($answered);
                    $temporary->setDateReply($lastestDate);
                    $data[$key]             = $temporary;
                    $globalRemark = array();
                }
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
        private static function getDateToInt($date)
        {
            $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            if (!is_null($date)) {
                foreach ($months as $key => $value) {
                    if (mb_strtolower($value) === mb_strtolower(explode(' ', $date)[1])) {
                        return explode(' ', $date)[2] . '-' . ($key + 1) . '-' . explode(' ', $date)[0];
                    }
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
        private static function FilterDataByMonth ($data, $year, $month, $state = null) {
            $filters = array();
            $state == self::FILTER_STATUS_REPLY ? $filter = 'getDateReply' : $filter = 'getDate';
            foreach ($data as $key => $datas) {
                foreach ($datas as $donnee) {
                    if ($donnee) {
                        if (self::getDateToInt($donnee->$filter())) {
                            if (explode('-',self::getDateToInt($donnee->$filter()))[1] == $month
                                && explode('-',self::getDateToInt($donnee->$filter()))[0] == $year) {
                                $filters[$key][] = $donnee;
                            }
                        }
                    }
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
            foreach ($data as $key => $datas) {
                foreach ($datas as $donnee) {
                    if ($fin) {
                        $dateFilter = self::getDateToInt($donnee->$filter());
                        if ($dateFilter && explode('-', $dateFilter)[1] < 10) {
                            $dateFilter = explode('-', $dateFilter)[0] . '-0' . explode('-', $dateFilter)[1] . '-' . explode('-', $dateFilter)[2];
                        }
                        if ($dateFilter >= $date && $dateFilter <= $fin) {
                            $filters[$key][] = $donnee;
                        }
                    } else {
                        if (self::getDateToInt($donnee->$filter()) == $date) {
                            $filters[$key][] = $donnee;
                        }
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
        static function filterBarometerBy ($data, $periode = null, $mois = null, $debut = null, $fin = null, $state = 0)
        {
            if ($state == 0) {
                $state = null;
            }
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
                } elseif ($periode != null) {
                    switch ($periode) {
                        case self::TODAY :
                            $state == self::FILTER_STATUS_REPLY ? $filter = 'getDateReply' : $filter = 'getDate';
                            foreach ($data as $donnees) {
                                foreach ($donnees as $barom) {
                                    if (self::getDateToInt($barom->$filter()) == date('Y-m-d')) {
                                        $listFilterDate[] = $barom;
                                    }
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
                        case self::FILTER_STATUS_ALL :
                            $listFilterDate = $data;
                            break;
                        default:
                            break;
                    }
                }
                $data = $listFilterDate;
            }
            return $data;
        }

        static function filterByState ($data, $stat = null) {
            $response = array();
            switch ((int)$stat) {
                case self::FILTER_STATUS_REPLY:
                    
                    foreach ($data as $key =>  $datas) {
                        foreach ($datas as $value) {
                            if ($value->getIsAnswered() == 'YES') {
                                $response[$key][] = $value;
                            }
                        }
                    }
                    break;
                case self::FILTER_STATUS_NO_REPLY:
                    foreach ($data as $key =>  $datas) {
                        foreach ($datas as$value) {
                            if ($value->getIsAnswered() == 'NO') {
                                $response[$key][] = $value;
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
            return $response;
        }

        static function filterByGroup ($data, $group)
        {
            $response = array();
            $datas   = array();
            foreach ($data as $key => $value) {
                if (is_null($value)) {
                    if (($key = array_search($value, $data)) !== false) {
                        unset($data[$key]);
                    }
                }
            }
            $data = array_values($data); // Ordonner les clef du tableau après unset
            if ($group != self::FILTER_GROUP_ALL) {
                foreach ($data as $value) {
                    if ($value) {
                        if ($group == self::FILTER_GROUP_POSTE) {
                            $filter = $value->getIdEmploye()->getIdServicePoste()->getIdEntreprisePoste()->getIdEntreprisePoste() . ':' . $value->getIdEmploye()->getIdServicePoste()->getIdEntreprisePoste()->getPoste();
                            $key = 'idPoste';
                        } else {
                            $filter = $value->getIdEmploye()->getIdServicePoste()->getIdEntrepriseService()->getIdEntrepriseService() . ':' . $value->getIdEmploye()->getIdServicePoste()->getIdEntrepriseService()->getService();
                            $key = 'idService';
                        }
                        $response[$key . ':' . $filter][] = $value;
                    }
                }
            } else {
                $response['toutEmploye'] = $data;
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
            $months         = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            $mois           = $months[date('m') - 1];
            $de             = (mb_substr($mois, 0, 1)== 'A' || mb_substr($mois, 0, 1) == 'O') ? "d'" : 'de ';
            $moisDe         =  $de . $mois;
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
                    'id_barometre'  => $barometre->getIdBarometre(),
                    'id_entreprise' => $_SESSION['user']['idEntreprise'],
                    'id_employe'    => $employe->getIdEmploye(),
                    'date'          => date('Y-m-d'),
                    'contents'      => serialize($barometre->getContents())
                ]);
                if ($retour->getIdBarometreList() != 0) {
                    $mail           = $this->sendMailNotification($employe->getEmail(), $_SESSION['user']['email'], "Baromètre du mois " . $moisDe, $this->generateMailContent($employe, $moisDe, 'entreprise', 0));
                    $mssgContent    = $this->generateMessageContent();
                    $objet          = "Baromètre du mois " . $moisDe;
                    $this->sendMessageNotification($employe->getIdCompte(), $objet, $mssgContent);
                    if ($mail) {
                        $_SESSION['info']['success']    = "Le baromètre a été bien envoyer avec succès";
                    } else {
                        $_SESSION['info']['danger']     = "Echec de l'envoie E-mail";
                    }
                }
            }
            $this->listerBarometre(null);
        }
        /**
         * Générer le contenu d'E-mail
         * 
         * @param object $employe 
         * @param string $month 
         * @param string $sender 
         * @param int    $idBarometer 
         *
         * @return html
        */
        private function generateMailContent($employe = null, $month, $sender, $idBarometer)
        {
            $content = "<html>
                            <body>
                                <div class='container'>";
                // if ($sender == "entreprise") {
                //     $content .= "   <label>Bonjour " . $employe->getCivilite() . " " . $employe->getPrenom() . " ,  </label><br><br>
                //                     <label> Nous vous invitons à répondre soigneusement toute la série de questions à l'évaluation du Congrés.
                //                          </label><br><br>
                //                         <label>
                //                             C'est parti pour l'évaluation du Congrés &ldquo;&rdquo; du mois " . $month . " . Prenez 5 minutes au maximum pour répondre en toute <mark style=&ldquo;background-color: #f9f80d!important;&rdquo;>sincérité</mark> et de manière <mark style=&ldquo;background-color: #f9f80d!important;&rdquo;>anonyme</mark> à quelques petites questions.
                //                          </label> <br><br>
                //                         <label>
                //                             On compte sur vous &#x1F609;
                //                         </label>
                //                         <br><br>
                //                         <label>Clickez <a href=" . HOST . "manage/employe/barometre?reply=NO>ici</a> pour répondre l'évaluation du Congrés'</label><br><br><br>";
                // } else {
                //     $content .= "   <label>Bonjour ,  </label><br><br>
                //                     <label> Nous vous informons que " . $employe->getCivilite() . " " . $employe->getPrenom() . " a répondu toute la série de questions à l'évaluation du Congrés du mois " . $month . " .
                //                     </label>
                //                     <br><br>
                //                     <label>
                //                         a effectué  <a href=" . HOST . "manage/barometre/graph_barometer?idBarometre=" . $idBarometer . ">sa réponse de l'évaluation du Congrés du mois</a>
                //                     </label>
                //                     <br><br><br>";
                // }
                if ($sender == "entreprise") {
                    $content .= "   <label>Bonjour " . $employe->getCivilite() . " " . $employe->getPrenom() . " ,  </label><br><br>
                                    <label> Nous vous invitons à répondre soigneusement toute la série de questions au baromètre.
                                         </label><br><br>
                                        <label>
                                            C'est parti pour le baromètre &ldquo;<span><strong><em>Happy Place To Work</em></strong></span>&rdquo; du mois " . $month . " . Prenez 5 minutes au maximum pour répondre en toute <mark style=&ldquo;background-color: #f9f80d!important;&rdquo;>sincérité</mark> et de manière <mark style=&ldquo;background-color: #f9f80d!important;&rdquo;>anonyme</mark> à quelques petites questions.
                                         </label> <br><br>
                                        <label>
                                            On compte sur vous &#x1F609;
                                        </label>
                                        <br><br>
                                        <label>Clickez <a href=" . HOST . "manage/employe/barometre?reply=NO>ici</a> pour répondre le baromètre</label><br><br><br>";
                } else {
                    $content .= "   <label>Bonjour ,  </label><br><br>
                                    <label> Nous vous informons que " . $employe->getCivilite() . " " . $employe->getPrenom() . " a répondu toute la série de questions au baromètre du mois " . $month . " .
                                    </label>
                                    <br><br>
                                    <label>
                                        a effectué  <a href=" . HOST . "manage/barometre/graph_barometer?idBarometre=" . $idBarometer . ">sa réponse du baromètre du mois</a>
                                    </label>
                                    <br><br><br>";
                }
            $content .="            <label>Cordialement, </label>
                                    <br><br><br>
                                    <label> L'équipe <a href='https://hco.mg/'>Human Cart'Office</a></label>
                                </div>
                            </body>
                        </html>";
            return $content;
        }
        /**
         * Envoyer la notification d'E-mail
         * 
         * @param object $employe 
         * @param string $month 
         * @param string $sender 
         * @param int    $idBarometer 
         *
         * @return html
        */
        private function sendMailNotification ($to, $from=null, $subject=null, $contentMail)
        {
            if (is_null($from)) {
                $manager    = new ManagerEmailContact();
                $from       = $manager->chercher(['type' => 'information'])->getEmail();
            }
            $headers[]  = "MIME-Version: 1.0";
            $headers[]  = 'Content-type: text/html; charset=iso-8859-1';
            $headers[]  = 'From: ' . $from;
            return mail($to, $subject, $contentMail, implode("\r\n", $headers));
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
            /**@changelog [EVOL] (Lansky) 30/06/2022 Ajouter fichier image baromètre */
            $referer    = $_SERVER["HTTP_REFERER"];
            $folderPath = DOC_ROOT. 'Ressources/images/' . end(explode('/', $referer)) . 's/';
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            foreach ($_FILES as $field => $value) {
                if (!empty($_FILES[$field]['name'])) {
                    $fieldName  = $field . "_" . time() . ".png";
                    $target     = DOC_ROOT. 'Ressources/images/' . end(explode('/', $referer)) . 's/' . basename($_FILES[$field]['name']);
                    move_uploaded_file( $_FILES[$field]['tmp_name'], $target);
                    rename($target, $folderPath . $fieldName);
                    $parameters[$field] = $fieldName;
                    $parameters['contentForm'] = str_replace("C:\\fakepath\\" . $_FILES[$field]['name'], $fieldName, $parameters['contentForm']);
                }
            }
            $response = array();
            if (!is_null($parameters['contentForm'])) {
                foreach (explode(',class:', $parameters['contentForm']) as $classify) {
                    if ($classify) {
                        $resp = array();
                        $tmpClass = explode(',questions:', $classify)[0];
                        $remarque = end(explode(',remarque:',(explode(',questions:', $classify)[1])));
                        $globalQuestion = explode(',remarque:',(explode(',questions:', $classify)[1]))[0];
                        foreach (explode(',question:', $globalQuestion) as $question) {
                            if ($question) {
                                $tmpQuest   = explode(',choise:',$question)[0];
                                $tmpChoise  = explode(',', explode(',image:', end(explode(',choise:',$question)))[0]);
                                $image      = end(explode(',image:', end(explode(',choise:',$question))));
                                $resp[] = [
                                    'question'  => $tmpQuest,
                                    'choise'    => $tmpChoise,
                                    'image'     => $image
                                ];
                            }
                        }
                        $response[] = [
                            'class'     => $tmpClass,
                            'questions' => $resp,
                            'remarque'  => $remarque
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
            $remarques = array();
            foreach (explode(',', $parameters['remarque']) as $value) {
                if (!empty($value)) {
                    $tmpVal             = explode(':', $value);
                    $tmpKey             = $tmpVal[0];
                    $tmpKey             = strval($tmpKey);
                    $remarques[$tmpKey] = end($tmpVal);
                }
            }
            $inc        = 0;
            $response   = explode(',', $parameters['myResponse']);
            foreach ($response as $key => $value) {
                $realChoise = explode('-', explode('flexRadio-', $value)[1]);
                $choix[]    = end($realChoise);
            }
            $manager    = new ManagerBarometreList();
            $barometre  = $manager->chercher(['id_barometre_list' => $parameters['idBarometre']]);
            $response   = array();
            $keyRmq     = 0;
            foreach ($barometre->getContents() as $keyPeriod => $valPeriod) {
                $resp   = array();
                foreach ($valPeriod['questions'] as $keyQuestions => $valQuestions) {
                    $valQuestions['answer'] = $valQuestions['choise'][$choix[$inc]];
                    // Point par rapport aux choix
                    $point = self::setPointOfAnswer($valQuestions['choise'], $choix[$inc], $valQuestions['answer']);
                    $valQuestions['point']  = $point;
                    $resp[]                 = $valQuestions; 
                    $valQuestions['point']  = $choix[$inc];
                    $inc++;
                }
                $valPeriod['questions']     = $resp;
                if (isset($remarques[strval($keyPeriod)])) {
                    $valPeriod['remarque']  = ['rmq' => $valPeriod['remarque'], 'answer' => $remarques[strval($keyPeriod)]];
                }
                $response[]                 = $valPeriod;
            }
            $response['suggestion']     = $parameters['suggestion'];
            $retour                     = $manager->modifier([
                'id_barometre_list' => $barometre->getIdBarometreList(),
                'date_reply'        => date('Y-m-d H:i:s'),
                'is_answered'       => 'YES',
                'contents'          => serialize($response)
            ]);
            if (!isset($moisDe) || is_null($moisDe)) {
                $months         = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                $mois           = $months[date('m') - 1];
                $de             = (mb_substr($mois, 0, 1)== 'A' || mb_substr($mois, 0, 1) == 'O') ? "d'" : 'de ';
                $moisDe         =  $de . $mois;
            }
            $mssgContent    = $this->generateMessageContent($retour->getIdBarometreList());
            $objet          = "La réponse du baromètre";
            $manager        = new ManagerEntreprise();
            $entreprise     = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $this->sendMessageNotification($entreprise->getIdCompte(), $objet, $mssgContent);
            // Envoyer notification aux supérieurs hierarchique
            $manager        = new ManagerEmploye();
            $tmpEmploye     = $manager->chercher(['idEmploye' => $_SESSION['user']['idEmploye']]);
            $contentMail    = $this->generateMailContent($tmpEmploye, $moisDe, 'employe', $barometre->getIdBarometreList());
            $this->sendMailNotification($entreprise->getEmail(), null, $objet, $contentMail);
            while ($tmpEmploye->getChefHierarchique() != self::NO) {
                $chef       = $manager->chercher(['idEmploye' => $tmpEmploye->getChefHierarchique()]);
                $this->sendMessageNotification($chef->getIdCompte(), $objet, $mssgContent);
                $this->sendMailNotification($chef->getEmail(), null, $objet, $contentMail);
                $tmpEmploye = $chef;
            }
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
            $response = $manager->chercher(['id_barometre_list' => (int)$parameters['idBarometre']]);
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
        private function getPosteEmploye($employe, $statut = null)
        {
            $manager  = new ManagerContratEmploye();
            if ($statut) {
                $contrat  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => self::VALIDATED]);
            } else {
                $contrat  = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
            }
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
        private function getServiceEmploye($employe, $statut = null)
        {
            $manager  = new ManagerContratEmploye();
            if ($statut) {
                $contrat  = $manager->chercher(['idEmploye' => $employe->getIdEmploye(), 'statut' => $statut]);
            } else {
                $contrat  = $manager->chercher(['idEmploye' => $employe->getIdEmploye()]);
            }
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
            $managerBarometreList   = new ManagerBarometreList();
            $managerEmploye         = new ManagerEmploye();
            $managerBarometre       = new ManagerBarometre();
            if (empty($parameters['content'])) {
                $barometre          = $managerBarometreList->chercher(['id_barometre_list' => $parameters['idBarometre']]);
                $employe            = $managerEmploye->chercher(['idEmploye' => $barometre->getIdEmploye()]);
                $barometreEnvoye    = $managerBarometre->chercher(['id_barometre' => $barometre->getIdBarometre()]);
                $lastThreeBarometer = $managerBarometreList->lastThreeBarometer($barometre->getIdEmploye(), $barometre->getIdBarometre(), $barometre->getIdBarometreList(), $_SESSION['user']['idEntreprise'], 2);
                $lastThreeRender    = [];
                // $dateObj = date($barometre->getDate()); // Créer un objet DateTime en utilisant createFromFormat()
                // $formattedDate = $dateObj->format('d/m/Y'); // Formater la date en utilisant format()
                $dates              = [$barometre->getDate()];
                if ($parameters['show'] == 'reduce' && count($barometre->getContents()) > 3) {
                    $barometre->setContents(serialize(self::getMoyenByGroup($barometre)));
                    foreach ($lastThreeBarometer as $value) {
                        $value->setContents(serialize(self::getMoyenByGroup($value)));
                    }
                }
                foreach ($lastThreeBarometer as $render) {
                    // $dateObj = date($render->getDate()); // Créer un objet DateTime en utilisant createFromFormat()
                    // $formattedDate = $dateObj->format('d/m/Y'); // Formater la date en utilisant format()
                    $dates[]            = $render->getDate();
                    $lastThreeRender[]  = $render->getContents();
                }
                $donnees = [
                    'employe'           => $employe,
                    'barometreEnvoye'   => $barometreEnvoye,
                    'barometre'         => $barometre,
                    'service'           => $this->getServiceEmploye($employe),
                    'poste'             => $this->getPosteEmploye($employe),
                    'show'              => isset($parameters['show']) ? $parameters['show'] : 'all',
                    'lastThreeRender'   => $lastThreeRender,
                    'dates'             => $dates
                ];

                $donnees = array_merge($donnees, self::generateDataView($donnees['barometre']));
            } else {
                // Créer un objet du barometreList
                // $parameters['content'] = $_SESSION[$parameters['content']];
                extract($parameters);
                $content            = unserialize(urldecode($content));
                $barometre          = $managerBarometreList->initialiser($content);
                $barometreEnvoye    = $managerBarometre->chercher(['id_barometre' => $barometre->getIdBarometre()]);
                $donnees            = [
                    'barometreEnvoye'   => $barometreEnvoye,
                    'barometre'         => $barometre,
                    'equipe'            => $idBarometre
                ];
            }
            $view = new View("barometerGraph");
            $view->send("Backend", "Barometre", $donnees, "");
            exit();
        }

        /**
         * Envoyer un message de notification à un utilisateur
         *
         * @param int    $idCompte l'identifiant de l'utilisateur
         * @param string $objet    l'objet du message
         * @param string $contenu  le contenu du message
         *
         * @return int
        */
        private function sendMessageNotification($idCompte, $objet, $contenu)
        {
            $today    = date('Y-m-d');
            $manager  = new ManagerMessage();
            $message  = $manager->ajouter([
                'idCompte'  => $idCompte,
                'objet'     => $objet,
                'contenu'   => $contenu,
                'date'      => $today,
                'statut'    => self::PROPOSED,
                'aFaire'    => self::NO
            ]);
            return $message->getIdMessage();
        }
        /**
         * Générer un contenu de message de notification
         *
         * @param string  $type   le type de notification
         * @param object  $object l'objet en question
         *
         * @return string
        */
        private function generateMessageContent($idBarometer = null)
        {
            $months         = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            $mois           = $months[date('m') - 1];
            $de             = (mb_substr($mois, 0, 1)== 'A' || mb_substr($mois, 0, 1) == 'O') ? 'd&#039' : 'de ';
            $moisDe         =  $de . $mois;
            
            // if ($_SESSION['compte']['identifiant'] == 'employe') {
            //     $content    = "<p>Bonjour, </p>";
            //     $content    .= "<p>" .
            //                     "Nous vous informons que " . $_SESSION['user']['civilite'] . " " . $_SESSION['user']['nom'] ." " . $_SESSION['user']['prenom'] . " a effectué  <a href=" . HOST . "manage/barometre/graph_barometer?idBarometre=" . $idBarometer . ">sa réponse du baromètre du mois</a>
            //                     <br><br>";
            // } else {
            //     $content    = "<p>Bonjour à tous, </p>";
            //     $content .= "<p>" .
            //                     "C'est parti pour l' <a href=" . HOST . "manage/employe/barometre?reply=NO>évaluation du Congrés</a> &ldquo;&rdquo; du mois " . $moisDe . " . Prenez 5 minutes pour répondre en toute <mark style=&ldquo;background-color: #f9f80d!important;&rdquo;>sincérité</mark> et de manière <mark style=&ldquo;background-color: #f9f80d!important;&rdquo;>anonyme</mark> à quelques petites questions.
            //                     <br><br>
            //                     On compte sur vous &#x1F609;
            //                     <br><br>
            //                     Bonne continuation !!!
            //                 </p>";
            // }
            if ($_SESSION['compte']['identifiant'] == 'employe') {
                $content    = "<p>Bonjour, </p>";
                $content    .= "<p>" .
                                "Nous vous informons que " . $_SESSION['user']['civilite'] . " " . $_SESSION['user']['nom'] ." " . $_SESSION['user']['prenom'] . " a effectué  <a href=" . HOST . "manage/barometre/graph_barometer?idBarometre=" . $idBarometer . ">sa réponse du baromètre du mois</a>
                                <br><br>";
            } else {
                $content    = "<p>Bonjour à tous, </p>";
                $content .= "<p>" .
                                "C'est parti pour le <a href=" . HOST . "manage/employe/barometre?reply=NO>baromètre</a> &ldquo;<span><strong><em>Happy Place To Work</em></strong></span>&rdquo; du mois " . $moisDe . " . Prenez 5 minutes pour répondre en toute <mark style=&ldquo;background-color: #f9f80d!important;&rdquo;>sincérité</mark> et de manière <mark style=&ldquo;background-color: #f9f80d!important;&rdquo;>anonyme</mark> à quelques petites questions.
                                <br><br>
                                On compte sur vous &#x1F609;
                                <br><br>
                                Bonne continuation !!!
                            </p>";
            }
            return $content;
        }
        /**@changelog 24/04/2023 [OPT] (Lansky) Afficher de façon linéaire le résultat du baromètre et importer en CSV */
        /**
         * Envoyer un message de notification à un utilisateur
         *
         * @param int    $idCompte l'identifiant de l'utilisateur
         * @param string $objet    l'objet du message
         * @param string $contenu  le contenu du message
         *
         * @return int
         */
        public function listerLinearAnswersBarometer($parameters)
        {
            extract($this->getData());
            if (is_null($parameters)) {
                return [
                    'postesFilter'      => $postes,
                    'employesFilter'    => $employes,
                    'servicesFilter'    => $services
                ];
            } else {
                extract($this->generateDataLinearBarometer($parameters, $employes));
                $view   = new View("listeBarometre");
                $view->sendWithoutTemplate("Backend", "Barometre", array('headers' => $headers, 'header' => $header, 'bodyTable' => $output, 'dates' => $dates), $_SESSION['compte']['identifiant']);
                exit();
            }
        }

        /**
         * Exporter les données du baromètre en ligne
         *
         * @param array $parameters
         * @param array $employes
         *
         * @return array
        */
        public function exportToCsv($parameters)
        {
            if (!$parameters) {
                $parameters = $_SESSION['filters'];
            }
            extract($this->getData());

            extract($this->generateDataLinearBarometer($parameters, $employes));
            // Définir les en-têtes de contrôle du cache pour empêcher la mise en cache
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Content-Type: application/csv', true); // Définir les en-têtes HTTP pour télécharger le fichier
            header('Content-Disposition: attachment; filename="' . $output[array_key_last($output)][0] . ' baromètre.csv"');
            $handle = fopen('php://output', 'w'); // Vider le tampon de sortie pour lancer le téléchargement
            foreach ($output as $row) {
                fputcsv($handle, $row);
            }
            fpassthru($handle);
            fclose($handle);
            die;
        }

        /**
         * Générer les données du baromètre en ligne
         *
         * @param array $parameters
         * @param array $employes
         *
         * @return array
        */
        private function generateDataLinearBarometer($parameters, $employes)
        {
            $managerBarometre = new ManagerBarometre();
            $barometres = $this->generateBarometer($parameters, $employes);
            $header     = array();
            $headers    = array();
            $dates      = array();
            $individual = false;
            usort($barometres, 'self::compareBarometerEmployees'); // Trie le baromètre par nom et prénom du salarié
            if (array_key_first($barometres) == 'toutEmploye') {
                $barometres = $barometres[array_key_first($barometres)];
                $individual = true;
            }
            // En attente d'évolution ou un retour, on poursuivis cette méthode prendre les entête et filtre ainsi que le résultat par le barometre même identifiant
            foreach ($barometres as $barometre) {
                $barometreRank[$barometre->getIdBarometre()][] = $barometre;
                $questBarometer = $managerBarometre->chercher(['id_barometre'=>$barometre->getIdBarometre()]);
                $headers[$questBarometer->getIdBarometre()] = self::getAllQuestionBarometer($questBarometer);
                $selectOptions[] = ['id' => $questBarometer->getIdBarometre(), 'name' => $questBarometer->getLibelle()];
            }

            foreach ($barometreRank as $idBarometer => $objectBarom){
                foreach ($objectBarom as $index => $barometre) {
                    $answers    = array();
                    $effectif   = 0;
                    $average    = 0;
                    $feel       = '';
                    $label      = '';
                    if (!in_array($barometre->getContents()[0]['questions'][0]['question'], $headers)) {
                        $headers[$barometre->getDate()]  = $barometre->getContents()[0]['questions'][0]['question'];
                        $dates[]    = $barometre->getDate();
                    }
                    $key = array_search($barometre->getContents()[0]['questions'][0]['question'], $headers);
                    if ($barometre->getIsAnswered() === 'YES') {
                        foreach ($barometre->getContents() as $clef => $contents) {
                            if (isset($contents['questions'])) {
                                foreach ($contents["questions"] as $value) {
                                    $average    += $value['point'] ? $value['point'] : 0;
                                    $effectif++;
                                    if (!array_key_exists($key, $header)) {
                                        $header[$key][] = $value['question'];
                                    } else {
                                        if (!in_array($value['question'], $header[$key])) {
                                            $header[$key][] = $value['question'];
                                        }
                                    }
                                    $answers[]  = isset($value['point']) ? $value['point'] : '-';
                                }
                                if ($average > 0) {
                                    $average = $average / $effectif;
                                    if (($average) >= 3.5) {
                                        $feel   = html_entity_decode('&#x1F604;', ENT_QUOTES, 'UTF-8');
                                        $label  = 'Smile';
                                    } elseif (($average) < 1.5) {
                                        $feel   = html_entity_decode('&#x1F61E;', ENT_QUOTES, 'UTF-8');
                                        $label  = 'Bad';
                                    } else {
                                        $feel   = html_entity_decode('&#x1F610;', ENT_QUOTES, 'UTF-8');
                                        $label  = 'Pipe';
                                    }
                                } else {
                                    $feel   = '-';
                                    $label  = 'Aucun';
                                }
                            } elseif ($clef == 'suggestion') {
                                $suggest = $contents;
                            }
                        }
                        if ($individual) {
                            $firstName  = $barometre->getIdEmploye()->getIdEmploye()->getPrenom() ;
                            $lastName   = $barometre->getIdEmploye()->getIdEmploye()->getNom() ;
                            $tabTmp     = array($barometre->getDate(), $firstName, $lastName);
                        } else {
                            $tmpName    = explode(':', $index);
                            $tabTmp     = array($barometre->getDate(), end($tmpName));
                        }
                        array_push($answers, $feel ? $feel : '-', !empty($suggest) ? $suggest : '-');
                        $donnees[$key][] = array_merge($tabTmp, $answers);
                    }
                }
            }
            foreach ($header as $key => $head) {
                if ($individual) {
                    array_unshift($head ,'Date' ,'Nom'  ,'Prénoms');
                } else {
                    $tab        = explode(':', array_key_first($barometres));
                    $additional = substr($tab[0], 2); // On supprime les deux premières lettres dans le string 
                    array_unshift($head ,'Date' , $additional);
                }
                array_push($head, 'Émotion', "Suggestion ou demande particulières");
                foreach ($head as $k => $val) {
                    if ($this->changeManualString(strtolower($val))) {
                        $head[$k] = $this->changeManualString(strtolower($val));
                    }
                }
                $header[$key] = $head;
            }
            $output = array();
            $shownKey = $parameters['dateReceive'] ? $parameters['dateReceive'] : ($dates ? $dates[0] : '0000-00-00');
            if (isset($donnees[$shownKey])) {
                $output = $donnees[$shownKey];
                // $output = $donnees[array_key_last($dates)];
            }
            array_unshift($output, array_key_exists($shownKey, $header) ? $header[$shownKey] : ($header ? $header[array_key_first($header)] : array())); // Ajout l'entête en premier du tableau
            return array('headers' => $headers, 'header' => $header, 'output' => $output, 'dates' => $dates);
        }

        /**
         * Générer le baromètre selon le filtre
         *
         * @param array $parameters
         * @param array $employes
         *
         * @return array
        */
        private function generateBarometer($parameters, $employes)
        {
            $entreprise             = $this->getEntreprise();
            $barometres             = [];
            $_SESSION['filters']    = array(
                'groupe'    => isset($parameters["groupe"]) ? $parameters["groupe"] : '',
                'periode'   => isset($parameters["periode"]) ? $parameters["periode"] : '',
                'debut'     => isset($parameters["debut"]) ? $parameters["debut"] : '',
                'fin'       => isset($parameters["fin"]) ? $parameters["fin"] : '',
                'mois'      => isset($parameters["mois"]) ? $parameters["mois"] : '',
                'etat'      => isset($parameters["etat"]) ? $parameters["etat"] : self::FILTER_STATUS_ALL,
                'offset'    => array_key_exists("offset", $_SESSION['filters']) ? $_SESSION['filters']["offset"] : '1'
            );
            $manager    = new ManagerBarometreList();
            foreach ($employes as $employe) {
                $tmpList = $manager->lister([
                    'id_entreprise' => $entreprise->getIdEntreprise(),
                    'is_archived'   => 'NO',
                    'id_employe'    => $employe->getIdEmploye()
                ]);
                if (count($tmpList) > 1) {
                    foreach ($tmpList as $tmp) {
                        $barometres[] = $tmp;
                    }
                } else if (count($tmpList) == 1) {
                    $barometres[] = $tmpList[0];
                }
            }
            if ($barometres) {
                foreach ($barometres as $barometre) {
                    if ($barometre) {
                        $manager        = new ManagerContratEmploye();
                        $contratEmploye = $manager->chercher(['idEmploye' => $barometre->getIdEmploye()]);
                        if ($contratEmploye) {
                            $manager        = new ManagerServicePoste();
                            $servicePoste   = $manager->chercher(['idServicePoste' => $contratEmploye->getIdServicePoste()]);
                            $manager        = new ManagerEntreprisePoste();
                            $servicePoste->setIdEntreprisePoste($manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]));
                            $manager        = new ManagerEntrepriseService();
                            $servicePoste->setIdEntrepriseService($manager->chercher(['idEntrepriseService' => $servicePoste->getIdEntrepriseService()]));
                            $contratEmploye->setIdServicePoste($servicePoste);
                            $manager        = new ManagerEmploye();
                            $contratEmploye->setIdEmploye($manager->chercher([
                                'idEntreprise'  => $entreprise->getIdEntreprise(),
                                'idEmploye'     => $barometre->getIdEmploye()
                            ]));
                            $barometre->setIdEmploye($contratEmploye);
                        }
                    }
                }
            }
            return $this->filterNeed($barometres, $parameters);
        }
        /**
         * Récupérer les données  du filtre
         *
         * @param NULL
         * 
         * @return array
        */
        private function getData()
        {
            $entreprise = $this->getEntreprise();
            $manager    = new ManagerEntreprisePoste();
            $postes     = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            /**@changelog 16/05/2022 [OPT] (Lansky) Partager aux supérieurs le baromètre du collaborateurs */
            $manager    = new ManagerEmploye();
            if ($_SESSION['compte']['identifiant'] == 'entreprise') {
                $employes   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            } else {
                $factory    = new PublicFonctions();
                $employes   = $factory->listOfMyTeam()['subordonnes'];

            }
            $manager    = new ManagerEntrepriseService();
            $services   = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            if (!isset($_SESSION['filters'])) {
                $_SESSION['filters'] = [
                    'periode'   => self::FILTER_STATUS_ALL,
                    'groupe'    => self::FILTER_GROUP_ALL,
                    'etat'      => self::FILTER_STATUS_ALL
                ];
            }
            if (!array_key_exists('periode', $_SESSION['filters'])) {
                $_SESSION['filters']['periode'] = self::FILTER_STATUS_ALL;
            }
            if (!array_key_exists('groupe', $_SESSION['filters'])) {
                $_SESSION['filters']['groupe'] = self::FILTER_GROUP_ALL;
            }
            if (!array_key_exists('etat', $_SESSION['filters'])) {
                $_SESSION['filters']['etat'] = self::FILTER_STATUS_ALL;
            }
            if (!array_key_exists('offset', $_SESSION['filters'])) {
                $_SESSION['filters']['offset'] = '1';
            }
            return array('postes' => $postes, 'employes' => $employes, 'services' => $services);
        }

        /**
         * Changer en manuel une chaîne de caractère
         *
         * @param string $str La chaîne de caractère à voir sa correspondance
         *
         * @return string
        */
        private function changeManualString($str) {
            $find       = "";
            $findArray  = explode(" ", $str);
            $correspondence = [
                "Alignement Vision"                         => "orientations stratégiques",
                "Bien-être"                                 => "comment ça va en ce moment",
                "Bien-être."                                => "comment vas-tu aujourd'hui",
                "Espace de travail"                         => "mon cadre de travail me permet de bien travailler",
                "Relations collaborateurs"                  => "la relation avec mes collègues est enthousiasmante",
                "Perspectives Evolution"                    => "je suis satisfait(e) de mes perspectives d'évolution",
                "Autonomie"                                 => "je me sens suffisamment autonome dans ma mission",
                "Influence"                                 => "je me sens influent(e) dans la vie de l'agence",
                "Fierté d'appartenance"                     => "fier(e) de travailler",
                "Reconnaissance/Feedback"                   => "je me sens reconnu(e) grâce aux retours que l'on peut me faire au travail",
                "Relation avec Manager"                     => "j'apprécie la relation que j'ai avec mon dirigeant",
                "Toutes mes compétences étaient mobilisées" => "j’ai eu le sentiment que toutes mes compétences étaient mobilisées",
                "Mes outils de travail étaient"             => "mes outils de travail étaient performants",
                "Mon environnement de travail était"        => "mon environnement de travail était agréable",
                "Mon manager et moi"                        => "mon manager a été à mon écoute et a été est accessible",
                "Demande particulières"                     => "suggestion ou demande particulières",
                "Fierté d'appartenance"                     => "je suis fier(e) de travailler pour mon agence",
                "Espace de travail."                        => "mon cadre de travail me permet de bien travailler (environnement, outils, matériels…)",
                "Alignement Vision."                        => "je partage les orientations stratégiques de ma marque"
            ];
            foreach ($correspondence as $key => $string) {
                $stringArray    = explode(" ", $string);
                $commonWords    = array_intersect($stringArray, $findArray);
                if (count($commonWords) == count($findArray)) {
                    $find = $key;
                    break;
                }
            }
            // return array_search($str, $correspondence);
            return $find;
        }

        /**
         * Assigner un point
         * 
         * @changeLog [EVOL] (Lansky) Ajout de la méthode
         * 
         * @param arary $choise     Choix à proposer 
         * @param int $answer       Reponse donnée
         * @param arary $extrem     Sur nombrable ne pas convertir sa valeur
         *
         * @return int
        */
        private static function setPointOfAnswer($choise, $answer, $extrem)
        {
            $point = 0;
            if (count($choise) == 5) {
               $point = 5 - $answer ; 
            } elseif (count($choise) ==4 ) {
                $point = 5 - ($answer < 2 ? $answer : $answer + 1);
            } elseif (count($choise) == 3){
                $point = 5 - ($answer == 0 ? 0 : $answer * 2) ;
            } elseif (count($choise) >0 && count($choise) <=2) {
                $point = $answer + 1;
            } elseif (count($choise) > 5) {
                if (intval($extrem)) {
                    $point = intval($extrem);
                }
            }
            return $point;
        }

        /**
         * Effectuer le calcul de la moyenne de reponse
         *
         *  @changeLog 2023-07-24 [EVOL] (Lansky) Ajout de la méthode  
         * 
         * @param object $barometre
         *
         * @return object
        */
        private static function getMoyenByGroup($barometre)
        {
            // $response = [];
            foreach ($barometre->getContents() as $key => $groupe) {
                // echo "<pre>";
                // var_dump($groupe);
                // exit;
                if (is_array($groupe)) {
                    $somme      = 0;
                    $questions  = $groupe['questions'];
                    foreach ($questions as $indx => $question) {
                        $somme += $question['point'];
                        if (count($questions) - 1 == $indx) {
                            $moyenne    = (int)round($somme / count($questions));
                            $indice     = count($question['choise']) <= 5 ? 5 - $moyenne : $moyenne;
                            $response[] = [
                                'class'     => array_key_exists('class', $groupe) ? $groupe['class'] : $groupe['periode'],
                                'remarque'  =>  array_key_exists('remarque', $groupe) ? $groupe['remarque'] : "",
                                'questions' => [
                                    'answer'    => $question['choise'][$indice],
                                    'point'     => $moyenne,
                                    'question'  => array_key_exists('class', $groupe) ? $groupe['class'] : $groupe['periode'],
                                    'choise'    => $question['choise'],
                                    'image'     => ''
                                ]
                            ];
                        }
                    }
                } else {
                    $response[$key] = $groupe;
                }
            }
            return $response;
        }

        /**
         * Générer les données trasportées au DOM
         *
         *  @changeLog 2023-07-24 [EVOL] (Lansky) Ajout de la méthode  
         * 
         * @param object $barometre
         *
         * @return array
        */
        private static function generateDataView($barometre)
        {
            $moyenne  = 0;
            $nombre   = 0;
            $dataQuestions    = array();
            $remarqueContents  = array();
            foreach ($barometre->getContents() as $vals) {
                if (is_array($vals) && isset($vals['questions'])) {
                    if (is_int(array_key_first($vals['questions']))) {
                        foreach ($vals['questions'] as $value) {
                            $dataQuestions[] = $value;
                            extract($value);
                            $moyenne+= $point;
                            $nombre++;
                        }
                    } else {
                        $dataQuestions[] = $vals['questions'];
                        $moyenne+= $vals['questions']['point'];
                        $nombre++;
                    }
                }
                if (!empty($vals['remarque'])) {
                    if (!isset($vals['class'])) {
                        foreach ($vals['remarque'] as $rmk) {
                            $result = array_search($rmk['rmq'], array_column($remarqueContents, 'classify'));
                            if ($result) {
                                $addAnswer = '<br>* ' . $rmk['answer'];
                                $remarqueContents[$result]['remarque'] .=  nl2br($addAnswer);
                            } else {
                                $remarqueContents[] = ['classify' => $rmk['rmq'], 'remarque' => $rmk['answer']];
                            }
                        }
                    } else {
                        $lib = $vals['remarque']['rmq'];
                        $resp = $vals['remarque']['answer'];
                        $remarqueContents[] = [
                            'classify'  => $lib,
                            'remarque'  => $resp
                        ];
                    }
                }
            }
            if ($nombre > 0) {
                if (($moyenne/$nombre) >= 3.5) {
                    $img = 'happySmiley.png';
                    $label= 'Smile';
                } elseif (($moyenne/$nombre) < 1.5) {
                    $img = 'sadSmiley.png';
                    $label = 'Bad';
                } else {
                    $img = 'pipeSmiley2.jpg';
                    $label = 'Pipe';
                }
            } else {
                $img    = 'sadSmiley.png';
                $label  = 'Bad';
            }
            $images = [
                'bien_etre.png',
                'espace_de_travail.png',
                'relations_collaborateurs.png',
                'perspectives.png',
                'autonomie.png',
                'influence.png',
                'fièreté.png',
                'reconnaissance.png',
                'relation_avec_manager.png',
                'vision.png'
            ];
            $color = [
                '216,180,217',
                '252,213,210'
                // '#fcd5d2'
            ];
            // echo "<pre>";
            //       var_dump($dataQuestions);
            //       var_dump($remarqueContents);
            //       exit;
            return [ 
                'color'             => $color,
                'images'             => $images,
                'img'               => $img,
                'label'             => $label,
                'dataQuestions'     => $dataQuestions,
                'remarqueContents'  => $remarqueContents
            ];
        }

        /**
         * Fonction de comparaison pour trier par nom et prénom de la liste du baromètre
         *
         *  @changeLog 2023-07-26 [EVOL] (Lansky) Ajout de la méthode  
         * 
         * @param object $a     Le comparant
         * @param object $b     Le comparé
         *
         * @return object
        */
        private static function compareBarometerEmployees($a, $b) {
            $result = strcmp($a->getIdEmploye()->getIdEmploye()->getNom(), $b->getIdEmploye()->getIdEmploye()->getNom()); // Compare d'abord les noms
            if ($result === 0) { // Si les noms sont identiques, compare les prénoms
                $result = strcmp($a->getIdEmploye()->getIdEmploye()->getPrenom(), $b->getIdEmploye()->getIdEmploye()->getPrenom());
            }
            return $result;
        }

        /**
         * Récupère le questionnaire du baromètre
         *
         *  @changeLog 2023-07-26 [EVOL] (Lansky) Ajout de la méthode  
         * 
         * @param object $barometer     Le baromètre a  été envoyé
         *
         * @return object
        */
        private static function getAllQuestionBarometer($barometer)
        {
            $response = [];
            foreach ($barometer->getContents() as $responses) {
                foreach ($responses['questions'] as $value) {
                    $response[] = $value['question'];
                }
            }
            return $response;
        }
    }