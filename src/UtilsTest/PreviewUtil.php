<?php
namespace Efaturacim\Util\Utils;

use DOMDocument;

class PreviewUtil{
    public static function showAsHtml($html,$options=null){
        if(Options::ensureParam($options) && $options instanceof Options){
            echo "<html>";
            echo "<body>";
            echo "".$html;
            echo "</body></html>";
        }
        die("");
    }
    /**
     * Displays a given XML string as pretty-printed, syntax-highlighted HTML.
     *
     * @param string|null $xmlString The XML string to display.
     * @return void
     */
    public static function previewXml(?string $xmlString,$showOutput=false){
        $s = '';
        if (empty($xmlString)) {
            $s .= '<p>No XML content to display.</p>';
            if($showOutput){ return self::showAsHtml($s); }
            return $s;
        }

        // Use DOMDocument to format the XML nicely
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        // Use @ to suppress warnings on invalid XML, we'll handle it below
        if (!@$dom->loadXML($xmlString)) {
            // If loading fails, just display the raw string, escaped.
            $s .= '<h3>Invalid XML</h3>';
            $s .= '<pre><code>' . htmlspecialchars($xmlString) . '</code></pre>';
            if($showOutput){ return self::showAsHtml($s); }
            return $s;
        }

        $formattedXml = $dom->saveXML();

        // Escape the formatted XML for safe display in HTML
        $escapedXml = htmlspecialchars($formattedXml);

        // Output with some basic styling for readability
        $s .= <<<HTML
<style>
    pre.my_xml {
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-left: 3px solid #f36d33;
        color: #333;
        font-family: monospace;
        font-size: 14px;
        line-height: 1.5;
        padding: 1em 1.5em;
    }
</style>
<pre class="my_xml"><code>{$escapedXml}</code></pre>
HTML;
        if($showOutput){ return self::showAsHtml($s); }
        return $s;
    }

    /**
     * Displays a given JSON string as pretty-printed, syntax-highlighted HTML.
     *
     * @param string|null $jsonString The JSON string to display.
     * @param bool $showOutput
     * @return string|void
     */
    public static function previewJson(?string $jsonString, bool $showOutput = false)
    {
        $s = '';
        if (empty($jsonString)) {
            $s .= '<p>No JSON content to display.</p>';
            if ($showOutput) {
                return self::showAsHtml($s);
            }
            return $s;
        }

        // Try to decode and re-encode for pretty printing
        $decoded = json_decode($jsonString);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // If decoding fails, just display the raw string, escaped.
            $s .= '<h3>Invalid JSON</h3>';
            $s .= '<pre><code>' . htmlspecialchars($jsonString) . '</code></pre>';
            if ($showOutput) {
                return self::showAsHtml($s);
            }
            return $s;
        }

        $formattedJson = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Escape the formatted JSON for safe display in HTML
        $escapedJson = htmlspecialchars($formattedJson);

        // Output with some basic styling for readability
        $s .= <<<HTML
<style>
    pre.my_json {
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-left: 3px solid #007bff; /* Blue for JSON */
        color: #333;
        font-family: monospace;
        font-size: 14px;
        line-height: 1.5;
        padding: 1em 1.5em;
    }
</style>
<pre class="my_json"><code>{$escapedJson}</code></pre>
HTML;
        if ($showOutput) {
            return self::showAsHtml($s);
        }
        return $s;
    }

    /**
     * Displays a given PHP variable as pretty-printed, syntax-highlighted HTML.
     *
     * @param mixed $variable The variable to display.
     * @param bool $showOutput
     * @return string|void
     */
    public static function previewPhpVar(mixed $variable, bool $showOutput = false)
    {
        $s = '';
        if (is_null($variable)) {
            $s .= '<p>No variable content to display.</p>';
            if ($showOutput) {
                return self::showAsHtml($s);
            }
            return $s;
        }

        // Use print_r to get a human-readable representation of the variable
        $formattedVar = print_r($variable, true);

        // Escape the formatted variable for safe display in HTML
        $escapedVar = htmlspecialchars($formattedVar);

        // Output with some basic styling for readability
        $s .= <<<HTML
<style>
    pre.my_phpvar {
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        border-left: 3px solid #8855ff; /* Purple for PHP Var */
        color: #333;
        font-family: monospace;
        font-size: 14px;
        line-height: 1.5;
        padding: 1em 1.5em;
    }
</style>
<pre class="my_phpvar"><code>{$escapedVar}</code></pre>
HTML;
        if ($showOutput) {
            return self::showAsHtml($s);
        }
        return $s;
    }
    
}
?>