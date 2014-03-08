Templavoila
===========

This is a fork of the official templavoila. The key differences are:

* support for rendering of template objects using Fluid templates, without using any DB tables
* support for TYPO3 6.2
* support for IE10+

Fluid templates
-----------

You can configure the list of TOs using TypoScript:

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