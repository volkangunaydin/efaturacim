<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

class WidgetCard extends HtmlComponent
{
    public function toHtmlAsString()
    {
        $this->options['bg1'] = isset($this->options['bg1']) ? $this->options['bg1'] : null;
        $this->options['bg2'] = isset($this->options['bg2']) ? $this->options['bg2'] : null;
        $this->options['deg'] = isset($this->options['deg']) ? $this->options['deg'] : 90.0;
        $this->options['title'] = isset($this->options['title']) ? $this->options['title'] : null;
        $this->options['message'] = isset($this->options['message']) ? $this->options['message'] : null;
        $this->options['icon'] = isset($this->options['icon']) ? $this->options['icon'] : null;
        $this->options['textColor'] = isset($this->options['textColor']) ? $this->options['textColor'] : null;
        $this->options['text'] = isset($this->options['text']) ? $this->options['text'] : null;
        $this->options['btnText'] = isset($this->options['btnText']) ? $this->options['btnText'] : null;
        $this->options['btnUrl'] = isset($this->options['btnUrl']) ? $this->options['btnUrl'] : null;
        $this->options['number'] = isset($this->options['number']) ? $this->options['number'] : null;
        $this->options['columns'] = isset($this->options['columns']) ? $this->options['columns'] : [];
        $this->options['alerts'] = isset($this->options['alerts']) ? $this->options['alerts'] : [];
        $this->options['margin'] = isset($this->options['margin']) ? $this->options['margin'] : [];

        $backgroundCss = '';
        if ($this->options['bg1'] !== '') {
            if ($this->options['bg2'] !== '') {
                $degreeNum = is_numeric($this->options['deg']) ? (float) $this->options['deg'] : 90.0;
                if ($degreeNum < 0) {
                    $degreeNum = 0;
                }
                if ($degreeNum > 360) {
                    $degreeNum = 360;
                }
                $degStr = rtrim(rtrim(sprintf('%.2f', $degreeNum), '0'), '.');
                $backgroundCss = 'linear-gradient(' . $degStr . 'deg, ' . $this->options['bg1'] . ', ' . $this->options['bg2'] . ')';
            } else {
                $backgroundCss = $this->options['bg1'];
            }
        }

        $marginCss = '';
        if ($this->options['margin']) {
            if (isset($this->options['margin']['top']) && $this->options['margin']['top'] != 0) {
                $marginCss .= 'margin-top: ' . $this->options['margin']['top'] . 'px;';
            }
            if (isset($this->options['margin']['left']) && $this->options['margin']['left'] != 0) {
                $marginCss .= 'margin-left: ' . $this->options['margin']['left'] . 'px;';
            }
            if (isset($this->options['margin']['bottom']) && $this->options['margin']['bottom'] != 0) {
                $marginCss .= 'margin-bottom: ' . $this->options['margin']['bottom'] . 'px;';
            }
            if (isset($this->options['margin']['right']) && $this->options['margin']['right'] != 0) {
                $marginCss .= 'margin-right: ' . $this->options['margin']['right'] . 'px;';
            }
        }

        $iconHtml = '';
        if ($this->options['icon'] !== '') {
            $iconHtml = '<div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.15);"><i class="' . $this->options['icon'] . ' fs-2" style="color: ' . $this->options['textColor'] . '; opacity: 0.9;"></i></div>';
        }

        if ($this->options['btnText'] && $this->options['btnUrl']) {
            $btnHtml = '<a href="' . $this->options['btnUrl'] . '" class="btn btn-outline-light btn-sm rounded-pill" style="color: ' . $this->options['textColor'] . '; border-color: rgba(255,255,255,0.3);">' . $this->options['btnText'] . '</a>';
        } else {
            $btnHtml = '';
        }

        $columnsHtml = '';
        if (isset($this->options['columns']) && is_array($this->options['columns'])) {
            $columnsHtml = '<div class="row mt-3">';
            foreach ($this->options['columns'] as $column) {
                $columnText = isset($column['title']) ? htmlspecialchars($column['title'], ENT_QUOTES, 'UTF-8') : '';
                $columnNumber = isset($column['text']) ? htmlspecialchars($column['text'], ENT_QUOTES, 'UTF-8') : '';

                $columnsHtml .= '<div class="col-lg-6 mb-2">'
                    . '<div class="d-flex justify-content-between align-items-center p-2" style="background: rgba(255,255,255,0.1); border-radius: 8px;">'
                    . '<span style="color: ' . $this->options['textColor'] . '; font-size: 0.9rem;">' . $columnText . '</span>'
                    . '<span style="color: ' . $this->options['textColor'] . '; font-size: 1.2rem; font-weight: 600;">' . $columnNumber . '</span>'
                    . '</div>'
                    . '</div>';
            }
            $columnsHtml .= '</div>';
        }

        $alertsHtml = '';
        if ($this->options['alerts']) {
            foreach ($this->options['alerts'] as $alert) {
                $alertType = isset($alert['type']) ? htmlspecialchars($alert['type'], ENT_QUOTES, 'UTF-8') : 'info';
                $alertText = isset($alert['text']) ? htmlspecialchars($alert['text'], ENT_QUOTES, 'UTF-8') : '';
                $alertsHtml .= '<div class="alert alert-' . $alertType . ' mt-3" role="alert">' . $alertText . '</div>';
            }
        }

        $s = '<div class="card w-100 border-0 rounded-3 shadow-lg overflow-hidden position-relative" style="background: ' . $backgroundCss . '; backdrop-filter: blur(10px); transition: all 0.3s ease; transform: translateY(0); ' . $marginCss . '" onmouseover="this.style.transform=\'translateY(-8px)\'; this.style.boxShadow=\'0 12px 40px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 8px 32px rgba(0,0,0,0.1)\'">';
        $s .= '<div class="card-body p-4 position-relative" style="z-index: 2;">';
        $s .= '<div class="d-flex align-items-center justify-content-between">';
        $s .= '<div class="d-flex align-items-center flex-grow-1">';
        $s .= $iconHtml;
        $s .= '<div class="flex-grow-1">';
        $s .= '<h5 class="card-title h5 fw-semibold mb-2" style="color: ' . $this->options['textColor'] . '; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">' . $this->options['title'] . '</h5>';
        $s .= '<p class="card-text small mb-2" style="color: ' . $this->options['textColor'] . '; opacity: 0.85; line-height: 1.4;">' . $this->options['text'] . '</p>';
        $s .= $btnHtml;
        $s .= '</div>';
        $s .= '</div>';
        $s .= '<div class="text-end flex-shrink-0"><div style="color: ' . $this->options['textColor'] . '; text-shadow: 0 2px 4px rgba(0,0,0,0.1); font-size: 5rem; font-weight: 700; line-height: 1;">' . $this->options['number'] . '</div></div>';
        $s .= '</div>';
        $s .= $columnsHtml;
        $s .= $alertsHtml;
        $s .= '</div>';
        $s .= '<div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%); pointer-events: none; z-index: 1;"></div>';
        $s .= '</div>';
        return $s;
    }
    public function getJsLines()
    {
        return null;
    }

    public static function newObj($options = null)
    {
        return (new static($options));
    }

    // Hazır tema fonksiyonları
    public function danger()
    {
        $this->setBg1('#dc3545')
             ->setBg2('#c82333')
             ->setDeg(135)
             ->setIcon('fas fa-exclamation-triangle')
             ->setTextColor('#ffffff');
        return $this;
    }

    public function success()
    {
        $this->setBg1('#28a745')
             ->setBg2('#1e7e34')
             ->setDeg(135)
             ->setIcon('fas fa-check-circle')
             ->setTextColor('#ffffff');
        return $this;
    }

    public function warning()
    {
        $this->setBg1('#ffc107')
             ->setBg2('#e0a800')
             ->setDeg(135)
             ->setIcon('fas fa-exclamation-circle')
             ->setTextColor('#212529');
        return $this;
    }

    public function info()
    {
        $this->setBg1('#17a2b8')
             ->setBg2('#138496')
             ->setDeg(135)
             ->setIcon('fas fa-info-circle')
             ->setTextColor('#ffffff');
        return $this;
    }

    public function primary()
    {
        $this->setBg1('#007bff')
             ->setBg2('#0056b3')
             ->setDeg(135)
             ->setIcon('fas fa-star')
             ->setTextColor('#ffffff');
        return $this;
    }

    public function secondary()
    {
        $this->setBg1('#6c757d')
             ->setBg2('#545b62')
             ->setDeg(135)
             ->setIcon('fas fa-cog')
             ->setTextColor('#ffffff');
        return $this;
    }

    public function dark()
    {
        $this->setBg1('#343a40')
             ->setBg2('#1d2124')
             ->setDeg(135)
             ->setIcon('fas fa-moon')
             ->setTextColor('#ffffff');
        return $this;
    }

    public function light()
    {
        $this->setBg1('#f8f9fa')
             ->setBg2('#e2e6ea')
             ->setDeg(135)
             ->setIcon('fas fa-sun')
             ->setTextColor('#212529');
        return $this;
    }

    public function purple()
    {
        $this->setBg1('#6f42c1')
             ->setBg2('#5a2d91')
             ->setDeg(135)
             ->setIcon('fas fa-gem')
             ->setTextColor('#ffffff');
        return $this;
    }

    public function pink()
    {
        $this->setBg1('#e83e8c')
             ->setBg2('#c71f6b')
             ->setDeg(135)
             ->setIcon('fas fa-heart')
             ->setTextColor('#ffffff');
        return $this;
    }

    public function orange()
    {
        $this->setBg1('#fd7e14')
             ->setBg2('#e55a00')
             ->setDeg(135)
             ->setIcon('fas fa-fire')
             ->setTextColor('#ffffff');
        return $this;
    }

    public function teal()
    {
        $this->setBg1('#20c997')
             ->setBg2('#1a9f7a')
             ->setDeg(135)
             ->setIcon('fas fa-leaf')
             ->setTextColor('#ffffff');
        return $this;
    }

    public function setBg1($bg1)
    {
        $this->options['bg1'] = $bg1;
        return $this;
    }
    public function setBg2($bg2)
    {
        $this->options['bg2'] = $bg2;
        return $this;
    }
    public function setDeg($deg)
    {
        $this->options['deg'] = $deg;
        return $this;
    }
    public function setTitle($title)
    {
        $this->options['title'] = $title;
        return $this;
    }
    public function setMessage($message)
    {
        $this->options['message'] = $message;
        return $this;
    }
    public function setIcon($icon)
    {
        $this->options['icon'] = $icon;
        return $this;
    }
    public function setTextColor($textColor)
    {
        $this->options['textColor'] = $textColor;
        return $this;
    }
    public function setMargin($top = 0, $left = 0, $bottom = 0, $right = 0)
    {
        $this->options['margin'] = [
            'top' => $top,
            'left' => $left,
            'bottom' => $bottom,
            'right' => $right
        ];
        return $this;
    }
    public function setText($text)
    {
        $this->options['text'] = $text;
        return $this;
    }
    public function setButtonText($btnText)
    {
        $this->options['btnText'] = $btnText;
        return $this;
    }
    public function setButtonUrl($btnUrl)
    {
        $this->options['btnUrl'] = $btnUrl;
        return $this;
    }
    public function setNumber($number)
    {
        $this->options['number'] = $number;
        return $this;
    }

    public function appendColumn($title, $text)
    {
        if (!isset($this->options['columns'])) {
            $this->options['columns'] = [];
        }
        $this->options['columns'][] = ['title' => $title, 'text' => $text];
        return $this;
    }

    public function appendAlert($type, $text)
    {
        if (!isset($this->options['alerts'])) {
            $this->options['alerts'] = [];
        }
        $this->options['alerts'][] = ['type' => $type, 'text' => $text];
        return $this;
    }

    public static function widgetCard($title, $message, $options = null)
    {
        $instance = new self();
        $instance->setTitle($title);
        $instance->setMessage($message);

        if ($options && is_array($options)) {
            foreach ($options as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($instance, $method)) {
                    $instance->$method($value);
                }
            }
        }

        return $instance->toHtmlAsString();
    }
}
?>