Templavoila
===========

This is a fork of the official templavoila. The key differences are:

* support for rendering of template objects using Fluid templates, without using any DB tables
* support for TYPO3 6.2
* support for IE10+

## 1. TypoScript template object configuration

You can configure the list of TOs (template objects) using TypoScript. Lets start with a simple sample, one page TO and one simple grid TO:

```
plugin.tx_templavoila {
    settings {
        templates {
            1000 {
                title = [Page] Content - Single Column
                description = Content page with one column
                datastructure = typo3conf/ext/capri_base/templavoila/page/[Page] Default.xml
                previewicon = EXT:capri_template/Resources/Public/Images/Thumbs/[Page] Content (1 column).png
                fileref = EXT:capri_template/Resources/Private/Templates/Templavoila/Page/[Page] Content (1 column).html
                fileref_mtime =
                fileref_md5 =
                parent =
                localprocessing =
                belayout = EXT:capri_base/templavoila/page/belayout/[Page] Content (1 column).xml
            }
            
            1200 {
                title = [Grid] 50% - 50%
                description = 2 columns grid with equal widths
                datastructure = typo3conf/ext/capri_base/templavoila/fce/[Grid] 2 columns.xml
                previewicon = EXT:capri_template/Resources/Public/Images/Thumbs/[Grid] 2 columns - 50-50.png
                fileref = EXT:capri_template/Resources/Private/Templates/Templavoila/FCE/[Grid] 2 columns - 50-50.html
                fileref_mtime =
                fileref_md5 =
                parent =
                localprocessing =
                belayout = EXT:capri_base/templavoila/fce/belayout/[Grid] 2 columns - 50-50.xml
            }
        }
    }
}
```

The _datastructure_, _previewicon_ and _belayout_ fields are the same as with the official templavoila version (when configured to store data structures in XML files). Fields _fileref_mtime_ and _fileref_md5_ are not needed. Title and description can be localized using LLL:EXT: syntax.

Field _fileref_ points to Fluid template.

## 2. Fluid templates

Lets start with a simple sample. Lets assume that the content of _[Grid] 2 columns - 50-50.xml_ file from the previous TypoScript configuration is:

```xml
<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<T3DataStructure>
    <meta type="array">
		<noEditOnCreation>1</noEditOnCreation>
		<langDisable>1</langDisable>
		<default type="array">
			<TCEForms type="array">
				<sys_language_uid>-1</sys_language_uid>
			</TCEForms>
		</default>
	</meta>
	<ROOT type="array">
		<tx_templavoila type="array">
			<title>ROOT</title>
			<description>Select the HTML element on the page which you want to be the overall container element for the template.</description>
			<tags></tags>
			<preview></preview>
		</tx_templavoila>
		<type>array</type>
		<el type="array">
			<field_column_1 type="array">
				<type>no_map</type>
				<tx_templavoila type="array">
					<title>Column 1</title>
					<sample_data type="array">
						<numIndex index="0"></numIndex>
					</sample_data>
					<eType>ce</eType>
                    <TypoScript><![CDATA[

						10 = RECORDS
						10 {
							source.current = 1
							tables = tt_content
						}

					]]></TypoScript>
					<proc type="array">
						<int>0</int>
						<HSC>0</HSC>
						<stdWrap></stdWrap>
					</proc>
					<preview></preview>
					<oldStyleColumnNumber>0</oldStyleColumnNumber>
					<enableDragDrop>1</enableDragDrop>
				</tx_templavoila>
				<TCEforms type="array">
					<label>Column 1</label>
					<displayCond>false</displayCond>
					<config type="array">
						<type>group</type>
						<internal_type>db</internal_type>
						<allowed>tt_content</allowed>
						<size>5</size>
						<maxitems>200</maxitems>
						<minitems>0</minitems>
						<multiple>1</multiple>
						<show_thumbs>1</show_thumbs>
					</config>
				</TCEforms>
			</field_column_1>
			<field_column_2 type="array">
				<type>no_map</type>
				<tx_templavoila type="array">
					<title>Column 2</title>
					<sample_data type="array">
						<numIndex index="0"></numIndex>
					</sample_data>
					<eType>ce</eType>
                    <TypoScript><![CDATA[
                    
						10 = RECORDS
						10 {
							source.current = 1
							tables = tt_content
						}

					]]></TypoScript>
					<proc type="array">
						<int>0</int>
						<HSC>0</HSC>
						<stdWrap></stdWrap>
					</proc>
					<preview></preview>
					<oldStyleColumnNumber>0</oldStyleColumnNumber>
					<enableDragDrop>1</enableDragDrop>
				</tx_templavoila>
				<TCEforms type="array">
					<label>Column 2</label>
					<displayCond>false</displayCond>
					<config type="array">
						<type>group</type>
						<internal_type>db</internal_type>
						<allowed>tt_content</allowed>
						<size>5</size>
						<maxitems>200</maxitems>
						<minitems>0</minitems>
						<multiple>1</multiple>
						<show_thumbs>1</show_thumbs>
					</config>
				</TCEforms>
			</field_column_2>
		</el>
	</ROOT>
</T3DataStructure>
```

The Fluid template _[Grid] 2 columns - 50-50.html_ for this would be really simple:

```html
<div class="row">
    <div class="small-12 medium-6 columns">
        <f:format.raw>{field_column_1}</f:format.raw>
    </div>
    <div class="small-12 medium-6 columns">
        <f:format.raw>{field_column_2}</f:format.raw>
    </div>
</div>
```

## 3. Samples

Simple page TO:

```html
<div class="container">
    <div class="header-outer">
        <header class="header">
            <nav class="logos">
                <f:cObject typoscriptObjectPath="lib.misc.logos"/>
            </nav>
            <nav class="menu-bar-section meta-menu" data-toggle-id="meta-menu" id="meta-menu">
                <f:cObject typoscriptObjectPath="lib.navigation.meta"/>
            </nav>
            <nav class="company-logos">
                <f:cObject typoscriptObjectPath="lib.misc.partner.logos.header"/>
            </nav>
            <nav class="location-menu">
                <f:cObject typoscriptObjectPath="lib.navigation.location"/>
            </nav>
    		<nav class='mobile-menu'>
				<span aria-hidden='true' class='icon-toggle-main-menu' data-toggle-for='main-mobile-menu' data-toggle-hide-on='meta-menu'></span>
				<span aria-hidden='true' class='icon-toggle-search-menu right' data-toggle-for='search-menu'></span>
			</nav>
            <nav class="menu-bar-section search-menu" data-toggle-id="search-menu" id="search-menu">
                <f:cObject typoscriptObjectPath="lib.misc.search.mobile"/>
            </nav>
            <nav class="main-menu">
                <div class="menu-bar-background"></div>
                <div class="menu-bar-section" data-toggle-id="main-menu" id="main-menu">
                    <f:cObject typoscriptObjectPath="lib.navigation.main"/>
                </div>
            </nav>
			<nav class="main-mobile-menu">
				<div class="menu-bar-background"></div>
				<div class="menu-bar-section" data-toggle-id="main-mobile-menu" id="main-mobile-menu">

				</div>
			</nav>
        </header>
    </div>
    <!--TYPO3SEARCH_begin-->
    <section class="content_container c">
        <div class="row main_content">
            <f:format.raw>{field_content}</f:format.raw>
        </div>
    </section>
    <!--TYPO3SEARCH_end-->
    <footer class="row">
        <div class="footer-top">
            <nav class="social">
                <f:cObject typoscriptObjectPath="lib.navigation.social"/>
            </nav>
            <nav class="footer-menu">
                <f:cObject typoscriptObjectPath="lib.navigation.footer"/>
            </nav>
            <nav>
                <f:cObject typoscriptObjectPath="lib.misc.partner.logos.footer"/>
            </nav>
        </div>
        <div class="copyright">
            <f:cObject typoscriptObjectPath="lib.misc.copyright"/>
        </div>
    </footer>
</div>
```

Complex sample using custom viewhelpers:

```html
{namespace fal=EssentialDots\ExtbaseFal\ViewHelpers}
{namespace er=EssentialDots\EdResponsive\ViewHelpers}
{namespace h=EssentialDots\ExtbaseHijax\ViewHelpers}

<fal:val.file as="image" fileUid="{field_image}" />

<article class="teaser-panel">
    <div class="inner">
        <f:if condition="{field_title}">
            <h3>{field_title}</h3>
        </f:if>
        <f:if condition="{image}">
            <div class="image">
                <er:image file="{image}" imagesConfiguration="config.tx_extbase.settings.responsiveImages.quarterImage"/>
            </div>
        </f:if>
        <div class="content">
            <f:if condition="{field_description}">
                <p>{field_description}</p>
            </f:if>
        </div>
        <f:if condition="{field_link}">
            <f:if condition="{field_link_label}">
                <nav class="call-to-action">
                    <f:link.page pageUid="{field_link}" class="button {f:if(condition: field_link_lightbox, then:'lightbox', else:'')}">{field_link_label}</f:link.page>
                </nav>
            </f:if>
        </f:if>
    </div>
</article>
```

## 4. Support

Please use github issue tracker to submit questions. Of course, feel free to fork and contribute.
