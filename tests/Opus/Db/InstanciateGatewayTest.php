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
 * @category    Tests
 * @package     Opus_Db
 * @author      Ralf Claussnitzer <ralf.claussnitzer@slub-dresden.de>
 * @author      Thoralf Klein <thoralf.klein@zib.de>
 * @copyright   Copyright (c) 2008-2010, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */

/**
 * Test cases for instanciation of table gateway classes.
 *
 * @category    Tests
 * @package     Opus_Db
 *
 * @group       InstanciateGatewayTest
 */
class Opus_Db_InstanciateGatewayTest extends TestCase {

    /**
     * Overwrite parent methods.
     */
    public function setUp() {}
    public function tearDown() {}

    /**
     * Provider for table gateway class names.
     *
     * @return array List of table gateways.
     */
    public function tableGatewayDataProvider() {
        return array(
            array('Opus_Db_Accounts'),
            array('Opus_Db_CollectionsRoles'),
            array('Opus_Db_Collections'),
            array('Opus_Db_CollectionsEnrichments'),
            array('Opus_Db_DnbInstitutes'),
            array('Opus_Db_DocumentEnrichments'),
            array('Opus_Db_DocumentFiles'),
            array('Opus_Db_DocumentIdentifiers'),
            array('Opus_Db_DocumentLicences'),
            array('Opus_Db_DocumentNotes'),
            array('Opus_Db_DocumentPatents'),
            array('Opus_Db_DocumentReferences'),
            array('Opus_Db_Documents'),
            array('Opus_Db_DocumentStatistics'),
            array('Opus_Db_DocumentSubjects'),
            array('Opus_Db_DocumentTitleAbstracts'),
            array('Opus_Db_FileHashvalues'),
            array('Opus_Db_Ipranges'),
            array('Opus_Db_Languages'),
            array('Opus_Db_LinkAccountsRoles'),
            array('Opus_Db_LinkDocumentsCollections'),
            array('Opus_Db_LinkDocumentsDnbInstitutes'),
            array('Opus_Db_LinkDocumentsLicences'),
            array('Opus_Db_LinkIprangesRoles'),
            array('Opus_Db_LinkPersonsDocuments'),
            array('Opus_Db_Persons'),
            array('Opus_Db_UserRoles'),
        );
    }

    /**
     * Test if a given table gateway class can be instanciated.
     *
     * @param string $tableGateway Class name of a table gateway.
     * @param mixed  $param        Special instanciation argument.
     * @return void
     *
     * @dataProvider tableGatewayDataProvider
     */
    public function testSpawnGateway($tableGateway) {
        try {
            // Test, if creating instance works.
            $table = Opus_Db_TableGateway::getInstance($tableGateway);
            $this->assertNotNull($table);
            $this->assertNotNull(get_class($table) === $tableGateway);

            $exampleRow = $table->createRow();

            // Test, if instance exists in instances array afterwards.
            $instances = Opus_Db_TableGateway::getAllInstances();
            $this->assertTrue(is_array($instances),
                    'Instances should be array.');
            $this->assertArrayHasKey($tableGateway, $instances,
                    'Current instance should be in instance array.');

            // Test, if second call gives same TableGateway.
            $table_2 = Opus_Db_TableGateway::getInstance($tableGateway);
            $this->assertNotNull($table_2);
            $this->assertNotNull(get_class($table_2) === $tableGateway);

            $this->assertTrue($table === $table_2,
                    'Singleton should return same object on second call');
        }
        catch (Exception $ex) {
            $this->fail("Failed to instanciate $tableGateway: " . $ex->getMessage());
        }
    }

}
