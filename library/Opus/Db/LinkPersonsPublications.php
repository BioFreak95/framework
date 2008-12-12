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
 * @category	Framework
 * @package		Opus_Db
 * @author     	Ralf Claussnitzer <ralf.claussnitzer@slub-dresden.de>
 * @copyright  	Copyright (c) 2008, OPUS 4 development team
 * @license    	http://www.gnu.org/licenses/gpl.html General Public License
 * @version    	$Id$
 */

/**
 * Table gateway class to table 'link_persons_publications'.
 *
 * @category    Framework
 * @package     Opus_Db
 *
 */
class Opus_Db_LinkPersonsPublications extends Zend_Db_Table {

    /**
     * DB table name.
     *
     * @var string
     */
    protected $_name = 'link_persons_publications';

    /**
     * DB table primary key name.
     *
     * @var string
     */
    protected $_primary = array('persons_id', 'document_publication_id');

    /**
     * Map foreign keys in this table to the column in the table they originate
     * from (i.e. the referenced table)
     *
     * @var array $_referenceMap
     */
    protected $_referenceMap = array(
            'Persons' => array(
                'columns' => 'persons_id',
                'refTableClass' => 'Opus_Db_Persons',
                'refColumns' => 'persons_id',
                ),
            'DocumentPublications' => array(
                'columns' => 'document_publication_id',
                'refTableClass' => 'Opus_Db_DocumentsPublications',
                'refColumns' => 'document_publication_id'
                ),
            );
}
