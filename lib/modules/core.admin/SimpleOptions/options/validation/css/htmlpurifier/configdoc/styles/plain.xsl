<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
    version     = "1.0"
    xmlns       = "http://www.w3.org/1999/xhtml"
    xmlns:xsl   = "http://www.w3.org/1999/XSL/Transform"
>
    <xsl:output
        method      = "xml"
        encoding    = "UTF-8"
        doctype-public = "-//W3C//DTD XHTML 1.0 Transitional//EN"
        doctype-system = "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
        indent = "no"
        media-type = "text/html"
    />
    <xsl:param name="css" select="'styles/plain.css'"/>
    <xsl:param name="title" select="'Configuration Documentation'"/>

    <xsl:variable name="typeLookup"  select="document('../types.xml')/types" />
    <xsl:variable name="usageLookup" select="document('../usage.xml')/usage" />

    <!-- Twiddle this variable to get the columns as even as possible -->
    <xsl:variable name="maxNumberAdjust" select="2" />

    <xsl:template match="/">
        <html lang="en" xml:lang="en">
            <head>
                <title><xsl:value-of select="$title" /> - <xsl:value-of select="/configdoc/title" /></title>
                <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
                <link rel="stylesheet" type="text/css" href="{$css}" />
            </head>
            <body>
                <div id="content">
                    <div id="library"><xsl:value-of select="/configdoc/title" /></div>
                    <h1><xsl:value-of select="$title" /></h1>
                    <div id="tocContainer">
                        <h2>Table of Contents</h2>
                        <ul id="toc">
                            <xsl:apply-templates mode="toc">
                                <xsl:with-param name="overflowNumber" select="round(count(/configdoc/namespace) div 2) + $maxNumberAdjust" />
                            </xsl:apply-templates>
                        </ul>
                    </div>
                    <div id="typesContainer">
                        <h2>Types</h2>
                        <xsl:apply-templates select="$typeLookup" mode="types" />
                    </div>
                    <xsl:apply-templates />
                </div>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="type" mode="types">
        <div class="type-block">
            <xsl:attribute name="id">type-<xsl:value-of select="@id" /></xsl:attribute>
            <h3><code><xsl:value-of select="@id" /></code>: <xsl:value-of select="@name" /></h3>
            <div class="type-description">
                <xsl:copy-of xmlns:xhtml="http://www.w3.org/1999/xhtml" select="xhtml:div/node()" />
            </div>
        </div>
    </xsl:template>

    <xsl:template match="title" mode="toc" />
    <xsl:template match="namespace" mode="toc">
        <xsl:param name="overflowNumber" />
        <xsl:variable name="number"><xsl:number level="single" /></xsl:variable>
        <xsl:variable name="directiveNumber"><xsl:number level="any" count="directive" /></xsl:variable>
        <xsl:if test="count(directive)&gt;0">
            <li>
                <!-- BEGIN multicolumn code -->
                <xsl:if test="$number &gt;= $overflowNumber">
                    <xsl:attribute name="class">col-2</xsl:attribute>
                </xsl:if>
                <xsl:if test="$number = $overflowNumber">
                    <xsl:attribute name="style">margin-top:-<xsl:value-of select="($number * 2 + $directiveNumber - 3) * 1.2" />em</xsl:attribute>
                </xsl:if>
                <!-- END multicolumn code -->
                <a href="#{@id}"><xsl:value-of select="name" /></a>
                <ul>
                    <xsl:apply-templates select="directive" mode="toc">
                        <xsl:with-param name="overflowNumber" select="$overflowNumber" />
                    </xsl:apply-templates>
                </ul>
                <xsl:if test="$number + 1 = $overflowNumber">
                    <div class="col-l" />
                </xsl:if>
            </li>
        </xsl:if>
    </xsl:template>
    <xsl:template match="directive" mode="toc">
        <xsl:variable name="number">
            <xsl:number level="any" count="directive|namespace" />
        </xsl:variable>
        <xsl:if test="not(deprecated)">
            <li>
                <a href="#{@id}"><xsl:value-of select="name" /></a>
            </li>
        </xsl:if>
    </xsl:template>

    <xsl:template match="title" />

    <xsl:template match="namespace">
        <div class="namespace">
            <xsl:apply-templates />
            <xsl:if test="count(directive)=0">
                <p>No configuration directives defined for this namespace.</p>
            </xsl:if>
        </div>
    </xsl:template>
    <xsl:template match="namespace/name">
        <h2 id="{../@id}"><xsl:value-of select="." /></h2>
    </xsl:template>
    <xsl:template match="namespace/description">
        <div class="description">
            <xsl:copy-of xmlns:xhtml="http://www.w3.org/1999/xhtml" select="xhtml:div/node()" />
        </div>
    </xsl:template>

    <xsl:template match="directive">
        <div>
            <xsl:attribute name="class"><!--
                -->directive<!--
                --><xsl:if test="deprecated"> deprecated</xsl:if><!--
            --></xsl:attribute>
            <xsl:apply-templates>
                <xsl:with-param name="id" select="@id" />
            </xsl:apply-templates>
        </div>
    </xsl:template>
    <xsl:template match="directive/name">
        <xsl:param name="id" />
        <xsl:apply-templates select="../aliases/alias" mode="anchor" />
        <h3 id="{$id}"><xsl:value-of select="$id" /></h3>
    </xsl:template>
    <xsl:template match="alias" mode="anchor">
        <a id="{.}"></a>
    </xsl:template>

    <!-- Do not pass through -->
    <xsl:template match="alias"></xsl:template>

    <xsl:template match="directive/constraints">
        <xsl:param name="id" />
        <table class="constraints">
            <xsl:apply-templates />
            <xsl:if test="../aliases/alias">
                <xsl:apply-templates select="../aliases" mode="constraints" />
            </xsl:if>
            <xsl:apply-templates select="$usageLookup/directive[@id=$id]" />
        </table>
    </xsl:template>
    <xsl:template match="directive/aliases" mode="constraints">
        <tr>
            <th>Aliases</th>
            <td>
                <xsl:for-each select="alias">
                    <xsl:if test="position()&gt;1">, </xsl:if>
                    <xsl:value-of select="." />
                </xsl:for-each>
            </td>
        </tr>
    </xsl:template>
    <xsl:template match="directive/description">
        <div class="description">
            <xsl:copy-of xmlns:xhtml="http://www.w3.org/1999/xhtml" select="xhtml:div/node()" />
        </div>
    </xsl:template>
    <xsl:template match="directive/deprecated">
        <div class="deprecated-notice">
            <strong>Warning:</strong>
            This directive was deprecated in version <xsl:value-of select="version" />.
            <a href="#{use}">%<xsl:value-of select="use" /></a> should be used instead.
        </div>
    </xsl:template>
    <xsl:template match="usage/directive">
        <tr>
            <th>Used in</th>
            <td>
                <ul>
                    <xsl:apply-templates />
                </ul>
            </td>
        </tr>
    </xsl:template>
    <xsl:template match="usage/directive/file">
        <li>
            <em><xsl:value-of select="@name" /></em> on line<xsl:if test="count(line)&gt;1">s</xsl:if>
            <xsl:text> </xsl:text>
            <xsl:for-each select="line">
                <xsl:if test="position()&gt;1">, </xsl:if>
                <xsl:value-of select="." />
            </xsl:for-each>
        </li>
    </xsl:template>

    <xsl:template match="constraints/version">
        <tr>
            <th>Version added</th>
            <td><xsl:value-of select="." /></td>
        </tr>
    </xsl:template>
    <xsl:template match="constraints/type">
        <tr>
            <th>Type</th>
            <td>
                <xsl:variable name="type" select="text()" />
                <xsl:attribute name="class">type type-<xsl:value-of select="$type" /></xsl:attribute>
                <a>
                    <xsl:attribute name="href">#type-<xsl:value-of select="$type" /></xsl:attribute>
                    <xsl:value-of select="$typeLookup/type[@id=$type]/@name" />
                    <xsl:if test="@allow-null='yes'">
                        (or null)
                    </xsl:if>
                </a>
            </td>
        </tr>
    </xsl:template>
    <xsl:template match="constraints/allowed">
        <tr>
            <th>Allowed values</th>
            <td>
                <xsl:for-each select="value"><!--
                 --><xsl:if test="position()&gt;1">, </xsl:if>
                    &quot;<xsl:value-of select="." />&quot;<!--
             --></xsl:for-each>
            </td>
        </tr>
    </xsl:template>
    <xsl:template match="constraints/default">
        <tr>
            <th>Default</th>
            <td><pre><xsl:value-of select="." xml:space="preserve" /></pre></td>
        </tr>
    </xsl:template>
    <xsl:template match="constraints/external">
        <tr>
            <th>External deps</th>
            <td>
                <ul>
                    <xsl:apply-templates />
                </ul>
            </td>
        </tr>
    </xsl:template>
    <xsl:template match="constraints/external/project">
        <li><xsl:value-of select="." /></li>
    </xsl:template>

</xsl:stylesheet>

<!-- vim: et sw=4 sts=4
-->
