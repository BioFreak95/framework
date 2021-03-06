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
 * @package     Opus_Doi_Generator
 * @author      Sascha Szott <szott@zib.de>
 * @copyright   Copyright (c) 2018, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 */

class Opus_Doi_Generator_DefaultGeneratorTest extends TestCase {

    public function testGenerateWithMissingConfig() {
        // create minimal test document to provide document ID
        $doc = new Opus_Document();
        $doc->store();

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $exception = null;
        try {
            $generator->generate($doc);
        }
        catch (Opus_Doi_Generator_DoiGeneratorException $e) {
            $exception = $e;
        }

        $this->assertTrue($exception instanceof Opus_Doi_Generator_DoiGeneratorException);

    }

    public function testGenerateWithPartialConfig() {
        // create minimal test document to provide document ID
        $doc = new Opus_Document();
        $doc->store();

        $this->adaptDoiConfiguration(array('prefix' => ''));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $exception = null;
        try {
            $generator->generate($doc);
        }
        catch (Opus_Doi_Generator_DoiGeneratorException $e) {
            $exception = $e;
        }

        $this->assertTrue($exception instanceof Opus_Doi_Generator_DoiGeneratorException);
    }

    public function testGenerateWithPrefixConfig() {
        // create minimal test document to provide document ID
        $doc = new Opus_Document();
        $docId = $doc->store();

        $this->adaptDoiConfiguration(array('prefix' => '12.3456'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $doi = $generator->generate($doc);
        $this->assertEquals('12.3456/' . $docId, $doi);
    }

    public function testGenerateWithPrefixConfigAlt() {
        // create minimal test document to provide document ID
        $doc = new Opus_Document();
        $docId = $doc->store();

        $this->adaptDoiConfiguration(array('prefix' => '12.3456/'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $doi = $generator->generate($doc);
        $this->assertEquals('12.3456/' . $docId, $doi);
    }

    public function testGenerateWithCompleteConfig() {
        // create minimal test document to provide document ID
        $doc = new Opus_Document();
        $docId = $doc->store();

        $this->adaptDoiConfiguration(array('prefix' => '12.3456/', 'localPrefix' => 'opustest'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $doi = $generator->generate($doc);
        $this->assertEquals('12.3456/opustest-' . $docId, $doi);
    }

    public function testGenerateWithCompleteConfigAlt() {
        // create minimal test document to provide document ID
        $doc = new Opus_Document();
        $docId = $doc->store();

        $this->adaptDoiConfiguration(array('prefix' => '12.3456/', 'localPrefix' => 'opustest-'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $doi = $generator->generate($doc);
        $this->assertEquals('12.3456/opustest-' . $docId, $doi);
    }

    public function testIsLocalWithMissingConfig() {
        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $this->assertFalse($generator->isLocal('doiValue'));
    }

    public function testIsLocalWithPartialConfigNegative() {
        $this->adaptDoiConfiguration(array('prefix' => '12.3456'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $this->assertFalse($generator->isLocal('doiValue'));

        $this->assertFalse($generator->isLocal('12.3456'));
    }

    public function testIsLocalWithPartialConfigPositive() {
        $this->adaptDoiConfiguration(array('prefix' => '12.3456'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $this->assertTrue($generator->isLocal('12.3456/'));
    }

    public function testIsLocalWithPartialAltConfigNegative() {
        $this->adaptDoiConfiguration(array('prefix' => '12.3456/'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $this->assertFalse($generator->isLocal('doiValue'));

        $this->assertFalse($generator->isLocal('12.3456'));
    }

    public function testIsLocalWithPartialAltConfigPositive() {
        $this->adaptDoiConfiguration(array('prefix' => '12.3456/'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $this->assertTrue($generator->isLocal('12.3456/'));
    }

    public function testIsLocalWithCompleteConfigNegative() {
        $this->adaptDoiConfiguration(array('prefix' => '12.3456/', 'localPrefix' => 'opustest'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $this->assertFalse($generator->isLocal('doiValue'));

        $this->assertFalse($generator->isLocal('12.3456/opustest'));
    }

    /**
     * Regression OPUSVIER-3900.
     */
    public function testIsLocalWithEmptyLocalPrefix()
    {
        $this->adaptDoiConfiguration(array('prefix' => '12.3456', 'localPrefix' => ''));

        $generator = new Opus_Doi_Generator_DefaultGenerator();

        $this->assertTrue($generator->isLocal('12.3456/104'));
    }

    public function testIsLocalWithCompleteConfigPositive() {
        $this->adaptDoiConfiguration(array('prefix' => '12.3456/', 'localPrefix' => 'opustest'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $this->assertTrue($generator->isLocal('12.3456/opustest-'));

        $this->assertTrue($generator->isLocal('12.3456/opustest-789'));
    }

    public function testIsLocalWithCompleteAltConfigNegative() {
        $this->adaptDoiConfiguration(array('prefix' => '12.3456/', 'localPrefix' => 'opustest-'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $this->assertFalse($generator->isLocal('doiValue'));

        $this->assertFalse($generator->isLocal('12.3456/opustest'));
    }

    public function testIsLocalWithCompleteAltConfigPositive() {
        $this->adaptDoiConfiguration(array('prefix' => '12.3456/', 'localPrefix' => 'opustest-'));

        $generator = new Opus_Doi_Generator_DefaultGenerator();
        $this->assertTrue($generator->isLocal('12.3456/opustest-'));

        $this->assertTrue($generator->isLocal('12.3456/opustest-789'));
    }

    private function adaptDoiConfiguration($doiConfig) {
        Zend_Registry::set('Zend_Config',
            Zend_Registry::get('Zend_Config')->merge(new Zend_Config(array('doi' => $doiConfig))));
    }
}