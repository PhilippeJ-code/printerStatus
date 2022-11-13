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

    public function importer($nomImprimante)
    {
        $array = array();

        $json_file = __DIR__ . '/../../data/'.$nomImprimante.'.json';

        $string = file_get_contents($json_file);
        $array = json_decode($string, true);

        $n = count($array);
        for ($i=0; $i<$n; $i++) {
            if ($array[$i]['nom'] == 'oidSystemName') {
                $this->setConfiguration('oid_system_name', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidModel') {
                $this->setConfiguration('oid_model', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidSerial') {
                $this->setConfiguration('oid_serial', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidHote') {
                $this->setConfiguration('oid_hote', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidNoir') {
                $this->setConfiguration('oid_noir', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidJaune') {
                $this->setConfiguration('oid_jaune', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidMagenta') {
                $this->setConfiguration('oid_magenta', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidCyan') {
                $this->setConfiguration('oid_cyan', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidNoirMax') {
                $this->setConfiguration('oid_noir_max', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidJauneMax') {
                $this->setConfiguration('oid_jaune_max', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidMagentaMax') {
                $this->setConfiguration('oid_magenta_max', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidCyanMax') {
                $this->setConfiguration('oid_cyan_max', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidPagesCouleur') {
                $this->setConfiguration('oid_pages_couleur', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidPagesMonochrome') {
                $this->setConfiguration('oid_pages_monochrome', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidPagesTotal') {
                $this->setConfiguration('oid_pages_total', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidRefNoir') {
                $this->setConfiguration('oid_ref_noir', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidRefJaune') {
                $this->setConfiguration('oid_ref_jaune', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidRefMagenta') {
                $this->setConfiguration('oid_ref_magenta', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidRefCyan') {
                $this->setConfiguration('oid_ref_cyan', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidBacPolyvalent') {
                $this->setConfiguration('oid_bac_polyvalent', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidBacCassette1') {
                $this->setConfiguration('oid_bac_cassette_1', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidBacCassette1Max') {
                $this->setConfiguration('oid_bac_cassette_1_max', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidState') {
                $this->setConfiguration('oid_state', $array[$i]['valeur']);
            } elseif ($array[$i]['nom'] == 'oidTimeTicks') {
                $this->setConfiguration('oid_timeticks', $array[$i]['valeur']);
            }
        }

        $this->save();

        return $array;
    }

    public function exporter($nomImprimante)
    {
        $array = array();

        $json_file = __DIR__ . '/../../data/'.$nomImprimante.'.json';

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
        $oidState = $this->getConfiguration('oid_state', '');
        $oidTimeTicks = $this->getConfiguration('oid_timeticks', '');

        $array[] = array('nom'=>'oidSystemName','valeur'=>$oidSystemName);
        $array[] = array('nom'=>'oidModel','valeur'=>$oidModel);
        $array[] = array('nom'=>'oidSerial','valeur'=>$oidSerial);
        $array[] = array('nom'=>'oidHote','valeur'=>$oidHote);
        $array[] = array('nom'=>'oidNoir','valeur'=>$oidNoir);
        $array[] = array('nom'=>'oidJaune','valeur'=>$oidJaune);
        $array[] = array('nom'=>'oidMagenta','valeur'=>$oidMagenta);
        $array[] = array('nom'=>'oidCyan','valeur'=>$oidCyan);
        $array[] = array('nom'=>'oidNoirMax','valeur'=>$oidNoirMax);
        $array[] = array('nom'=>'oidJauneMax','valeur'=>$oidJauneMax);
        $array[] = array('nom'=>'oidMagentaMax','valeur'=>$oidMagentaMax);
        $array[] = array('nom'=>'oidCyanMax','valeur'=>$oidCyanMax);
        $array[] = array('nom'=>'oidPagesCouleur','valeur'=>$oidPagesCouleur);
        $array[] = array('nom'=>'oidPagesMonochrome','valeur'=>$oidPagesMonochrome);
        $array[] = array('nom'=>'oidPagesTotal','valeur'=>$oidPagesTotal);
        $array[] = array('nom'=>'oidRefNoir','valeur'=>$oidRefNoir);
        $array[] = array('nom'=>'oidRefJaune','valeur'=>$oidRefJaune);
        $array[] = array('nom'=>'oidRefMagenta','valeur'=>$oidRefMagenta);
        $array[] = array('nom'=>'oidRefCyan','valeur'=>$oidRefCyan);
        $array[] = array('nom'=>'oidBacPolyvalent','valeur'=>$oidBacPolyvalent);
        $array[] = array('nom'=>'oidBacCassette1','valeur'=>$oidBacCassette1);
        $array[] = array('nom'=>'oidBacCassette1Max','valeur'=>$oidBacCassette1Max);
        $array[] = array('nom'=>'oidState','valeur'=>$oidState);
        $array[] = array('nom'=>'oidTimeTicks','valeur'=>$oidTimeTicks);

        $json = json_encode($array, JSON_PRETTY_PRINT);
        file_put_contents($json_file, $json);

        return array();
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
        $oidState = $this->getConfiguration('oid_state', '');
        $oidTimeTicks = $this->getConfiguration('oid_timeticks', '');

        if ($adresseIp === '') {
            return;
        }

        $ping_check=exec('/bin/ping -c2 -q -w2 '.$adresseIp.' | grep transmitted | cut -f3 -d"," | cut -f1 -d"," | cut -f1 -d"%"');
        if ($ping_check != 0) {
            $this->getCmd(null, 'state')->event('Hors ligne');
            return;
        } else {
            $this->getCmd(null, 'state')->event('En ligne');
        }

        try {
            snmp_set_valueretrieval(SNMP_VALUE_PLAIN);

            if ($oidSystemName !== '') {
                $systemName = snmpget($adresseIp, 'public', $oidSystemName, 200000, 1);
                if ($systemName != false) {
                    $this->getCmd(null, 'system_name')->event($systemName);
                }
            } else {
                $this->getCmd(null, 'system_name')->event('');
            }
            if ($oidModel !== '') {
                $model = snmpget($adresseIp, 'public', $oidModel, 200000, 1);
                if ($model != false) {
                    $this->getCmd(null, 'model')->event($model);
                }
            } else {
                $this->getCmd(null, 'model')->event('');
            }
            if ($oidSerial !== '') {
                $serial = snmpget($adresseIp, 'public', $oidSerial, 200000, 1);
                if ($serial != false) {
                    $this->getCmd(null, 'serial')->event($serial);
                }
            } else {
                $this->getCmd(null, 'serial')->event('');
            }
            if ($oidHote !== '') {
                $hote = snmpget($adresseIp, 'public', $oidHote, 200000, 1);
                if ($hote != false) {
                    $this->getCmd(null, 'hote')->event($hote);
                }
            } else {
                $this->getCmd(null, 'hote')->event('');
            }

            $noirMax = 1;
            if ($oidNoirMax !== '') {
                $noirMax = snmpget($adresseIp, 'public', $oidNoirMax, 200000, 1);
            }
            if ($noirMax == false) {
                $noirMax = 1;
            }
            $jauneMax = 1;
            if ($oidJaune !== '') {
                $jauneMax = snmpget($adresseIp, 'public', $oidJauneMax, 200000, 1);
            }
            if ($jauneMax == false) {
                $jauneMax = 1;
            }
            $magentaMax = 1;
            if ($oidMagenta !== '') {
                $magentaMax = snmpget($adresseIp, 'public', $oidMagentaMax, 200000, 1);
            }
            if ($magentaMax == false) {
                $magentaMax = 1;
            }
            $cyanMax = 1;
            if ($oidCyanMax !== '') {
                $cyanMax = snmpget($adresseIp, 'public', $oidCyanMax, 200000, 1);
            }
            if ($cyanMax == false) {
                $cyanMax = 1;
            }

            if ($oidNoir !== '') {
                $noir = snmpget($adresseIp, 'public', $oidNoir, 200000, 1);
                if ($noir != false) {
                    $this->getCmd(null, 'noir')->event(round((intval($noir)*100)/intval($noirMax)), 0);
                }
            } else {
                $this->getCmd(null, 'noir')->event('');
            }

            if ($oidJaune !== '') {
                $jaune = snmpget($adresseIp, 'public', $oidJaune, 200000, 1);
                if ($jaune != false) {
                    $this->getCmd(null, 'jaune')->event(round((intval($jaune)*100)/intval($jauneMax)), 0);
                }
            } else {
                $this->getCmd(null, 'jaune')->event('');
            }

            if ($oidMagenta !== '') {
                $magenta = snmpget($adresseIp, 'public', $oidMagenta, 200000, 1);
                if ($magenta != false) {
                    $this->getCmd(null, 'magenta')->event(round((intval($magenta)*100)/intval($magentaMax)), 0);
                }
            } else {
                $this->getCmd(null, 'magenta')->event('');
            }
            if ($oidCyan !== '') {
                $cyan = snmpget($adresseIp, 'public', $oidCyan, 200000, 1);
                if ($cyan != false) {
                    $this->getCmd(null, 'cyan')->event(round((intval($cyan)*100)/intval($cyanMax)), 0);
                }
            } else {
                $this->getCmd(null, 'cyan')->event('');
            }

            if ($oidPagesCouleur !== '') {
                $pagesCouleur = snmpget($adresseIp, 'public', $oidPagesCouleur, 200000, 1);
                if ($pagesCouleur != false) {
                    $this->getCmd(null, 'pages_couleur')->event(intval($pagesCouleur));
                }
            } else {
                $this->getCmd(null, 'pages_couleur')->event(0);
            }
            if ($oidPagesMonochrome !== '') {
                $pagesMonochrome = snmpget($adresseIp, 'public', $oidPagesMonochrome, 200000, 1);
                if ($pagesMonochrome != false) {
                    $this->getCmd(null, 'pages_monochrome')->event(intval($pagesMonochrome));
                }
            } else {
                $this->getCmd(null, 'pages_monochrome')->event(0);
            }
            if ($oidPagesTotal !== '') {
                $pagesTotal = snmpget($adresseIp, 'public', $oidPagesTotal, 200000, 1);
                if ($pagesTotal != false) {
                    $this->getCmd(null, 'pages_total')->event(intval($pagesTotal));
                }
            } else {
                $this->getCmd(null, 'pages_total')->event(0);
            }

            if ($oidPagesMonochrome == '') {
                $pagesMonochrome = $pagesTotal - $pagesCouleur;
            }

            if ($oidRefNoir !== '') {
                $refNoir = snmpget($adresseIp, 'public', $oidRefNoir, 200000, 1);
                if ($refNoir != false) {
                    $this->getCmd(null, 'ref_noir')->event($refNoir);
                }
            } else {
                $this->getCmd(null, 'ref_noir')->event('');
            }
            if ($oidRefJaune !== '') {
                $refJaune = snmpget($adresseIp, 'public', $oidRefJaune, 200000, 1);
                if ($refJaune != false) {
                    $this->getCmd(null, 'ref_jaune')->event($refJaune);
                }
            } else {
                $this->getCmd(null, 'ref_jaune')->event('');
            }
            if ($oidRefMagenta !== '') {
                $refMagenta = snmpget($adresseIp, 'public', $oidRefMagenta, 200000, 1);
                if ($refMagenta != false) {
                    $this->getCmd(null, 'ref_magenta')->event($refMagenta);
                }
            } else {
                $this->getCmd(null, 'ref_magenta')->event('');
            }
            if ($oidRefCyan !== '') {
                $refCyan = snmpget($adresseIp, 'public', $oidRefCyan, 200000, 1);
                if ($refCyan != false) {
                    $this->getCmd(null, 'ref_cyan')->event($refCyan);
                }
            } else {
                $this->getCmd(null, 'ref_cyan')->event('');
            }
            if ($oidBacPolyvalent !== '') {
                $bacPolyvalent = snmpget($adresseIp, 'public', $oidBacPolyvalent, 200000, 1);
                if ($bacPolyvalent != false) {
                    $this->getCmd(null, 'bac_polyvalent')->event(intval($bacPolyvalent));
                }
            }
            $bacCassette1Max = 1;
            if ($oidBacCassette1Max !== '') {
                $bacCassette1Max = snmpget($adresseIp, 'public', $oidBacCassette1Max, 200000, 1);
            }
            if ($bacCassette1Max == 0) {
                $bacCassette1Max = 1;
            }
            if ($oidBacCassette1 !== '') {
                $bacCassette1 = snmpget($adresseIp, 'public', $oidBacCassette1, 200000, 1);
                if ($bacCassette1 != false) {
                    $this->getCmd(null, 'bac_cassette1')->event((intval($bacCassette1)*100)/intval($bacCassette1Max));
                }
            }

            if ($oidState !== '') {
                $state = snmpget($adresseIp, 'public', $oidState, 200000, 1);
                if ($state != false) {
                    $this->getCmd(null, 'state')->event($state);
                }
            }

            if ($oidTimeTicks !== '') {
                $durationInSeconds = intval(snmpget($adresseIp, 'public', $oidTimeTicks, 200000, 1)/100);

                $duration = '';
                $days = floor($durationInSeconds / 86400);
                $durationInSeconds -= $days * 86400;
                $hours = floor($durationInSeconds / 3600);
                $durationInSeconds -= $hours * 3600;
                $minutes = floor($durationInSeconds / 60);
                $seconds = $durationInSeconds - $minutes * 60;

                if ($days > 0) {
                    $duration .= $days . ' j';
                }
                if ($hours > 0) {
                    $duration .= ' ' . $hours . ' h';
                }
                if ($minutes > 0) {
                    $duration .= ' ' . $minutes . ' m';
                }
                if ($seconds > 0) {
                    $duration .= ' ' . $seconds . ' s';
                }
                $this->getCmd(null, 'activity_duration')->event($duration);
            } else {
                $this->getCmd(null, 'activity_duration')->event('');
            }
        } catch (Throwable $t) {
            log::add('printerStatus', 'error', $t->getMessage());
        } catch (Exception $e) {
            log::add('printerStatus', 'error', $e->getMessage());
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

        $obj = $this->getCmd(null, 'state');
        if (!is_object($obj)) {
            $obj = new printerStatusCmd();
            $obj->setName(__('Etat', __FILE__));
            $obj->setIsVisible(1);
            $obj->setIsHistorized(0);
        }
        $obj->setEqLogic_id($this->getId());
        $obj->setType('info');
        $obj->setSubType('string');
        $obj->setLogicalId('state');
        $obj->save();

        $obj = $this->getCmd(null, 'activity_duration');
        if (!is_object($obj)) {
            $obj = new printerStatusCmd();
            $obj->setName(__('Temps activité', __FILE__));
            $obj->setIsVisible(1);
            $obj->setIsHistorized(0);
        }
        $obj->setEqLogic_id($this->getId());
        $obj->setType('info');
        $obj->setSubType('string');
        $obj->setLogicalId('activity_duration');
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

        $obj = $this->getCmd(null, 'state');
        $replace["#etat#"] = $obj->execCmd();
        $replace["#idEtat#"] = $obj->getId();

        $obj = $this->getCmd(null, 'activity_duration');
        $replace["#tempsActivite#"] = $obj->execCmd();
        $replace["#idTempsActivite#"] = $obj->getId();

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
