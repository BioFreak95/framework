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
 * @package     Opus_Controller
 * @author      Felix Ostrowski <ostrowski@hbz-nrw.de>
 * @copyright   Copyright (c) 2009, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */

/**
 * CRUD Controller for Opus Applications.
 *
 * Extend this class and set protected static $_modelclass to the class of an
 * Opus_Model to gain basic C(reate) R(ead) U(pdate) D(elete) interface:
 *
 * New Form:    GET module/controller/new
 * Create:      POST module/controller/create
 * Read:        GET module/controller/show/id/x
 * Update Form: POST module/controller/create (with id set in model)
 * Delete:      POST module/controller/delete (with id parameter)
 *
 * See modules/admin/licence/views/scripts/licence/*.phtml for example templates.
 *
 * @category    Framework
 * @package     Opus_Controller
 */
class Opus_Controller_CRUDAction extends Opus_Controller_Action {

    /**
     * The class of the model being administrated.
     *
     * @var Opus_Model_Abstract
     */
    protected static $_modelclass = null;

    /**
     * Late static binding workaround, returns value of static variable of
     * descendant classes.
     *
     * @return string
     */
    private function __getModelClass() {
        eval('$modelclass = ' . get_class($this) . '::$_modelclass;');
        return $modelclass;
    }

    /**
     * List all available model instances
     *
     * @return void
     */
    public function indexAction() {
        eval('$entries = ' . $this->__getModelClass() . '::getAll();');
        $this->view->entries = array();
        foreach ($entries as $entry) {
            $this->view->entries[$entry->getId()] = $entry->getDisplayName();
        }
    }

    /**
     * Displays a model instance
     *
     * @return void
     */
    public function showAction() {
        $id = $this->getRequest()->getParam('id');
        $modelclass = $this->__getModelclass();
        $model = new $modelclass($id);
        $this->view->entry = $model->toArray();
    }

    /**
     * Create a new model instance
     *
     * @return void
     */
    public function newAction() {
        $form_builder = new Opus_Form_Builder();
        $modelclass = $this->__getModelclass();
        $model = new $modelclass;
        $modelForm = $form_builder->build($model);
        $action_url = $this->view->url(array("action" => "create"));
        $modelForm->setAction($action_url);
        $this->view->form = $modelForm;
    }

    /**
     * Save model instance
     *
     * @return void
     */
    public function createAction() {
        if ($this->_request->isPost() === true) {
            $data = $this->_request->getPost();
            $form_builder = new Opus_Form_Builder();
            if (array_key_exists('submit', $data) === false) {
                $form = $form_builder->buildFromPost($data);
                $action_url = $this->view->url(array("action" => "rolecreate"));
                $form->setAction($action_url);
                $this->view->form = $form;
            } else {
                $form = $form_builder->buildFromPost($data);
                if ($form->isValid($data) === true) {
                    // retrieve values from form and save them into model
                    $role = $form_builder->getModelFromForm($form);
                    $form_builder->setFromPost($role, $form->getValues());
                    $role->store();
                    $this->_redirectTo('Model successfully created.', 'index');
                } else {
                    $this->view->form = $form;
                }
            }
        } else {
            $this->_redirectTo('', 'index');
        }
    }

    /**
     * Edits a model instance
     *
     * @return void
     */
    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $form_builder = new Opus_Form_Builder();
        $modelclass = $this->__getModelclass();
        $model = new $modelclass($id);
        $modelForm = $form_builder->build($model);
        $action_url = $this->view->url(array("action" => "create"));
        $modelForm->setAction($action_url);
        $this->view->form = $modelForm;
    }

    /**
     * Deletes a model instance
     *
     * @return void
     */
    public function deleteAction() {
        if ($this->_request->isPost() === true) {
            $id = $this->getRequest()->getPost('id');
            $modelclass = $this->__getModelclass();
            $model = new $modelclass($id);
            $model->delete();
            $this->_redirectTo('Model successfully deleted.', 'index');
        } else {
            $this->_redirectTo('', 'index');
        }
    }
}
