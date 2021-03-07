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
          $oidModel = $this->getConfiguration('oid_model', '');
          $oidSerial = $this->getConfiguration('oid_serial', '');

          if ($adresse_ip === '') {
              return;
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

        return;
      }

      public static function cron15()
      {
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
