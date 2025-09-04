<?php

namespace Efaturacim\Tests\UBLTest;

use Efaturacim\Util\Ubl\Turkce\EFaturaBelgesi;
use PHPUnit\Framework\TestCase;

class XMLComparisonTest extends TestCase
{
    private $xmlDataPath;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->xmlDataPath = __DIR__ . '/../xml_data/efatura/';
    }

    /**
     * XML karşılaştırma testi - belirtilen dosya için
     * 
     * @dataProvider xmlFileProvider
     */
    public function testXmlComparison($xmlFileName)
    {
        $xmlFilePath = $this->xmlDataPath . $xmlFileName;
        
        // Dosya var mı kontrol et
        $this->assertFileExists($xmlFilePath, "XML dosyası bulunamadı: {$xmlFileName}");
        
        // Orijinal XML'i oku
        $originalXml = file_get_contents($xmlFilePath);
        $this->assertNotEmpty($originalXml, "Orijinal XML dosyası boş: {$xmlFileName}");
        
        // EFaturaBelgesi ile XML'i işle
        $fatura = EFaturaBelgesi::fromXmlFile($xmlFilePath);
        $this->assertNotNull($fatura, "XML dosyası EFaturaBelgesi ile işlenemedi: {$xmlFileName}");
        
        // İşlenmiş XML'i al
        $processedXml = $fatura->ubl->toXml();
        $this->assertNotEmpty($processedXml, "İşlenmiş XML boş: {$xmlFileName}");
        
        // XML karşılaştırması yap
        $comparisonResult = $this->compareXmls($originalXml, $processedXml, $xmlFileName);
        
        // Test sonuçlarını kontrol et
        $this->assertTrue($comparisonResult['success'], 
            "XML karşılaştırması başarısız: {$xmlFileName}\n" . 
            "Hatalar: " . implode(', ', $comparisonResult['errors'])
        );
        
        // Kritik hatalar var mı kontrol et
        $this->assertEmpty($comparisonResult['critical_errors'], 
            "Kritik hatalar bulundu: {$xmlFileName}\n" . 
            "Hatalar: " . implode(', ', $comparisonResult['critical_errors'])
        );
        
        // Farklılıklar var mı kontrol et
        if (!empty($comparisonResult['differences'])) {
            $this->addToAssertionCount(1); // Test geçti ama uyarı var
            echo "\n⚠️  UYARI: {$xmlFileName} - Farklılıklar bulundu:\n";
            foreach ($comparisonResult['differences'] as $diff) {
                echo "   - {$diff}\n";
            }
        }
        
        echo "\n✅ BAŞARILI: {$xmlFileName} - XML karşılaştırması tamamlandı\n";
        echo "   - Etiket sayısı: Orijinal={$comparisonResult['original_tag_count']}, İşlenmiş={$comparisonResult['processed_tag_count']}\n";
        echo "   - Değer sayısı: Orijinal={$comparisonResult['original_value_count']}, İşlenmiş={$comparisonResult['processed_value_count']}\n";
    }

    /**
     * Test edilecek XML dosyalarını sağlar
     */
    public function xmlFileProvider()
    {
        $xmlDataPath = __DIR__ . '/../xml_data/efatura/';
        $files = [];
        
        if (is_dir($xmlDataPath)) {
            $xmlFiles = glob($xmlDataPath . '*.xml');
            foreach ($xmlFiles as $file) {
                $files[] = [basename($file)];
            }
        }
        
        // Eğer hiç dosya yoksa varsayılan test
        if (empty($files)) {
            $files[] = ['test.xml']; // Varsayılan test dosyası
        }
        
        return $files;
    }

    /**
     * XML'leri karşılaştırır
     */
    private function compareXmls($originalXml, $processedXml, $fileName)
    {
        try {
            // XML'leri DOM'a çevir
            $originalDom = new \DOMDocument('1.0', 'UTF-8');
            $originalDom->preserveWhiteSpace = false;
            $originalDom->loadXML($originalXml);

            $processedDom = new \DOMDocument('1.0', 'UTF-8');
            $processedDom->preserveWhiteSpace = false;
            $processedDom->loadXML($processedXml);

            // Etiket sayma
            $originalTags = $this->countTags($originalDom);
            $processedTags = $this->countTags($processedDom);

            // Değer karşılaştırması
            $originalValues = $this->extractElementValues($originalDom);
            $processedValues = $this->extractElementValues($processedDom);

            // Karşılaştırma sonuçları
            $result = [
                'success' => true,
                'errors' => [],
                'critical_errors' => [],
                'differences' => [],
                'original_tag_count' => count($originalTags),
                'processed_tag_count' => count($processedTags),
                'original_value_count' => count($originalValues),
                'processed_value_count' => count($processedValues)
            ];

            // Etiket karşılaştırması
            foreach ($originalTags as $tagName => $count) {
                if (!isset($processedTags[$tagName])) {
                    $result['critical_errors'][] = "Etiket bulunamadı: {$tagName}";
                } elseif ($count !== $processedTags[$tagName]) {
                    $result['differences'][] = "Etiket sayısı farklı: {$tagName} (Orijinal: {$count}, İşlenmiş: {$processedTags[$tagName]})";
                }
            }

            // Değer karşılaştırması
            foreach ($originalValues as $key => $originalValue) {
                if (!isset($processedValues[$key])) {
                    $result['critical_errors'][] = "Değer bulunamadı: {$key}";
                } elseif (!$this->valuesAreEqual($originalValue, $processedValues[$key])) {
                    $result['differences'][] = "Değer farklı: {$key} (Orijinal: '{$originalValue}', İşlenmiş: '{$processedValues[$key]}')";
                }
            }

            // İşlenmiş XML'de ek değerler var mı kontrol et
            foreach ($processedValues as $key => $processedValue) {
                if (!isset($originalValues[$key])) {
                    $result['differences'][] = "Ek değer eklendi: {$key} = '{$processedValue}'";
                }
            }

            return $result;

        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => ["XML karşılaştırma hatası: " . $e->getMessage()],
                'critical_errors' => ["XML karşılaştırma hatası: " . $e->getMessage()],
                'differences' => []
            ];
        }
    }

    /**
     * XML'deki etiketleri sayar
     */
    private function countTags($dom)
    {
        $tags = [];
        $elements = $dom->getElementsByTagName('*');
        
        foreach ($elements as $element) {
            // Signature ve UBLExtensions etiketlerini atla
            if ($this->isSignatureElement($element) || $this->isUBLExtensionsElement($element)) {
                continue;
            }
            
            $tagName = $element->tagName;
            if (!isset($tags[$tagName])) {
                $tags[$tagName] = 0;
            }
            $tags[$tagName]++;
        }
        
        return $tags;
    }

    /**
     * XML'den element değerlerini çıkarır
     */
    private function extractElementValues($dom)
    {
        $values = [];
        $elements = $dom->getElementsByTagName('*');
        
        foreach ($elements as $element) {
            $path = $this->getElementPathWithIndex($element);
            
            // Signature ve UBLExtensions etiketlerini atla
            if ($this->isSignatureElement($element) || $this->isUBLExtensionsElement($element)) {
                continue;
            }
            
            // Sadece leaf element'lerin text content'ini al
            if ($this->isLeafElement($element)) {
                $textContent = $this->getElementTextContent($element);
                if ($textContent !== '') {
                    $values[$path . '|text()'] = $textContent;
                }
            }
            
            // Attributes - namespace ve schema location'ları hariç
            if ($element->hasAttributes()) {
                foreach ($element->attributes as $attribute) {
                    // Namespace ve schema location attribute'larını atla
                    if ($this->shouldIgnoreAttribute($attribute->name)) {
                        continue;
                    }
                    
                    // Debug için attribute ismini logla
                    if (strpos($attribute->name, 'schema') !== false || strpos($attribute->name, 'xmlns') !== false) {
                        echo "DEBUG: Attribute name: '{$attribute->name}', Local name: '{$attribute->localName}'\n";
                    }
                    
                    $values[$path . '|@' . $attribute->name] = $attribute->value;
                }
            }
        }
        
        return $values;
    }

    /**
     * Element'in leaf element olup olmadığını kontrol eder
     */
    private function isLeafElement($element)
    {
        foreach ($element->childNodes as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                return false;
            }
        }
        return true;
    }

    /**
     * Element'in gerçek text içeriğini alır
     */
    private function getElementTextContent($element)
    {
        $nodeValue = trim($element->nodeValue ?? '');
        if ($nodeValue !== '') {
            return $nodeValue;
        }
        
        $text = '';
        foreach ($element->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE || $child->nodeType === XML_CDATA_SECTION_NODE) {
                $text .= $child->textContent;
            }
        }
        return trim($text);
    }

    /**
     * Element'in XML path'ini index ile oluşturur
     */
    private function getElementPathWithIndex($element)
    {
        $path = [];
        $current = $element;
        
        while ($current && $current->nodeType === XML_ELEMENT_NODE) {
            $tagName = $current->tagName;
            $index = $this->getElementIndex($current);
            $path[] = $tagName . '[' . $index . ']';
            $current = $current->parentNode;
        }
        
        return implode('/', array_reverse($path));
    }

    /**
     * Element'in kardeş elementler arasındaki index'ini bulur
     */
    private function getElementIndex($element)
    {
        $tagName = $element->tagName;
        $index = 1;
        $current = $element->previousSibling;
        
        while ($current) {
            if ($current->nodeType === XML_ELEMENT_NODE && $current->tagName === $tagName) {
                $index++;
            }
            $current = $current->previousSibling;
        }
        
        return $index;
    }

    /**
     * Signature element kontrolü
     */
    private function isSignatureElement($element)
    {
        $current = $element;
        
        while ($current && $current->nodeType === XML_ELEMENT_NODE) {
            $tagName = $current->tagName;
            
            if (strpos($tagName, 'Signature') !== false || 
                strpos($tagName, 'signature') !== false ||
                $tagName === 'ds:Signature' ||
                $tagName === 'Signature' ||
                $tagName === 'cac:Signature' ||
                $tagName === 'cbc:Signature') {
                return true;
            }
            
            $current = $current->parentNode;
        }
        
        return false;
    }

    /**
     * UBLExtensions element kontrolü
     */
    private function isUBLExtensionsElement($element)
    {
        $current = $element;
        
        while ($current && $current->nodeType === XML_ELEMENT_NODE) {
            $tagName = $current->tagName;
            
            if (strpos($tagName, 'UBLExtensions') !== false || 
                strpos($tagName, 'extensions') !== false ||
                $tagName === 'ext:UBLExtensions' ||
                $tagName === 'UBLExtensions' ||
                $tagName === 'cac:UBLExtensions' ||
                $tagName === 'cbc:UBLExtensions') {
                return true;
            }
            
            $current = $current->parentNode;
        }
        
        return false;
    }

    /**
     * İki değerin eşit olup olmadığını kontrol eder
     */
    private function valuesAreEqual($value1, $value2)
    {
        // Önce basit string karşılaştırması
        if ($value1 === $value2) {
            return true;
        }

        // Sayısal değer kontrolü
        if (is_numeric($value1) && is_numeric($value2)) {
            $float1 = (float) $value1;
            $float2 = (float) $value2;
            return abs($float1 - $float2) < 0.0001;
        }

        // Tarih formatı kontrolü
        if ($this->isDateFormat($value1) && $this->isDateFormat($value2)) {
            try {
                $date1 = new \DateTime($value1);
                $date2 = new \DateTime($value2);
                return $date1->format('Y-m-d H:i:s') === $date2->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return $value1 === $value2;
            }
        }

        // Boşluk normalize et
        $trimmed1 = trim($value1);
        $trimmed2 = trim($value2);
        if ($trimmed1 === $trimmed2) {
            return true;
        }

        return false;
    }

    /**
     * Tarih formatı kontrolü
     */
    private function isDateFormat($value)
    {
        $datePatterns = [
            '/^\d{4}-\d{2}-\d{2}$/',
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/',
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/',
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/',
        ];

        foreach ($datePatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Attribute'un ignore edilip edilmeyeceğini kontrol eder
     */
    private function shouldIgnoreAttribute($attributeName)
    {
        // Tam eşleşme kontrolü
        $ignoreAttributes = [
            'xmlns',
            'xmlns:cac',
            'xmlns:cbc', 
            'xmlns:ccts',
            'xmlns:ds',
            'xmlns:ext',
            'xmlns:qdt',
            'xmlns:ubltr',
            'xmlns:udt',
            'xmlns:xades',
            'xmlns:xsi',
            'xsi:schemaLocation',
            'xsi:type',
            'xsi:nil',
            'schemaLocation',  // Local name olarak da gelebilir
            'type',
            'nil'
        ];

        if (in_array($attributeName, $ignoreAttributes)) {
            return true;
        }

        // Namespace ile başlayan attribute'ları ignore et
        if (strpos($attributeName, 'xmlns') === 0) {
            return true;
        }

        // xsi ile başlayan attribute'ları ignore et
        if (strpos($attributeName, 'xsi:') === 0) {
            return true;
        }

        // schemaLocation, type, nil gibi xsi attribute'larını ignore et
        if (in_array($attributeName, ['schemaLocation', 'type', 'nil'])) {
            return true;
        }

        return false;
    }

    /**
     * Belirli bir XML dosyası için test çalıştır
     * 
     * @param string $xmlFileName Test edilecek XML dosya adı
     */
    public function testSpecificXmlFile($xmlFileName = null)
    {
        if ($xmlFileName === null) {
            $this->markTestSkipped('XML dosya adı belirtilmedi');
            return;
        }
        
        $this->testXmlComparison($xmlFileName);
    }
}
