<?php

namespace EFaturacim\Utils\Html\JqueryToast;

class Toast
{
    private string $text;
    private string $title;
    private string $icon;
    private array $options;

    public function __construct(string $text, string $title = '', string $icon = 'info', array $options = [])
    {
        $this->text = $text;
        $this->title = $title;
        $this->icon = $icon;
        $this->options = $options;
    }

    public function render(): string
    {
        $defaultOptions = [
            'showHideTransition' => 'slide',
            'allowToastClose' => true,
            'hideAfter' => 3000,
            'stack' => false,
            'position' => 'top-right'
        ];

        $mergedOptions = array_merge($defaultOptions, $this->options);
        
        $optionsJson = json_encode($mergedOptions, JSON_UNESCAPED_UNICODE);
        
        $html = "
        <script>
        $(document).ready(function() {
            $.toast({
                text: " . json_encode($this->text, JSON_UNESCAPED_UNICODE) . ",
                title: " . json_encode($this->title, JSON_UNESCAPED_UNICODE) . ",
                icon: " . json_encode($this->icon, JSON_UNESCAPED_UNICODE) . ",
                " . $this->buildOptionsString($mergedOptions) . "
            });
        });
        </script>";

        return $html;
    }

    private function buildOptionsString(array $options): string
    {
        $optionStrings = [];
        foreach ($options as $key => $value) {
            if (is_bool($value)) {
                $optionStrings[] = "$key: " . ($value ? 'true' : 'false');
            } elseif (is_string($value)) {
                $optionStrings[] = "$key: " . json_encode($value, JSON_UNESCAPED_UNICODE);
            } else {
                $optionStrings[] = "$key: $value";
            }
        }
        return implode(",\n                ", $optionStrings);
    }

    public static function success(string $text, string $title = '', array $options = []): self
    {
        return new self($text, $title, 'success', $options);
    }

    public static function error(string $text, string $title = '', array $options = []): self
    {
        return new self($text, $title, 'error', $options);
    }

    public static function warning(string $text, string $title = '', array $options = []): self
    {
        return new self($text, $title, 'warning', $options);
    }

    public static function info(string $text, string $title = '', array $options = []): self
    {
        return new self($text, $title, 'info', $options);
    }
}
