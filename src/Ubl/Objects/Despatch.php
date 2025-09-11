<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMElement;
use Efaturacim\Util\Utils\Date\DateUtil;
use Efaturacim\Util\Utils\String\StrUtil;
use Efaturacim\Util\Ubl\Objects\Attachment;

class Despatch extends UblDataType
{
    public ?string $actualDespatchDate = null;
    public ?string $actualDespatchTime = null;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }
    public function initMe()
    {
    }
    public function setPropertyFromOptions($k, $v, $options): bool
    {
        if (in_array($k, ['actualDespatchDate', 'gerçek_tahmin_tarihi']) && StrUtil::notEmpty($v)) {
            $this->actualDespatchDate = $v;
            return true;
        }
        if (in_array($k, ['actualDespatchTime', 'gerçek_tahmin_saati']) && StrUtil::notEmpty($v)) {
            $this->actualDespatchTime = $v;
            return true;
        }
        return false;
    }
    public function loadFromArray($arr, $depth = 0, $isDebug = false, $dieOnDebug = true)
    {
        return parent::loadFromArray($arr, $depth, $isDebug, $dieOnDebug);
    }
    public function isEmpty(): bool
    {
        // An Despatch must have an actualDespatchDate to be valid.
        return StrUtil::isEmpty($this->actualDespatchDate) || StrUtil::isEmpty($this->actualDespatchTime);
    }

    public function toDOMElement(DOMDocument $document): ?DOMElement
    {
        if ($this->isEmpty()) {
            return null;
        }

        $element = $document->createElement('cac:Despatch');

        $this->appendElement($document, $element, 'cbc:ActualDespatchDate', $this->actualDespatchDate);
        $this->appendElement($document, $element, 'cbc:ActualDespatchTime', $this->actualDespatchTime);

        return $element;
    }
}