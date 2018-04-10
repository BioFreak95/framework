<?php
/**
 * This file is part of OPUS. The software OPUS has been originally developed
 * at the University of Stuttgart with funding from the German Research Net,
 * the Federal Department of Higher Education and Research and the Ministry
 * of Science, Research and the Arts of the State of Baden-Wuerttemberg.
 *
 * OPUS 4 is a complete rewrite of the original OPUS software and was developed
 * by the Stuttgart University Library, the Library Service Center
 * Baden-Wuerttemberg, the Cooperative Library Network Berlin-Brandenburg,
 * the Saarland University and State Library, the Saxon State Library -
 * Dresden State and University Library, the Bielefeld University Library and
 * the University Library of Hamburg University of Technology with funding from
 * the German Research Foundation and the European Regional Development Fund.
 *
 * LICENCE
 * OPUS is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the Licence, or any later version.
 * OPUS is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details. You should have received a copy of the GNU General Public License
 * along with OPUS; if not, write to the Free Software Foundation, Inc., 51
 * Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * @category    Framework
 * @package     Opus_Doi
 * @author      Sascha Szott <szott@zib.de>
 * @copyright   Copyright (c) 2018, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 */

class Opus_Doi_Generator_DefaultGenerator implements Opus_Doi_Generator_DoiGeneratorInterface {

    private $config;

    public function __construct() {
        $this->config = Zend_Registry::get('Zend_Config');
    }

    /**
     * Erzeugt auf Basis der Konfigurationseinstellungen eine DOI in der Form
     *
     * doi.prefix/doi.localPrefix-docId
     *
     * Ist doi.localPrefix in der Konfiguration nicht gesetzt, so wird die Form
     *
     * doi.prefix/docId
     *
     * verwendet.
     *
     * Schrägstrich / bzw. Bindestrich - werden im Bedarfsfall eingefügt, sofern in den Konfigurationswerten
     * nicht angegeben.
     *
     * Der Konfigurationsparameter doi.suffixFormat wird von dieser DOI-Generierungsklasse NICHT berücksichtigt.
     * Er ist fest auf {docId} gesetzt.
     *
     */
    public function generate($document) {

        if (!isset($this->config->doi->prefix) or $this->config->doi->prefix == '') {
            throw new Opus_Doi_Generator_DoiGeneratorException('configuration setting doi.prefix is missing - DOI cannot be generated');
        }
        $prefix = $this->config->doi->prefix;
        // Schrägstrich als Trennzeichen, wenn Präfix nicht bereits einen Schrägstrich als Suffix besitzt
        if (!$this->endsWithChar($prefix, '/')) {
            $prefix .= '/';
        }

        if (isset($this->config->doi->localPrefix) and $this->config->doi->localPrefix != '') {
            $prefix .= $this->config->doi->localPrefix;

            // DocID wird als Suffix mit Bindestrich an das Präfix angefügt (füge Bindestrich hinzu, wenn erforderlich)
            if (!$this->endsWithChar($prefix, '-')) {
                $prefix .= '-';
            }
        }

        $generatedDOI = $prefix . $document->getId();
        return $generatedDOI;
    }

    /**
     * Liefert true zurück, wenn die übergebene DOI als lokale DOI zu betrachten ist.
     * Im Falle der vorliegenden Implementierungsklasse muss eine lokale DOI folgenden
     * Präfix haben: '{doi.prefix}/{doi.localPrefix}-'
     *
     */
    public function isLocal($doiValue) {
        if (!isset($this->config->doi->prefix)) {
            return false;
        }

        $prefix = $this->config->doi->prefix;
        if (!$this->endsWithChar($prefix, '/')) {
            $prefix .= '/';
        }

        if (isset($this->config->doi->localPrefix)) {
            $prefix .= $this->config->doi->localPrefix;
            if (!$this->endsWithChar($prefix, '-')) {
                $prefix .= '-';
            }
        }

        $result = substr($doiValue, 0, strlen($prefix)) == $prefix;
        return $result;
    }

    private function endsWithChar($str, $suffix) {
        return (substr($str, -1) == $suffix);
    }
}