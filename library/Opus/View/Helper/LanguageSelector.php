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
 * @category   Framework
 * @package    Opus_View
 * @author     Felix Ostrowski <ostrowski@hbz-nrw.de>
 * @copyright  Copyright (c) 2009, OPUS 4 development team
 * @license    http://www.gnu.org/licenses/gpl.html General Public License
 * @version    $Id$
 */

/**
 * Builds the language selection form.
 *
 * @category    Framework
 * @package     Opus_View
 */
class Opus_View_Helper_LanguageSelector {

    /**
     * Holds the current view object.
     *
     * @var Zend_View_Interface
     */
    protected $_view = null;

    /**
     * Sets the current view object.
     *
     * @param Zend_View_Interface $view The current view object.
     * @return void
     */
    public function setView(Zend_View_Interface $view) {
        $this->_view = $view;
    }

    /**
     * Get an instance of the view helper.
     *
     * @return Opus_View_Helper_LanguageSelector
     */
    public function languageSelector() {
        return $this;
    }

    /**
     * Return view helper output.
     *
     * @return string
     */
    public function __toString() {
        $form = new Zend_Form;
        $form->setAction($this->_view->url(array('action' => 'language', 'controller' => 'index', 'module' => 'home')));
        $form->setAttrib('id', 'language_selector');

        $language = new Zend_Form_Element_Select('language');
        $language->setMultiOptions(Zend_Registry::get('Zend_Translate')->getList());
        $language->setValue(Zend_Registry::get('Zend_Translate')->getLocale());
        $language->setLabel($this->_view->translate('home_index_language_label'));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Ok');

        $form->addElements(array($language, $submit));
        return $form->render($this->_view);
    }
}
