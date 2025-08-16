<?php
namespace Efaturacim\Util\Usage\Html;

use Efaturacim\Util\Utils\Html\Bootstrap\Alert;
use Efaturacim\Util\Utils\Html\Bootstrap\Badge;
use Efaturacim\Util\Utils\Html\Bootstrap\BootstrapDocument;
use Efaturacim\Util\Utils\Html\Bootstrap\Carousel;
use Efaturacim\Util\Utils\Html\Bootstrap\Collapse;
use Efaturacim\Util\Utils\Html\Bootstrap\Dropdown;
use Efaturacim\Util\Utils\Html\Bootstrap\ListGroup;
use Efaturacim\Util\Utils\Html\Bootstrap\Modal;
use Efaturacim\Util\Utils\Html\Bootstrap\Navbar;
use Efaturacim\Util\Utils\Html\Bootstrap\Offcanvas;
use Efaturacim\Util\Utils\Html\Bootstrap\Placeholders;
use Efaturacim\Util\Utils\Html\Bootstrap\Popovers;
use Efaturacim\Util\Utils\Html\Bootstrap\Scrollspy;
use Efaturacim\Util\Utils\Html\Bootstrap\Spinners;
use Efaturacim\Util\Utils\Html\Bootstrap\Toasts;
use Efaturacim\Util\Utils\Html\Bootstrap\Tooltips;
use Efaturacim\Util\Utils\Html\PrettyPrint\PrettyPrint;

class BootstrapUsage{
    public static function runDemo(){
        $doc = new BootstrapDocument();
        $doc->setBodyContent(self::getDemoHtml($doc));
        $doc->show();
    }

    public static function getDemoHtml(&$doc){
        $s = '<div class="container-fluid">';
        $s .= '<h1 class="mb-4">Bootstrap Components Demo</h1>';
        $s .= '<p class="lead">This page showcases all Bootstrap components available in the Efaturacim library.</p>';
        
        // Add all component demos
        $s .= self::getAlertDemo($doc);
        $s .= self::getBadgeDemo($doc);
        $s .= self::getCarouselDemo($doc);
        $s .= self::getCollapseDemo($doc);
        $s .= self::getDropdownDemo($doc);
        $s .= self::getListGroupDemo($doc);
        $s .= self::getModalDemo($doc);
        $s .= self::getNavbarDemo($doc);
        $s .= self::getOffcanvasDemo($doc);
        $s .= self::getPlaceholdersDemo($doc);
        $s .= self::getPopoversDemo($doc);
        $s .= self::getScrollspyDemo($doc);
        $s .= self::getSpinnersDemo($doc);
        $s .= self::getToastsDemo($doc);
        $s .= self::getTooltipsDemo($doc);
        
        $s .= '</div>';
        return $s;
    }

    private static function getAlertDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Alerts</h2>';
        $s .= '<div class="mb-3">';
        $s .= Alert::primary('This is a primary alert');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Alert::success('This is a success alert');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Alert::danger('This is a danger alert'); // Changed from error() to danger()
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Alert::warning('This is a warning alert');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Alert::info('This is an info alert');
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Alert;

// Simple alerts (return strings directly)
echo Alert::primary("This is a primary alert");
echo Alert::success("This is a success alert");
echo Alert::danger("This is a danger alert"); // Note: use danger() not error()
echo Alert::warning("This is a warning alert");
echo Alert::info("This is an info alert");

// Alert with custom options
echo Alert::alert("primary", "Custom alert", [
    "class" => "custom-alert"
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getBadgeDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Badges</h2>';
        $s .= '<div class="mb-3">';
        $s .= Badge::primary('Primary')->toHtmlAsString();
        $s .= ' ' . Badge::secondary('Secondary')->toHtmlAsString();
        $s .= ' ' . Badge::success('Success')->toHtmlAsString();
        $s .= ' ' . Badge::danger('Danger')->toHtmlAsString();
        $s .= ' ' . Badge::warning('Warning')->toHtmlAsString();
        $s .= ' ' . Badge::info('Info')->toHtmlAsString();
        $s .= ' ' . Badge::light('Light')->toHtmlAsString();
        $s .= ' ' . Badge::dark('Dark')->toHtmlAsString();
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Badge::primaryPill('Pill badge')->toHtmlAsString();
        $s .= ' ' . Badge::successPill('Rounded badge')->toHtmlAsString();
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Badge;

// Basic badges (return objects, need toHtmlAsString())
echo Badge::primary("Primary")->toHtmlAsString();
echo Badge::secondary("Secondary")->toHtmlAsString();
echo Badge::success("Success")->toHtmlAsString();
echo Badge::danger("Danger")->toHtmlAsString();
echo Badge::warning("Warning")->toHtmlAsString();
echo Badge::info("Info")->toHtmlAsString();
echo Badge::light("Light")->toHtmlAsString();
echo Badge::dark("Dark")->toHtmlAsString();

// Pill badges
echo Badge::primaryPill("Pill badge")->toHtmlAsString();
echo Badge::successPill("Rounded badge")->toHtmlAsString();

// Badge with custom options
echo Badge::create("Custom badge", "primary", true)->toHtmlAsString();';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getCarouselDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Carousel</h2>';
        $s .= '<div class="mb-3">';
        $s .= Carousel::withCaptions([
            ['image' => 'https://via.placeholder.com/800x400/007bff/ffffff?text=Slide+1', 'title' => 'First Slide', 'text' => 'This is the first slide'],
            ['image' => 'https://via.placeholder.com/800x400/28a745/ffffff?text=Slide+2', 'title' => 'Second Slide', 'text' => 'This is the second slide'],
            ['image' => 'https://via.placeholder.com/800x400/dc3545/ffffff?text=Slide+3', 'title' => 'Third Slide', 'text' => 'This is the third slide']
        ]);
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Carousel;

// Simple carousel with just images
echo Carousel::simple([
    "https://via.placeholder.com/800x400/007bff/ffffff?text=Slide+1",
    "https://via.placeholder.com/800x400/28a745/ffffff?text=Slide+2"
]);

// Carousel with captions
echo Carousel::withCaptions([
    [
        "image" => "https://via.placeholder.com/800x400/007bff/ffffff?text=Slide+1",
        "title" => "First Slide",
        "text" => "This is the first slide"
    ],
    [
        "image" => "https://via.placeholder.com/800x400/28a745/ffffff?text=Slide+2",
        "title" => "Second Slide",
        "text" => "This is the second slide"
    ]
]);

// Carousel with custom options
echo Carousel::withCaptions($slides, [
    "id" => "custom-carousel",
    "interval" => 3000,
    "controls" => true,
    "indicators" => true,
    "crossfade" => true,
    "pause" => "hover"
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getCollapseDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Collapse</h2>';
        $s .= '<div class="mb-3">';
        $s .= Collapse::simple('Click to toggle', 'This is the collapsible content that can be shown or hidden.');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        // Use simple method for multiple items instead of accordion
        $s .= Collapse::simple('Accordion Item 1', 'Content for accordion item 1');
        $s .= Collapse::simple('Accordion Item 2', 'Content for accordion item 2');
        $s .= Collapse::simple('Accordion Item 3', 'Content for accordion item 3');
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Collapse;

// Simple collapse (returns string directly)
echo Collapse::simple("Click to toggle", "This is the collapsible content.");

// Multiple collapse items
echo Collapse::simple("Item 1", "Content 1");
echo Collapse::simple("Item 2", "Content 2");
echo Collapse::simple("Item 3", "Content 3");

// Collapse with custom options
echo Collapse::simple($trigger, $content, [
    "id" => "custom-collapse",
    "accordion" => false,
    "multiple" => true,
    "class" => "custom-collapse"
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getDropdownDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Dropdown</h2>';
        $s .= '<div class="mb-3">';
        $s .= Dropdown::simple('Dropdown', [
            ['text' => 'Action', 'href' => '#'],
            ['text' => 'Another action', 'href' => '#'],
            ['text' => 'Something else here', 'href' => '#'],
            ['divider' => true],
            ['text' => 'Separated link', 'href' => '#']
        ]);
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Dropdown::split('Split Dropdown', [
            ['text' => 'Action', 'href' => '#'],
            ['text' => 'Another action', 'href' => '#'],
            ['text' => 'Something else here', 'href' => '#']
        ]);
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Dropdown;

// Simple dropdown (returns string directly)
echo Dropdown::simple("Dropdown", [
    ["text" => "Action", "href" => "#"],
    ["text" => "Another action", "href" => "#"],
    ["text" => "Something else here", "href" => "#"],
    ["divider" => true],
    ["text" => "Separated link", "href" => "#"]
]);

// Split dropdown
echo Dropdown::split("Split Dropdown", [
    ["text" => "Action", "href" => "#"],
    ["text" => "Another action", "href" => "#"]
]);

// Dropdown with custom options
echo Dropdown::simple($text, $items, [
    "id" => "custom-dropdown",
    "direction" => "dropup",
    "autoClose" => true,
    "dark" => false
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getListGroupDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>List Group</h2>';
        $s .= '<div class="mb-3">';
        $s .= ListGroup::simple([
            ['text' => 'List item 1'],
            ['text' => 'List item 2'],
            ['text' => 'List item 3'],
            ['text' => 'List item 4']
        ]);
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= ListGroup::withLinks([
            ['text' => 'Actionable item 1', 'href' => '#'],
            ['text' => 'Actionable item 2', 'href' => '#'],
            ['text' => 'Actionable item 3', 'href' => '#']
        ]);
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\ListGroup;

// Simple list group (returns string directly)
echo ListGroup::simple([
    ["text" => "List item 1"],
    ["text" => "List item 2"],
    ["text" => "List item 3"]
]);

// List group with links
echo ListGroup::withLinks([
    ["text" => "Actionable item 1", "href" => "#"],
    ["text" => "Actionable item 2", "href" => "#"]
]);

// List group with badges
echo ListGroup::withBadges([
    ["text" => "Item 1", "badge" => "14", "badgeColor" => "primary"],
    ["text" => "Item 2", "badge" => "2", "badgeColor" => "secondary"]
]);

// List group with custom options
echo ListGroup::simple($items, [
    "id" => "custom-list-group",
    "flush" => false,
    "numbered" => false,
    "horizontal" => false
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getModalDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Modal</h2>';
        $s .= '<div class="mb-3">';
        $s .= Modal::simple('Modal Title', 'This is the modal content. You can put any content here.', 'Open Modal');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Modal::large('Large Modal', 'This is a large modal with more content.', 'Open Large Modal');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Modal::small('Small Modal', 'This is a small modal.', 'Open Small Modal');
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Modal;

// Simple modal (returns string directly)
echo Modal::simple("Modal Title", "This is the modal content.", "Open Modal");

// Large modal
echo Modal::large("Large Modal", "This is a large modal.", "Open Large Modal");

// Small modal
echo Modal::small("Small Modal", "This is a small modal.", "Open Small Modal");

// Modal with custom options
echo Modal::simple($title, $content, $triggerText, [
    "id" => "custom-modal",
    "size" => "lg",
    "fullscreen" => false,
    "scrollable" => true,
    "centered" => true,
    "staticBackdrop" => false
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getNavbarDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Navbar</h2>';
        $s .= '<div class="mb-3">';
        $s .= Navbar::simple('Brand', [
            ['text' => 'Home', 'href' => '#'],
            ['text' => 'Features', 'href' => '#'],
            ['text' => 'Pricing', 'href' => '#'],
            ['text' => 'About', 'href' => '#']
        ]);
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Navbar::dark('Dark Brand', [
            ['text' => 'Home', 'href' => '#'],
            ['text' => 'Features', 'href' => '#'],
            ['text' => 'Pricing', 'href' => '#']
        ]);
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Navbar;

// Simple navbar (returns string directly)
echo Navbar::simple("Brand", [
    ["text" => "Home", "href" => "#"],
    ["text" => "Features", "href" => "#"],
    ["text" => "Pricing", "href" => "#"]
]);

// Dark navbar
echo Navbar::dark("Dark Brand", [
    ["text" => "Home", "href" => "#"],
    ["text" => "Features", "href" => "#"]
]);

// Navbar with dropdown
echo Navbar::simple("Brand", [
    ["text" => "Home", "href" => "#"],
    ["text" => "Dropdown", "dropdown" => [
        ["text" => "Action", "href" => "#"],
        ["text" => "Another action", "href" => "#"]
    ]]
]);

// Navbar with custom options
echo Navbar::simple($brand, $items, [
    "id" => "custom-navbar",
    "color" => "light",
    "expand" => "lg",
    "container" => "container-fluid"
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getOffcanvasDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Offcanvas</h2>';
        $s .= '<div class="mb-3">';
        $s .= Offcanvas::simple('Offcanvas Title', 'This is the offcanvas content. You can put any content here.');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Offcanvas::start('Left Offcanvas', 'This is a left-positioned offcanvas.');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Offcanvas::end('Right Offcanvas', 'This is a right-positioned offcanvas.');
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Offcanvas;

// Simple offcanvas (returns string directly)
echo Offcanvas::simple("Offcanvas Title", "This is the offcanvas content.");

// Left offcanvas
echo Offcanvas::start("Left Offcanvas", "This is a left-positioned offcanvas.");

// Right offcanvas
echo Offcanvas::end("Right Offcanvas", "This is a right-positioned offcanvas.");

// Top offcanvas
echo Offcanvas::top("Top Offcanvas", "This is a top-positioned offcanvas.");

// Bottom offcanvas
echo Offcanvas::bottom("Bottom Offcanvas", "This is a bottom-positioned offcanvas.");

// Offcanvas with custom options
echo Offcanvas::simple($title, $content, [
    "id" => "custom-offcanvas",
    "placement" => "start",
    "backdrop" => true,
    "scroll" => false,
    "staticBackdrop" => false
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getPlaceholdersDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Placeholders</h2>';
        $s .= '<div class="mb-3">';
        $s .= Placeholders::text('This is a placeholder text');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Placeholders::button('Button placeholder');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Placeholders::glow('Glow placeholder');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Placeholders::wave('Wave placeholder');
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Placeholders;

// Text placeholder (returns string directly)
echo Placeholders::text("This is a placeholder text");

// Button placeholder
echo Placeholders::button("Button placeholder");

// Glow placeholder
echo Placeholders::glow("Glow placeholder");

// Wave placeholder
echo Placeholders::wave("Wave placeholder");

// Placeholder with custom options
echo Placeholders::simple($text, [
    "id" => "custom-placeholder",
    "type" => "text",
    "size" => "lg",
    "color" => "primary",
    "width" => "100%",
    "height" => "20px",
    "animation" => "glow"
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getPopoversDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Popovers</h2>';
        $s .= '<div class="mb-3">';
        $s .= Popovers::simple('Popover content', 'Click to show popover');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Popovers::top('Top popover', 'Top positioned popover');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Popovers::bottom('Bottom popover', 'Bottom positioned popover');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Popovers::left('Left popover', 'Left positioned popover');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Popovers::right('Right popover', 'Right positioned popover');
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Popovers;

// Simple popover (returns string directly)
echo Popovers::simple("Popover content", "Click to show popover");

// Positioned popovers
echo Popovers::top("Top popover", "Top positioned popover");
echo Popovers::bottom("Bottom popover", "Bottom positioned popover");
echo Popovers::left("Left popover", "Left positioned popover");
echo Popovers::right("Right popover", "Right positioned popover");

// Popover with HTML content
echo Popovers::html("<strong>Bold</strong> HTML popover", "HTML popover trigger");

// Popover with custom options
echo Popovers::simple($content, $triggerText, [
    "id" => "custom-popover",
    "placement" => "top",
    "trigger" => "click",
    "animation" => true,
    "delay" => 0,
    "html" => false,
    "sanitize" => true
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getScrollspyDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Scrollspy</h2>';
        $s .= '<div class="mb-3">';
        $s .= Scrollspy::simple([
            ['id' => 'section1', 'text' => 'Section 1'],
            ['id' => 'section2', 'text' => 'Section 2'],
            ['id' => 'section3', 'text' => 'Section 3']
        ], [
            ['id' => 'section1', 'title' => 'Section 1', 'content' => 'Content for section 1'],
            ['id' => 'section2', 'title' => 'Section 2', 'content' => 'Content for section 2'],
            ['id' => 'section3', 'title' => 'Section 3', 'content' => 'Content for section 3']
        ]);
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Scrollspy;

// Simple scrollspy (returns string directly)
echo Scrollspy::simple([
    ["id" => "section1", "text" => "Section 1"],
    ["id" => "section2", "text" => "Section 2"],
    ["id" => "section3", "text" => "Section 3"]
], [
    ["id" => "section1", "title" => "Section 1", "content" => "Content for section 1"],
    ["id" => "section2", "title" => "Section 2", "content" => "Content for section 2"],
    ["id" => "section3", "title" => "Section 3", "content" => "Content for section 3"]
]);

// Scrollspy with custom options
echo Scrollspy::simple($navItems, $contentSections, [
    "id" => "custom-scrollspy",
    "navType" => "nav-pills",
    "offset" => 10,
    "method" => "auto",
    "smoothScroll" => true
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getSpinnersDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Spinners</h2>';
        $s .= '<div class="mb-3">';
        $s .= Spinners::border('Loading...');
        $s .= ' ' . Spinners::grow('Loading...');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Spinners::primary('Primary spinner');
        $s .= ' ' . Spinners::secondary('Secondary spinner');
        $s .= ' ' . Spinners::success('Success spinner');
        $s .= ' ' . Spinners::danger('Danger spinner');
        $s .= ' ' . Spinners::warning('Warning spinner');
        $s .= ' ' . Spinners::info('Info spinner');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Spinners::small('Small spinner');
        $s .= ' ' . Spinners::large('Large spinner');
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Spinners;

// Border spinners (returns string directly)
echo Spinners::border("Loading...");
echo Spinners::grow("Loading...");

// Colored spinners
echo Spinners::primary("Primary spinner");
echo Spinners::secondary("Secondary spinner");
echo Spinners::success("Success spinner");
echo Spinners::danger("Danger spinner");
echo Spinners::warning("Warning spinner");
echo Spinners::info("Info spinner");

// Sized spinners
echo Spinners::small("Small spinner");
echo Spinners::large("Large spinner");

// Spinner with custom options
echo Spinners::simple($text, [
    "id" => "custom-spinner",
    "type" => "border",
    "size" => "md",
    "color" => "primary",
    "text" => "Custom loading text"
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getToastsDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Toasts</h2>';
        $s .= '<div class="mb-3">';
        $s .= Toasts::simple('Toast Title', 'This is a simple toast message');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Toasts::primary('Primary Toast', 'This is a primary toast');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Toasts::success('Success Toast', 'This is a success toast');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Toasts::danger('Danger Toast', 'This is a danger toast');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Toasts::warning('Warning Toast', 'This is a warning toast');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Toasts::info('Info Toast', 'This is an info toast');
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Toasts;

// Simple toast (returns string directly)
echo Toasts::simple("Toast Title", "This is a simple toast message");

// Colored toasts
echo Toasts::primary("Primary Toast", "This is a primary toast");
echo Toasts::success("Success Toast", "This is a success toast");
echo Toasts::danger("Danger Toast", "This is a danger toast");
echo Toasts::warning("Warning Toast", "This is a warning toast");
echo Toasts::info("Info Toast", "This is an info toast");

// Toast with custom options
echo Toasts::simple($title, $content, [
    "id" => "custom-toast",
    "color" => "primary",
    "autohide" => true,
    "delay" => 5000,
    "animation" => true,
    "show" => false,
    "position" => "top-0 end-0"
]);

// Persistent toast
echo Toasts::persistent("Persistent Toast", "This toast won\'t auto-hide");

// Toast with delay
echo Toasts::withDelay("Delayed Toast", "This toast has custom delay", 3000);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }

    private static function getTooltipsDemo(&$doc){
        $s = '<div class="row mb-5">';
        $s .= '<div class="col-12">';
        $s .= '<h2>Tooltips</h2>';
        $s .= '<div class="mb-3">';
        $s .= Tooltips::simple('This is a tooltip', 'Hover me');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Tooltips::top('Top tooltip', 'Top positioned tooltip');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Tooltips::bottom('Bottom tooltip', 'Bottom positioned tooltip');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Tooltips::left('Left tooltip', 'Left positioned tooltip');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Tooltips::right('Right tooltip', 'Right positioned tooltip');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Tooltips::focus('Focus tooltip', 'Focus me');
        $s .= '</div>';
        $s .= '<div class="mb-3">';
        $s .= Tooltips::click('Click tooltip', 'Click me');
        $s .= '</div>';
        $s .= '</div>';
        
        $s .= '<div class="col-12">';
        $s .= '<h4>Usage Example:</h4>';
        $code = '<?php
use Efaturacim\Util\Utils\Html\Bootstrap\Tooltips;

// Simple tooltip (returns string directly)
echo Tooltips::simple("This is a tooltip", "Hover me");

// Positioned tooltips
echo Tooltips::top("Top tooltip", "Top positioned tooltip");
echo Tooltips::bottom("Bottom tooltip", "Bottom positioned tooltip");
echo Tooltips::left("Left tooltip", "Left positioned tooltip");
echo Tooltips::right("Right tooltip", "Right positioned tooltip");

// Trigger tooltips
echo Tooltips::focus("Focus tooltip", "Focus me");
echo Tooltips::click("Click tooltip", "Click me");

// HTML tooltips
echo Tooltips::html("<strong>Bold</strong> HTML tooltip", "HTML tooltip trigger");

// Tooltip with custom options
echo Tooltips::simple($content, $triggerText, [
    "id" => "custom-tooltip",
    "placement" => "top",
    "trigger" => "hover",
    "animation" => true,
    "delay" => 0,
    "html" => false,
    "sanitize" => true
]);';
        $s .= PrettyPrint::php($doc, $code, null, 400);
        $s .= '</div>';
        $s .= '</div>';
        
        return $s;
    }
}
?>