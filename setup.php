<?php
/**
 * -------------------------------------------------------------------------
 * MeshCentral plugin for GLPI
 * Copyright (C) 2022 by the MeshCentral Development Team.
 * -------------------------------------------------------------------------
 *
 * MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * --------------------------------------------------------------------------
 */

use Glpi\Plugin\Hooks;

define('PLUGIN_MESHCENTRAL_VERSION', '0.0.1');

// Minimal GLPI version, inclusive
define("PLUGIN_MESHCENTRAL_MIN_GLPI_VERSION", "10.0.0");
// Maximum GLPI version, exclusive
define("PLUGIN_MESHCENTRAL_MAX_GLPI_VERSION", "10.0.99");

//cache config
$MC_CONFIG = [];

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_meshcentral() {
   global $PLUGIN_HOOKS, $MC_CONFIG, $CFG_GLPI;

   $Plugin = new Plugin();

   $PLUGIN_HOOKS['csrf_compliant']['meshcentral'] = true;
	$PLUGIN_HOOKS[Hooks::ITEM_ADD]['meshcentral'] = ['Computer', ['PluginMeshcentralRemote' => 'checkUrl'] ];

   //Session::getLoginUserID()
   if ($Plugin->isActivated('meshcentral')) { // check if plugin is active
	$PLUGIN_HOOKS['menu_toadd']['meshcentral'] = [
	   'title' => __("MeshCentral", 'meshcentral'),
	   //On Plugins menu
	   //'plugins' => 'PluginMeshcentralConfig'
	   'admin' => 'PluginMeshcentralConfig'
	   ];
	$PLUGIN_HOOKS['config_page']['meshcentral'] = 'front/config.form.php';
	//$PLUGIN_HOOKS['post_item_form']['meshcentral'] = [
	/*$PLUGIN_HOOKS[Hooks::POST_ITEM_FORM]['meshcentral'] = [
	   //'Item_RemoteManagement' , ['PluginMeshcentralRemote' => 'checkUrl']
	   //'Computer' , ['PluginMeshcentralConfig' => 'checkUrl']
	   Item_RemoteManagement::class => 'checkUrl'
	   //'Item_RemoteManagement', 'plugin_meshcentral_hook_checkurl'
	   //'Item_RemoteManagement' => 'plugin_meshcentral_hook_checkurl'
	];*/
	$PLUGIN_HOOKS[Hooks::POST_ITEM_FORM]['meshcentral'] = ['Computer'=> 'plugin_meshcentral_item_checkurl'];
	/*$Plugin::registerClass('PluginMeshcentralConfig',
		['addtabon' => 'Computer']);*/
   }
}


/**
 * Get the name and the version of the plugin
 * REQUIRED
 *
 * @return array
 */
function plugin_version_meshcentral() {
   return [
      'name'           => 'MeshCentral',
      'shortname'      => 'meshcentral',
      'version'        => PLUGIN_MESHCENTRAL_VERSION,
      'author'         => 'Imagunet, Miguel Ruiz',
      'license'        => 'GPLv3+',
      'homepage'       => '',
      'requirements'   => [
         'glpi' => [
            'min' => PLUGIN_MESHCENTRAL_MIN_GLPI_VERSION,
            'max' => PLUGIN_MESHCENTRAL_MAX_GLPI_VERSION,
         ]
      ]
   ];
}

/**
 * Check pre-requisites before install
 * OPTIONNAL, but recommanded
 *
 * @return boolean
 */
function plugin_meshcentral_check_prerequisites() {
   return true;
}

/**
 * Check configuration process
 *
 * @param boolean $verbose Whether to display message on failure. Defaults to false
 *
 * @return boolean
 */
function plugin_meshcentral_check_config($verbose = false) {
   if (true) { // Your configuration check
      return true;
   }

   if ($verbose) {
      echo __('Installed / not configured', '{LNAME}');
   }
   return false;
}
