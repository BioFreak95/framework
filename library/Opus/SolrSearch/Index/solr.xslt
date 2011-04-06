<?xml version="1.0" encoding="utf-8"?>
<!--
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
 * @package     Opus_SolrSearch
 * @author      Oliver Marahrens <o.marahrens@tu-harburg.de>
 * @author      Sascha Szott <szott@zib.de>
 * @copyright   Copyright (c) 2008-2011, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */
-->

<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">

    <xsl:output method="xml" indent="yes" />

    <!-- Suppress output for all elements that don't have an explicit template. -->
    <xsl:template match="*" />
    
    <xsl:template match="/">
        <xsl:element name="add">
            <xsl:element name="doc">

                <!-- id -->
                <xsl:element name="field">
                    <xsl:attribute name="name">id</xsl:attribute>
                    <xsl:value-of select="/Opus/Opus_Document/@Id" />
                </xsl:element>

                <!-- year -->
                <xsl:element name="field">
                    <xsl:attribute name="name">year</xsl:attribute>
                    <xsl:choose>
                        <xsl:when test="/Opus/Opus_Document/PublishedDate/@Year != ''">
                            <xsl:value-of select="/Opus/Opus_Document/PublishedDate/@Year" />
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:value-of select="/Opus/Opus_Document/@PublishedYear" />
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:element>

                <!-- server_date_published -->
                <xsl:if test="/Opus/Opus_Document/ServerDatePublished/@UnixTimestamp != ''">
                    <xsl:element name="field">
                        <xsl:attribute name="name">server_date_published</xsl:attribute>
                        <xsl:value-of select="/Opus/Opus_Document/ServerDatePublished/@UnixTimestamp" />
                    </xsl:element>
                </xsl:if>

                <!-- language -->
                <xsl:variable name="language" select="/Opus/Opus_Document/@Language" />
                <xsl:element name="field">
                    <xsl:attribute name="name">language</xsl:attribute>
                    <xsl:value-of select="$language" />
                </xsl:element>            

                <!-- title / title_output -->
                <xsl:for-each select="/Opus/Opus_Document/TitleMain">
                    <xsl:element name="field">
                        <xsl:attribute name="name">title</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                    <xsl:if test="@Language = $language">
                        <xsl:element name="field">
                            <xsl:attribute name="name">title_output</xsl:attribute>
                            <xsl:value-of select="@Value" />
                        </xsl:element>
                    </xsl:if>
                </xsl:for-each>

                <!-- abstract / abstract_output -->
                <xsl:for-each select="/Opus/Opus_Document/TitleAbstract">
                    <xsl:element name="field">
                        <xsl:attribute name="name">abstract</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                    <xsl:if test="@Language = $language">
                        <xsl:element name="field">
                            <xsl:attribute name="name">abstract_output</xsl:attribute>
                            <xsl:value-of select="@Value" />
                        </xsl:element>
                    </xsl:if>
                </xsl:for-each>
                
                <!-- author -->
                <xsl:for-each select="/Opus/Opus_Document/PersonAuthor">
                    <xsl:element name="field">
                        <xsl:attribute name="name">author</xsl:attribute>
                        <xsl:value-of select="@FirstName" />
                        <xsl:text> </xsl:text>
                        <xsl:value-of select="@LastName" />
                    </xsl:element>
                </xsl:for-each>

                <!-- author_sort -->
                <xsl:element name="field">
                    <xsl:attribute name="name">author_sort</xsl:attribute>
                    <xsl:for-each select="/Opus/Opus_Document/PersonAuthor">
                        <xsl:value-of select="@LastName" />
                        <xsl:text> </xsl:text>
                        <xsl:value-of select="@FirstName" />
                        <xsl:text> </xsl:text>
                    </xsl:for-each>
                </xsl:element>

                <!-- fulltext -->
                <xsl:for-each select="/Opus/Opus_Document/Fulltext_Index">
                    <xsl:element name="field">
                        <xsl:attribute name="name">fulltext</xsl:attribute>
                        <xsl:value-of select="." />
                    </xsl:element>
                </xsl:for-each>

                <!-- has fulltext -->
                <xsl:element name="field">
                    <xsl:attribute name="name">has_fulltext</xsl:attribute>
                    <xsl:value-of select="/Opus/Opus_Document/Has_Fulltext" />
                </xsl:element>

                <!-- referee -->
                <xsl:for-each select="/Opus/Opus_Document/PersonReferee">
                    <xsl:element name="field">
                        <xsl:attribute name="name">referee</xsl:attribute>
                        <xsl:value-of select="@FirstName" />
                        <xsl:text> </xsl:text>
                        <xsl:value-of select="@LastName" />
                    </xsl:element>
                </xsl:for-each>

                <!-- doctype -->
                <xsl:element name="field">
                    <xsl:attribute name="name">doctype</xsl:attribute>
                    <xsl:value-of select="/Opus/Opus_Document/@Type" />
                </xsl:element>

                <!-- subject (swd) -->
                <xsl:for-each select="/Opus/Opus_Document/Subject[@Type = 'swd']">
                    <xsl:element name="field">
                        <xsl:attribute name="name">subject</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                </xsl:for-each>

                <!-- subject (uncontrolled) -->
                <xsl:for-each select="/Opus/Opus_Document/Subject[@Type = 'uncontrolled']">
                    <xsl:element name="field">
                        <xsl:attribute name="name">subject</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                </xsl:for-each>

                <!-- subject (msc) -->
                <xsl:for-each select="/Opus/Opus_Document/Subject[@Type = 'msc']">
                    <xsl:element name="field">
                        <xsl:attribute name="name">subject_msc</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                </xsl:for-each>

                <!-- belongs_to_bibliography -->
                <xsl:element name="field">
                    <xsl:attribute name="name">belongs_to_bibliography</xsl:attribute>
                    <xsl:choose>
                        <xsl:when test="/Opus/Opus_Document/@BelongsToBibliography = 0" >
                            <xsl:text>false</xsl:text>
                       </xsl:when>
                       <xsl:otherwise>
                            <xsl:text>true</xsl:text>
                       </xsl:otherwise>
                    </xsl:choose>                    
                </xsl:element>

                <!-- collections: project, app_area, institute -->
                <xsl:for-each select="/Opus/Opus_Document/Collection">
                    <xsl:choose>
                        <xsl:when test="@RoleName = 'projects'">
                            <xsl:element name="field">
                                <xsl:attribute name="name">project</xsl:attribute>
                                <xsl:value-of select="@Number" />
                            </xsl:element>
                            <xsl:element name="field">
                                <xsl:attribute name="name">app_area</xsl:attribute>
                                <xsl:value-of select="substring(@Number, 0, 2)" />
                            </xsl:element>
                        </xsl:when>
                        <xsl:when test="@RoleName = 'institutes'">
                            <xsl:element name="field">
                                <xsl:attribute name="name">institute</xsl:attribute>
                                <xsl:value-of select="@Name" />
                            </xsl:element>
                        </xsl:when>
                    </xsl:choose>

                    <xsl:element name="field">
                        <xsl:attribute name="name">collection_ids</xsl:attribute>
                        <xsl:value-of select="@Id" />
                    </xsl:element>
                </xsl:for-each>

                <!-- title parent -->
                <xsl:for-each select="/Opus/Opus_Document/TitleParent">
                    <xsl:element name="field">
                        <xsl:attribute name="name">title_parent</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                </xsl:for-each>

                <!-- persons: PersonSubmitter, PersonsReferee, PersonEditor, PersonTranslator, PersonContributor, PersonAdvisor, PersonOther -->
                <!--xsl:for-each select="/Opus/Opus_Document/*">
                    <xsl:if test="substring(name(), 1, 6)='Person'">
                        <xsl:if test="name()!='PersonAuthor'">
                            <xsl:element name="field">
                                <xsl:attribute name="name">persons</xsl:attribute>
                                <xsl:value-of select="@Name" />
                            </xsl:element>
                        </xsl:if>
                    </xsl:if>
                </xsl:for-each-->

                <!-- TODO: CreatingCorporation, ContributingCorporation -->

                <!-- TODO: PublisherName, PublisherPlace -->


            </xsl:element>
        </xsl:element>
    </xsl:template>
</xsl:stylesheet>