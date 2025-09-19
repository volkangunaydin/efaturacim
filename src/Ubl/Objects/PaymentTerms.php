<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Date\DateUtil;
use Efaturacim\Util\Utils\String\StrUtil;

class PaymentTerms extends UblDataType
{
    /**
     * @var UblDataTypeList
     */
    public $note;
    public ?string $paymentDueDate = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function initMe()
    {
        $this->note = new UblDataTypeList(Note::class);
    }

    public function addNote(array $options): self
    {
        $this->note->add(new Note($options));
        return $this;
    }

    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array(strtolower($k), ['note', 'not', 'notes', 'notlar'])) {
            if (is_array($v)) {
                foreach ($v as $noteValue) {
                    if (is_array($noteValue)) {
                        $this->addNote($noteValue);
                    } elseif (StrUtil::notEmpty($noteValue)) {
                        $this->addNote(['value' => $noteValue]);
                    }
                }
            } elseif (StrUtil::notEmpty($v)) {
                $this->addNote(['value' => $v]);
            }
            return true;
        }
        if (in_array($k, ['paymentDueDate', 'vade_tarihi', 'odeme_tarihi']) && StrUtil::notEmpty($v)) {
            $this->paymentDueDate = DateUtil::getAsDbDate($v);
            return true;
        }
        return false;
    }

    public function isEmpty(): bool
    {
        // PaymentTerms is considered empty if it has no note and no due date.
        return $this->note->isEmpty() && StrUtil::isEmpty($this->paymentDueDate);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:PaymentTerms');

        $this->appendChild($element, $this->note->toDOMElement($document));
        $this->appendElement($document, $element, 'cbc:PaymentDueDate', $this->paymentDueDate);

        return $element;
    }
}