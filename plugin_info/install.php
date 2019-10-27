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

function comptes_install() {
    
}

function comptes_update() {
    //suppressions des fichiers de la police "icon plugins-comptes" installées dans le core jeedom pour le fonctionnement en V3, modification pour V4
    $plugincomptesdir = dirname(__FILE__) . '/../../../core/css/icon/plugin-comptes';
    $pluginfontdir = dirname(__FILE__) . '/../../../core/css/icon/plugin-comptes/fonts';
 
    if (file_exists($plugincomptesdir.'/style.css')) {
		$status=unlink($plugincomptesdir.'/style.css'); 
	}
	if (file_exists($pluginfontdir.'/plugin-comptes.ttf')) {
		$status=unlink($pluginfontdir.'/plugin-comptes.ttf'); 
	}
    if (file_exists($pluginfontdir.'/plugin-comptes.svg')) {
		$status=unlink($pluginfontdir.'/plugin-comptes.svg'); 
	}
    if (file_exists($pluginfontdir.'/plugin-comptes.woff')) {
		$status=unlink($pluginfontdir.'/plugin-comptes.woff'); 
	}
    
    $status=rmdir($pluginfontdir);
    $status=rmdir($plugincomptesdir);
}


function comptes_remove() {
    
    //suppressions des fichiers de la police "icon plugins-comptes" installées dans le core jeedom
    $plugincomptesdir = dirname(__FILE__) . '/../../../data/fonts/plugin-comptes';
    $pluginfontdir = dirname(__FILE__) . '/../../../data/fonts/plugin-comptes/fonts';
 
    if (file_exists($plugincomptesdir.'/style.css')) {
		$status=unlink($plugincomptesdir.'/style.css'); 
	}
	if (file_exists($pluginfontdir.'/plugin-comptes.ttf')) {
		$status=unlink($pluginfontdir.'/plugin-comptes.ttf'); 
	}
    if (file_exists($pluginfontdir.'/plugin-comptes.svg')) {
		$status=unlink($pluginfontdir.'/plugin-comptes.svg'); 
	}
    if (file_exists($pluginfontdir.'/plugin-comptes.woff')) {
		$status=unlink($pluginfontdir.'/plugin-comptes.woff'); 
	}
    
    $status=rmdir($pluginfontdir);
    $status=rmdir($plugincomptesdir);
    
}


?>
