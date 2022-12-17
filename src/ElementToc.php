<?php

namespace Sunnysideup\ElementalToc;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

use SilverStripe\Forms\LiteralField;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\ORM\FieldType\DBField;

use DNADesign\Elemental\Models\ElementContent;

/**
 * @property string $HTML
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
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    LiteralField::create('TOC', $this->getToc())
                ]
            );
        });
        $fields = parent::getCMSFields();
        // $fields->removeByName('HTML');
        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->HTML = $this->getToc();
        $this->Sort = -1;
    }

    protected function getToc()
    {
        if($this->WithNumbers) {
            $tagType = 'ol';
        } else {
            $tagType = 'ul';
        }
        $html = '<'.$tagType.'>';
        $items = $this->Parent()->Elements()->exclude(['ID' => $this->ID]);
        foreach($items as $item) {
            $html .= '<li><a href="'.$item->Link().'">'.$item->Title.'</a></li>';
        }
        $html = str_ireplace('?stage=Stage', '', $html);
        $html = str_ireplace('?stage=Live', '', $html);
        $html .= '</'.$tagType.'>';
        return $html;
    }

    public function getType()
    {
        return 'Table of Contents';
    }
}
