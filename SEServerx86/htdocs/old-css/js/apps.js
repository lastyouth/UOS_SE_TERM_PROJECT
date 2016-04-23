
!function ($) {
	

 
jQuery.fn.textNodes = function() {
    var ret = [];
    this.contents().each(function() {
        var fn = arguments.callee;
        if(this.nodeType == 3) {
            ret.push(this);
        } else if(this.nodeType==1 &&!(
        this.tagName.toLowerCase()=='script' ||
        this.tagName.toLowerCase()=='head' ||
        this.tagName.toLowerCase()=='iframe' ||
        this.tagName.toLowerCase()=='textarea' ||
        this.tagName.toLowerCase()=='option' ||
        this.tagName.toLowerCase()=='style' ||
        this.tagName.toLowerCase()=='title' ||
        this.tagName.toLowerCase()=='a')){
            jQuery(this).contents().each(fn);
        }
    });
    return ret;
}
 
jQuery.fn.livelink = function() {
    re_link2 = new RegExp('(https?://(?:[A-Z0-9]\.)*(?:(.*).(.*))[-()A-Z0-9+&@#/%?=~_|!:,.;]*[A-Z0-9+&@#/%=~_|])', "ig");
    re_link3 = new RegExp('https?://(?:[A-Z0-9]\.)*(?:(.*).(.*))[-()A-Z0-9+&@#/%?=~_|!:,.;]*[A-Z0-9+&@#/%=~_|]', "i");
    this.each(function(i){
        jQuery.each($(this).textNodes(), function(i, node){
            text = node.nodeValue;
            if(re_link3.test(text)){
                newNode=document.createElement('span');
                text=jQuery('<div/>').text(text).html();
                newNode.innerHTML=text.replace(re_link2, '<a href="$1" target="_blank">$1</a>');
                node.parentNode.replaceChild(newNode, node);
            }
        });
    });
}
 
$(function() {
    $("div").livelink();
});




}(window.jQuery);