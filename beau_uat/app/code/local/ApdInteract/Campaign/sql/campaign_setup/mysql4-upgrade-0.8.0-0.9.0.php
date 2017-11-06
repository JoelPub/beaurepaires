<?php

$installer = $this;
$installer->startSetup();
$content = <<<EOF
    <section class="campaign-header">
      <div class="row">
        <div class="small-12 columns">
          <!-- NOTE TO BACKEND: The SRC value will have to be chagned for live -->
          <img src="/skin/frontend/polar/default/images/easter_campaign/header-banner.png" alt="Beaurepaires.  Buy 4 tyres.  Get up yo $150.*">
        </div>
      </div>
    </section>

    <section class="campaign-body">
      <div class="row">
        <div class="small-12 medium-6 medium-push-6 columns">
          <!-- NOTE TO BACKEND: The SRC value will have to be chagned for live -->
          <img src="/skin/frontend/polar/default/images/easter_campaign/520x520.png" alt="" role="presentation">
        </div>
        <div class="small-12 medium-6 medium-pull-6 columns">

          <h2 class="page-title">Landing Page Title</h2>

          <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse egestas accumsan nulla, quis consectetur
            est faucibus et. Vestibulum id fringilla nisl. Morbi eget efficitur justo. Morbi risus erat, mattis ut ante et,
            pretium volutpat dui. Aliquam nulla orci, congue eget nisl vel, cursus scelerisque ipsum.
          </p>

          <p>
            Aenean nec mi eget nibh convallis vestibulum in ac nisl. Vestibulum faucibus leo a metus ornare, eu molestie
            ipsum iaculis.
          </p>

          <p>
            Etiam imperdiet varius velit sed convallis. Nulla eu aliquet libero, eget dictum ante. Donec nisi eros, efficitur
            hendrerit erat at, porttitor scelerisque augue. Nullam tincidunt ante vel elit gravida suscipit. Vestibulum eu orci
            sit amet eros hendrerit venenatis.
          </p>

          <p>
            <a class="button radius" href="tel://1-800-961-563"><i class="fa fa-fw fa-phone" aria-hidden="true"></i>1800 961 563</a><br>
            <!-- NOTE TO BACKEND: The HREF value will have to be chagned for live -->
            <a class="button radius" href="/store-locator/"><i class="fa fa-fw fa-map-marker" aria-hidden="true"></i>Find your nearest store</a>
          </p>

        </div>
      </div>
    </section>

    <section class="campaign-form">
        {{block type="campaign/easter" template="campaign/easter/easter-campaign.phtml"}}
    </section>

    <section class="campaign-footer">
      <div class="row">
        <div class="small-12 columns text-center">
          <!-- NOTE TO BACKEND: The SRC value will have to be chagned for live -->
          <img src="/skin/frontend/polar/default/images/easter_campaign/footer-banner.png" alt="Who cares? Beaurepaires">
          <hr>
        </div>
      </div>
      <div class="row">
        <div class="small-12 columns">
          <small>
            <strong>Term and Conditions</strong><br>
            Curabitur arcu tortor, egestas in diam in, sagittis pharetra ante. Praesent non consectetur erat, non consequat orci.
            Praesent nec pellentesque orci. Etiam in fringilla dui. Duis laoreet semper libero sed imperdiet. Vivamus maximus,
            erat nec interdum feugiat, eros justo aliquam sem, a tempus lorem justo eu metus. Fusce luctus id tellus ut venenatis.
            Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In malesuada diam ipsum, in
            posuere arcu vehicula id. Curabitur nec enim id metus accumsan vulputate. Class aptent taciti sociosqu ad litora torquent
            per conubia nostra, per inceptos himenaeos. Duis eleifend arcu molestie molestie dictum.
          </small>
        </div>
      </div>
    </section>
                    
EOF
;

$cms = Mage::getModel('cms/page')->load('easter-campaign', 'identifier');
if ($cms->getId()){
    $cms->setContent($content)->save();
}

$installer->endSetup();
