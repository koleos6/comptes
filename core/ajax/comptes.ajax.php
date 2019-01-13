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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}
    ajax::init();

	/*
	if (init('action') == 'creationTableVirAuto') {
	
		$sql = file_get_contents(dirname(__FILE__) . '/install_virements_auto.sql');
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
        ajax::success();
    }
	
	if (init('action') == 'creationTableOperations') {
	
		$sql = file_get_contents(dirname(__FILE__) . '/install_operations.sql');
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
        ajax::success();
    }
	
	if (init('action') == 'creationTableBanques') {
	
		$sql = file_get_contents(dirname(__FILE__) . '/install_banques.sql');
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
        ajax::success();
    }
	
	if (init('action') == 'creationTableCategories') {
	
		$sql = file_get_contents(dirname(__FILE__) . '/install_categories.sql');
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
        ajax::success();
    }
	*/
	if (init('action') == 'updateHide') {
		$Id = json_decode(init('id'), true);
        if (isset($Id)) {
			$op = comptes_operations::byId($Id);
			$hide = $op->getHide();
			$hide = !$hide;
			$op->setHide($hide);
			$op->save();
		}
		$return['hide'] = $hide;
		$return['id'] = $Id;
		ajax::success(jeedom::toHumanReadable($return));
    }
	
	if (init('action') == 'importContenuTableCategories') {
	
		$sql = file_get_contents(dirname(__FILE__) . '/comptes_categories.sql');
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
        ajax::success();
    }
	
	if (init('action') == 'getBankOperations') {
		$typeEqLogic = init('type');
        if ($typeEqLogic == '' || !class_exists($typeEqLogic)) {
            throw new Exception(__('{{Type incorrect (classe équipement inexistante) : }}', __FILE__) . $typeEqLogic);
        }
        $eqLogic = $typeEqLogic::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('{{Banque inconnue verifié l\'id : }}', __FILE__) . init('id'));
        }
		
        $return = utils::o2a($eqLogic);
		$return['eqLogic_id'] = init('id');
        $return['op'] = utils::o2a(comptes_operations::getOperations_debut(init('id')));
		//$return['op'] = utils::o2a($eqLogic->getOperations());
		$return['cat'] = utils::o2a(comptes_categories::all());
        //log::add('comptes', 'debug', 'test 0');
        $return['optPointage'] = $eqLogic->getConfiguration('ActivationPointage');
        //log::add('comptes', 'debug', 'test '.$return['optPointage']);
        $return['optType'] = $eqLogic->getConfiguration('ActivationTypeOperation');
        $return['optDateUnique'] = $eqLogic->getConfiguration('ActivationDateValeur');
        ajax::success(jeedom::toHumanReadable($return));
	}
	
	if (init('action') == 'getBankOperations_suite') {
		$typeEqLogic = init('type');
        if ($typeEqLogic == '' || !class_exists($typeEqLogic)) {
            throw new Exception(__('{{Type incorrect (classe équipement inexistante) : }}', __FILE__) . $typeEqLogic);
        }
        $eqLogic = $typeEqLogic::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('{{Banque inconnue verifié l\'id : }}', __FILE__) . init('id'));
        }
		
        $return = utils::o2a($eqLogic);
		$return['eqLogic_id'] = init('id');
        
        $return['op'] = utils::o2a(comptes_operations::getOperations_suite(init('id'), init('nb_op'),init('mode'),init('filterCatId'),init('search')));
        
		$return['cat'] = utils::o2a(comptes_categories::all());
        $return['optPointage'] = $eqLogic->getConfiguration('ActivationPointage');
        $return['optType'] = $eqLogic->getConfiguration('ActivationTypeOperation');
        $return['optDateUnique'] = $eqLogic->getConfiguration('ActivationDateValeur');
        ajax::success(jeedom::toHumanReadable($return));
	}
    
    if (init('action') == 'getBankOperations_filter') {
        
		$typeEqLogic = init('type');
        if ($typeEqLogic == '' || !class_exists($typeEqLogic)) {
            throw new Exception(__('{{Type incorrect (classe équipement inexistante) : }}', __FILE__) . $typeEqLogic);
        }
        $eqLogic = $typeEqLogic::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('{{Banque inconnue verifié l\'id : }}', __FILE__) . init('id'));
        }
		
        $return = utils::o2a($eqLogic);
		$return['eqLogic_id'] = init('id');
        $return['op'] = utils::o2a(comptes_operations::getOperations_filter(init('id'), init('catid')));
		$return['cat'] = utils::o2a(comptes_categories::all());
        $return['filterCatId'] = init('catid');
        $return['optPointage'] = $eqLogic->getConfiguration('ActivationPointage');
        $return['optType'] = $eqLogic->getConfiguration('ActivationTypeOperation');
        $return['optDateUnique'] = $eqLogic->getConfiguration('ActivationDateValeur');
        ajax::success(jeedom::toHumanReadable($return));
        
	}
    
    if (init('action') == 'getBankOperations_search') {
        
		$typeEqLogic = init('type');
        if ($typeEqLogic == '' || !class_exists($typeEqLogic)) {
            throw new Exception(__('{{Type incorrect (classe équipement inexistante) : }}', __FILE__) . $typeEqLogic);
        }
        $eqLogic = $typeEqLogic::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('{{Banque inconnue verifié l\'id : }}', __FILE__) . init('id'));
        }
		
        $return = utils::o2a($eqLogic);
		$return['eqLogic_id'] = init('id');
        $return['op'] = utils::o2a(comptes_operations::getOperations_search(init('id'), init('search')));
		$return['cat'] = utils::o2a(comptes_categories::all());
        $return['search'] = init('search');
        $return['optPointage'] = $eqLogic->getConfiguration('ActivationPointage');
        $return['optType'] = $eqLogic->getConfiguration('ActivationTypeOperation');
        $return['optDateUnique'] = $eqLogic->getConfiguration('ActivationDateValeur');
        ajax::success(jeedom::toHumanReadable($return));
        
	}
	
	if (init('action') == 'updatePieCharts') {
        $typeEqLogic = init('type');
        if ($typeEqLogic == '' || !class_exists($typeEqLogic)) {
            throw new Exception(__('{{Type incorrect (classe équipement inexistante) : }}', __FILE__) . $typeEqLogic);
        }
		$eqLogic = $typeEqLogic::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('{{Banque inconnue verifié l\'id : }}', __FILE__) . init('id'));
        }
		if (init('date_sens') == 'left') {
			$_nextDate = date("Y-m-01", strtotime("-1 month", strtotime(init('pie_chart_date'))));
			$_nextDateLetterMonth = date("F", strtotime("-1 month", strtotime(init('pie_chart_date'))));
			$_nextDateLetterYear = date("Y", strtotime("-1 month", strtotime(init('pie_chart_date'))));
		}
		if (init('date_sens') == 'right')  {
			$_nextDate = date("Y-m-01", strtotime("+1 month", strtotime(init('pie_chart_date'))));
			$_nextDateLetterMonth = date("F", strtotime("+1 month", strtotime(init('pie_chart_date'))));
			$_nextDateLetterYear = date("Y", strtotime("+1 month", strtotime(init('pie_chart_date'))));
  		}
		if (init('date_sens') == 'update')  {
			$_nextDate = date("Y-m-01", strtotime(init('pie_chart_date')));
			$_nextDateLetterMonth = date("F", strtotime(init('pie_chart_date')));
			$_nextDateLetterYear = date("Y", strtotime(init('pie_chart_date')));
		}
		switch ($_nextDateLetterMonth) {
			case 'January': $_nextDateLetterMonth = 'Janvier'; break;
			case 'February': $_nextDateLetterMonth = 'Février'; break;
			case 'March': $_nextDateLetterMonth = 'Mars'; break;
			case 'April': $_nextDateLetterMonth = 'Avril'; break;
			case 'May': $_nextDateLetterMonth = 'Mai'; break;
			case 'June': $_nextDateLetterMonth = 'Juin'; break;
			case 'July': $_nextDateLetterMonth = 'Juillet'; break;
			case 'August': $_nextDateLetterMonth = 'Août'; break;
			case 'September': $_nextDateLetterMonth = 'Septembre'; break;
			case 'October': $_nextDateLetterMonth = 'Octobre'; break;
			case 'November': $_nextDateLetterMonth = 'Novembre'; break;
			case 'December': $_nextDateLetterMonth = 'Decembre'; break;
			default: $_nextDateLetterMonth =''; break;
		}
		//$return = utils::o2a($eqLogic);
		$return['DepensesDuMois'] = comptes_operations::getDepensesDuMois(init('id'),$_nextDate, $eqLogic->getConfiguration('ActivationPointage'));
		$return['RecettesDuMois'] = comptes_operations::getRecettesDuMois(init('id'),$_nextDate, $eqLogic->getConfiguration('ActivationPointage'));
		$res =  comptes_operations::getDepensesRecettesDuMois(init('id'),$_nextDate, $eqLogic->getConfiguration('ActivationPointage'));
		$return['depenses'] = $res['depenses'];
		$return['recettes'] = $res['recettes'];
		$return['new_pie_date'] = $_nextDate;
		$return['new_pie_date_letterMonth'] = $_nextDateLetterMonth; 
		$return['new_pie_date_letterYear'] = $_nextDateLetterYear; 
		$return['date_now'] = date('Y-m-01', strtotime('now'));
        ajax::success($return);
	}
	
	if (init('action') == 'getCatLevel') {
		$CatId = json_decode(init('id'), true);
        if (isset($CatId)) {
			$cat = comptes_categories::byId($CatId);
			$cpn = $cat->getLevel();
		}
		$return['CatLevel'] = $cpn;
        ajax::success(jeedom::toHumanReadable($return));
	}
	
	if (init('action') == 'moveCatDown') {
		$CatId = json_decode(init('id'), true);
        if (isset($CatId)) {
			$cat = comptes_categories::byId($CatId);
			$cpn = $cat->getLevel();
			$cpn++;
			$cat->setLevel($cpn);
			$cat->save();
		}
		ajax::success();
	}
	
	if (init('action') == 'moveCatUp') {
		$CatId = json_decode(init('id'), true);
        if (isset($CatId)) {
			$cat = comptes_categories::byId($CatId);
			$cpn = $cat->getLevel();
			$cpn--;
			$cat->setLevel($cpn);
			$cat->save();
		}
		ajax::success();
	}
	
	if (init('action') == 'getCategories') {
		$return['cat'] = utils::o2a(comptes_categories::all());
        ajax::success(jeedom::toHumanReadable($return));
	}
	
	if (init('action') == 'getCompteInfos') {
		$bankId = json_decode(init('id'), true);
		$solde = 0;
		$apointer = 0;
        if (isset($bankId)) {
            $bank = eqLogic::byId($bankId);
			$solde = $bank->computeSolde();
			$apointer = $bank->computeAPointer();
			$soldefinmois = $bank->computeSoldeFinDeMois();
        }
		$return['bankid'] = $bankId;
		$return['solde'] = $solde;
		$return['apointer'] = $apointer;
		$return['soldefinmois'] = $soldefinmois;
		ajax::success(jeedom::toHumanReadable($return));
	}
	
	if (init('action') == 'performTransfertCompte') {
		
		
        $opInfos = json_decode(init('event'), true);

        $opDebit = new comptes_operations();
		$opCredit = new comptes_operations();
		
		$opDebit->setId("");
		$opCredit->setId("");
		
		$opDebit->setEqLogic_id($opInfos['BankDebit']);
		$opCredit->setEqLogic_id($opInfos['BankCredit']);
		
		$opDebit->setCatId($opInfos['CatIdDebit']);
		$opCredit->setCatId($opInfos['CatIdCredit']);
		
		$opDebit->setBankOperation($opInfos['Title']);
		$opCredit->setBankOperation($opInfos['Title']);
		
		$opDebit->setType(3);
		$opCredit->setType(3);
		
		$opDebit->setAmount("-".$opInfos['Amount']);//moins à rajouter
		$opCredit->setAmount($opInfos['Amount']);
		
		$opDebit->setOperationDate($opInfos['Date']);
		$opCredit->setOperationDate($opInfos['Date']);
		
		$opDebit->setCheckedOn($opInfos['Date']);
		$opCredit->setCheckedOn($opInfos['Date']);
		
		$opDebit->setChecked(false);
		$opCredit->setChecked(false);
		
		$opDebit->setHide(false);
		$opCredit->setHide(false);
		
        $opDebit->save();
		$opCredit->save();
		
        ajax::success();
    }
	
	if (init('action') == 'updateOperation') {
	
        $opSave = json_decode(init('event'), true);
        $op = null;
        if (isset($opSave['id'])) {
            $op = comptes_operations::byId($opSave['id']);
        }
        if (!is_object($op)) {
            $op = new comptes_operations();
        }
        utils::a2o($op, jeedom::fromHumanReadable($opSave));
        $op->save();
        ajax::success();
    }
	
	if (init('action') == 'updateCheckedStatus') {
	
        $op = null;
		$cid = init('id');
		$cchecked = init('checked');
        if (isset($cid)) {
            $op = comptes_operations::byId($cid);
        }
		//Sécu à mettre si non existante ?
		
        $op->setChecked($cchecked);
        $op->save();
        ajax::success();
    }
	
	if (init('action') == 'updateCat') {
	
        $catSave = json_decode(init('event'), true);
        $cat = null;
        if (isset($catSave['id'])) {
            $cat = comptes_categories::byId($catSave['id']);
        }
        if (!is_object($cat)) {
            $cat = new comptes_categories();
        }
        utils::a2o($cat, jeedom::fromHumanReadable($catSave));
        $cat->save();
		$return['id'] = $cat->getId();
		$return['name'] = $cat->getName();
		$return['tagColor'] = $cat->getImage('tagColor');
		$return['tagTextColor'] = $cat->getImage('tagTextColor');
		$return['icon'] = $cat->getImage('icon');
        ajax::success(jeedom::toHumanReadable($return));
    }
	
	if (init('action') == 'updateVirAuto') {
	
        $virAutoSave = json_decode(init('event'), true);
        $virAuto = null;
        if (isset($virAutoSave['id'])) {
            $virAuto = comptes_virements_auto::byId($virAutoSave['id']);
        }
        if (!is_object($virAuto)) {
            $virAuto = new comptes_virements_auto();
        }
        utils::a2o($virAuto, jeedom::fromHumanReadable($virAutoSave));
        $virAuto->save();
        ajax::success();
    }
	
	if (init('action') == 'newVirAuto') {
	
        $virAuto = new comptes_virements_auto();  
		$virAuto->setTitle('{{Nouveau}}');
		$virAuto->setEqlogic_id(0);
		$virAuto->setCatId(0);
		$virAuto->save();
        $return['id'] = $virAuto->getId();
		$return['Title'] = $virAuto->getTitle();
		ajax::success(jeedom::toHumanReadable($return));
    }
	
	if (init('action') == 'refreshGraphs') {
	
		comptes::updateHistory();
        $return['msg'] = "ok";
		ajax::success(jeedom::toHumanReadable($return));
    }
	
	if (init('action') == 'testCRON') {
	
		//comptes::updateHistory();
        $return['msg'] = "ok";
		ajax::success(jeedom::toHumanReadable($return));
    }
	
	if (init('action') == 'updateBank') {
	
        $bankSave = json_decode(init('event'), true);
        $bank = null;
        if (isset($bankSave['id'])) {
            $bank = comptes_banques::byId($bankSave['id']);
        }
        if (!is_object($bank)) {
            $bank = new comptes_banques();
        }
        utils::a2o($bank, jeedom::fromHumanReadable($bankSave));
        $bank->save();
        ajax::success();
    }
	
	if (init('action') == 'deleteOperation') {
        $op = comptes_operations::byId(init('id'));
        if (!is_object($op)) {
            throw new Exception(__('{{Aucune opération correspondant à : }}', __FILE__) . init('id'));
        }
        $op->remove();
        ajax::success();
    }
	
	if (init('action') == 'deleteVirAuto') {
        $vir = comptes_virements_auto::byId(init('id'));
        if (!is_object($vir)) {
            throw new Exception(__('{{Aucun virement auto correspondant à : }}', __FILE__) . init('id'));
        }
        $vir->remove();
        ajax::success();
    }
	
	if (init('action') == 'deleteCat') {
        $cat = comptes_categories::byId(init('id'));
        if (!is_object($cat)) {
            throw new Exception(__('{{Aucune catégorie correspondant à : }}', __FILE__) . init('id'));
        }
        if ($cat->getId() > 0) { // pas possibble de supprimer la catégorie "Aucune"
            $cat->remove();
        }
        ajax::success();
    }
	
	if (init('action') == 'deleteBank') {
        $bank = comptes_banques::byId(init('id'));
        if (!is_object($bank)) {
            throw new Exception(__('{{Aucune banque correspondant à : }}', __FILE__) . init('id'));
        }
        $bank->remove();
        ajax::success();
    }
	
    if (init('action') == 'setCatOrder') {

        $position = 1;
        foreach (json_decode(init('categories'), true) as $id) {
            $cat = comptes_categories::byId($id);
            if (is_object($cat)) {
                if ($cat->getId()>0) { //Pour ne pas modifier la catégorie par défaut à ID 0
                    $cat->setPosition($position);
                    $cat->save();
                    $position++;
                }
            }
        }
        ajax::success();
    }
	
	if (init('action') == 'setVirAutoOrder') {

        $position = 1;
        foreach (json_decode(init('virement_auto'), true) as $id) {
            $vir = comptes_virements_auto::byId($id);
            if (is_object($vir)) {
                $vir->setPosition($position);
                $vir->save();
                $position++;
            }
        }
        ajax::success();
    }
	

    throw new Exception(__('{{Aucune action ajax correspondant à : }}', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
