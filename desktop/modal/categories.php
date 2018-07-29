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

if (!isConnect()) {
	throw new Exception(__('401 - Accès non autorisé', __FILE__));
}

include_file('desktop', 'panel', 'css', 'comptes'); 

if (init('cat_id') == '') {
    throw new Exception('{{La catégorie de l\'opération ne peut etre vide : }}' . init('cat_id'));
}
$cat_id = init('cat_id');


?>

<div id='div_CatAlert' style="display: none;"></div>
<div class="row row-overflow">
    <div class="">
            <ul id="ul_cat" class="nav nav-list bs-sidenav">
                    <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                $allCats = comptes_categories::all();
				$i=0;
                foreach ($allCats as $cat) {
                    $margin = 25 * $cat->getLevel();
					//Search upper level cat if exists: 
					$cat_i=$i;
					$cat_level=$cat->getLevel();
					$cat_name=$cat->getName();
					$cat_upper_level_name=$cat->getName();
					$stop=0;
					
					if (($cat->getId() != '') && ($cat_level > 0)) {
						for ($j=$cat_i; ($j >= 0) && ($stop == 0); $j--) {
							if ($allCats[$j]->getLevel() == ($cat_level - 1))  {
								$cat_upper_level_name = $allCats[$j]->getName();
								$cat_upper_level_name = $cat_upper_level_name . ' -> ';
								$stop = 1;
							}
						}
						
					}
					if (($cat->getId() != '') && ($cat_level == 0)) {
						$cat_upper_level_name =  "";
					
					}
                    echo '<li style="height:60px;" class="cursor li_cat';
					if ($cat->getId() == $cat_id) {
						echo 'catFocus';
					}
                    echo '" data-cat_id="' . $cat->getId() . '" data-cat_name_upper="' . $cat_upper_level_name . '" data-cat_name="' . $cat_name . '" data-cat_imgBackgroundcolor="' . $cat->getImage("tagColor") . '" data-cat_imgColor="' . $cat->getImage("tagTextColor") . '" data-cat_imgIcon="' . $cat->getImage("icon") . '">'
					. '<a style="position:relative;left:' . $margin . 'px;">';
					echo '<div class="image_cat" style="font-size : 2em;color:' . $cat->getImage("tagTextColor")
		            . ';border: 1px solid ' . $cat->getImage("tagColor")	. ';border-radius: 5px 5px 5px 5px ;width:40px;height:40px;text-align:center;vertical-align:middle;background-color:' . $cat->getImage("tagColor") . ';" >' 
				    . $cat->getImage("icon") . '</div>';
					echo '<div style="float:left;margin-left:50px;margin-top:-30px">';
					echo $cat->getName();
					echo '</div>';
					echo '</a>'
                    . '</li>';
					$i++;
                }
                ?>
            </ul>
    </div>
	
<form class="form-horizontal" id="form_catEdit">
    <fieldset>
		<input type="hidden" id="mod_catResult" value="0" />
		<input type="hidden" id="mod_catResultText" value="" />
		<input type="hidden" id="mod_catResultText_upper" value="" />
		<input type="hidden" id="mod_catResultImg" value="" />
		<input type="hidden" id="mod_catResultImgBackgroundcolor" value="" />
		<input type="hidden" id="mod_catResultImgColor" value="" />
		<input type="hidden" id="mod_catResultImgIcon" value="" />
	</fieldset> 
</form>

<script>

	$(".li_cat").on('click', function (event) {

		$('.li_cat').removeClass("catFocus");
		$(this).addClass("catFocus");
		
		//alert($(this).attr('data-cat_id'));
		$("#mod_catResult").val($(this).attr('data-cat_id'));
		$("#mod_catResultText_upper").val($(this).attr('data-cat_name_upper'));
		$("#mod_catResultText").val($(this).attr('data-cat_name'));
		$("#mod_catResultImg").val($(this).attr('data-cat_img'));
		$("#mod_catResultImgBackgroundcolor").val($(this).attr('data-cat_imgBackgroundcolor'));
		$("#mod_catResultImgColor").val($(this).attr('data-cat_imgColor'));
		$("#mod_catResultImgIcon").val($(this).attr('data-cat_imgIcon'));
	});

	


mod_insertCatValue = function () {
    return $('#mod_catResult').val();
}

mod_insertCatText_upper = function () {
    return $('#mod_catResultText_upper').val();
}

mod_insertCatText = function () {
    return $('#mod_catResultText').val();
}

mod_insertCatImg = function () {
    return $('#mod_catResultImg').val();
}

mod_insertCatImgBackgroundcolor = function () {
    return $('#mod_catResultImgBackgroundcolor').val();
}
mod_insertCatImgColor = function () {
    return $('#mod_catResultImgColor').val();
}
mod_insertCatImgIcon = function () {
    return $('#mod_catResultImgIcon').val();
}

</script>