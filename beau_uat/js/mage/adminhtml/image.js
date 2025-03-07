/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

if(!window.Flex) {
    alert('Flex library not loaded');
} else {
    Flex.ImageEditor = Class.create();
    Flex.ImageEditor.prototype = {
        flex: null,
        filters:null,
        containerId:null,
        flexContainerId:null,
        container:null,
        initialize: function(containerId, movieSrc, config) {
            this.containerId = containerId;
            this.container   = $(containerId);

            this.container.controller = this;

            this.config = config;
            this.flexContainerId = this.containerId + '-flash';
            Element.insert(this.container, {bottom: '<div id="'+this.flexContainerId+'"></div>'});

            this.flex = new Flex.Object({
                width:  "1024",
                height: "786",
                src:    movieSrc,
                wmode: 'transparent'
            });


            this.flex.onBridgeInit = this.handleBridgeInit.bind(this);
            this.flex.apply(this.flexContainerId);
        },
        getInnerElement: function(elementName) {
            return $(this.containerId + '-' + elementName);
        },
        handleBridgeInit: function() {
            this.flex.getBridge().addEventListener('image_loaded', this.handleImageLoad.bind(this));
            this.flex.getBridge().setImage(this.config.image);


        },
        handleImageLoad: function(event) {
            alert('image_loaded:' + this.config.image);
            this.hangleImageResize();
        },
        hangleImageResize: function() {
            var size = this.flex.getBridge().getSize();
            this.getInnerElement('width').value = size.width;
            this.getInnerElement('height').value = size.height;

        },
        rotateCw: function() {
            this.flex.getBridge().rotateFw();
            this.hangleImageResize();
        },
        rotateCCw: function() {
            this.flex.getBridge().rotateBw();
            this.hangleImageResize();
        },
        resize: function() {
            this.flex.getBridge().resize(parseFloat(this.getInnerElement('width').value), parseFloat(this.getInnerElement('height').value));
        },
        getImage: function() {
            this.getInnerElement('b64').value = this.flex.getBridge().getBase64Image();
        }
    };
}
