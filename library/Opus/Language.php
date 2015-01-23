<?php
/**
 * This file is part of OPUS. The software OPUS has been originally developed
 * at the University of Stuttgart with funding from the German Research Net,
 * the Federal Department of Higher Education and Research and the Ministry
 * of Science, Research and the Arts of the State of Baden-Wuerttemberg.
 *
 * OPUS 4 is a complete rewrite of the original OPUS software and was developed
 * by the Stuttgart University Library, the Library Service Center
 * Baden-Wuerttemberg, the North Rhine-Westphalian Library Service Center,
 * the Cooperative Library Network Berlin-Brandenburg, the Saarland University
 * and State Library, the Saxon State Library - Dresden State and University
 * Library, the Bielefeld University Library and the University Library of
 * Hamburg University of Technology with funding from the German Research
 * Foundation and the European Regional Development Fund.
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
 * @package     Opus
 * @author      Felix Ostrowski <ostrowski@hbz-nrw.de>
 * @author      Simone Finkbeiner <simone.finkbeiner@ub.uni-stuttgart.de>
 * @copyright   Copyright (c) 2009, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */

/**
 * Domain model for languages in the Opus framework
 *
 * @category    Framework
 * @package     Opus
 * @uses        Opus_Model_AbstractDb
 */
class Opus_Language extends Opus_Model_AbstractDb {

    /**
     * Specify then table gateway.
     *
     * @var string Classname of Zend_DB_Table to use if not set in constructor.
     */
    protected static $_tableGatewayClass = 'Opus_Db_Languages';

    /**
     * Initialize model with fields.
     *
     * @return void
     */
    protected function _init() {
        $part2B = new Opus_Model_Field('Part2B');

        $part2T = new Opus_Model_Field('Part2T');
        $part2T->setMandatory(true)
            ->setValidator(new Zend_Validate_NotEmpty());

        $part1 = new Opus_Model_Field('Part1');
        $scope = new Opus_Model_Field('Scope');
        $type = new Opus_Model_Field('Type');

        $refName = new Opus_Model_Field('RefName');
        $refName->setMandatory(true)
            ->setValidator(new Zend_Validate_NotEmpty());

        $comment = new Opus_Model_Field('Comment');
        $active = new Opus_Model_Field('Active');
        $active->setCheckbox(true);

        $this->addField($part2B)
            ->addField($part2T)
            ->addField($part1)
            ->addField($scope)
            ->addField($type)
            ->addField($refName)
            ->addField($comment)
            ->addField($active);
    }

    /**
     * Retrieve all Opus_Language instances from the database.
     *
     * @return array Array of Opus_Language objects.
     */
    public static function getAll() {
        return self::getAllFrom('Opus_Language', self::$_tableGatewayClass);
    }

    /**
     * Get all active languages.
     *
     * @return array Array of Opus_Language objects which are active.
     */
    public static function getAllActive() {
        $table = Opus_Db_TableGateway::getInstance(self::$_tableGatewayClass);
        $rows = $table->fetchAll($table->select()->where('active = ?', 1));
        $result = array();
        foreach ($rows as $row) {
            $result[] = new Opus_Language($row);
        }
        return $result;
    }

    /**
     * Get all active languages.
     *
     * @return array Array of Opus_Language objects which are active.
     */
    public static function getAllActiveTable() {
        $table = Opus_Db_TableGateway::getInstance(self::$_tableGatewayClass);
        $rows = $table->fetchAll($table->select()->where('active = ?', 1))->toArray();
        return $rows;
    }

    /**
     * 
     * Get properties of language object as array for a specific terminology code
     * @param string $code ISO639-2 terminology code to retrieve properties for
     * @return array|null Array of properties or null if object not found in database
     */
    public static function getPropertiesByPart2T($code) {
        $table = Opus_Db_TableGateway::getInstance(self::$_tableGatewayClass);
        $rows = $table->fetchAll($table->select()->where('part2_t = ?', $code))->toArray();
        return isset($rows[0]) ? $rows[0] : null;
        
    }
    
    /**
     * Returns reference language name.
     *
     * @see library/Opus/Model/Opus_Model_Abstract#getDisplayName()
     */
    public function getDisplayName() {
       return $this->getRefName();
    }
}
