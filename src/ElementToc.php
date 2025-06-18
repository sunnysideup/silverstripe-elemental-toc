<?php

namespace Sunnysideup\ElementalToc;

use DNADesign\Elemental\Models\ElementContent;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\LiteralField;

/**
 * Class \Sunnysideup\ElementalToc\ElementToc
 *
 * @property bool $WithNumbers
 */
class ElementToc extends ElementContent
{
    private static $icon = 'font-icon-list';


    private static $table_name = 'ElementToc';

    private static $singular_name = 'table of contents';

    private static $plural_name = 'tables of contents';

    private static $description = 'Table of Contents for Blocks';

    private static $db = [
        'WithNumbers' => 'Boolean',
    ];

    /**
     * Re-title the HTML field to Content
     *
     * {@inheritDoc}
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            /** @var HTMLEditorField $editorField */
            $fields->removeByName('HTML');
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    LiteralField::create('TOC', $this->getHTML())
                ]
            );
        });
        return parent::getCMSFields();
    }

    protected function getToc()
    {
        return $this->Parent()
            ->Elements()
            ->exclude(['ID' => $this->ID])
            ->filter(['Sort:GreaterThanOrEqual' => $this->Sort]);
    }

    public function getHTML()
    {
        return $this->renderWith("SunnySideUp\\ElementalToC\\ElementToC");
    }

    public function getType()
    {
        return 'Table of Contents';
    }
}
