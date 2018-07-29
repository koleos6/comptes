
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

$('#sel_banque').on('change', function () {
	//au changement de banque, chargement du logo de la banque
	var banque = $('#sel_banque').val();
	var logo = $('.opt_bk[data-bank='+banque+']').attr("data-logo");
	$('#logo_banque').attr('src','plugins/comptes/images/banques/'+logo);
	
	

});

$('.btn-default[data-l1key=chooseIcon]').on('click', function () {
    
    chooseIcon(function (_icon) {
        $('.eqLogicAttr[data-l1key=configuration][data-l2key=icon]').empty().append(_icon);
    });
});

//N'a plus l'air de surcharger le comportement par defaut
$('.eqLogicAction[data-action=addCompte]').on('click', function () {
    bootbox.prompt("{{Nom du compte ?}}", function (result) {
        if (result !== null) {
            jeedom.eqLogic.save({
                type: eqType,
                eqLogics: [{name: result}],
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (_data) {
                    var vars = getUrlVars();
                    var url = 'index.php?';
                    for (var i in vars) {
                        if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
                            url += i + '=' + vars[i].replace('#', '') + '&';
                        }
                    }
                    modifyWithoutSave = false;
                    url += 'id=' + _data.id + '&saveSuccessFull=1';
                    window.location.href = url;
                }
            });
        }
    });
});

