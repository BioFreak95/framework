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
 * @category    Application
 * @author      Thomas Urban <thomas.urban@cepharum.de>
 * @copyright   Copyright (c) 2009-2015, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */


class Opus_Solr_ParametersTest extends TestCase {

	public function testInitiallyEmpty() {
		$params = new Opus_Solr_Parameters();

		$this->assertFalse( isset( $params->start ) );
		$this->assertFalse( isset( $params->rows ) );
		$this->assertFalse( isset( $params->fields ) );
		$this->assertFalse( isset( $params->sort ) );
		$this->assertFalse( isset( $params->union ) );
	}

	public function testSupportingExplicitGetter() {
		$params = new Opus_Solr_Parameters();

		$this->assertNull( $params->get( 'start' ) );
		$this->assertNull( $params->get( 'rows' ) );
		$this->assertNull( $params->get( 'fields' ) );
		$this->assertNull( $params->get( 'sort' ) );
		$this->assertNull( $params->get( 'union' ) );
	}

	public function testSupportingImplicitGetter() {
		$params = new Opus_Solr_Parameters();

		$this->assertNull( $params->start );
		$this->assertNull( $params->rows );
		$this->assertNull( $params->fields );
		$this->assertNull( $params->sort );
		$this->assertNull( $params->union );
	}

	public function testSupportingGetterMethods() {
		$params = new Opus_Solr_Parameters();

		$this->assertNull( $params->getStart() );
		$this->assertNull( $params->getRows() );
		$this->assertNull( $params->getFields() );
		$this->assertNull( $params->getSort() );
		$this->assertNull( $params->getUnion() );
	}

	/**
	 * @dataProvider provideValidScalarSettings
	 */
	public function testSupportingImplicitScalarSetterValid( $value, $property, $method, $expecting ) {
		$params = new Opus_Solr_Parameters();
		$params->{$property} = $value;
		$this->assertEquals( $expecting, $params->{$property} );
	}

	/**
	 * @dataProvider provideValidScalarSettings
	 */
	public function testSupportingExplicitScalarSetterValid( $value, $property, $method, $expecting ) {
		$params = new Opus_Solr_Parameters();
		$params->set( $property, $value );
		$this->assertEquals( $expecting, $params->get( $property ) );

		$params->set( $property, $value, false );
		$this->assertEquals( $expecting, $params->get( $property ) );
	}

	/**
	 * @dataProvider provideValidScalarSettings
	 */
	public function testSupportingScalarSetterMethodValid( $value, $property, $method, $expecting ) {
		$params = new Opus_Solr_Parameters();
		$params->{$method}( $value );
		$this->assertEquals( $expecting, $params->get( $property ) );
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider provideValidScalarSettings
	 */
	public function testSupportingExplicitScalarSetterValidRejectToAdd( $value, $property, $method, $expecting ) {
		$params = new Opus_Solr_Parameters();
		$params->set( $property, $value, true );
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider provideValidScalarSettings
	 */
	public function testSupportingScalarSetterMethodValidRejectToAdd( $value, $property, $method, $expecting ) {
		$method = preg_replace( '/^set/', 'add', $method );

		$params = new Opus_Solr_Parameters();
		$params->{$method}( $value );
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider provideInvalidScalarSettings
	 */
	public function testSupportingImplicitScalarSetterInvalid( $value, $property, $method ) {
		$params = new Opus_Solr_Parameters();
		$params->{$property} = $value;
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider provideInvalidScalarSettings
	 */
	public function testSupportingExplicitScalarSetterInvalid( $value, $property, $method ) {
		$params = new Opus_Solr_Parameters();
		$params->{$property} = $value;
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider provideInvalidScalarSettings
	 */
	public function testSupportingScalarSetterMethodInvalid( $value, $property, $method ) {
		$params = new Opus_Solr_Parameters();
		$params->{$method}( $value );
	}

	public function provideValidScalarSettings() {
		return array(
			array( 0, 'start', 'setStart', 0 ),
			array( 10, 'start', 'setStart', 10 ),
			array( 100, 'start', 'setStart', 100 ),
			array( 1000, 'start', 'setStart', 1000 ),
			array( 10000, 'start', 'setStart', 10000 ),
			array( 100000, 'start', 'setStart', 100000 ),
			array( 1000000, 'start', 'setStart', 1000000 ),
			array( 10000000, 'start', 'setStart', 10000000 ),
			array( 100000000, 'start', 'setStart', 100000000 ),
			array( 1000000000, 'start', 'setStart', 1000000000 ),

			array( 0, 'rows', 'setRows', 0 ),
			array( 10, 'rows', 'setRows', 10 ),
			array( 100, 'rows', 'setRows', 100 ),
			array( 1000, 'rows', 'setRows', 1000 ),
			array( 10000, 'rows', 'setRows', 10000 ),
			array( 100000, 'rows', 'setRows', 100000 ),
			array( 1000000, 'rows', 'setRows', 1000000 ),
			array( 10000000, 'rows', 'setRows', 10000000 ),
			array( 100000000, 'rows', 'setRows', 100000000 ),
			array( 1000000000, 'rows', 'setRows', 1000000000 ),

			array( true, 'union', 'setUnion', true ),
			array( 1, 'union', 'setUnion', true ),
			array( "yes", 'union', 'setUnion', true ),
			array( "no", 'union', 'setUnion', true ),
			array( array( 1 ), 'union', 'setUnion', true ),
			array( false, 'union', 'setUnion', false ),
			array( null, 'union', 'setUnion', false ),
			array( 0, 'union', 'setUnion', false ),
			array( "", 'union', 'setUnion', false ),
		);
	}

	public function provideInvalidScalarSettings() {
		return array(
			array( -10, 'start', 'setStart' ),
			array( 5.5, 'start', 'setStart' ),
			array( array( 10 ), 'start', 'setStart' ),
			array( array(), 'start', 'setStart' ),
			array( "test", 'start', 'setStart' ),
			array( array( 'test' => 10 ), 'start', 'setStart' ),
			array( (object) array( 'test' => 10 ), 'start', 'setStart' ),

			array( -10, 'rows', 'setRows' ),
			array( 5.5, 'rows', 'setRows' ),
			array( array( 10 ), 'rows', 'setRows' ),
			array( array(), 'rows', 'setRows' ),
			array( "test", 'rows', 'setRows' ),
			array( array( 'test' => 10 ), 'rows', 'setRows' ),
			array( (object) array( 'test' => 10 ), 'rows', 'setRows' ),
		);
	}

	/**
	 * @dataProvider provideValidFieldsSettings
	 */
	public function testSupportingImplicitFieldsSetterValid( $value, $property, $method, $expecting ) {
		$params = new Opus_Solr_Parameters();
		$params->{$property} = $value;
		$this->assertEquals( $expecting, $params->{$property} );
	}

	/**
	 * @dataProvider provideValidFieldsSettings
	 */
	public function testSupportingExplicitFieldsSetterValid( $value, $property, $method, $expecting ) {
		$params = new Opus_Solr_Parameters();
		$params->set( $property, $value );
		$this->assertEquals( $expecting, $params->get( $property ) );

		$params->set( $property, $value, false );
		$this->assertEquals( $expecting, $params->get( $property ) );
	}

	/**
	 * @dataProvider provideValidFieldsSettings
	 */
	public function testSupportingFieldsSetterMethodValid( $value, $property, $method, $expecting ) {
		$params = new Opus_Solr_Parameters();
		$params->{$method}( $value );
		$this->assertEquals( $expecting, $params->get( $property ) );
	}

	/**
	 * @dataProvider provideValidFieldsSettings
	 */
	public function testSupportingExplicitFieldsSetterValidAdding( $value, $property, $method, $expecting ) {
		$params = new Opus_Solr_Parameters();
		$params->set( $property, 'first', false );
		$params->set( $property, $value, true );
		$this->assertEquals( array_merge( array( 'first' ), $expecting ), $params->get( $property ) );
	}

	/**
	 * @dataProvider provideValidFieldsSettings
	 */
	public function testSupportingFieldsSetterMethodValidAdding( $value, $property, $method, $expecting ) {
		$adder = preg_replace( '/^set/', 'add', $method );

		$params = new Opus_Solr_Parameters();
		$params->{$method}( 'first' );
		$params->{$adder}( $value );
		$this->assertEquals( array_merge( array( 'first' ), $expecting ), $params->get( $property ) );
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider provideInvalidFieldsSettings
	 */
	public function testSupportingImplicitFieldsSetterInvalid( $value, $property, $method ) {
		$params = new Opus_Solr_Parameters();
		$params->{$property} = $value;
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider provideInvalidFieldsSettings
	 */
	public function testSupportingExplicitFieldsSetterInvalid( $value, $property, $method ) {
		$params = new Opus_Solr_Parameters();
		$params->{$property} = $value;
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider provideInvalidFieldsSettings
	 */
	public function testSupportingFieldsSetterMethodInvalid( $value, $property, $method ) {
		$params = new Opus_Solr_Parameters();
		$params->{$method}( $value );
	}

	public function provideValidFieldsSettings() {
		return array(
			array( '*', 'fields', 'setFields', array( '*' ) ),
			array( 'a', 'fields', 'setFields', array( 'a' ) ),
			array( 'a,b', 'fields', 'setFields', array( 'a', 'b' ) ),
			array( 'a,a', 'fields', 'setFields', array( 'a' ) ),
			array( 'ab', 'fields', 'setFields', array( 'ab' ) ),
			array( 'ab,cd', 'fields', 'setFields', array( 'ab', 'cd' ) ),
			array( 'abcdefghijklmnopqrstuvwxyzaaabacadaeafagahaiajakalamanaoapaqarasatauavawaxayaz', 'fields', 'setFields', array( 'abcdefghijklmnopqrstuvwxyzaaabacadaeafagahaiajakalamanaoapaqarasatauavawaxayaz' ) ),
			array( array( '*' ), 'fields', 'setFields', array( '*' ) ),
			array( array( 'a' ), 'fields', 'setFields', array( 'a' ) ),
			array( array( 'a', 'b' ), 'fields', 'setFields', array( 'a', 'b' ) ),
			array( array( 'a', 'a' ), 'fields', 'setFields', array( 'a' ) ),
			array( array( 'ab,cd', 'ef' ), 'fields', 'setFields', array( 'ab', 'cd', 'ef' ) ),
		);
	}

	public function provideInvalidFieldsSettings() {
		return array(
			array( array(), 'fields', 'setFields' ),
			array( array( array() ), 'fields', 'setFields' ),
			array( array( array( "a" ) ), 'fields', 'setFields' ),
			array( array( "a", array() ), 'fields', 'setFields' ),
			array( array( "a", array( "b" ) ), 'fields', 'setFields' ),
			array( null, 'fields', 'setFields' ),
			array( array( null ), 'fields', 'setFields' ),
			array( array( array( null ) ), 'fields', 'setFields' ),
			array( array( null, array( null ) ), 'fields', 'setFields' ),
			array( array( "a", null ), 'fields', 'setFields' ),
		);
	}
}
