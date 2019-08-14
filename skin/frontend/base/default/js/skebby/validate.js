//http://stackoverflow.com/questions/2113908/what-regular-expression-will-match-valid-international-phone-numbers
if(typeof(Validation) !== 'undefined'){
    Validation.add('validate-skebby-phone', 'Please provide us with the international prefix of your country in front of the phone number e.g. +32499111111 for Belgium', function(v) {
       return !v || v.match(/^\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$/);
    });    
}
document.observe('dom:loaded',function(){
    if($('telephone')){ $('telephone').addClassName('validate-skebby-phone'); }
    if($('billing:telephone')){ $('billing:telephone').addClassName('validate-skebby-phone'); }
    if($('shipping:telephone')){ $('shipping:telephone').addClassName('validate-skebby-phone'); }
});