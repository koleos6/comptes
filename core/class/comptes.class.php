<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class comptes extends eqLogic {
    /*     * *************************Attributs****************************** */
/*
	private $solde;
	private $solde_estime;
	private $a_pointer;
	private $virements;
	private $prelevements;
	*/

    /*     * ***********************Methode static*************************** */

	public function computeSolde() {
		$solde = 0;
		foreach (comptes_operations::byBankIdAll($this->id) as $op) {
            if ($this->getConfiguration('ActivationPointage') == 1) {
                if ($op->getChecked())
                    $solde += $op->getAmount();
            }else 
            {
                $solde += $op->getAmount();
            }
        
        }
		return round($solde, 2);
	}
	
	public function computeAPointer() {
		$apointer = 0;
		foreach (comptes_operations::byBankIdAll($this->id) as $op) {
			if (!$op->getChecked())
				$apointer += $op->getAmount();
		}
		return round($apointer, 2);
	}
		
	public function computeSoldeFinDeMois() {
		$mois = date("m",time()); 
		$jour = date("d",time()); 
		$annee_mois = date("Y-m-",time());
		$date = date("Y-m-d",time());
		$nombreDeJours = intval(date("t",$mois));
		$date_fin_mois = $annee_mois . $nombreDeJours;
		$solde = 0;
		foreach (comptes_operations::byBankIdAll($this->id) as $op) {
			if ($op->getChecked())
				$solde += $op->getAmount();
				
			$op_date = $op->getChekedOn(); //Basé sur date de valeur pour fonctionner tout le temps
			if (!$op->getChecked() && ($op_date <=  $date_fin_mois)) 
				$solde += $op->getAmount();
		}

		foreach (comptes_virements_auto::byBankIdAll($this->id) as $vir) {

			//vérification si le virement doit se faire avant la fin du mois:
			//Check 1 : date de début est avant la date de fin de mois
			$check1 =  $vir->getStartDate() <= $date_fin_mois; 
			
			
			//Check 2 : vérification si le jour du virement est entre "aujourd'hui" et la fin du mois
			
			$jour_op = $vir->getJour();

			if ($jour_op > $jour) {
					$check2 = true;
			}else 	{	
					$check2 = false;
			}
			//Check 3 : vérification de la date de fin
			$check3 = $vir->getEndDate() > $date_fin_mois; 
			
			if ($check1 && $check2 && $check3) {
				$cpt_freq = $vir->getCompteur_frequence();
				//Check 4 : vérification de la fréquence mensuelle	
				if ($cpt_freq  == 0) {
					$solde += $vir->getAmount();
				} 
			}
		
		
		}
		return round($solde, 2);
	}
	
	public static function updateHistory() {
		
		foreach(eqLogic::byType('comptes') as $compte) {
            log::add('comptes', 'debug', 'History compte '.$compte->getId());
			if ($compte->getConfiguration('Historiser') == 1) {
				//purge du contenu de la table historique 
				//Récupération de la commande du compte (si existante)
                log::add('comptes', 'debug', 'Historisation à faire pour le compte '.$compte->getId());
				$cmd = $compte->getCmd();
                
				//alert($cmd); 
                if(count($cmd)) {
					log::add('comptes', 'debug', 'mise à jour history cmd pour le compte '.$compte->getId());
					$cmd = $cmd[0];
					//$cmd = cmd::byId(2522);
				}
				else {
                    log::add('comptes', 'debug', 'ajout history cmd pour le compte '.$compte->getId());
					$cmd = new cmd();
					//$cmd->setId("");
					$cmd->setName("Solde");
					$cmd->setType("info");
					$cmd->setSubType("other");
					$cmd->setEqLogic_id($compte->getId());
					$cmd->setIsHistorized(true);
					//$cmd->setUnite("");
					$cmd->setEventOnly(0);
					//$cmd->setCache();
					//$cmd->setTemplate();
					//$cmd->setConfiguration();
					//$cmd->setDisplay();
					//$cmd->setCollectDate();
					$cmd->setValue(null);
					$cmd->setIsVisible(true);
					$cmd->setOrder(0);
					$cmd->setLogicalId(null);
					$cmd->setEqType("comptes");
					
					$cmd->save();
				}
        
				$cmd->emptyHistory();
                log::add('comptes', 'debug', 'purge historique done');
				//ajout du contenu dans la table historique
				$operations = comptes_operations::byBankIdCheckedOrderedByDateValeur($compte->getId());
					
				log::add('comptes', 'debug', 'operation bdd');
				$old_date = "new"; 
				$sum_date = 0;
					
				foreach ($operations as $op) {
					if ($op->getChekedOn() == $old_date) {
						//même jour qu'avant: on somme
						$sum_date += $op->getAmount();
					}
					else {
						//jour différent
						if ($old_date != "new") {
							//sauvegarde en bdd de sum_date & old_date 
							
							$cmd->addHistoryValue($sum_date,$old_date);
                            //log::add('comptes', 'debug', 'ajout historique');
						}
						$old_date = $op->getChekedOn();
						$sum_date += $op->getAmount();
					}
					
				}
				if ($old_date != "new") {
					$cmd->addHistoryValue($sum_date,$old_date);
                    //log::add('comptes', 'debug', 'ajout historique 2');
				}
			}
		}
	}
	
    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {

      }
     */


    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom */

      public static function cronDaily() {
		//parcourir tous les virements automatiques
		
		$virementsAuto = comptes_virements_auto::all();
		foreach($virementsAuto as $vir) {
			//récupération de la date du jour
			$date = date('Y-m-d', strtotime('now'));
			$mois = date('m', strtotime('now'));
			$jour = date('d', strtotime('now'));
			//vérification si le virement doit s'ajouter aujourd'hui:
			//Check 1 : date du jour supérieur à date de début
			$check1 = $date >= $vir->getStartDate(); 
			//Check 2 : vérification si le jour est le bon dans le mois: 
			if (($mois == 2) && ($vir->getJour() >= 29 ) && ($jour == 28)) {
				//Si moi de février ET jour vir auto sup ou egal à 29 & si on est le 28 février
				$check2 = true;
			} else {
				if ((($mois == 4) || ($mois == 6) || ($mois == 9) || ($mois == 11)) && ($jour ==30) && ($vir->getJour() == 31 )) {
					//Si mois à 30 jour ET vir auto egal 31 et si on est le 30
					$check2 = true;
				}
				else {
					$check2 = $vir->getJour() == $jour;
				}
			}
					
			//Check 3 : vérification de la date de fin
			$check3 = $date <= $vir->getEndDate(); 
			
			if ($check1 && $check2 && $check3) {
				$cpt_freq = $vir->getCompteur_frequence();
				$freq = $vir->getFrequence();
				//Mise à jour du compteur pour la fréquence mensuelle
				if (($cpt_freq < $freq) && (($freq - $cpt_freq) > 1)) {
					$vir->setCompteur_frequence($vir->getCompteur_frequence() + 1);
					$vir->save();
				} else {
					$vir->setCompteur_frequence(0);
					$vir->save();
				}
				//Check 4 : vérification de la fréquence mensuelle	
				if ($cpt_freq  == 0) {
					$new_op = new comptes_operations();
					$opSave['id'] = "";
					$opSave['BankOperation'] = $vir->getTitle();
					$opSave['CatId'] = $vir->getCatId();
					$opSave['eqLogic_id'] = $vir->getEqLogic_id();
					$opSave['Type'] = 3;
					$opSave['Amount'] = $vir->getAmount();
					$opSave['OperationDate'] = $date;
					$opSave['CheckedOn'] = $date;
					$opSave['Checked'] = 0;
					$opSave['hide'] = 0;		
					utils::a2o($new_op, jeedom::fromHumanReadable($opSave));
					$new_op->save();
				} 
			}
			
			
		}
      
		
		//Générer les historiques
		comptes::updateHistory();
		
		
	  
	  }
     
	public function getLinkToComptes() {
		return 'index.php?v=d&m=' . $this->getEqType_name() . '&p=panel&id=' . $this->getId();
		//&p=' . $this->getEqType_name() . '
	}

    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        //TODO: supprimer les fonts installées
    }
	
	public function getOperations() {
		
		return comptes_operations::byBankId($this->id);
	}
	
	
    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
     */ 
	public function toHtml($_version = 'dashboard') {

        if ($this->getIsEnable() != 1) {
			return '';
		}
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
        
        
        if ($this->getConfiguration('ActivationPointage') == 1) {
            $solde_apointer= "(";
            $solde_apointer.=  $this->computeAPointer();
            $solde_apointer.= " ";
            $solde_apointer.= $this->getConfiguration("currency");
            $solde_apointer.= ")";
            $hidden="";
        }else {
            $solde_apointer="";
            $hidden="hidden";
        }
        $replace['#SoldeMsg#'] = "";
        $replace['#EndMonthMsg#'] = "Fin de mois: ";
        $replace['#solde_reel#'] = $this->computeSolde();
        $replace['#solde_findemois#'] = $this->computeSoldeFinDeMois();
        $replace['#solde_apointer#'] = $solde_apointer;
        $replace['#devise#'] = $this->getConfiguration("currency");
        $replace['#eqLink#'] = $this->getLinkToComptes();
        /*
		$replace = array(
			//'#id#' => $this->getId(),
            '#SoldeMsg#' => "", 
            '#hidden#' => $hidden, 
            '#EndMonthMsg#' => "Fin de mois: ", 
			//'#name#' => ($this->getIsEnable()) ? $this->getName() : '<del>' . $this->getName() . '</del>',
            //'#eqLink#' => $this->getLinkToComptes(),
			'#solde_reel#' => $this->computeSolde(),
			'#solde_findemois#' => $this->computeSoldeFinDeMois(),
            '#solde_apointer#' => $solde_apointer,
			'#devise#' => $this->getConfiguration("currency"),
			//'#background_color#' => $this->getBackgroundColor(jeedom::versionAlias($_version)),
        );
        */
		return template_replace($replace, getTemplate('core', jeedom::versionAlias($_version), 'comptes', 'comptes'));			
    }
     

    /*     * **********************Getteur Setteur*************************** */

}

class comptes_banques {

	private $id;
	private $name;
	private $logo_name;
	private $logo_mini_name;
	
	
	public static function byId($_id) {
        $values = array(
            'id' => $_id
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_banques
        WHERE id=:id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

	
	public static function all() {
		
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_banques ORDER BY name';
		
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public function remove() {
		return DB::remove($this);
	}
	
	public function preSave() {
	
	
	}
	
	public function save() {
		return DB::save($this);
	}
	
	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}


	public function getName() {
		return $this->name;
	}
	
	public function getLogo_name() {
		return $this->logo_name;
	}

	public function getLogo_mini_name() {
		return $this->logo_mini_name;
	}
	
	public function setId($id) {
		$this->id = $id;
	}

	public function setName($data) {
		$this->name = $data;
	}
	
	public function setLogo_name($data) {
		$this->logo_name = $data;
	}
	
	public function setLogo_mini_name($data) {
		$this->logo_mini_name = $data;
	}

}

class comptes_categories {

	private $id;
	private $name;
	private $level;
	private $users;
	private $position;
	private $image;
	
	
	public static function byId($_id) {
        $values = array(
            'id' => $_id
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_categories
        WHERE id=:id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public static function all() {
		
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_categories ORDER BY position';
		
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public function getDisplay($_key = '', $_default = '') {
        return utils::getJsonAttr($this->image, $_key, $_default);
    }
	
	public function remove() {
		return DB::remove($this);
	}
	
	public function preSave() {
	
	
	}
	
	public function save() {
		return DB::save($this);
	}
	
	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}


	public function getName() {
		return $this->name;
	}
	/*
	public function getFather_id() {
		return $this->father_id;
	}
	*/
	public function getLevel() {
		if ($this->level == 0)
			$this->level = 0;
		return $this->level;
	}
	public function getUsers() {
		return $this->users;
	}
	
	public function getPosition() {
		return $this->position;
	}
	/*
	public function getImage() {
		return $this->image;
	}
	*/
	public function getImage($_key = '', $_default = '') {
		return utils::getJsonAttr($this->image, $_key, $_default);
	}
	
	public function setId($id) {
		$this->id = $id;
	}

	public function setName($data) {
		$this->name = $data;
	}
	/*
	public function setFather_id($data) {
		$this->father_id = $data;
	}
	*/
	public function setLevel($data) {
		$this->level = $data;
	}
	public function setUsers($data) {
		$this->users = $data;
	}
	
	public function setPosition($data) {
		$this->position = $data;
	}
	
	public function setImage($data) {
		$this->image = $data;
	}

}

class comptes_virements_auto {

	private $id;
    private $eqLogic_id;//BankId
    private $CatId;
    private $Title;
    private $Reference;
    private $Amount;
    private $StartDate;
	private $EndDate;
	private $frequence;
	private $jour;
	private $position;
	private $comments;
	private $compteur_frequence;

	/* database interactions */
	
	public static function byId($_id) {
        $values = array(
            'id' => $_id
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_virements_auto
        WHERE id=:id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public static function byBankId($_BankId) {
		
        $values = array(
            'id' => $_BankId
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_virements_auto
        WHERE eqLogic_id=:id';
		
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public static function byBankIdAll($_BankId) {
		
        $values = array(
            'id' => $_BankId
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_virements_auto
        WHERE eqLogic_id=:id';
		
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public static function all() {
		
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_virements_auto ORDER BY position';
		
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public function remove() {
		return DB::remove($this);
	}
	
	public function preSave() {
	
	
	}
	
	public function save() {
		return DB::save($this);
	}
	
	
	
	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getEqLogic_id() {
		return $this->eqLogic_id;
	}

	public function getCatId() {
		return $this->CatId;
	}
	
	public function getTitle() {
		return $this->Title;
	}

	public function getReference() {
		return $this->Reference;
	}
	
	public function getAmount() {
		return $this->Amount;
	}
	
	public function getStartDate() {
		return $this->StartDate;
	}
	
	public function getEndDate() {
		return $this->EndDate;
	}
	
	public function getFrequence() {
		return $this->frequence;
	}
	
	public function getJour() {
		return $this->jour;
	}
	
	public function getPosition() {
		return $this->Position;
	}
	
	public function getCompteur_frequence() {
		return $this->compteur_frequence;
	}
	
	public function getComments() {
		return $this->Comments;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function setEqLogic_id($id) {
		$this->eqLogic_id = $id;
	}
	
	public function setCatId($id) {
		$this->CatId = $id;
	}
	
	public function setTitle($data) {
		$this->Title = $data;
	}
	
	public function setReference($data) {
		$this->Reference = $data;
	}
	
	public function setAmount($amount) {
		$this->Amount = $amount;
	}
	
	public function setStartDate($date) {
		$this->StartDate = $date;
	}
	
	public function setEndDate($date) {
		$this->EndDate = $date;
	}
		
	public function setFrequence($data) {
		$this->frequence = $data;
	}
	
	public function setJour($data) {
		$this->jour = $data;
	}
	
	public function setPosition($position) {
		$this->Position = $position;
	}
	
	public function setComments($data) {
		$this->Comments = $data;
	}
	
	public function setCompteur_frequence($data) {
		$this->compteur_frequence = $data;
	}

	public function getEqLogic() {//Bank
		return comptes::byId($this->eqLogic_id);
	}


}

class comptes_operations {

	private $id;
    private $eqLogic_id;//BankId
    private $CatId;
    private $BankOperation;
    private $Type;
    private $Amount;
    private $OperationDate;
	private $CheckedOn;
	private $Checked;
	private $hide;

	/* database interactions */
	
	public static function byId($_id) {
        $values = array(
            'id' => $_id
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_operations
        WHERE id=:id ';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public static function byBankId($_BankId) {
		
        $values = array(
            'id' => $_BankId
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_operations
        WHERE eqLogic_id=:id order by `id` DESC LIMIT 20';
		
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public static function byBankIdCheckedOrderedByDateValeur($_BankId) {
        $values = array(
            'id' => $_BankId
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_operations
        WHERE eqLogic_id=:id ';
        $bank = eqLogic::byId($_BankId);
         
        if ($bank->getConfiguration('ActivationPointage') == 1)
            $sql.='AND Checked = 1 ';
        
        $sql.= 'order by `CheckedOn`';
		
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public static function byBankIdCheckedOrderedByDate($_BankId) {
		
        $values = array(
            'id' => $_BankId
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_operations
        WHERE eqLogic_id=:id AND checked=1 order by `OperationDate`';
		
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public static function byBankIdAll($_BankId) {
		
        $values = array(
            'id' => $_BankId
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_operations
        WHERE eqLogic_id=:id';
		
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public function getDepensesDuMois($_BankId,$_date ='', $optPointage='0') {
		if ($_date =='') {
			$_date = date('Y-m-01', strtotime('now'));
		}
		$_nextDate = date("Y-m-01", strtotime("+1 month", strtotime($_date)));
		$values = array(
            'id' => $_BankId,
			'date_month' => $_date,
			'next_date_month' => $_nextDate
            );
		$sql = 'SELECT *
        FROM (SELECT name as label, SUM(Amount) as value, image as color FROM comptes_operations INNER JOIN comptes_categories ON comptes_categories.id = comptes_operations.CatId
        WHERE eqLogic_id=:id AND CheckedOn >= :date_month AND CheckedOn < :next_date_month ';
        
        if ($optPointage == 1)
            $sql.='AND Checked = 1 ';
        
        $sql.= 'AND Amount < 0 GROUP BY name)AS GDDM';
		
		$execute = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
		$i=0;
		foreach ($execute as $exec) {
			$res[$i] = $exec;
			$res[$i]['value'] =  floatval(- floatval($exec['value']));
			$res[$i]['color'] = utils::getJsonAttr($exec['color'], 'tagColor', '');
			$i++;
		}
        
        //Tri à bulle des datas pour les regrouper par couleur (même groupement de catégorie)
        
        $tab_en_ordre = 0;
        $taille=$i - 1 ;
        $res2 = $res;
        while(!$tab_en_ordre) {
            $tab_en_ordre = 1;
            for($i=0 ; $i < $taille -1; $i++){
                if($res2[$i]['color'] > $res2[$i+1]['color']) {
                    $temp = $res2[$i];
                    $res2[$i] = $res2[$i+1];
                    $res2[$i+1] = $temp;

                    $tab_en_ordre = 0;
                }
            }
            $taille--;
        }
        
        
		return $res2;
	}
	
	public function getRecettesDuMois($_BankId,$_date = '', $optPointage='0') {
		if ($_date =='')
			$_date = date('Y-m-01', strtotime('now'));
		
		$_nextDate = date("Y-m-01", strtotime("+1 month", strtotime($_date)));
		$values = array(
            'id' => $_BankId,
			'date_month' => $_date,
			'next_date_month' => $_nextDate
            );
		$sql = 'SELECT *
        FROM (SELECT name as label, SUM(Amount) as value, image as color FROM comptes_operations INNER JOIN comptes_categories ON comptes_categories.id = comptes_operations.CatId
        WHERE eqLogic_id=:id AND CheckedOn >= :date_month AND CheckedOn < :next_date_month ';
        
        if ($optPointage == 1)
            $sql.='AND Checked = 1 ';
        
        $sql.= 'AND Amount > 0 GROUP BY name)AS GDDM';
		
		$execute = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
		$i=0;
		foreach ($execute as $exec) {
			$res[$i] = $exec;
			$res[$i]['value'] = floatval($exec['value']);
			$res[$i]['color'] = utils::getJsonAttr($exec['color'], 'tagColor', '');
			$i++;
		}
        
        //Tri à bulle des datas pour les regrouper par couleur (même groupement de catégorie)
        
        $tab_en_ordre = 0;
        $taille=$i - 1 ;
        $res2 = $res;
        while(!$tab_en_ordre) {
            $tab_en_ordre = 1;
            for($i=0 ; $i < $taille -1; $i++){
                if($res2[$i]['color'] > $res2[$i+1]['color']) {
                    $temp = $res2[$i];
                    $res2[$i] = $res2[$i+1];
                    $res2[$i+1] = $temp;

                    $tab_en_ordre = 0;
                }
            }
            $taille--;
        }
        
        
		return $res2;
	}
	
	public function getDepensesRecettesDuMois($_BankId,$_date = '', $optPointage='0') {
		
		if ($_date =='')
			$_date = date('Y-m-01', strtotime('now'));
		
		$_nextDate = date("Y-m-01", strtotime("+1 month", strtotime($_date)));
		$values = array(
            'id' => $_BankId,
			'date_month' => $_date,
			'next_date_month' => $_nextDate
            );
		$sql = 'SELECT *
        FROM (SELECT name as label, SUM(Amount) as value, image as color FROM comptes_operations INNER JOIN comptes_categories ON comptes_categories.id = comptes_operations.CatId
        WHERE eqLogic_id=:id AND CheckedOn >= :date_month AND CheckedOn < :next_date_month ';
        
        if ($optPointage == 1)
            $sql.='AND Checked = 1 ';
        
        $sql.= 'AND Amount < 0 GROUP BY name)AS GDDM';
		
		$execute = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
		$res['depenses'] = 0;
		foreach ($execute as $exec) {
			$res['depenses'] += -floatval($exec['value']);
		}
		
		$sql = 'SELECT *
        FROM (SELECT name as label, SUM(Amount) as value, image as color FROM comptes_operations INNER JOIN comptes_categories ON comptes_categories.id = comptes_operations.CatId
        WHERE eqLogic_id=:id AND CheckedOn >= :date_month AND CheckedOn < :next_date_month ';
        
        if ($optPointage == 1)
            $sql.='AND Checked = 1 ';
        
        $sql.= 'AND Amount > 0 GROUP BY name)AS GDDM';
		
		$execute2 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
		$res['recettes'] = 0;
		foreach ($execute2 as $exec) {
			$res['recettes'] += floatval($exec['value']);
		}
		
		return $res;
	}
	
	public static function all() {
		
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_operations';
		
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
	
	public function getOperations_debut($_id) {
		$values = array(
            'id' => $_id
            );
            
        $compte = comptes::byId($_id);   
        
        $OptionPointage = $compte->getConfiguration('ActivationPointage');   
        
        if ($OptionPointage) {
            $Apointer = $compte->getConfiguration('AffAPointer');
            $pointer = $compte->getConfiguration('AffPointees');
        }else {//Toutes les opérations sont souhaitées car l'option est désactivée: 
            $Apointer = 1;
            $pointer = 1;
        }
            
            
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_operations
        WHERE eqLogic_id=:id ';

		if (($Apointer == 1)  && ($pointer == 1)) { //OUI et OUI
			$sql.= '';
		}
		if (($Apointer == 0) && ($pointer == 1)) { //NON ET OUI
			$sql.= ' AND `Checked`= 1 ';
		}
		if (($Apointer == 1) && ($pointer == 0)) { //OUI et NON
			$sql.= ' AND `Checked`= 0 ';
		}
		if (($Apointer == 0) && ($pointer == 0)) { //NON et NON
			$sql.= ' AND `Checked` > 1 ';
		}
		$sql.= ' ORDER BY `CheckedOn` DESC LIMIT 40';
		
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    public function getOperations_suite($_BankId, $_offset=0, $_mode=0, $_catId=0, $_search="") {
		 $values = array(
            'id' => $_BankId
            );
            
        $compte = comptes::byId($_BankId);   
        
        $OptionPointage = $compte->getConfiguration('ActivationPointage');   
        
        if ($OptionPointage) {
            $Apointer = $compte->getConfiguration('AffAPointer');
            $pointer = $compte->getConfiguration('AffPointees');
        }else {//Toutes les opérations sont souhaitées car l'option est désactivée: 
            $Apointer = 1;
            $pointer = 1;
        }
        
        
            
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_operations
        WHERE eqLogic_id = :id ';
        
        switch($_mode) {
            case 2:
                $sql.= ' AND `BankOperation` LIKE :_search ';
                $values = array(
                    'id' => $_BankId,
                    '_search' => '%'.$_search.'%'
                );
            break;
            case 1: 
                $sql.= ' AND `CatId` = :_catid ';
                $values = array(
                    'id' => $_BankId,
                    '_catid' => $_catId
                );
            case 0:
            default:
                if (($Apointer == 1)  && ($pointer == 1)) { //OUI et OUI
                    $sql.= '';
                }
                if (($Apointer == 0) && ($pointer == 1)) { //NON ET OUI
                    $sql.= ' AND `Checked`= 1 ';
                }
                if (($Apointer == 1) && ($pointer == 0)) { //OUI et NON
                    $sql.= ' AND `Checked`= 0 ';
                }
                if (($Apointer == 0) && ($pointer == 0)) { //NON et NON
                    $sql.= ' AND `Checked` > 1 ';
                }
            break; 
        }
		

		//throw new Exception(__('test' . $Apointer. 'test' .$pointer, __FILE__). $_BankId);
		$sql.= ' ORDER BY `CheckedOn` DESC LIMIT ';
        $sql.= $_offset;
        $sql.= ',15';
		log::add('comptes', 'debug', 'Schroll update: Requete: '.$sql);
        
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
    public function getOperations_filter($_BankId, $_catId) {
		
        $values = array(
            'id' => $_BankId,
			'_catid' => $_catId
            );
        log::add('comptes', 'debug', 'FilterAction: BankID: '.$_BankId);
        log::add('comptes', 'debug', 'FilterAction: CatID: '.$_catId);

        $compte = comptes::byId($_BankId);   
        
        $Apointer = $compte->getConfiguration('AffAPointer');
        $pointer = $compte->getConfiguration('AffPointees');
        
        log::add('comptes', 'debug', 'FilterAction: APointer: '.$Apointer);
        log::add('comptes', 'debugOR', 'FilterAction: Pointer: '.$pointer);
            
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_operations
        WHERE eqLogic_id = :id AND `CatId` = :_catid ';
		
		if (($Apointer == 1)  && ($pointer == 1)) { //OUI et OUI
			$sql.= '';
		}
		if (($Apointer == 0) && ($pointer == 1)) { //NON ET OUI
			$sql.= ' AND `Checked`= 1 ';
		}
		if (($Apointer == 1) && ($pointer == 0)) { //OUI et NON
			$sql.= ' AND `Checked`= 0 ';
		}
		if (($Apointer == 0) && ($pointer == 0)) { //NON et NON
			$sql.= ' AND `Checked` > 1 ';
		}

		//throw new Exception(__('test' . $Apointer. 'test' .$pointer, __FILE__). $_BankId);
		$sql.= ' ORDER BY `CheckedOn` DESC LIMIT 40';
        //$sql.= ' ORDER BY `id` ';
        
		log::add('comptes', 'debug', 'FilterAction: Requete: '.$sql);
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
    
    public function getOperations_search($_BankId, $_search) {
		
        $values = array(
            'id' => $_BankId,
			'_search' => '%'.$_search.'%'
            );
        log::add('comptes', 'debug', 'SearchAction: BankID: '.$_BankId);
        log::add('comptes', 'debug', 'SearchAction: Search: '.$_search);

        $compte = comptes::byId($_BankId);   
  
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM comptes_operations
        WHERE eqLogic_id = :id AND `BankOperation` LIKE :_search ';
		
		$sql.= ' ORDER BY `CheckedOn` DESC LIMIT 40';
        
		log::add('comptes', 'debug', 'SearchAction: Requete: '.$sql);
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public function remove() {
		return DB::remove($this);
	}
	
	public function preSave() {
	
	
	}
	
	public function save() {
		return DB::save($this);
	}
	
	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getEqLogic_id() {
		return $this->eqLogic_id;
	}

	public function getCatId() {
		return $this->CatId;
	}
	
	public function getBankOperation() {
		return $this->BankOperation;
	}

	public function getType() {
		return $this->Type;
	}
	
	public function getAmount() {
		return $this->Amount;
	}
	
	public function getOperationDate() {
		return $this->OperationDate;
	}
	
	public function getChekedOn() {
		return $this->CheckedOn;
	}
	
	public function getChecked() {
		return $this->Checked;
	}
	
	public function getHide() {
		return $this->hide;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function setEqLogic_id($id) {
		$this->eqLogic_id = $id;
	}
	
	public function setCatId($id) {
		$this->CatId = $id;
	}
	
	public function setBankOperation($op) {
		$this->BankOperation = $op;
	}
	
	public function setType($type) {
		$this->Type = $type;
	}
	
	public function setAmount($amount) {
		$this->Amount = $amount;
	}
	
	public function setOperationDate($date) {
		$this->OperationDate = $date;
	}
	
	public function setCheckedOn($date) {
		$this->CheckedOn = $date;
	}
	
	public function setChecked($boolean) {
		$this->Checked = $boolean;
	}
	
	public function setHide($data) {
		$this->hide = $data;
	}

	public function getEqLogic() {//Bank
		return comptes::byId($this->eqLogic_id);
	}


}

class comptes_stats {
	
	
	
}

class comptesCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        $compte = $this->getEqLogic();
		return $compte->computeSolde();    
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>
