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

  require_once __DIR__  . '/../../../../core/php/core.inc.php';

  class printerStatus extends eqLogic
  {

      // Statut des dépendances
      //
      public static function dependancy_info()
      {
          $return = array();
          $return['log'] = 'printerStatus_dep';
          $cmd = "dpkg -l php*-snmp*| grep snmp";
          exec($cmd, $output, $returnVar);
          if ($output[0] != "") {
              $return['state'] = 'ok';
          } else {
              $return['state'] = 'nok';
          }
          return $return;
      }

      // Installation des dépendances
      //
      public function dependancy_install()
      {
          log::add('printerStatus', 'info', 'Installation des dependances php-snmp');
          passthru('sudo apt install php-snmp -y >> ' . log::getPathToLog('printerStatus_dep') . ' 2>&1 &');
      }

      public function rafraichir()
      {
          $adresseIp = $this->getConfiguration('adresse_ip', '');
          $oidSystemName = $this->getConfiguration('oid_system_name', '');
          $oidModel = $this->getConfiguration('oid_model', '');
          $oidSerial = $this->getConfiguration('oid_serial', '');
          $oidHote = $this->getConfiguration('oid_hote', '');
          $oidNoir = $this->getConfiguration('oid_noir', '');
          $oidJaune = $this->getConfiguration('oid_jaune', '');
          $oidMagenta = $this->getConfiguration('oid_magenta', '');
          $oidCyan = $this->getConfiguration('oid_cyan', '');
          $oidNoirMax = $this->getConfiguration('oid_noir_max', '');
          $oidJauneMax = $this->getConfiguration('oid_jaune_max', '');
          $oidMagentaMax = $this->getConfiguration('oid_magenta_max', '');
          $oidCyanMax = $this->getConfiguration('oid_cyan_max', '');
          $oidPagesCouleur = $this->getConfiguration('oid_pages_couleur', '');
          $oidPagesMonochrome = $this->getConfiguration('oid_pages_monochrome', '');
          $oidPagesTotal = $this->getConfiguration('oid_pages_total', '');
          $oidRefNoir = $this->getConfiguration('oid_ref_noir', '');
          $oidRefJaune = $this->getConfiguration('oid_ref_jaune', '');
          $oidRefMagenta = $this->getConfiguration('oid_ref_magenta', '');
          $oidRefCyan = $this->getConfiguration('oid_ref_cyan', '');
          $oidBacPolyvalent = $this->getConfiguration('oid_bac_polyvalent', '');
          $oidBacCassette1 = $this->getConfiguration('oid_bac_cassette_1', '');
          $oidBacCassette1Max = $this->getConfiguration('oid_bac_cassette_1_max', '');

          if ($adresseIp === '') {
              $this->getCmd(null, 'bac_cassette1')->event(rand(0, 100));
              $this->getCmd(null, 'noir')->event(rand(0, 100));
              $this->getCmd(null, 'jaune')->event(rand(0, 100));
              $this->getCmd(null, 'magenta')->event(rand(0, 100));
              $this->getCmd(null, 'cyan')->event(rand(0, 100));

              return;
          }

          snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
          
          if ($oidSystemName !== '') {
              try {
                  $systemName = snmpget($adresseIp, 'public', $oidSystemName, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'system_name')->event($systemName);
              }
          }

          if ($oidModel !== '') {
              try {
                  $model = snmpget($adresseIp, 'public', $oidModel, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'model')->event($model);
              }
          }

          if ($oidSerial !== '') {
              try {
                  $serial = snmpget($adresseIp, 'public', $oidSerial, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'serial')->event($serial);
              }
          }

          if ($oidHote !== '') {
              try {
                  $hote = snmpget($adresseIp, 'public', $oidHote, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'hote')->event($hote);
              }
          }

          $noirMax = 1;
          if ($oidNoirMax !== '') {
              try {
                  $noirMax = snmpget($adresseIp, 'public', $oidNoirMax, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              }
          }
          if ($noirMax == 0) {
              $noirMax = 1;
          }

          $jauneMax = 1;
          if ($oidJaune !== '') {
              try {
                  $jauneMax = snmpget($adresseIp, 'public', $oidJauneMax, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              }
          }
          if ($jauneMax == 0) {
              $jauneMax = 1;
          }

          $magentaMax = 1;
          if ($oidMagenta !== '') {
              try {
                  $magentaMax = snmpget($adresseIp, 'public', $oidMagentaMax, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              }
          }
          if ($magentaMax == 0) {
              $magentaMax = 1;
          }

          $cyanMax = 1;
          if ($oidCyanMax !== '') {
              try {
                  $cyanMax = snmpget($adresseIp, 'public', $oidCyanMax, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              }
          }
          if ($cyanMax == 0) {
              $cyanMax = 1;
          }

          if ($oidNoir !== '') {
              try {
                  $noir = snmpget($adresseIp, 'public', $oidNoir, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'noir')->event(intval($noir)*100/intval($noirMax));
              }
          }

          if ($oidJaune !== '') {
              try {
                  $jaune = snmpget($adresseIp, 'public', $oidJaune, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'jaune')->event(intval($jaune)*100/intval($jauneMax));
              }
          }

          if ($oidMagenta !== '') {
              try {
                  $magenta = snmpget($adresseIp, 'public', $oidMagenta, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'magenta')->event(intval($magenta)*100/intval($magentaMax));
              }
          }

          if ($oidCyan !== '') {
              try {
                  $cyan = snmpget($adresseIp, 'public', $oidCyan, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'cyan')->event(intval($cyan)*100/intval($cyanMax));
              }
          }

          if ($oidPagesCouleur !== '') {
              try {
                  $pagesCouleur = snmpget($adresseIp, 'public', $oidPagesCouleur, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'pages_couleur')->event(intval($pagesCouleur));
              }
          }

          if ($oidPagesMonochrome !== '') {
              try {
                  $pagesMonochrome = snmpget($adresseIp, 'public', $oidPagesMonochrome, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'pages_monochrome')->event(intval($pagesMonochrome));
              }
          }

          if ($oidPagesTotal !== '') {
              try {
                  $pagesTotal = snmpget($adresseIp, 'public', $oidPagesTotal, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'pages_total')->event(intval($pagesTotal));
              }
          }

          if ($oidRefNoir !== '') {
              try {
                  $refNoir = snmpget($adresseIp, 'public', $oidRefNoir, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'ref_noir')->event($refNoir);
              }
          }

          if ($oidRefJaune !== '') {
              try {
                  $refJaune = snmpget($adresseIp, 'public', $oidRefJaune, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'ref_jaune')->event($refJaune);
              }
          }

          if ($oidRefMagenta !== '') {
              try {
                  $refMagenta = snmpget($adresseIp, 'public', $oidRefMagenta, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'ref_magenta')->event($refMagenta);
              }
          }

          if ($oidRefCyan !== '') {
              try {
                  $refCyan = snmpget($adresseIp, 'public', $oidRefCyan, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'ref_cyan')->event($refCyan);
              }
          }
          
          if ($oidBacPolyvalent !== '') {
              try {
                  $bacPolyvalent = snmpget($adresseIp, 'public', $oidBacPolyvalent, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'bac_polyvalent')->event(intval($bacPolyvalent));
              }
          }

          $bacCassette1Max = 1;
          if ($oidBacCassette1Max !== '') {
              try {
                  $bacCassette1Max = snmpget($adresseIp, 'public', $oidBacCassette1Max, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              }
          }
          if ($bacCassette1Max == 0) {
              $bacCassette1Max = 1;
          }
          
          if ($oidBacCassette1 !== '') {
              try {
                  $bacCassette1 = snmpget($adresseIp, 'public', $oidBacCassette1, 50000, 1);
              } catch (Throwable $t) {
                  log::add('printerStatus', 'error', $t->getMessage());
              } catch (Exception $e) {
                  log::add('printerStatus', 'error', $e->getMessage());
              } finally {
                  $this->getCmd(null, 'bac_cassette1')->event(intval($bacCassette1)*100/intval($bacCassette1Max));
              }
          }

          return;
      }

      public static function periodique()
      {
          foreach (self::byType('printerStatus') as $printerStatus) {
              if ($printerStatus->getIsEnable() == 1) {
                  $cmd = $printerStatus->getCmd(null, 'refresh');
                  if (!is_object($cmd)) {
                      continue;
                  }
                  $cmd->execCmd();
              }
          }
      }

      public static function cron()
      {
          self::periodique();
      }
    
      public static function cron5()
      {
          self::periodique();
      }
    
      public static function cron10()
      {
          self::periodique();
      }
    
      public static function cron15()
      {
          self::periodique();
      }
    
      public static function cron30()
      {
          self::periodique();
      }
    
      public static function cronHourly()
      {
          self::periodique();
      }
    
      public static function cronDaily()
      {
          self::periodique();
      }

      // Fonction exécutée automatiquement avant la création de l'équipement
      //
      public function preInsert()
      {
      }

      // Fonction exécutée automatiquement après la création de l'équipement
      //
      public function postInsert()
      {
      }

      // Fonction exécutée automatiquement avant la mise à jour de l'équipement
      //
      public function preUpdate()
      {
      }

      // Fonction exécutée automatiquement après la mise à jour de l'équipement
      //
      public function postUpdate()
      {
      }

      // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
      //
      public function preSave()
      {
      }

      // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
      //
      public function postSave()
      {
          $obj = $this->getCmd(null, 'refresh');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Rafraichir', __FILE__));
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setLogicalId('refresh');
          $obj->setType('action');
          $obj->setSubType('other');
          $obj->save();

          $obj = $this->getCmd(null, 'system_name');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Nom système', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('string');
          $obj->setLogicalId('system_name');
          $obj->save();

          $obj = $this->getCmd(null, 'model');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Modèle', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('string');
          $obj->setLogicalId('model');
          $obj->save();

          $obj = $this->getCmd(null, 'serial');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Numéro de série', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('string');
          $obj->setLogicalId('serial');
          $obj->save();

          $obj = $this->getCmd(null, 'hote');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Nom hôte', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('string');
          $obj->setLogicalId('hote');
          $obj->save();

          $obj = $this->getCmd(null, 'noir');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Toner noir', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('numeric');
          $obj->setLogicalId('noir');
          $obj->save();

          $obj = $this->getCmd(null, 'jaune');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Toner jaune', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('numeric');
          $obj->setLogicalId('jaune');
          $obj->save();

          $obj = $this->getCmd(null, 'magenta');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Toner magenta', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('numeric');
          $obj->setLogicalId('magenta');
          $obj->save();

          $obj = $this->getCmd(null, 'cyan');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Toner cyan', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('numeric');
          $obj->setLogicalId('cyan');
          $obj->save();

          $obj = $this->getCmd(null, 'pages_couleur');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Nombre pages couleur', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('numeric');
          $obj->setLogicalId('pages_couleur');
          $obj->save();

          $obj = $this->getCmd(null, 'pages_monochrome');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Nombre pages monochrome', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('numeric');
          $obj->setLogicalId('pages_monochrome');
          $obj->save();

          $obj = $this->getCmd(null, 'pages_total');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Nombre pages total', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('numeric');
          $obj->setLogicalId('pages_total');
          $obj->save();

          $obj = $this->getCmd(null, 'ref_noir');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Référence toner noir', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('string');
          $obj->setLogicalId('ref_noir');
          $obj->save();

          $obj = $this->getCmd(null, 'ref_jaune');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Référence toner jaune', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('string');
          $obj->setLogicalId('ref_jaune');
          $obj->save();

          $obj = $this->getCmd(null, 'ref_magenta');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Référence toner magenta', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('string');
          $obj->setLogicalId('ref_magenta');
          $obj->save();

          $obj = $this->getCmd(null, 'ref_cyan');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Référence toner cyan', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('string');
          $obj->setLogicalId('ref_cyan');
          $obj->save();

          $obj = $this->getCmd(null, 'bac_polyvalent');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Niveau bac polyvalent', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('numeric');
          $obj->setLogicalId('bac_polyvalent');
          $obj->save();

          $obj = $this->getCmd(null, 'bac_cassette1');
          if (!is_object($obj)) {
              $obj = new printerStatusCmd();
              $obj->setName(__('Niveau bac cassette 1', __FILE__));
              $obj->setIsVisible(1);
              $obj->setIsHistorized(0);
          }
          $obj->setEqLogic_id($this->getId());
          $obj->setType('info');
          $obj->setSubType('numeric');
          $obj->setLogicalId('bac_cassette1');
          $obj->save();
      }

      // Fonction exécutée automatiquement avant la suppression de l'équipement
      //
      public function preRemove()
      {
      }

      // Fonction exécutée automatiquement après la suppression de l'équipement
      //
      public function postRemove()
      {
      }
  
      // Permet de modifier l'affichage du widget (également utilisable par les commandes)
      //
      public function toHtml($_version = 'dashboard')
      {
          $isWidgetPlugin = $this->getConfiguration('isWidgetPlugin');

          if (!$isWidgetPlugin) {
              return eqLogic::toHtml($_version);
          }

          $replace = $this->preToHtml($_version);
          if (!is_array($replace)) {
              return $replace;
          }
          $version = jeedom::versionAlias($_version);
 
          $obj = $this->getCmd(null, 'system_name');
          $replace["#systemName#"] = $obj->execCmd();
          $replace["#idSystemName#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'model');
          $replace["#model#"] = $obj->execCmd();
          $replace["#idModel#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'serial');
          $replace["#serial#"] = $obj->execCmd();
          $replace["#idSerial#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'hote');
          $replace["#hote#"] = $obj->execCmd();
          $replace["#idHote#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'noir');
          $replace["#noir#"] = $obj->execCmd();
          $replace["#idNoir#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'jaune');
          $replace["#jaune#"] = $obj->execCmd();
          $replace["#idJaune#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'magenta');
          $replace["#magenta#"] = $obj->execCmd();
          $replace["#idMagenta#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'cyan');
          $replace["#cyan#"] = $obj->execCmd();
          $replace["#idCyan#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'pages_couleur');
          $replace["#pagesCouleur#"] = $obj->execCmd();
          $replace["#idPagesCouleur#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'pages_monochrome');
          $replace["#pagesMonochrome#"] = $obj->execCmd();
          $replace["#idPagesMonochrome#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'pages_total');
          $replace["#pagesTotal#"] = $obj->execCmd();
          $replace["#idPagesTotal#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'ref_noir');
          $replace["#refNoir#"] = $obj->execCmd();
          $replace["#idRefNoir#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'ref_jaune');
          $replace["#refJaune#"] = $obj->execCmd();
          $replace["#idRefJaune#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'ref_magenta');
          $replace["#refMagenta#"] = $obj->execCmd();
          $replace["#idRefMagenta#"] = $obj->getId();
    
          $obj = $this->getCmd(null, 'ref_cyan');
          $replace["#refCyan#"] = $obj->execCmd();
          $replace["#idRefCyan#"] = $obj->getId();

          $replace["#adresse_ip#"] = $this->getConfiguration('adresse_ip', '');
    
          $obj = $this->getCmd(null, 'bac_polyvalent');
          $replace["#bacPolyvalent#"] = $obj->execCmd();
          $replace["#idBacPolyvalent#"] = $obj->getId();

          $obj = $this->getCmd(null, 'bac_cassette1');
          $replace["#bacCassette1#"] = $obj->execCmd();
          $replace["#idBacCassette1#"] = $obj->getId();

          return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'printerStatus_view', 'printerStatus')));
      }
  }
  
  class printerStatusCmd extends cmd
  {
      // Exécution d'une commande
      //
      public function execute($_options = array())
      {
          $eqlogic = $this->getEqLogic();
          switch ($this->getLogicalId()) {
            case 'refresh':
                $info = $eqlogic->rafraichir();
                break;
        }
      }
  }
