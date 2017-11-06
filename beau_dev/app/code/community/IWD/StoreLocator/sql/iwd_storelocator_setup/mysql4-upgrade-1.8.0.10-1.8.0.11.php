<?php

/**
 *  Create Static blocks for each city landing page
 */
$installer = $this;
$installer->startSetup();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$blocks = array (
 	array (
		'identifier' => 'melbourne-landing-page',
		'title' => 'Melbourne Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
    array (
		'identifier' => 'geelong-landing-page',
		'title' => 'Geelong Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
	array (
		'identifier' => 'adelaide-landing-page',
		'title' => 'Adelaide Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
	array (
		'identifier' => 'darwin-landing-page',
		'title' => 'Darwin Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),    array (
		'identifier' => 'gold-coast-landing-page',
		'title' => 'Gold Coast Landing Page',
		'stores' => array(0),
		'is_active' => 1,
			'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores.
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
	array (
		'identifier' => 'hobart-landing-page',
		'title' => 'Hobart Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
	array (
		'identifier' => 'newcastle-landing-page',
		'title' => 'New castle Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores.
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),    array (
		'identifier' => 'northern-territory-landing-page',
		'title' => 'Northern territory Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
	array (
		'identifier' => 'nsw-landing-page',
		'title' => 'NSW Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
	array (
		'identifier' => 'perth-landing-page',
		'title' => 'Perth Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores.
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),    
	array (
		'identifier' => 'western-australia-landing-page',
		'title' => 'Western Australia Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
	array (
		'identifier' => 'queensland-landing-page',
		'title' => 'Queensland Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
	array (
		'identifier' => 'sydney-landing-page',
		'title' => 'Sydney Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores.
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),	array (
		'identifier' => 'tasmania-landing-page',
		'title' => 'Tasmania Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores.
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),    
	array (
		'identifier' => 'victoria-landing-page',
		'title' => 'Victoria Landing Page',
		'stores' => array(0),
		'is_active' => 1,
			'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores.
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
	array (
		'identifier' => 'canberra-landing-page',
		'title' => 'Canberra Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores. 
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
	array (
		'identifier' => 'act-landing-page',
		'title' => 'ACT Landing Page',
		'stores' => array(0),
		'is_active' => 1,
		'content' => <<<EOF
			<div class="store-links">
                <p>
			        <strong>Static Block</strong>
			    </p>
			    <p>
			        One per landing page:
                        <ul class="store-links-list">
			                <li><a href="#">- Beaurepaires/tyres/melbourne</a></li>
			                <li><a href="#">- Beaurepaires/tyres/geelong</a></li>
			                <li><a href="#">- Beaurepaires/tyres/adelaide</a></li>
			                <li><a href="#">- Beaurepaires/tyres/darwin</a></li>
			                <li><a href="#">- Beaurepaires/tyres/gold-coast</a></li>
			                <li><a href="#">- Beaurepaires/tyres/hobart</a></li>
			                <li><a href="#">- Beaurepaires/tyres/newcastle</a></li>
			                <li><a href="#">- Beaurepaires/tyres/northern-territory</a></li>
			                <li><a href="#">- Beaurepaires/tyres/nsw</a></li>
			                <li><a href="#">- Beaurepaires/tyres/perth</a></li>
			                <li><a href="#">- Beaurepaires/tyres/western-australia</a></li>
			                <li><a href="#">- Beaurepaires/tyres/queenslan</a></li>
			                <li><a href="#">- Beaurepaires/tyres/sysdney</a></li>
			                <li><a href="#">- Beaurepaires/tyres/tasmania</a></li>
			                <li><a href="#">- Beaurepaires/tyres/victoria</a></li>
			                <li><a href="#">- Beaurepaires/tyres/canberra</a></li>
			                <li><a href="#">- Beaurepaires/tyres/act</a></li>
			            </ul>
			    </p>
			    <p>
			        <u>What Can Our Tyres Stores Do for You?</u>
			        <p>
			            Beaurepaires has very every type of tyre you could possibly want, this includes car tyres, 4WD tyres, light commercial tyres, truck tyres and even for agricultural vehicles and tractores.
			        </p>
			    </p>
			    <p>
			        <u>Quality since 1922!</u>
			        <p>
			            Beaurepaires and our Melbourne Tyre stores have been around for a very long time. The company was founded as early as 1992, when Frank Beaurepaires started the very first Beaurepaires Tyre Service Centre.
			        </p>
			        <p>
			            Over the past few years, Beaurepaires has grown out to be one of the most successful tyres distributors in Australia. Customers know they can count on Beaurepaires and we provide them with the best possible service time and time again!
			        </p>
			    </p>
			</div>
EOF
	),
);

foreach ($blocks as $data){
$cmsBlock = Mage::getModel('cms/block');
$cmsBlock->setData($data)->save();
}

$deleteblock = Mage::getModel('cms/block')->load('city-landing-page')->delete();
$deleteblock->save();

$installer->endSetup();