<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns="http://www.tei-c.org/ns/1.0">
<xsl:template match="/">
	<?xml-model href="http://www.tei-c.org/release/xml/tei/custom/schema/relaxng/tei_all.rng" type="application/xml" schematypens="http://relaxng.org/ns/structure/1.0"?>
	<?xml-model href="http://www.tei-c.org/release/xml/tei/custom/schema/relaxng/tei_all.rng" type="application/xml" schematypens="http://purl.oclc.org/dsdl/schematron"?>
	<TEI >
	  <!-- solo per l'edizione critica -->
	  <teiHeader>
	      <fileDesc>
	         <!-- si fa per l'opera -->
	         <titleStmt>
	            <title>I promessi sposi</title>
	            <author>Alessandro Manzoni</author>
	         </titleStmt>
	         <editionStmt>
	            <edition>Edizione collaborativa</edition>
	            <!-- un respStmt per ogni contributo. E' corretto? boh chi lo sa™. respStmt in un template-->
	            $resp
	            <!--LISTA DI ISTANZE (?) DEL TEMPLATE
	            <respStmt xml:id=$id>
	               <resp>$resp</resp>
	               <name>$name</name>
	            </respStmt>
	            
	            !-->
	         </editionStmt>
	         <publicationStmt>
	            <publisher>$publisher</publisher>
	            <pubPlace>$pubplace</pubPlace>
	            <date>$date</date>
	            <availability>
	               <licence>$URL</licence>
	            </availability>
	         </publicationStmt>
	         <sourceDesc> <!--NON CHIARO-->
	            <listWit>
	               <witness xml:id="v1827">Alessandro Manzoni, "I promessi sposi, storia milanese del sec. XVII, scoperta e rifatta da Alessandro Manzoni", 1827</witness>
	               <witness xml:id="v1840">Alessandro Manzoni, "I promessi sposi", 1840</witness>
	            </listWit>
	         </sourceDesc>    
		 </fileDesc>
	     <encodingDesc>
	        <p>$encoding</p> 
	     </encodingDesc><!--COSA CI DEVE ANDARE? CODIFICA DEI CARATTERI? LO CHIEDO NEL FORM?--> 
	     <revisionDesc>
		$change
	     </revisionDesc>
	  </teiHeader>
	  <text>	
		<body>
			<div type="chapter" xml:id="$cap">
				<xsl:apply-templates/>
			</div>
		</body>
	  </text>	
	</TEI>
</xsl:template>
<xsl:template match="home">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="h3">
	<head><xsl:apply-templates/></head>
</xsl:template>

<xsl:template match="p">
	<p>
		<xsl:attribute name="xml:id">p<xsl:number/></xsl:attribute>
		<xsl:apply-templates/>
	</p>
</xsl:template>

<xsl:template match="span[@class='replace']">
	<app>
		<xsl:attribute name="type"><xsl:value-of select="replace(./@class,' ','-')"/></xsl:attribute>
		<rdg wit="#v1827">
			<xsl:choose>
			<xsl:when test="./@data-responsible">
						<xsl:attribute name="resp">#<xsl:value-of select="./@data-responsible"/></xsl:attribute>
			</xsl:when>
			<xsl:otherwise>
						<xsl:attribute name="resp">#void</xsl:attribute>
			</xsl:otherwise>
			</xsl:choose>
				<xsl:value-of select="./span[@class='oldVersion']"/>
			</rdg>
			<rdg wit="#v1840">
			<xsl:choose>
			<xsl:when test="./@data-responsible">
						<xsl:attribute name="resp">#<xsl:value-of select="./@data-responsible"/></xsl:attribute>
			</xsl:when>
			<xsl:otherwise>
						<xsl:attribute name="resp">#void</xsl:attribute>
			</xsl:otherwise>
		</xsl:choose>
			<xsl:value-of select="./span[contains(@class,'newVersion')]"/>
		</rdg>
	</app>
</xsl:template>

<xsl:template match="i">
	<emph>
		<xsl:apply-templates/>
	</emph>
</xsl:template>

</xsl:stylesheet>