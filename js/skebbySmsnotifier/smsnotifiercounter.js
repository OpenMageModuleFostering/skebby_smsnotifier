// Create the class
// MA FUNZIONA QUESTA CLASSE???
var mySmsnotifierCounter = Class.create({
    initialize: function(eventToObserve) // Is called when the page has finished loading by the Event.observe code below
    {

        var smsnotifierCounter = document.getElementById('smsnotifierCounter');
        var smsnotifierCounterContainer = document.getElementById('smsnotifierCounterContainer');
        var maxchars = 160;
        var childAdded = false;
        var activeTextArea = false;
        var textAreaId = false;
//        var storeNameLenght = document.getElementById('smsnotifier_main_conf_storename').value.length;

        $('smsnotifier_templates').observe(eventToObserve, function(event) {
            var textlength = 0;
            activeTextArea = event.findElement('textarea');

            if (activeTextArea) {
                

                if (textAreaId !== activeTextArea.id) {
                    smsnotifierCounterContainer.remove(); //remove old smsnotifierCounterContainer
                    activeTextArea.insert({//reinitialize conterContainer in new position
                        after: smsnotifierCounterContainer
                    });
                    smsnotifierCounterContainer.show(); //snow smsnotifierCounter div at starts
                }

                textlength = activeTextArea.value.length;
                
                
                
                smsnotifierCounter.update(textlength);

                if (textlength <= (maxchars - 50)) {

                    $('smsnotifierCounter').setStyle({
                        fontSize: '150%',
                        fontWeight: 'normal',
                        color: '#0F910F'
                    });
                    $('smsnotifierTooLongAlert').hide();

                }
                else if (textlength <= (maxchars - 20)) {

                    $('smsnotifierCounter').setStyle({
                        fontWeight: 'bold',
                        color: '#FF8400'
                    });
                    $('smsnotifierTooLongAlert').hide();

                } else {

                    $('smsnotifierCounter').setStyle({
                        fontWeight: ' bold',
                        color: '#B80000'
                    });
                    $('smsnotifierTooLongAlert').show();

                }


            }




        });

    }

});
// Global variable for the instance of the class
// Creating an instance of the class if the page has finished loading
Event.observe(window, 'load', function() {
    document.getElementById('smsnotifierCounterContainer').hide(); //hide smsnotifierCounter div at start
    new mySmsnotifierCounter('click');
    new mySmsnotifierCounter('keyup');
});