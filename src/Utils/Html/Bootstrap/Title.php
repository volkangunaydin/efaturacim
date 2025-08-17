<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

class Title extends HtmlComponent{
    public function initMe(){
        
    }
    /**
     * Get default options for the accordion
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return [
            'tag' => 'h1',
            'div' => false            
        ];
    }
    public function setSize($tagSize=1){
        $this->options['tag'] = 'h'.$tagSize;
        return $this;
    }
    public function toHtmlAsString($doc = null){
        $tag = $this->options['tag'];
        $text = $this->options['text'];
        $div = $this->options['div'];
        
        $html = '<'.$tag.'>'.$text.'</'.$tag.'>';
        
        if ($div) {
            $html = '<div>'.$html.'</div>';
        }
        
        return $html;
    }
}