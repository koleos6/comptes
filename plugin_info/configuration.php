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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');

if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
/*
$id="comptes_operations";
//$results = DB::Query("SHOW TABLES LIKE '$id'");
if(!$results) {
    //die(print_r($dbh->errorInfo(), TRUE));
}
if($results->rowCount()>0){$table = 1;}else {$table = 0;}
*/
$values_1 = array(
            'id' => 'comptes_operations',
            );
$values_2 = array(
            'id' => 'comptes_banques',
            );
$values_3 = array(
            'id' => 'comptes_categories',
            );
$values_4 = array(
            'id' => 'comptes_virements_auto',
            );			
$sql = "SHOW TABLES LIKE :id";
$result_1 = DB::Prepare($sql, $values_1, DB::FETCH_TYPE_ROW);
$result_2 = DB::Prepare($sql, $values_2, DB::FETCH_TYPE_ROW);
$result_3 = DB::Prepare($sql, $values_3, DB::FETCH_TYPE_ROW);
$result_4 = DB::Prepare($sql, $values_4, DB::FETCH_TYPE_ROW);
if ($result_1) {
	$table_1 = 1;
}
else {
	$table_1 = 0;
	
	$sql = file_get_contents(dirname(__FILE__) . '/install_operations.sql');
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
}
if ($result_2) {
	$table_2 = 1;
	
}
else {
	$table_2 = 0;
	$sql = file_get_contents(dirname(__FILE__) . '/install_banques.sql');
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
}

if ($result_3) {
	$table_3 = 1;
}
else {
	$table_3 = 0;
	$sql = file_get_contents(dirname(__FILE__) . '/install_categories.sql');
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
}
if ($result_4) {
	$table_4 = 1;
}
else {
	$table_4 = 0;
	$sql = file_get_contents(dirname(__FILE__) . '/install_virements_auto.sql');
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
}

$sql = "SHOW TABLES LIKE :id";
$result_1 = DB::Prepare($sql, $values_1, DB::FETCH_TYPE_ROW);
$result_2 = DB::Prepare($sql, $values_2, DB::FETCH_TYPE_ROW);
$result_3 = DB::Prepare($sql, $values_3, DB::FETCH_TYPE_ROW);
$result_4 = DB::Prepare($sql, $values_4, DB::FETCH_TYPE_ROW);


	$plugincomptesdir = dirname(__FILE__) . '/../../../core/css/icon/plugin-comptes';
	$pluginfontdir = dirname(__FILE__) . '/../../../core/css/icon/plugin-comptes/fonts';
	
	if (!file_exists($plugincomptesdir)) {
		$result = mkdir($plugincomptesdir, 0777, true);
	}
	if (!file_exists($pluginfontdir)) {
		$result = mkdir($pluginfontdir, 0777, true);
	}
    
	if (!file_exists($plugincomptesdir.'/style.css')) {
		$status=copy(dirname(__FILE__).'/fonts/style.css',$plugincomptesdir.'/style.css'); 
	}
	if (!file_exists($pluginfontdir.'/plugin-comptes.ttf')) {
		$status=copy(dirname(__FILE__).'/fonts/plugin-comptes.ttf',$pluginfontdir.'/plugin-comptes.ttf'); 
	}
    if (!file_exists($pluginfontdir.'/plugin-comptes.svg')) {
		$status=copy(dirname(__FILE__).'/fonts/plugin-comptes.svg',$pluginfontdir.'/plugin-comptes.svg'); 
	}
    if (!file_exists($pluginfontdir.'/plugin-comptes.woff')) {
		$status=copy(dirname(__FILE__).'/fonts/plugin-comptes.woff',$pluginfontdir.'/plugin-comptes.woff'); 
	}

   ?>
<form class="form-horizontal">
    <fieldset>
		<div class="form-group">
			<div class="col-lg-1" style="min-width:250px;"><u>{{Check de la base de donnée}}</u></div>
		</div>
        <div class="alert alert-success">{{La configuration de la base de donnée est valide}}</div>
		 
        <div class="form-group">
            <div class="col-lg-1" style="min-width:250px;"><u>{{Configuration de la base de donnée}}</u></div>
        </div>
            
		<div class="row row-overflow">
            <div class="col-sm-6">
                <div class="alert alert-danger">	
			{{Import des catégories par defaut: Attention, cela peut supprimer vos catégories existantes }}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-actions">
                    <a class="btn btn-block btn-primary " id="comptes_table_creation_cat"><i class="fas fa-download"></i> {{Import des catégories proposées par défaut}}</a>
                </div>
            </div>
		</div>
	<?php
		if ($result_2) {
    ?>
		<div class="form-group">
			<div class="col-lg-1" style="min-width:250px;"><u>{{Gestion des banques}}</u></div>
		</div>	
			<table id="table_op" class="table" style="width:650px">
                <thead>
                    <tr>
						<th style="width: 200px;" class="">{{Nom}}</th>
						<th style="width: 200px;">{{Logo}}</th>
						<th style="width: 200px">{{Mini logo}}</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
					<tr class="bank new_bank_row" id="new_bank">
						<td class="">
							<input type="text" class="bankAttr form-control" data-l1key="name" />
						</td>
						<td class="">
							<input type="text" class="bankAttr form-control" data-l1key="logo_name" />
						</td>
						<td class="">
							<input type="text" class="bankAttr form-control" data-l1key="logo_mini_name" />
						</td>
						<td class="">
							<a class="btn btn-primary add_bank form-control" ><i class="fas fa-plus"></i></a>
							<input type="hidden" class="bankAttr form-control" data-l1key="id" value="0" />
						</td>
					</tr>
				<?php 
					foreach (comptes_banques::all() as $bank_info) {
					echo '<tr class="bank">';
					echo '<td><input type="text" class="bankAttr form-control " data-l1key="name" value="' . $bank_info->getName() . '"/></td>';
					echo '<td><input type="text" class="bankAttr form-control " data-l1key="logo_name" value="' . $bank_info->getLogo_name() . '"/></td>';
					echo '<td><input type="text" class="bankAttr form-control " data-l1key="logo_mini_name" value="' . $bank_info->getLogo_mini_name() . '"/></td>';
					echo '<td><a class="btn btn-danger supp_bank"><i class="far fa-trash-alt"></i></a>';
					echo '<input type="hidden" class="bankAttr form-control" data-l1key="id" value="' . $bank_info->getId() . '" />';
					echo '</td>';
					echo '</tr>';
					}
				?>
                </tbody>
            </table>
			<!--
			<div class="col-lg-1" style="min-width:250px;"><u>{{Devise}}</u></div>
			<div class="col-lg-1">
				<input class="configKey form-control" data-l1key="currency" />
			</div>
			-->
		
	<?php
		}
	?>


    </fieldset>
</form>
<script>

	$('#comptes_table_creation_cat').on('click', function () {

		$.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/comptes/core/ajax/comptes.ajax.php", // url du fichier php
            data: {
                action: "importContenuTableCategories",
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alert').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#div_alert').showAlert({message: '{{Le contenu par défaut de la table "comptes_categories" a été correctement importé}}', level: 'success'});
                //$('#ul_plugin .li_plugin[data-plugin_id=comptes]').click();
            }
        });
    });
	$('.btn.btn-primary.add_bank').on('click', function (event) {
		var newBank = $(this).closest('.bank').getValues('.bankAttr');
		newBank = newBank[0];
	
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'updateBank',
				event: json_encode(newBank)
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_alert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_alert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				//$('#div_alert').showAlert({message: '{{Banque ajoutée avec succès}}', level: 'success'});
				$('#ul_plugin .li_plugin[data-plugin_id=comptes]').click();
			}
		});
	});
	function updateBank(bankdata) {
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'updateBank',
				event: json_encode(bankdata)
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_alert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_alert').showAlert({message: data.result, level: 'danger'});
					return;
				}
			}
		});

	}
	$('.bankAttr[data-l1key=name]').on('change', function (event) {
		var bankData = $(this).closest('.bank').getValues('.bankAttr');
		bankData = bankData[0];
		if (bankData.id != 0) {
			updateBank(bankData);
		}
	});
	$('.bankAttr[data-l1key=logo_name]').on('change', function (event) {
		var bankData = $(this).closest('.bank').getValues('.bankAttr');
		bankData = bankData[0];
		if (bankData.id != 0) {
			updateBank(bankData);
		}
	});
	$('.bankAttr[data-l1key=logo_mini_name]').on('change', function (event) {
		var bankData = $(this).closest('.bank').getValues('.bankAttr');
		bankData = bankData[0];
		if (bankData.id != 0) {
			updateBank(bankData);
		}
	});
	$('.btn.btn-danger.supp_bank').on('click', function (event) {
		var bankdata = $(this).closest('.bank').getValues('.bankAttr');
		bankdata = bankdata[0];
		$.ajax({
			type: 'POST',
			url: 'plugins/comptes/core/ajax/comptes.ajax.php',
			data: {
				action: 'deleteBank',
				id: bankdata.id
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_alert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_alert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				//$('#div_alert').showAlert({message: '{{Bank supprimée avec succès}}', level: 'success'});
				$('#ul_plugin .li_plugin[data-plugin_id=comptes]').click();
			}
		});	
	});
</script>