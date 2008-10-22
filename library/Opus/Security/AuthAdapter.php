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
 * @package     Opus_Security
 * @author      Ralf Claussnitzer <ralf.claussnitzer@slub-dresden.de>
 * @copyright   Copyright (c) 2008, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */

/**
 * A simple authentication adapter using the Opus_Security_Account mechanism.
 *
 * @category    Framework
 * @package     Opus_Security
 */
class Opus_Security_AuthAdapter implements Zend_Auth_Adapter_Interface {
    
    /**
     * Holds the login name.
     *
     * @var string
     */
    protected $_login;
    
    /**
     * Holds the password.
     *
     * @var string
     */
    protected $_password;
    
    /**
     * Holds an actual Opus_Security_Account implementation.
     *
     * @var Opus_Security_Account
     */
    protected $_account = null;
    
    /**
     * Set the credential values for authentication.
     *
     * @param string $login    Login or account name .
     * @param string $password Account password.
     * @return Opus_Security_AuthAdapter Fluent interface.
     */
    public function setCredentials($login, $password) {
        $this->_login = $login;
        $this->_password = $password;
        return $this;
    }
    
    /**
     * Inject an Opus_Security_Account object dependency.
     *
     * @param Opus_Security_Account $account Actual account implementation.
     * @return void
     */
    public function setAccountModel(Opus_Security_Account $account) {
        $this->_account = $account;
    }
    
    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed.
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        if ((is_string($this->_login) === false) or (is_string($this->_password) === false)) {
            throw new Zend_Auth_Adapter_Exception('Credentials are not strings.');
        }
        if (empty($this->_login) === true) {
            throw new Zend_Auth_Adapter_Exception('No login name or account name given.');
        }
        if (empty($this->_password) === true) {
            throw new Zend_Auth_Adapter_Exception('No password given.');
        }
        
        // Try to get the account information
        if (is_null($this->_account) === true) {
            $account = Opus_Security_Account::find($this->_login);    
        } else {
            $account = $this->_account->find($this->_login);
        }
        if (is_null($account) === true) {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->_login,
                array('The supplied identity could not be found.'));
        }
        
        // Check the password
        $pass = $account->isPasswordCorrect($this->_password);
        if ($pass === true) {
            return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_login, 
                array('Authentication successful.')); 
        } else {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $this->_login, 
                array('Supplied credential is invalid.'));
        }

        return $authresult; 
    }
    
}