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

/**
 * Plugin install process
 *
 * @return boolean
 */
function plugin_meshcentral_install() {
   global $CFG_GLPI, $DB, $MC_CONFIG;

   //instanciate migration with version
   $default_charset = DBConnection::getDefaultCharset();
   $default_collation = DBConnection::getDefaultCollation();
   $default_key_sign = DBConnection::getDefaultPrimaryKeySignOption();
   
   $migration = new Migration(PLUGIN_MESHCENTRAL_VERSION);

   $table = getTableForItemtype('PluginMeshcentralConfig');
   //Create table only if it does not exists yet!
   if (!$DB->tableExists($table)) {
      //table creation query
      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_meshcentral_configs` (
	          `id` INT {$default_key_sign} NOT NULL auto_increment,
                  `type` VARCHAR(255) DEFAULT NULL,
                  `value` VARCHAR(255) DEFAULT NULL,
                  PRIMARY KEY  (`id`),
		  UNIQUE KEY `unicity` (`type`)
	       ) ENGINE=InnoDB DEFAULT CHARSET={$default_charset} COLLATE={$default_collation} ROW_FORMAT=DYNAMIC;";
      $DB->queryOrDie($query, $DB->error());
   }

   $migration->displayMessage("Initialize configuration");

   $input = [];

   $input['url']                = 'http://URL_MESH';
   $input['user']               = '';
   $input['password']           = '';

   $config = new PluginMeshcentralConfig();
   $config->addValues($input, false);

   $migration->executeMigration();

   return true;
}

/**
 * Plugin uninstall process
 *
 * @return boolean
 */
function plugin_meshcentral_uninstall() {
   global $DB;

   $tables = [
      'configs'
   ];

   foreach ($tables as $table) {
      $tablename = 'glpi_plugin_meshcentral_' . $table;
      //Create table only if it does not exists yet!
      if ($DB->tableExists($tablename)) {
         $DB->queryOrDie(
            "DROP TABLE `$tablename`",
            $DB->error()
         );
      }
   }

   return true;
}

/**
  * Display informations on login page
  *
  * @return void
  */
/*public function meshcentral_ () {
   echo "That line will appear on the login page!";
}*/
/**
 * Function that handle a hook with array of parameters
 *
 * @param array $params Array of parameters
 *
 * @return void
 */
function plugin_meshcentral_item_checkurl($params) {
   echo "That line will appear on the login page!";
       Toolbox::logDebug('Enterprise Job');
   print_r($params);
   //Will display:
   //Array
   //(
   //   [item] => Computer Object
   //      (...)
   //
   //   [options] => Array
   //      (
   //            [_target] => /front/computer.form.php
   //            [id] => 1
   //            [withtemplate] =>
   //            [tabnum] => 1
   //            [itemtype] => Computer
   //      )
   //)
}
