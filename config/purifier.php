<?php

/**
 * Ok, glad you are here
 * first we get a config instance, and set the settings
 * $config = HTMLPurifier_Config::createDefault();
 * $config->set('Core.Encoding', $this->config->get('purifier.encoding'));
 * $config->set('Cache.SerializerPath', $this->config->get('purifier.cachePath'));
 * if ( ! $this->config->get('purifier.finalize')) {
 *     $config->autoFinalize = false;
 * }
 * $config->loadArray($this->getConfig());
 *
 * You must NOT delete the default settings
 * anything in settings should be compacted with params that needed to instance HTMLPurifier_Config.
 *
 * @link http://htmlpurifier.org/live/configdoc/plain.html
 */

return [
    'encoding' => 'UTF-8',
    'finalize' => true,
    'ignoreNonStrings' => false,
    'cachePath' => storage_path('app/purifier'),
    'cacheFileMode' => 0755,
    'settings' => [
        'default' => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',

            'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,div,b,strong,i,em,u,a[href|title|target|rel],ul,ol,li,p[style],br,span[style],img[width|height|alt|src|title|style],table[border|cellpadding|cellspacing|width|style|class|align|summary],thead,tbody,tfoot,tr[align|valign|style|class],th[colspan|rowspan|scope|align|valign|width|height|style|class],td[colspan|rowspan|align|valign|width|height|style|class],caption[align|style|class],colgroup[span|width|style|class],col[span|width|style|class],figure,figcaption,blockquote[cite],hr',

            'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,padding-right,padding-top,padding-bottom,color,background-color,text-align,vertical-align,border,border-collapse,border-spacing,width,height,min-width,max-width,margin,margin-left,margin-right,margin-top,margin-bottom,display,white-space,line-height',
            
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty' => true,
            'HTML.MaxImgLength' => null,
            'HTML.SafeObject' => true,
            'Output.FlashCompat' => true,
        ],
        'test' => [
            'Attr.EnableID' => 'true',
        ],
        'youtube' => [
            'HTML.SafeIframe' => 'true',
            'URI.SafeIframeRegexp' => '%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%',
        ],
        'custom_definition' => [
            'id' => 'html5-definitions',
            'rev' => 1,
            'debug' => false,
            'elements' => [
                // http://developers.whatwg.org/sections.html
                ['section', 'Block', 'Flow', 'Common'],
                ['nav',     'Block', 'Flow', 'Common'],
                ['article', 'Block', 'Flow', 'Common'],
                ['aside',   'Block', 'Flow', 'Common'],
                ['header',  'Block', 'Flow', 'Common'],
                ['footer',  'Block', 'Flow', 'Common'],

                // Content model actually excludes several tags, not modelled here
                ['address', 'Block', 'Flow', 'Common'],
                ['hgroup', 'Block', 'Required: h1 | h2 | h3 | h4 | h5 | h6', 'Common'],

                // http://developers.whatwg.org/grouping-content.html
                ['figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common'],
                ['figcaption', 'Inline', 'Flow', 'Common'],

                // http://developers.whatwg.org/the-video-element.html#the-video-element
                ['video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', [
                    'src' => 'URI',
                    'type' => 'Text',
                    'width' => 'Length',
                    'height' => 'Length',
                    'poster' => 'URI',
                    'preload' => 'Enum#auto,metadata,none',
                    'controls' => 'Bool',
                ]],
                ['source', 'Block', 'Flow', 'Common', [
                    'src' => 'URI',
                    'type' => 'Text',
                ]],

                // http://developers.whatwg.org/text-level-semantics.html
                ['s',    'Inline', 'Inline', 'Common'],
                ['var',  'Inline', 'Inline', 'Common'],
                ['sub',  'Inline', 'Inline', 'Common'],
                ['sup',  'Inline', 'Inline', 'Common'],
                ['mark', 'Inline', 'Inline', 'Common'],
                ['wbr',  'Inline', 'Empty', 'Core'],

                // http://developers.whatwg.org/edits.html
                ['ins', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
                ['del', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
            ],
            'attributes' => [
                ['iframe', 'allowfullscreen', 'Bool'],
                ['table', 'height', 'Text'],
                ['table', 'border', 'Text'],
                ['table', 'cellpadding', 'Text'],
                ['table', 'cellspacing', 'Text'],
                ['table', 'align', 'Enum#left,center,right'],
                ['table', 'summary', 'Text'],
                ['table', 'class', 'Text'],
                ['td', 'border', 'Text'],
                ['td', 'colspan', 'Number'],
                ['td', 'rowspan', 'Number'],
                ['td', 'align', 'Enum#left,center,right,justify'],
                ['td', 'valign', 'Enum#top,middle,bottom,baseline'],
                ['td', 'width', 'Length'],
                ['td', 'height', 'Length'],
                ['td', 'style', 'Text'],
                ['td', 'class', 'Text'],
                ['th', 'border', 'Text'],
                ['th', 'colspan', 'Number'],
                ['th', 'rowspan', 'Number'],
                ['th', 'scope', 'Enum#row,col,rowgroup,colgroup'],
                ['th', 'align', 'Enum#left,center,right,justify'],
                ['th', 'valign', 'Enum#top,middle,bottom,baseline'],
                ['th', 'width', 'Length'],
                ['th', 'height', 'Length'],
                ['th', 'style', 'Text'],
                ['th', 'class', 'Text'],
                ['tr', 'width', 'Text'],
                ['tr', 'height', 'Text'],
                ['tr', 'border', 'Text'],
                ['tr', 'align', 'Enum#left,center,right,justify'],
                ['tr', 'valign', 'Enum#top,middle,bottom,baseline'],
                ['tr', 'style', 'Text'],
                ['tr', 'class', 'Text'],
                ['caption', 'align', 'Enum#top,bottom,left,right'],
                ['caption', 'style', 'Text'],
                ['caption', 'class', 'Text'],
                ['colgroup', 'span', 'Number'],
                ['colgroup', 'width', 'Length'],
                ['colgroup', 'style', 'Text'],
                ['colgroup', 'class', 'Text'],
                ['col', 'span', 'Number'],
                ['col', 'width', 'Length'],
                ['col', 'style', 'Text'],
                ['col', 'class', 'Text'],
            ],
        ],
        'custom_attributes' => [
            ['a', 'target', 'Enum#_blank,_self,_target,_top'],
            ['a', 'rel', 'Text'],
        ],
        'custom_elements' => [
            ['u', 'Inline', 'Inline', 'Common'],
        ],
    ],
];