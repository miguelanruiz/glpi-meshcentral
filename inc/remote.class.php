<?php

// forbid direct calls of this file
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginMeshcentralRemote extends CommonDBTM {

    /**
    * Initialize config values of  plugin
    *
    * @param boolean $getOnly
    * @return array
    */
    public function initConfigModule($getOnly = false)
    {

        $input    = [];

        $input['url']                = 'http://URL_MESH';
        $input['user']               = '';
        $input['password']           = '';

        /*$input['server_upload_path'] =
              Toolbox::addslashes_deep(
                  implode(
                      DIRECTORY_SEPARATOR,
                      [
                        GLPI_PLUGIN_DOC_DIR,
                        'glpiinventory',
                        'upload'
                      ]
                  )
	      );*/

        if (!$getOnly) {
            $this->addValues($input);
        }
        return $input;
    }

    /**
    * Get configuration value with name
    *
    * @global array $MC_CONFIG
    * @param string $name name in configuration
    * @return null|string|integer
    */
    public function getValue($name)
    {
        global $MC_CONFIG;

        if (isset($MC_CONFIG[$name])) {
            return $MC_CONFIG[$name];
        }

        $config = current($this->find(['type' => $name]));
        if (isset($config['value'])) {
            return $config['value'];
        }
        return null;
    }

    /**
    * Update configuration value
    *
    * @param string $name name of configuration
    * @param string $value
    * @return boolean
    */
    public function updateValue($name, $value)
    {
        global $MC_CONFIG;

       // retrieve current config
        $config = current($this->find(['type' => $name]));

       // set in db
        if (isset($config['id'])) {
            $result = $this->update(['id' => $config['id'], 'value' => $value]);
        } else {
            $result = $this->add(['type' => $name, 'value' => $value]);
        }

       // set cache
        if ($result) {
            $MC_CONFIG[$name] = $value;
        }

        return $result;
    }

    /**
    * Add name + value in configuration if not exist
    *
    * @param string $name
    * @param string $value
    * @return integer|false integer is the id of this configuration name
    */
    public function addValue($name, $value)
    {
        $existing_value = $this->getValue($name);
        if (!is_null($existing_value)) {
            return $existing_value;
        } else {
            return $this->add(['type'  => $name,
                                 'value' => $value]);
        }
    }

    /**
    * Add multiple configuration values
    *
    * @param array $values configuration values, indexed by name
    * @param boolean $update say if add or update in database
    */
    public function addValues($values, $update = true)
    {

        foreach ($values as $type => $value) {
            if ($this->getValue($type) === null) {
                $this->addValue($type, $value);
            } elseif ($update == true) {
                $this->updateValue($type, $value);
            }
        }
    }

    /**
    * Load all configuration in global variable $PF_CONFIG
    *
    * Test if table exists before loading cache
    * The only case where table doesn't exists is when you click on
    * uninstall the plugin and it's already uninstalled
    *
    * @global object $DB
    * @global array $PF_CONFIG
    */
    public static function loadCache()
    {
        global $DB, $PF_CONFIG;

        if ($DB->tableExists('glpi_plugin_meshcentral_configs')) {
            $PF_CONFIG = [];
            foreach ($DB->request('glpi_plugin_meshcentral_configs') as $data) {
                $PF_CONFIG[$data['type']] = $data['value'];
            }
        }
    }

    /**
    * Creation of setup registry
    *
    * @return integer id of the user "Setup"
    */
    public function setDefault(){
       global $DB;

	if ($DB->getFromDB("")) {
            Toolbox::logDebug("Setup not inplemented");
        }
	
    }

    static function checkUrl(array $params) {
       echo "That line will appear on the login page!";
       Toolbox::logDebug('Enterprise Job');
    }
}
