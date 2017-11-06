<?php

class ApdInteract_Widgets_Block_Tripleblockcarousel extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface {

    public function getCmsBlockOutput($block_id) {
        return $this->getLayout()->createBlock('cms/block')->setBlockId($block_id)->toHtml();
    }

    public function printCmsBlock($block_id) {
        $html = $this->getCmsBlockOutput($block_id);
        if (empty($html)) {
            if ($block_id == 'homepage_feature') {
                $html = $this->getFeaturePlaceholderHtml($block_id); 
            }
            else {
                $html = $this->getCarouselPlaceholderHtml($block_id);                
            }
        }
        echo $html;
    }
        
    public function getCarouselPlaceholderHtml($block_id) {
        $html = <<<EOF
<a href="#" class="th">
    <img src="http://placehold.it/500x500" alt="">
  </a>
  <a href="#"><h3>Create a CMS block called {$block_id} to replace this placeholder content</h3></a>
  <a href="#" class="small radius button">Find out more</a>
                    
EOF
;        
        return $html;
    }
    
    
    public function getFeaturePlaceholderHtml($block_id) {
        $html = <<<EOF
<div class="large-6 columns">
    <a href="#" class="th"><img src="http://placehold.it/800x500" alt=""></a>
</div>
<div class="large-6 columns">
    <a href="#" class="th"><h3>Lorem ipsum dolor</h3></a>
    <p><strong>Create a CMS block called {$block_id} to replace this placeholder content</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed facilisis metus nec eleifend molestie. Aliquam mattis nisi ut ornare accumsan. Nullam pulvinar justo vel aliquam efficitur. Donec interdum lectus nisl, vitae facilisis arcu pharetra non. Ut turpis arcu, congue et consectetur non, bibendum eget urna. Donec nec purus eu tellus rutrum imperdiet. Ut tellus arcu, imperdiet quis accumsan in, aliquam sit amet nisi. Sed nulla sapien, sodales sit amet ex non, blandit sagittis tortor. Sed tempus dolor a felis dictum viverra. Nunc lobortis nisi id turpis cursus, et porttitor urna hendrerit. Nullam mi arcu, ullamcorper ac nulla tempus, congue tempor arcu. Etiam tempus mauris eget lacinia porttitor. Quisque gravida diam ac turpis tristique dictum.</p>
</div>
                    
EOF
;        
        return $html;
    }
    
    
}

    

;
