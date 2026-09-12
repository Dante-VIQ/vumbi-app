<?php

/**
 * HTMLPurifier configuration — Vumbi Ventures
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

            // ✅ Table elements listed here — HTMLPurifier's built-in Tables module
            //    will automatically allow colspan/rowspan with the correct 'Number' type.
            'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,div,b,strong,i,em,u,a[href|title|target|rel],ul,ol,li,p[style],br,span[style],img[width|height|alt|src|title|style],table[border|cellpadding|cellspacing|width|style|class|align|summary],thead,tbody,tfoot,tr[align|valign|style|class],th[colspan|rowspan|scope|align|valign|width|height|style|class],td[colspan|rowspan|align|valign|width|height|style|class],caption[align|style|class],colgroup[span|width|style|class],col[span|width|style|class],figure,figcaption,blockquote[cite],hr',

            'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,padding-right,padding-top,padding-bottom,color,background-color,text-align,vertical-align,border,border-collapse,border-spacing,width,height,min-width,max-width,margin,margin-left,margin-right,margin-top,margin-bottom,display,white-space,line-height',

            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty' => true,
            'HTML.MaxImgLength' => null,
        ],

        'test' => [
            'Attr.EnableID' => true,
        ],

        'youtube' => [
            'HTML.SafeIframe' => true,
            'URI.SafeIframeRegexp' => '%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%',
        ],

        'custom_definition' => [
            'id' => 'html5-definitions',
            'rev' => 1,
            'debug' => false,

            'elements' => [
                // Sectioning
                ['section', 'Block', 'Flow', 'Common'],
                ['nav',     'Block', 'Flow', 'Common'],
                ['article', 'Block', 'Flow', 'Common'],
                ['aside',   'Block', 'Flow', 'Common'],
                ['header',  'Block', 'Flow', 'Common'],
                ['footer',  'Block', 'Flow', 'Common'],
                ['address', 'Block', 'Flow', 'Common'],
                ['hgroup', 'Block', 'Required: h1 | h2 | h3 | h4 | h5 | h6', 'Common'],

                // Grouping
                ['figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common'],
                ['figcaption', 'Inline', 'Flow', 'Common'],

                // Video
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

                // Text-level semantics
                ['s',    'Inline', 'Inline', 'Common'],
                ['var',  'Inline', 'Inline', 'Common'],
                ['sub',  'Inline', 'Inline', 'Common'],
                ['sup',  'Inline', 'Inline', 'Common'],
                ['mark', 'Inline', 'Inline', 'Common'],
                ['wbr',  'Inline', 'Empty', 'Core'],

                // Edits
                ['ins', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
                ['del', 'Block', 'Flow', 'Common', ['cite' => 'URI', 'datetime' => 'CDATA']],
            ],

            // ❌ REMOVED all table-related attribute definitions.
            //    HTMLPurifier's built-in Tables module handles colspan/rowspan correctly
            //    using its internal 'Number' type, avoiding the Illuminate\Support\Number collision.
            'attributes' => [
                ['iframe', 'allowfullscreen', 'Bool'],
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