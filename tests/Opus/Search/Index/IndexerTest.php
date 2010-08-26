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
 * @category    Test
 * @package     Opus_Search
 * @author      Henning Gerhardt (henning.gerhardt@slub-dresden.de)
 * @author      Thoralf Klein <thoralf.klein@zib.de>
 * @copyright   Copyright (c) 2009-2010, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */

/**
 * Test search indexing.
 *
 * @category   Test
 * @package    Opus_Search
 *
 * @group SearchIndexIndexerTests
 */
class Opus_Search_Index_IndexerTest extends TestCase {

    /**
     * Setup initial stuff.
     *
     * @return void
    */
    public function setUp() {
        parent::setUp();

        $lucenePath = dirname(dirname(dirname(dirname(__FILE__)))) . '/workspace/tmp';
        Zend_Registry::set('Zend_LuceneIndexPath', $lucenePath);

        // cleanup prevuios lucene index
        $fh = opendir($lucenePath);
        while (false !== $file = readdir($fh)) {
            @unlink($lucenePath . '/' . $file);
        }
        closedir($fh);

    }

    /**
     * Cleanup after testing.
     *
     * @return void
     */
    public function tearDown() {
        parent::tearDown();

        // cleanup lucene directory
        $lucenePath = Zend_Registry::get('Zend_LuceneIndexPath');

        // cleanup prevuios lucene index
        $fh = opendir($lucenePath);
        while (false !== $file = readdir($fh)) {
            //@unlink($lucenePath . '/' . $file);
        }
        closedir($fh);
    }

    public function testCorrectIndexingOfEasyDocuments() {
        // create document
        $doc = new Opus_Document();
        $doc->setType("monographie");

        // fill document with data
        $doc->setLanguage('ger');
        $doc->setPublishedDate('12.08.2007');
        $title = new Opus_Title;
        $title->setValue('Gegen die Wand und noch viel weiter');
        $title->setLanguage('ger');
        $doc->addTitleMain($title);
        $abstract = new Opus_Title();
        $abstract->setValue('Eine kleine Nachtgeschichte.');
        $abstract->setLanguage('ger');
        $doc->addTitleAbstract($abstract);
        $person = new Opus_Person();
        $person->setLastName('Tester');
        $person->setFirstName('Gustav');
        $doc->addPersonAuthor($person);
        $docId = $doc->store();

        // build lucene search index
        $indexer = new Opus_Search_Index_Lucene_Indexer;
        $result = $indexer->addDocumentToEntryIndex($doc);
        $indexer->finalize();

        $index = Zend_Search_Lucene::open(Zend_Registry::get('Zend_LuceneIndexPath'));
        $this->assertEquals(1, $index->count(), 'There should be one documents in index');

        // check data of document
        $indexdoc = $index->getDocument(0);
        $this->assertEquals(' Gegen die Wand und noch viel weiter', $indexdoc->getFieldValue('title'), 'Title not correct.');
        $this->assertEquals(' Eine kleine Nachtgeschichte.', $indexdoc->getFieldValue('abstract'), 'Abstract not correct.');
        $this->assertEquals('Tester, Gustav', $indexdoc->getFieldValue('author'), 'Author not correct.');

    }
}
