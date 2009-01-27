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
 * @author     Henning Gerhardt (henning.gerhardt@slub-dresden.de)
 * @copyright  Copyright (c) 2009, OPUS 4 development team
 * @license    http://www.gnu.org/licenses/gpl.html General Public License
 * @version    $Id$
 */

/**
 * View helper for displaying a model
 *
 * @category    Framework
 * @package     Opus_View
 */
class Opus_View_Helper_ShowModel extends Zend_View_Helper_Abstract {

    /**
     * Supress all empty fields
     *
     * @var boolean
     */
    private $__saef = false;

    /**
     * Helper method to create a proper array
     *
     * @param string $name  Name of element
     * @param string $value Value of element
     * @param string $label (Optional) Label of element
     * @return array
     */
    private function __skeleton($name, $value, $label = null) {
        $result = array();
        $result['divclass'] = $name;
        $result['labelclass'] = $name . ' label';
        $result['valueclass'] = $name . ' value';
        if (empty($label) === true) {
            $result['label'] = $name;
        } else {
            $result['label'] = $label;
        }
        $result['value'] = $value;
        return $result;
    }

    /**
     * Helper method for complex data
     *
     * @param string $field  Field to display
     * @param array  &$value Value of field
     * @param string $label  (Optional) Label for display field
     * @return string
     */
    private function __complexHelper($field, array &$value, $label = null) {
        $result = '';
        $data = array();
        foreach ($value as $fieldname => $internal_value) {
            if (($this->__saef === false) or (empty($internal_value) === false)) {
                $data[] = $this->__skeleton($fieldname, $internal_value);
            }
        }
        if (($this->__saef === false) or (empty($data) === false)) {
            $iterim_data = $this->view->partialLoop('_model.phtml', $data);
            $outer = $this->__skeleton($field, $iterim_data, $label);
            $result = $this->view->partial('_model.phtml', $outer);
        }
        return $result;
    }

    /**
     * General method for complex fields
     *
     * @param string $field   Field to display
     * @param mixed  &$values Values of a field
     * @return string
     */
    private function __complexDisplay($field, &$values) {
        $result = '';
        // silence decision about multi values or not
        if (@is_array($values[0]) === false) {
            // only one element to display
            $result = $this->__complexHelper($field, $values);
        } else {
            // more than one element to display
            foreach ($values as $number => $value) {
                if (($this->__saef === false) or (empty($value) === false)) {
                    $label = (++$number) . '. ' . $field;
                    $result .= $this->__complexHelper($field, $value, $label);
                }
            }
        }
        return $result;
    }

    /**
     * Helper method for person data
     *
     * @param string $field  Specific field
     * @param array  &$value Value of field
     * @param string $label  (Optional) Label for field
     * @return string
     */
    private function __personHelper($field, array &$value, $label = null) {
        $result = '';
        $data = array();
        // merge academic title, lastname and firstname
        $title = $value['AcademicTitle'];
        $lastname = $value['LastName'];
        $firstname = $value['FirstName'];
        $merged = $title . $lastname;
        if (empty($firstname) === false) {
            $merged .=  ', ' . $firstname;
        }
        $fieldname = 'PersonName';
        if (($this->__saef === false) or (empty($merged) === false)) {
            $data[] = $this->__skeleton($fieldname, $merged);
        }
        // other fields
        $other_fields = array('DateOfBirth', 'PlaceOfBirth', 'Email');
        foreach ($other_fields as $fieldname) {
            if (array_key_exists($fieldname, $value) === true) {
                if (($this->__saef === false) or (empty($value[$fieldname]) === false)) {
                    $data[] = $this->__skeleton($fieldname, $value[$fieldname]);
                }
            }
        }
        if (($this->__saef === false) or (empty($data) === false)) {
            $iterim_data = $this->view->partialLoop('_model.phtml', $data);
            $outer = $this->__skeleton($field, $iterim_data, $label);
            $result = $this->view->partial('_model.phtml', $outer);
        }
        return $result;
    }

    /**
     * General method for displaying person data
     *
     * @param string $field   Field to display
     * @param array  &$values Value of field
     * @return string
     */
    private function __personDisplay($field, array &$values) {
        $result = '';
        // silence decision about multi values or not
        if (@is_array($values[0]) === false) {
            // only one element to display
            $result = $this->__personHelper($field, $values);
        } else {
            // more than one element to display
            foreach ($values as $number => $value) {
                if (($this->__saef === false) or (empty($value) === false)) {
                    $label = (++$number) . '. ' . $field;
                    $result .= $this->__personHelper($field, $value, $label);
                }
            }
        }
        return $result;
    }

    /**
     * Helper method for displaying titles or abstracts
     *
     * @param string $field  Field for displaying
     * @param array  &$value Value of field
     * @param string $label  (Optional) Label for displaying field
     * @return string
     */
    private function __titleHelper($field, array &$value, $label = null) {
        $data = array();
        // title language
        $language_list = Zend_Registry::get('Available_Languages');
        $language_field = 'TitleAbstractLanguage';
        $language = $language_list[$value[$language_field]];
        $data[] = $this->__skeleton($language_field, $language);
        // title value
        $title_field = 'TitleAbstractValue';
        $iterim_value = $value[$title_field];
        $data[] = $this->__skeleton($title_field, $iterim_value);
        $iterim_data = $this->view->partialLoop('_model.phtml', $data);
        $outer = $this->__skeleton($field, $iterim_data, $label);
        return $this->view->partial('_model.phtml', $outer);
    }

    /**
     * General method for displaying titles or abstracts
     *
     * @param string $field   Field to display
     * @param mixed  &$values Value of field
     * @return string
     */
    private function __titleDisplay($field, &$values) {
        $result = '';
        // silence decision about multi values or not
        if (@is_array($values[0]) === false) {
            // only one element to display
            $result = $this->__titleHelper($field, $values);
        } else {
            // more than one element to display
            foreach ($values as $number => $value) {
                if (($this->__saef === false) or (empty($value) === false)) {
                    $label = (++$number) . '. ' . $field;
                    $result .= $this->__titleHelper($field, $value, $label);
                }
            }
        }
        return $result;
    }

    /**
     * General method for displaying a field
     *
     * @param string $name  Field to display
     * @param string $value Value of field
     * @return string
     */
    protected function _displayGeneralElement($name, $value) {
        $result = '';
        if (($this->__saef === false) or (empty($value) === false)) {
            $data = $this->__skeleton($name, $value);
            $result = $this->view->partial('_model.phtml', $data);
        }
        return $result;
    }

    /**
     *  Method for displaying licences.
     *
     * @param string $field Licence field for displaying
     * @param string $value Value of licence field
     * @return string
     */
    protected function _displayLicence($field, $value) {
        $result = '';
        // we "know" that the licence name is in NameLong
        $iterim_value = @$value['NameLong'];
        if (($this->__saef === false) or (empty($iterim_value) === false)) {
            $data = $this->__skeleton($field, $iterim_value);
            $result = $this->view->partial('_model.phtml', $data);
        }
        return $result;
    }

    /**
     * Method for displaying language field
     *
     * @param string $field Lanugage field to display
     * @param string $value Value of language field
     * @return string
     */
    protected function _displayLanguage($field, $value) {
        $result = '';
        $language_list = Zend_Registry::get('Available_Languages');
        $iterim_value = @$language_list[$value];
        if (($this->__saef === false) or (empty($iterim_value) === false)) {
            $data = $this->__skeleton($field, $iterim_value);
            $result = $this->view->partial('_model.phtml', $data);
        }
        return $result;
    }

    /**
     * Method for displaying files of a document
     *
     * @param string $field Files field for displaying
     * @param string $value Value of files field
     * @return void
     */
    protected function _displayFile($field, $value) {
        // TODO need more information for displaying
        // makes code sniffer happy
        $my_field = $field;
        $my_value = $value;
        return;
    }

    /**
     * Wrapper method for person advisor
     *
     * @param string $field Person field for displaying
     * @param mixed  $value Value of person field
     * @return string
     */
    protected function _displayPersonAdvisor($field, $value) {
        return $this->__personDisplay($field, $value);
    }

    /**
     * Wrapper method for person author
     *
     * @param string $field Person field for displaying
     * @param mixed  $value Value of person field
     * @return string
     */
    protected function _displayPersonAuthor($field, $value) {
        return $this->__personDisplay($field, $value);
    }

    /**
     * Wrapper method for person referee
     *
     * @param string $field Person field for displaying
     * @param mixed  $value Value of person field
     * @return string
     */
    protected function _displayPersonReferee($field, $value) {
        return $this->__personDisplay($field, $value);
    }

    /**
     * Wrapper method for person other
     *
     * @param string $field Person field for displaying
     * @param mixed  $value Value of person field
     * @return string
     */
    protected function _displayPersonOther($field, $value) {
        return $this->__personDisplay($field, $value);
    }

    /**
     * Wrapper method for isbn
     *
     * @param string $field Isbn field for displaying
     * @param mixed  $value Value of isbn field
     * @return string
     */
    protected function _displayIsbn($field, $value) {
        return $this->__complexDisplay($field, $value);
    }

    /**
     * Wrapper method for title abstract
     *
     * @param string $field Title field for displaying
     * @param mixed  $value Value of title field
     * @return string
     */
    protected function _displayTitleAbstract($field, $value) {
        return $this->__titleDisplay($field, $value);
    }

    /**
     * Wrapper method for title main
     *
     * @param string $field Title field for displaying
     * @param mixed  $value Value of title field
     * @return string
     */
    protected function _displayTitleMain($field, $value) {
        return $this->__titleDisplay($field, $value);
    }

    /**
     * Wrapper method for title parent
     *
     * @param string $field Title field for displaying
     * @param mixed  $value Value of title field
     * @return string
     */
    protected function _displayTitleParent($field, $value) {
        return $this->__titleDisplay($field, $value);
    }

    /**
     * View helper for displaying a model
     *
     * @param array   &$modeldata Contains model data
     * @param boolean $saef       (Optional) Supress all empty fields.
     * @return string
     */
    public function showModel(array &$modeldata, $saef = false) {
        if (is_bool($saef) === true) {
            $this->__saef = $saef;
        }
        $result = '';
        foreach ($modeldata as $field => $value) {
            $method_name = '_display' . $field;
            if (method_exists($this, $method_name) === true) {
                $result .= $this->$method_name($field, $value);
            } else {
                $result .= $this->_displayGeneralElement($field, $value);
            }
        }
        return $result;
    }

}
