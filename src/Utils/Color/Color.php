<?php
namespace Efaturacim\Util\Utils\Color;
class Color{
    protected $org;
    protected $rgba   = array(0,0,0,100);            
    protected $_isok  = false;
    
    public static $NAMED_COLORS = array(
        // Standard CSS Named Colors
        'aliceblue' => 'F0F8FF'
        ,'antiquewhite' => 'FAEBD7','aqua' => '00FFFF','aquamarine' => '7FFFD4'
        ,'azure' => 'F0FFFF','beige' => 'F5F5DC','bisque' => 'FFE4C4'
        ,'black' => '000000','blanchedalmond' => 'FFEBCD','blue' => '0000FF'
        ,'blueviolet' => '8A2BE2','brown' => 'A52A2A','burlywood' => 'DEB887'
        ,'cadetblue' => '5F9EA0','chartreuse' => '7FFF00','chocolate' => 'D2691E'
        ,'coral' => 'FF7F50','cornflowerblue' => '6495ED','cornsilk' => 'FFF8DC'
        ,'crimson' => 'DC143C','cyan' => '00FFFF','darkblue' => '00008B'
        ,'darkcyan' => '008B8B','darkgoldenrod' => 'B8860B','darkgray' => 'A9A9A9'
        ,'darkgreen' => '006400','darkgrey' => 'A9A9A9','darkkhaki' => 'BDB76B','darkmagenta' => '8B008B'
        ,'darkolivegreen' => '556B2F','darkorange' => 'FF8C00','darkorchid' => '9932CC'
        ,'darkred' => '8B0000','darksalmon' => 'E9967A','darkseagreen' => '8FBC8F','darkslateblue' => '483D8B'
        ,'darkslategray' => '2F4F4F','darkslategrey' => '2F4F4F','darkturquoise' => '00CED1','darkviolet' => '9400D3'
        ,'deeppink' => 'FF1493','deepskyblue' => '00BFFF','dimgray' => '696969','dimgrey' => '696969','dodgerblue' => '1E90FF'
        ,'firebrick' => 'B22222','floralwhite' => 'FFFAF0','forestgreen' => '228B22','fuchsia' => 'FF00FF','gainsboro' => 'DCDCDC'
        ,'ghostwhite' => 'F8F8FF','gold' => 'FFD700','goldenrod' => 'DAA520','gray' => '808080','green' => '008000'
        ,'greenyellow' => 'ADFF2F','grey' => '808080','honeydew' => 'F0FFF0','hotpink' => 'FF69B4','indianred' => 'CD5C5C'
        ,'indigo' => '4B0082','ivory' => 'FFFFF0','khaki' => 'F0E68C','lavender' => 'E6E6FA','lavenderblush' => 'FFF0F5'
        ,'lawngreen' => '7CFC00','lemonchiffon' => 'FFFACD','lightblue' => 'ADD8E6','lightcoral' => 'F08080','lightcyan' => 'E0FFFF'
        ,'lightgoldenrodyellow' => 'FAFAD2','lightgray' => 'D3D3D3','lightgreen' => '90EE90','lightgrey' => 'D3D3D3'
        ,'lightpink' => 'FFB6C1','lightsalmon' => 'FFA07A','lightseagreen' => '20B2AA','lightskyblue' => '87CEFA'
        ,'lightslategray' => '778899','lightslategrey' => '778899','lightsteelblue' => 'B0C4DE','lightyellow' => 'FFFFE0'
        ,'lime' => '00FF00','limegreen' => '32CD32','linen' => 'FAF0E6','magenta' => 'FF00FF','maroon' => '800000'
        ,'mediumaquamarine' => '66CDAA','mediumblue' => '0000CD','mediumorchid' => 'BA55D3','mediumpurple' => '9370D0','mediumseagreen' => '3CB371'
        ,'mediumslateblue' => '7B68EE','mediumspringgreen' => '00FA9A','mediumturquoise' => '48D1CC','mediumvioletred' => 'C71585'
        ,'midnightblue' => '191970','mintcream' => 'F5FFFA','mistyrose' => 'FFE4E1','moccasin' => 'FFE4B5','navajowhite' => 'FFDEAD'
        ,'navy' => '000080','oldlace' => 'FDF5E6','olive' => '808000','olivedrab' => '6B8E23','orange' => 'FFA500','orangered' => 'FF4500'
        ,'orchid' => 'DA70D6','palegoldenrod' => 'EEE8AA','palegreen' => '98FB98','paleturquoise' => 'AFEEEE','palevioletred' => 'DB7093'
        ,'papayawhip' => 'FFEFD5','peachpuff' => 'FFDAB9','peru' => 'CD853F','pink' => 'FFC0CB','plum' => 'DDA0DD','powderblue' => 'B0E0E6'
        ,'purple' => '800080','red' => 'FF0000','rosybrown' => 'BC8F8F','royalblue' => '4169E1','saddlebrown' => '8B4513','salmon' => 'FA8072'
        ,'sandybrown' => 'F4A460','seagreen' => '2E8B57','seashell' => 'FFF5EE','sienna' => 'A0522D','silver' => 'C0C0C0','skyblue' => '87CEEB'
        ,'slateblue' => '6A5ACD','slategray' => '708090','slategrey' => '708090','snow' => 'FFFAFA','springgreen' => '00FF7F'
        ,'steelblue' => '4682B4','tan' => 'D2B48C','teal' => '008080','thistle' => 'D8BFD8','tomato' => 'FF6347','turquoise' => '40E0D0'
        ,'violet' => 'EE82EE','wheat' => 'F5DEB3','white' => 'FFFFFF','whitesmoke' => 'F5F5F5','yellow' => 'FFFF00','yellowgreen' => '9ACD32'
        
        // Custom Colors
        ,"blue_soft"=>"1a50cb","pinkdark"=>"ce08b9"
        
        // Modern Web Colors
        ,'transparent' => '000000'
        ,'currentcolor' => '000000'
        ,'inherit' => '000000'
        
        // Material Design Colors
        ,'material_red' => 'F44336','material_pink' => 'E91E63','material_purple' => '9C27B0','material_deeppurple' => '673AB7'
        ,'material_indigo' => '3F51B5','material_blue' => '2196F3','material_lightblue' => '03A9F4','material_cyan' => '00BCD4'
        ,'material_teal' => '009688','material_green' => '4CAF50','material_lightgreen' => '8BC34A','material_lime' => 'CDDC39'
        ,'material_yellow' => 'FFEB3B','material_amber' => 'FFC107','material_orange' => 'FF9800','material_deeporange' => 'FF5722'
        ,'material_brown' => '795548','material_grey' => '9E9E9E','material_bluegrey' => '607D8B'
        
        // Bootstrap Colors
        ,'bootstrap_primary' => '007BFF','bootstrap_secondary' => '6C757D','bootstrap_success' => '28A745'
        ,'bootstrap_danger' => 'DC3545','bootstrap_warning' => 'FFC107','bootstrap_info' => '17A2B8'
        ,'bootstrap_light' => 'F8F9FA','bootstrap_dark' => '343A40'
        
        // Social Media Brand Colors
        ,'facebook' => '1877F2','twitter' => '1DA1F2','instagram' => 'E4405F','linkedin' => '0A66C2'
        ,'youtube' => 'FF0000','whatsapp' => '25D366','telegram' => '0088CC','discord' => '5865F2'
        ,'github' => '181717','reddit' => 'FF4500','pinterest' => 'BD081C','snapchat' => 'FFFC00'
        
        // Popular Brand Colors
        ,'google' => '4285F4','microsoft' => '00A4EF','apple' => '000000','amazon' => 'FF9900'
        ,'netflix' => 'E50914','spotify' => '1DB954','airbnb' => 'FF5A5F','uber' => '000000'
        ,'starbucks' => '006241','coca_cola' => 'F40009','mcdonalds' => 'FFC72C','nike' => '000000'
        
        // Modern UI Colors
        ,'slate_50' => 'F8FAFC','slate_100' => 'F1F5F9','slate_200' => 'E2E8F0','slate_300' => 'CBD5E1'
        ,'slate_400' => '94A3B8','slate_500' => '64748B','slate_600' => '475569','slate_700' => '334155'
        ,'slate_800' => '1E293B','slate_900' => '0F172A','slate_950' => '020617'
        
        ,'gray_50' => 'F9FAFB','gray_100' => 'F3F4F6','gray_200' => 'E5E7EB','gray_300' => 'D1D5DB'
        ,'gray_400' => '9CA3AF','gray_500' => '6B7280','gray_600' => '4B5563','gray_700' => '374151'
        ,'gray_800' => '1F2937','gray_900' => '111827','gray_950' => '030712'
        
        ,'zinc_50' => 'FAFAFA','zinc_100' => 'F4F4F5','zinc_200' => 'E4E4E7','zinc_300' => 'D4D4D8'
        ,'zinc_400' => 'A1A1AA','zinc_500' => '71717A','zinc_600' => '52525B','zinc_700' => '3F3F46'
        ,'zinc_800' => '27272A','zinc_900' => '18181B','zinc_950' => '09090B'
        
        // Semantic Colors
        ,'success' => '28A745','error' => 'DC3545','warning' => 'FFC107','info' => '17A2B8'
        ,'primary' => '007BFF','secondary' => '6C757D','accent' => 'FF6B6B','muted' => '6C757D'
        
        // Accessibility Colors
        ,'accessible_blue' => '0066CC','accessible_green' => '2E7D32','accessible_red' => 'D32F2F'
        ,'accessible_yellow' => 'F57C00','accessible_purple' => '7B1FA2','accessible_teal' => '00796B'
        
        // Seasonal Colors
        ,'spring_green' => '77DD77','summer_yellow' => 'FFD700','autumn_orange' => 'FF8C00','winter_blue' => '87CEEB'
        ,'christmas_red' => 'DC143C','christmas_green' => '228B22','valentine_pink' => 'FF69B4','halloween_orange' => 'FF6600'
        
        // Nature Colors
        ,'forest_green' => '228B22','ocean_blue' => '006994','sunset_orange' => 'FD5E53','sky_blue' => '87CEEB'
        ,'grass_green' => '7CFC00','rose_pink' => 'FF007F','lavender_purple' => '967BB6','sunflower_yellow' => 'FFD700'
        
        // Tech Colors
        ,'neon_blue' => '00FFFF','neon_green' => '39FF14','neon_pink' => 'FF1493','neon_yellow' => 'FFFF00'
        ,'cyber_black' => '0A0A0A','cyber_gray' => '1A1A1A','cyber_white' => 'F0F0F0','cyber_red' => 'FF0033'
        
                 // Gradient Colors
         ,'gradient_sunset' => 'FF6B6B','gradient_ocean' => '4ECDC4','gradient_forest' => '45B7D1','gradient_cherry' => 'FF9A9E'
         ,'gradient_aurora' => 'A8E6CF','gradient_cotton' => 'FFD3A5','gradient_lavender' => 'E0C3FC','gradient_rose' => 'FFAFBD'
         
         // Turkish Color Names - Türkçe Renk İsimleri
         ,'kirmizi' => 'FF0000','yesil' => '008000','mavi' => '0000FF','sari' => 'FFFF00','turuncu' => 'FFA500'
         ,'mor' => '800080','pembe' => 'FFC0CB','kahverengi' => 'A52A2A','siyah' => '000000','beyaz' => 'FFFFFF'
         ,'gri' => '808080','lacivert' => '000080','turkuaz' => '40E0D0','altin' => 'FFD700','gumus' => 'C0C0C0'
         ,'bronz' => 'CD7F32','bakir' => 'B87333','platinum' => 'E5E4E2','elmas' => 'B9F2FF','zirkon' => 'F4F4F4'
         
         // Turkish Nature Colors - Türkçe Doğa Renkleri
         ,'deniz_mavisi' => '006994','gokyuzu_mavisi' => '87CEEB','orman_yesili' => '228B22','cimen_yesili' => '7CFC00'
         ,'gunes_sarisi' => 'FFD700','ay_parlati' => 'F0F8FF','yildiz_parlati' => 'FFFFF0','bulut_grisi' => 'D3D3D3'
         ,'toprak_kahve' => '8B4513','kum_sarisi' => 'F4A460','tas_grisi' => '708090','agac_kahve' => 'A0522D'
         
         // Turkish Food Colors - Türkçe Yemek Renkleri
         ,'domates_kirmizi' => 'FF6347','salatalik_yesili' => '90EE90','havuc_turuncu' => 'FF8C00','patlican_mor' => '800080'
         ,'limon_sarisi' => 'FFFF00','portakal_turuncu' => 'FFA500','elma_kirmizi' => 'DC143C','uzum_mor' => '800080'
         ,'kayisi_turuncu' => 'FFA500','seftali_pembe' => 'FFC0CB','kiraz_kirmizi' => 'DC143C','cilek_kirmizi' => 'FF1493'
         
         // Turkish Cultural Colors - Türkçe Kültürel Renkler
         ,'turk_bayragi_kirmizi' => 'E30A17','turk_bayragi_beyaz' => 'FFFFFF','osmanli_altin' => 'FFD700'
         ,'sultan_mor' => '800080','harem_pembe' => 'FFC0CB','camii_mavi' => '006994','minare_grisi' => '708090'
         ,'halicik_kirmizi' => 'DC143C','kilim_desen' => 'CD853F','seramik_turkuaz' => '40E0D0','mozaik_altin' => 'FFD700'
         
         // Turkish Regional Colors - Türkçe Bölgesel Renkler
         ,'kapadokya_turuncu' => 'FF8C00','pamukkale_beyaz' => 'FFFFFF','efes_altin' => 'FFD700','trabzon_yesili' => '228B22'
         ,'antalya_mavi' => '006994','izmir_deniz' => '87CEEB','bursa_yesil' => '228B22','ankara_gri' => '708090'
         ,'istanbul_boğaz' => '006994','van_golu_mavi' => '87CEEB','nemrut_altin' => 'FFD700','safranbolu_kahve' => '8B4513'
         
         // Turkish Seasonal Colors - Türkçe Mevsim Renkleri
         ,'ilkbahar_yesili' => '77DD77','yaz_sarisi' => 'FFD700','sonbahar_turuncu' => 'FF8C00','kis_mavi' => '87CEEB'
         ,'nevruz_yesili' => '77DD77','ramazan_yesili' => '228B22','kurban_kirmizi' => 'DC143C','bayram_altin' => 'FFD700'
         
         // Turkish Traditional Colors - Türkçe Geleneksel Renkler
         ,'gelin_beyazi' => 'FFFFFF','damat_siyah' => '000000','nazar_mavi' => '006994','boncuk_mavi' => '87CEEB'
         ,'halicik_kirmizi' => 'DC143C','kilim_desen' => 'CD853F','seramik_turkuaz' => '40E0D0','mozaik_altin' => 'FFD700'
         ,'ebru_mavi' => '87CEEB','ebru_yesil' => '90EE90','ebru_turuncu' => 'FFA500','ebru_pembe' => 'FFC0CB'
         
         // Turkish Modern Colors - Türkçe Modern Renkler
         ,'teknoloji_mavi' => '0066CC','dijital_yesil' => '00FF00','sanal_mor' => '800080','cyber_pembe' => 'FF1493'
         ,'metro_gri' => '708090','avm_beyaz' => 'FFFFFF','plaza_cam' => 'F0F8FF','otoban_asfalt' => '2F4F4F'
    );        
    /**
     * Color class for html and images
     * Usage     : Color::newColor(colorStringOrArray);
     * Hex Usage : Color::newColor("#Aa12dd") || Color::newColor("Aa12dd") || Color::newColor("6f9") || Color::newColor("#6f9")  
     * Rgb Usage : Color::newColor(array(121,11,33))
     * HSL Usage : Color::newColor(array("H"=>147,"S"=>0.5,"L"=>0.47))
     * Object Usage : Color::newColor( otherColorObject )
     */
    public function __construct($org,$alpha=null){
        $this->setValue($org,$alpha);
    }
    /**
     * Check if color parsing is ok
     * @return boolean
     */
    public function isOK(){ return $this->_isok; }
    /**
     * Setting new value
     * @param String $org
     * @return \Vulcan\Base\Util\Color\Color
     */
    public function setValue($org,$alpha=null){
        $this->org = $org;
        if($alpha && is_int($alpha) && $alpha>0 && $alpha<=100){
            $this->rgba[3] = $alpha;
        }
        if($this->org && is_string($this->org) && strlen($this->org)>0){
            $this->org = trim("".$org);
        }
        $this->parse();
        return $this;
    }
    public function toImageColor($img){
        return imagecolorallocate($img, $this->red(), $this->green(), $this->blue());
    }
    public function toImageColorAlpha($img,$trueColor=true){
        if($trueColor){
            return imagecolorallocatealpha($img, $this->red(), $this->green(), $this->blue(),$this->alpha127());
        }else{
            return imagecolorallocate($img, $this->red(), $this->green(), $this->blue());
        }            
    }
    public function red(){
        return 0 + $this->rgba[0];
    }
    public function green(){
        return 0 + $this->rgba[1];
    }
    public function blue(){
        return 0 + $this->rgba[2];
    }
    public function alpha127(){
        //0-127
        return round($this->rgba[3]*127/100);
    }
    public function alpha(){
        //0-100
        return $this->rgba[3];
    }
    public function toRgbaArray(){
        return $this->rgba;
    }
    public function toRgbArray(){
        return array($this->rgba[0],$this->rgba[1],$this->rgba[2]);
    }
    
    /**
     * to hex string for css
     * @return string|NULL
     */
    public function toHex(){
        return $this->isOK() ? "#".$this->_toHexString() : null;
    }
    public function getLuminance (){
        $luminance = 0.2126 * $this->rgba[0] + 0.7152 * $this->rgba[1] + 0.0722 * $this->rgba[2];
        return $luminance;
    }
    public function isDark($threshold=130){
        $luminance  = $this->getLuminance();
        return $luminance < $threshold;
    }
    public function toHexChars(){
        return $this->isOK() ? "".$this->_toHexString() : null;
    }        
    /**
     * to hsl string for css
     * @return string|NULL
     */
    public function toHsl(){
        return $this->isOK() ? "".$this->_toHslString() : null;
    }
    /**
     * to rgba string for css
     * @return string|NULL
     */
    public function toRgba(){
        return $this->isOK() ? "".$this->_toRgba() : null;
    }
    
    
    /**
     * Darken color by percent if percent is below 0 then half way to black is set
     * @param float $percent
     * @return \Vulcan\Base\Util\Color\Color
     */
    public function darken($percent=-1){
        $hsl = $this->_toHslArray();            
        if($percent && $percent>0){
            $hsl['L'] = ($hsl['L'] * 100) - min($percent,100);
            $hsl['L'] = ($hsl['L'] <= 0) ? 0 : $hsl['L'] / 100;
        }else{
            $hsl['L'] = $hsl['L'] / 2;
        }
        $this->setValue($hsl);            
        return $this;
    }
    /**
     * Lighten color by percent if percent is below 0 then half way to white is set
     * @param float $percent
     * @return \Vulcan\Base\Util\Color\Color
     */
    public function lighten($percent=-1){
        $hsl = $this->_toHslArray();
        if($percent && $percent>0){
            $hsl['L'] = ($hsl['L'] * 100) + min($percent,100);
            $hsl['L'] = ($hsl['L'] >= 100) ? 1 : $hsl['L'] / 100;
        }else{
            $hsl['L'] += (1 - $hsl['L']) / 2;
        }            
        $this->setValue($hsl);
        return $this;
    }
    public function getRgbaArray(){
        return $this->rgba;
    }
    /** STATIC  METHODS */
    /**
     * 
     * @param String $colorStringOrArray
     * @return \Vulcan\Base\Util\Color\Color
     */
    public static function newColor($colorStringOrArray,$alpha=null){
        $a = new Color($colorStringOrArray,$alpha);
        return $a;
    }
    public static function getAsHexString($hex){
        $color = "".str_replace(array("#"," "), array("",""), "".$hex);            
        // Validate hex string
        if (!preg_match('/^[a-fA-F0-9]+$/', $color)) {return ""; }            
        // Make sure it's 6 digits
        if (strlen($color) === 3) {
            $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
        } elseif (strlen($color) !== 6) {
            return "";
        }                        
        return strtoupper($color);
    }
    /** PROTECTED METHODS */
    protected function parse(){
        $this->_isok = false;
        if(!is_null($this->org)){                  
            if($this->org && is_object($this->org) && $this->org instanceof Color){
                $this->rgba = $this->org->getRgbaArray();
                $this->_isok = true;
            }else if($this->org && is_array($this->org)){
                if(count($this->org)==3 && key_exists("H", $this->org) && key_exists("S", $this->org) && key_exists("L", $this->org)){
                    list($H, $S, $L) = array($this->org['H'] / 360, $this->org['S'], $this->org['L']);
                    if($L>1){ $L = 1; }else if ($L<0){ $L = 0; }
                    if($S>1){ $S = 1; }else if ($S<0){ $L = 0; }
                    if($S == 0){
                        $this->rgba[0] = $L * 255;
                        $this->rgba[1] = $L * 255;
                        $this->rgba[2] = $L * 255;
                        $this->_isok = true;
                    } else {
                        if ($L < 0.5) {
                            $v2 = $L * (1 + $S);
                        } else {
                            $v2 = ($L + $S) - ($S * $L);
                        }                            
                        $v1 = 2 * $L - $v2;                            
                        $this->rgba[0] = 255 * $this->_hueToRgb($v1, $v2, $H + (1 / 3));
                        $this->rgba[1] = 255 * $this->_hueToRgb($v1, $v2, $H);
                        $this->rgba[2] = 255 * $this->_hueToRgb($v1, $v2, $H - (1 / 3));
                        $this->_isok = true;
                    }
                }else if(count($this->org)==3 && key_exists(0, $this->org) && key_exists(1, $this->org) && key_exists(2, $this->org)){
                    $this->rgba[0] = $this->org[0] % 256;
                    $this->rgba[1] = $this->org[1] % 256;
                    $this->rgba[2] = $this->org[2] % 256;
                    $this->_isok = true;
                }
            }else if($this->org && is_string($this->org)){
                $colorString = $this->org;
                $colorString2 = str_replace(array(" ","_"),array("",""),strtolower($colorString));                    
                if($colorString && key_exists($colorString, Color::$NAMED_COLORS)){
                    $colorString = Color::$NAMED_COLORS[$colorString];
                }else if($colorString2 && key_exists($colorString2, Color::$NAMED_COLORS)){
                    $colorString = Color::$NAMED_COLORS[$colorString2];
                }                    
                if (strlen($this->org)>0 && substr($this->org, 0,1)=="#"){ 
                    $colorString = substr($this->org, 1); 
                }
                if(strlen($colorString)==3 || strlen($colorString)==6){
                    $hexString = Color::getAsHexString($colorString);
                    if(strlen("".$hexString)>0){
                        // Convert HEX to DEC
                        $this->rgba[0] = hexdec(substr($hexString, 0,2));
                        $this->rgba[1] = hexdec(substr($hexString, 2,2));
                        $this->rgba[2] = hexdec(substr($hexString, 4,2));
                        $this->_isok = true;
                    }
                }     
            }
        }                
        //if(!$this->_isok){  \Vulcan\V::dump($this); }
    }
    protected function _hueToRgb($v1,$v2,$vH){
        if ($vH < 0) { ++$vH; }            
        if ($vH > 1) { --$vH; }            
        if ((6 * $vH) < 1) { return ($v1 + ($v2 - $v1) * 6 * $vH); }            
        if ((2 * $vH) < 1) { return $v2;}            
        if ((3 * $vH) < 2) { return ($v1 + ($v2 - $v1) * ((2 / 3) - $vH) * 6); }            
        return $v1;
    }
    protected function _toHexString(){
        $hex    = array();
        $hex[0] = str_pad(dechex((int)$this->rgba[0]), 2, '0', STR_PAD_LEFT);
        $hex[1] = str_pad(dechex((int)$this->rgba[1]), 2, '0', STR_PAD_LEFT);
        $hex[2] = str_pad(dechex((int)$this->rgba[2]), 2, '0', STR_PAD_LEFT);            
        return implode('', $hex);
    }
    protected function _toHslString(){
        $hsl = $this->_toHslArray();
        return "hsl(".$hsl['H'].", ".round(@$hsl['S']*100)."%, ".round(@$hsl['L']*100)."%)";            
    }
    protected function _toRgba(){
        return 'rgba('.$this->rgba[0].', '.$this->rgba[1].', '.$this->rgba[2].', '.round($this->rgba[3]/100,4).')';
    }
    protected function _toHslArray(){
        $HSL   = array();
        $red   = ($this->rgba[0] / 255);
        $green = ($this->rgba[1] / 255);
        $blue  = ($this->rgba[2] / 255);            
        $var_Min = min($red, $green, $blue);
        $var_Max = max($red, $green, $blue);
        $del_Max = $var_Max - $var_Min;            
        $L = ($var_Max + $var_Min) / 2;            
        if ($del_Max == 0) {
            $H = 0;
            $S = 0;
        } else {
            if ($L < 0.5) {
                $S = $del_Max / ($var_Max + $var_Min);
            } else {
                $S = $del_Max / (2 - $var_Max - $var_Min);
            }                
            $del_R = ((($var_Max - $red) / 6) + ($del_Max / 2)) / $del_Max;
            $del_G = ((($var_Max - $green) / 6) + ($del_Max / 2)) / $del_Max;
            $del_B = ((($var_Max - $blue) / 6) + ($del_Max / 2)) / $del_Max;                
            if ($red == $var_Max) {
                $H = $del_B - $del_G;
            } elseif ($green == $var_Max) {
                $H = (1 / 3) + $del_R - $del_B;
            } elseif ($blue == $var_Max) {
                $H = (2 / 3) + $del_G - $del_R;
            }
            
            if ($H < 0) {
                $H++;
            }
            if ($H > 1) {
                $H--;
            }
        }            
        $HSL['H'] = ($H * 360);
        $HSL['S'] = $S;
        $HSL['L'] = $L;
        return $HSL;
    }
    public static function ensureHexColorString($color){
        $c = new Color($color);
        return $c->toHex();
    }
    public function copy(){
        return new Color(array($this->toRgbaArray()));
    }
    public static function div($colorStr=null,$size=24){
        if($colorStr=="transparent"){
            return '<div style="width:'.$size.'px;height:'.$size.'px;background:transparent;border:1px solid #cccccc;" title="Transparent" >&nbsp;</div>';
        }
        $c  = new Color($colorStr);
        if($c->isOK()){
            $color1 = $c->toHex();                
            return '<div style="width:'.$size.'px;height:'.$size.'px;background:'.$color1.';" title="'.$color1.'" >&nbsp;</div>';
        }
        return "";            
    }        
    public static function showMeTheColor($colorStr=null){
        $c  = new Color($colorStr);
        $c2 = $c->copy()->lighten(50);
        $color1 = $c->toHex();
        $color2 = $c2->toHex();
        die('<html><head></head><body style="padding:0px;margin:0px;"><div style="width:100%;height:100%;background:'.$color1.'" ><h1 style="color:'.$color2.';">'.$color1.'</h1></div></body></html>');
    }
    public static function isColorString($colorString){
        if($colorString && strlen("".$colorString)>0){
            if(substr("".$colorString, 0,1)=="#"){
                return true;
            }
        }
        return false;
    }
    
    // COLOR MANIPULATION METHODS - RENK MANIPÜLASYON METODLARI
    
    /**
     * Saturate color by percent
     * @param float $percent
     * @return Color
     */
    public function saturate($percent = 10){
        $hsl = $this->_toHslArray();
        $hsl['S'] = min(1, $hsl['S'] + ($percent / 100));
        $this->setValue($hsl);
        return $this;
    }
    
    /**
     * Desaturate color by percent
     * @param float $percent
     * @return Color
     */
    public function desaturate($percent = 10){
        $hsl = $this->_toHslArray();
        $hsl['S'] = max(0, $hsl['S'] - ($percent / 100));
        $this->setValue($hsl);
        return $this;
    }
    
    /**
     * Rotate hue by degrees
     * @param float $degrees
     * @return Color
     */
    public function rotateHue($degrees = 180){
        $hsl = $this->_toHslArray();
        $hsl['H'] = ($hsl['H'] + $degrees) % 360;
        if($hsl['H'] < 0) $hsl['H'] += 360;
        $this->setValue($hsl);
        return $this;
    }
    
    /**
     * Get complementary color
     * @return Color
     */
    public function complementary(){
        return $this->copy()->rotateHue(180);
    }
    
    /**
     * Get analogous colors (30 degrees apart)
     * @param int $count Number of colors to generate
     * @return array
     */
    public function analogous($count = 3){
        $colors = [];
        $step = 30;
        for($i = 0; $i < $count; $i++){
            $colors[] = $this->copy()->rotateHue($step * $i);
        }
        return $colors;
    }
    
    /**
     * Get triadic colors (120 degrees apart)
     * @return array
     */
    public function triadic(){
        return [
            $this->copy(),
            $this->copy()->rotateHue(120),
            $this->copy()->rotateHue(240)
        ];
    }
    
    /**
     * Get tetradic colors (90 degrees apart)
     * @return array
     */
    public function tetradic(){
        return [
            $this->copy(),
            $this->copy()->rotateHue(90),
            $this->copy()->rotateHue(180),
            $this->copy()->rotateHue(270)
        ];
    }
    
    /**
     * Blend with another color
     * @param Color|string $color
     * @param float $ratio (0-1)
     * @return Color
     */
    public function blend($color, $ratio = 0.5){
        if(is_string($color)){
            $color = new Color($color);
        }
        
        $r = $this->red() * (1 - $ratio) + $color->red() * $ratio;
        $g = $this->green() * (1 - $ratio) + $color->green() * $ratio;
        $b = $this->blue() * (1 - $ratio) + $color->blue() * $ratio;
        
        return new Color([$r, $g, $b]);
    }
    
    /**
     * Get contrast ratio with another color
     * @param Color|string $color
     * @return float
     */
    public function contrastRatio($color){
        if(is_string($color)){
            $color = new Color($color);
        }
        
        $l1 = $this->getLuminance();
        $l2 = $color->getLuminance();
        
        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);
        
        return ($lighter + 0.05) / ($darker + 0.05);
    }
    
    /**
     * Check if contrast meets WCAG AA standards
     * @param Color|string $color
     * @param string $level 'AA' or 'AAA'
     * @return bool
     */
    public function meetsContrastGuidelines($color, $level = 'AA'){
        $ratio = $this->contrastRatio($color);
        $thresholds = [
            'AA' => ['normal' => 4.5, 'large' => 3],
            'AAA' => ['normal' => 7, 'large' => 4.5]
        ];
        
        return $ratio >= $thresholds[$level]['normal'];
    }
    
    /**
     * Get best text color (black or white) for background
     * @return Color
     */
    public function getBestTextColor(){
        $black = new Color('#000000');
        $white = new Color('#FFFFFF');
        
        $contrastBlack = $this->contrastRatio($black);
        $contrastWhite = $this->contrastRatio($white);
        
        return $contrastBlack > $contrastWhite ? $black : $white;
    }
    
    /**
     * Convert to grayscale
     * @return Color
     */
    public function grayscale(){
        $gray = $this->getLuminance();
        return new Color([$gray, $gray, $gray]);
    }
    
    /**
     * Invert color
     * @return Color
     */
    public function invert(){
        return new Color([
            255 - $this->red(),
            255 - $this->green(),
            255 - $this->blue()
        ]);
    }
    
    /**
     * Get color temperature (warm/cool)
     * @return string 'warm' or 'cool'
     */
    public function getTemperature(){
        $hsl = $this->_toHslArray();
        $hue = $hsl['H'];
        
        // Warm colors: reds, oranges, yellows (0-60, 300-360)
        // Cool colors: greens, blues, purples (60-300)
        if(($hue >= 0 && $hue <= 60) || ($hue >= 300 && $hue <= 360)){
            return 'warm';
        } else {
            return 'cool';
        }
    }
    
    /**
     * Get color family
     * @return string
     */
    public function getColorFamily(){
        $hsl = $this->_toHslArray();
        $hue = $hsl['H'];
        
        if($hue >= 0 && $hue < 15) return 'red';
        if($hue >= 15 && $hue < 45) return 'orange';
        if($hue >= 45 && $hue < 75) return 'yellow';
        if($hue >= 75 && $hue < 165) return 'green';
        if($hue >= 165 && $hue < 195) return 'cyan';
        if($hue >= 195 && $hue < 255) return 'blue';
        if($hue >= 255 && $hue < 285) return 'purple';
        if($hue >= 285 && $hue < 315) return 'magenta';
        if($hue >= 315 && $hue < 360) return 'red';
        
        return 'unknown';
    }
    
    /**
     * Validate if color string is valid
     * @param string $colorString
     * @return bool
     */
    public static function isValid($colorString){
        try {
            $color = new Color($colorString);
            return $color->isOK();
        } catch(\Exception $e){
            return false;
        }
    }
    
    /**
     * Get random color
     * @return Color
     */
    public static function random(){
        return new Color([
            rand(0, 255),
            rand(0, 255),
            rand(0, 255)
        ]);
    }
    
    /**
     * Get color palette based on current color
     * @param int $count Number of colors in palette
     * @return array
     */
    public function getPalette($count = 5){
        $palette = [];
        $palette[] = $this->copy();
        
        // Add variations
        $palette[] = $this->copy()->lighten(20);
        $palette[] = $this->copy()->darken(20);
        $palette[] = $this->copy()->saturate(30);
        $palette[] = $this->copy()->desaturate(30);
        
        return array_slice($palette, 0, $count);
    }
}    

?>