"use strict";
(function () {
    var handler, form, update, oldIE, i, oldIEHanlder;

    handler = function (event) {
        if (!document.formvalidator.isValid(form)) {
            if (event) {
                event.preventDefault();
            } else { // mootools bug?
                throw new Error('form is not valid');
            }
        }
    };
    
    update = function (id) {
        var obj, tvalue, arr, ret, ret2;
        
        obj = document.getElementById(id);
        tvalue = obj.value;
		arr = tvalue.split(",");
        ret  = arr[1];
        ret2 = arr[2];
        
        document.getElementById('subject').value = arr[3];
        document.getElementById('emailto_id').value = arr[0];
				
        if (!ret) {
            document.getElementById('extrarow').style.display='none';
        } else {
            document.getElementById('extrarow').style.display='table-row';
            document.getElementById('extraname').firstChild.nodeValue = ret;
        }

        if (!ret2) {
            document.getElementById('extra2row').style.display='none';
        } else {
            document.getElementById('extra2row').style.display='table-row';
            document.getElementById('extra2name').firstChild.nodeValue = ret2;
        }
    };

    oldIEHanlder = function (e) {
        if (document.formvalidator.isValid(form)) {
            form.submit();
        }
    };

    oldIE = navigator.appVersion;
    oldIE = oldIE.substring(oldIE.indexOf('(') + 1, oldIE.indexOf(')'));
    oldIE = oldIE.split(/\s*;\s*/);
    for (i = 0; i < oldIE.length; i++) {
        if (oldIE[i].substring(0, 5) == "MSIE ") {
            oldIE = parseFloat(oldIE[i].substring(5));
            if (oldIE < 8) {
                oldIE = true;
            }
        }
    }
    if (oldIE === true) {
        window.attachEvent('onload', function () {
            var emailid, inputs, i, buttons;
            update('emailid');
            form = document.getElementById('contact-form');
            emailid = document.getElementById('emailid');
            emailid.attachEvent('onchange', function () {
                update('emailid');
            });
            form.attachEvent('onsubmit', oldIEHanlder);
            inputs = form.getElementsByTagName('input');
            for (i = 0; i < inputs.length; i++) {
                if (inputs[i].type == 'submit') {
                    inputs[i].attachEvent('onclick', function (e) {
                        oldIEHanlder(e);
                    });
                    inputs[i].attachEvent('onkeypress', function (e) {
                        if ((e.keyCode == 32) || (e.keyCode == 13)) {
                            oldIEHanlder(e);
                        }
                    });
                    continue;
                }
                inputs[i].attachEvent('onkeypress', function (e) {
                    if (e.keyCode == 13) {
                        oldIEHanlder(e);
                    }
                });
            }
            buttons = form.getElementsByTagName('button');
            for (i = 0; i < buttons.length; i++) {
                buttons[i].attachEvent('onkeypress', function (e) {
                    if (e.keyCode == 32) {
                        oldIEHanlder(e);
                    }
                });
                buttons[i].attachEvent('onclick', function (e) {
                    oldIEHanlder(e);
                });
            }
        });
    } else {
        window.addEvent('domready', function() {
            form = $('contact-form');
            $('emailid').addEvent('change', function () {
                update('emailid');
            });
            if (ALFContact.validate) {
                form.addEvent('submit', handler);
            }
            update('emailid');
        });
    }
}());
