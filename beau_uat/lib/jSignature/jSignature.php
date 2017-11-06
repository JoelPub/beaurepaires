<?php

class jSignature_Tools_SVG {

    /**
     * This is a simple, points-to-lines (not curves) renderer. 
     * Keeping it around so we can activate it from time to time and see
     * if smoothing logic is off much.
     * @public
     * @function
     * @param $stroke {Array} Hash representing a single stroke, with two properties 
     * 		('x' => array(), 'y' => array()) where 'array()' is an array of 
     * 		coordinates for that axis.
     * @returns {String} Like so 'M 53 7 l 1 2 3 4 -5 -6 5 -6' which is in format of SVG's Path.d argument.
     */
    private function addstroke($stroke, $shiftx, $shifty) {
        $lastx = $stroke['x'][0];
        $lasty = $stroke['y'][0];
        $i;
        $l = sizeof($stroke['x']);
        $answer = array('M', round($lastx - $shiftx, 2), round($lasty - $shifty, 2), 'l');

        if ($l == 1) {
            // meaning this was just a DOT, not a stroke.
            // instead of creating a circle, we just create a short line "up and to the right" :)
            array_push($answer, 1);
            array_push($answer, -1);
        } else {
            for ($i = 1; $i < $l; $i++) {
                array_push($answer, $stroke['x'][$i] - $lastx);
                array_push($answer, $stroke['y'][$i] - $lasty);
                $lastx = $stroke['x'][$i];
                $lasty = $stroke['y'][$i];
            }
        }
        return implode(' ', $answer);
    }

    // eg $data = [some array in native format]
    // $svg_class = new jSignature_Tools_SVG();
    // $svg_img = $svg_class->NativeToSVG($data)

    public function NativeToSVG($data, $stroke_width = 5) {
        $answer = array(
            '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'
            , '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'
        );
        $i;
        $l = sizeof($data);
        $stroke;
        $xlimits = array();
        $ylimits = array();
        $sizex = 0;
        $sizey = 0;
        $shiftx = 0;
        $shifty = 0;
        $minx;
        $maxx;
        $miny;
        $maxy;
        $padding = 1;

        if ($l !== 0) {
            for ($i = 0; $i < $l; $i++) {
                $stroke = $data[$i];
                $xlimits = array_merge($xlimits, $stroke['x']);
                $ylimits = array_merge($ylimits, $stroke['y']);
            }
            $minx = min($xlimits) - $padding;
            $maxx = max($xlimits) + $padding;
            $miny = min($ylimits) - $padding;
            $maxy = max($ylimits) + $padding;
            $shiftx = $minx < 0 ? 0 : $minx;
            $shifty = $miny < 0 ? 0 : $miny;
            $sizex = $maxx - $minx;
            $sizey = $maxy - $miny;
        }

        array_push($answer, '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="' .
                $sizex .
                '" height="' .
                $sizey .
                '">'
        );

//		// This is a nice idea: use style declaration on top, and mark the lines with 'class="f"'
//		// thus saving space in svg... 
//		// alas, many SVG renderers don't understand "class" and render the $strokes in default "fill = black, no $stroke" style. Ugh!!!
//		// TODO: Rewrite ImageMagic / GraphicsMagic, InkScape, http://svg.codeplex.com/ to support style + class. until then
//		// , we hardcode the stroke style within the path. 
//		$answer.push(
//			'<style type="text/css"><![C$data[.f {fill:none;$stroke:#000000;$stroke-width:2;$stroke-linecap:round;$stroke-linejoin:round}]]></style>'
//		)
        for ($i = 0; $i < $l; $i++) {
            array_push(
                    $answer
                    , '<path fill="none" stroke="#000000" stroke-width="'.$stroke_width.'"' .
                    ' stroke-linecap="round" stroke-linejoin="round" d="' .
                    $this->addstroke($data[$i], $shiftx, $shifty) . '"/>'
            );
        }
        array_push($answer, '</svg>');
        return implode('', $answer);
    }

}

class jSignature_Tools_Base30 {

    // private $acceptedformat = 'image/jsignature;base30';
    private $chunkSeparator = '';
    private $charmap = array(); // {'1':'g','2':'h','3':'i','4':'j','5':'k','6':'l','7':'m','8':'n','9':'o','a':'p','b':'q','c':'r','d':'s','e':'t','f':'u','0':'v'}
    private $charmap_reverse = array(); // will be filled by 'uncompress*" function
    private $allchars = array();
    private $bitness = 0;
    private $minus = '';
    private $plus = '';

    function __construct() {
        global $bitness, $allchars, $charmap, $charmap_reverse, $minus, $plus, $chunkSeparator;
        $allchars = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWX');
        $bitness = sizeof($allchars) / 2;
        $minus = 'Z';
        $plus = 'Y';
        $chunkSeparator = '_';
        for ($i = $bitness - 1; $i > -1; $i--) {
            $charmap[$allchars[$i]] = $allchars[$i + $bitness];
            $charmap_reverse[$allchars[$i + $bitness]] = $allchars[$i];
        }
    }

    /*
      Decompresses half of a stroke in a base30-encoded jSignature image.
      $c = new jSignature_base30();
      $t = array(236, 233, 231, 229, 226, 224, 222, 216, 213, 210, 205, 202, 200, 198, 195, 193, 191, 189, 186, 183, 180, 178, 174, 172);
      $leg = '7UZ32232263353223222333242';
      $a = $c->uncompress_stroke_leg($leg);
      $t == $a
     */

    private function uncompress_stroke_leg($datastring) {
        global $charmap, $charmap_reverse, $bitness, $minus, $plus;
        // we convert half-stroke (only 'x' series or only 'y' series of numbers)
        // datastring like this:
        // "5agm12100p1235584210m53"
        // is converted into this:
        // [517,516,514,513,513,513,514,516,519,524,529,537,541,543,544,544,539,536]
        // each number in the chain is converted such:
        // - digit char = start of new whole number. Alpha chars except "p","m" are numbers in hiding.
        //   These consecutive digist expressed as alphas mapped back to digit char.
        //   resurrected number is the diff between this point and prior coord.
        // - running polaritiy is attached to the number.
        // - we undiff (signed number + prior coord) the number.
        // - if char 'm','p', flip running polarity 
        $answer = array();
        $chars = str_split($datastring);
        $l = sizeof($chars);
        $ch = '';
        $polarity = 1;
        $partial = array();
        $preprewhole = 0;
        $prewhole = 0;
        for ($i = 0; $i < $l; $i++) {
            // echo "adding $i of $l to answer\n";
            $ch = $chars[$i];
            if (array_key_exists($ch, $charmap) || $ch == $minus || $ch == $plus) {

                // this is new number - start of a new whole number.
                // before we can deal with it, we need to flush out what we already 
                // parsed out from string, but keep in limbo, waiting for this sign
                // that prior number is done.
                // we deal with 3 numbers here:
                // 1. start of this number - a diff from previous number to 
                //    whole, new number, which we cannot do anything with cause
                //    we don't know its ending yet.
                // 2. number that we now realize have just finished parsing = prewhole
                // 3. number we keep around that came before prewhole = preprewhole
                if (sizeof($partial) != 0) {
                    // yep, we have some number parts in there.
                    $prewhole = intval(implode('', $partial), $bitness) * $polarity + $preprewhole;
                    array_push($answer, $prewhole);
                    $preprewhole = $prewhole;
                }
                if ($ch == $minus) {
                    $polarity = -1;
                    $partial = array();
                } else if ($ch == $plus) {
                    $polarity = 1;
                    $partial = array();
                } else {
                    // now, let's start collecting parts for the new number:
                    $partial = array($ch);
                }
            } else /* alphas replacing digits */ {
                // more parts for the new number
                array_push($partial, $charmap_reverse[$ch]);
            }
        }
        // we always will have something stuck in partial
        // because we don't have closing delimiter
        array_push($answer, intval(implode('', $partial), $bitness) * $polarity + $preprewhole);

        return $answer;
    }

    /*
      $c = new jSignature_base30();
      $signature = "3E13Z5Y5_1O24Z66_1O1Z3_3E2Z4";

      // This is exactly the same as "native" format within jSignature.
      $t = array(
      array(
      'x'=>array(100,101,104,99,104)
      ,'y'=>array(50,52,56,50,44)
      )
      ,array(
      'x'=>array(50,51,48)
      ,'y'=>array(100,102,98)
      )
      );
      $a = $c->Base64ToNative($signature);
      $t == $a
     */

    public function Base64ToNative($datastring) {
        global $chunkSeparator;
        $data = array();
        $chunks = explode($chunkSeparator, $datastring);
        $l = sizeof($chunks) / 2;
        for ($i = 0; $i < $l; $i++) {
            array_push($data, array(
                'x' => $this->uncompress_stroke_leg($chunks[$i * 2])
                , 'y' => $this->uncompress_stroke_leg($chunks[$i * 2 + 1])
            ));
        }
        return $data;
    }

}

class jSignatureConverter {

    private $_sigdata;

    public function setBase30($sig) {
        $this->_sigdata = $sig;
        return $this;
    }

    public function getSvg($stroke_width = 5) {
        if (!isset($this->_sigdata)) {
            return;
        }
        $temp = explode(',', $this->_sigdata);
        $sig = $temp[1];
        $c = new jSignature_Tools_Base30();
        $a = $c->Base64ToNative($sig);
        $svg_class = new jSignature_Tools_SVG();
        $svg_img = $svg_class->NativeToSVG($a, $stroke_width);
        return $svg_img;
    }

    public function getPng($stroke_width = 3) {
        if (!isset($this->_sigdata)) {
            return;
        }
        $svg = $this->getSvg($stroke_width);
        $file = tempnam(sys_get_temp_dir(), 'signature');
        file_put_contents($file . '.svg', $svg);

        // This will need
        // (Ubuntu) sudo apt-get install librsvg2-bin
        // to work                
        exec("rsvg-convert {$file}.svg > {$file}.png");
        return "{$file}.png";
    }

}

////Example use:
//$sig = 'data:image/jsignature;base30,8K100000Z3101110000101210101000000Y21112010122675978644511000000Z132111454778987764_2Ubdgfehc964675967Za8597969556576978567546443010323221Y14586bfijkec85420220100000Z1_bSZ545232010Y156764565565414300000Z1465556544_4N0476766554235112100Z65554556586452100Y1112_eB00000000110Z1_7BZ899886554465_eC5342435554433120213022211_5IZ475422000Y1423565555745354_muZ45333745262230000000Y31222443442234346864_1VZ1Y223375537345665767865334434535345310Z112_mM02112201103000_3uc7bab95648795Z5_mB994755_4JZ231231_nT0000322312211110000000003744223433132_2N5575899686646a65Z876a785730Y14344746563_rv34535831Z57645444542100Y103636594854354_5PZ21323544423221210Y13855464221001221201_sw65757888975454434532000Z2347468635643454556_2vZ1000000Y235346789a8536565445244323342134212';
//$converter = new jSignatureConverter();
//$png_path = $converter->setBase30($sig)->getPng();


