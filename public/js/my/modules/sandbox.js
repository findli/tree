/**
 * Created by ya on 1/20/14.
 */

/*
The SandBox ensures a consistent interface
    Modules can rely on the methods to always be there
Modules only know the sandBox
    The rest of the architecture doesn't exist to them
The sandBox also acts like a security guard
    Knows what the modules are allowed to access and do on framework

SandBox job:
* Consistency
* Security
* Communication

Take the time to design the correct sandBox interface
    It can't change later
 */
var sandBox = {

}


/*
 Extensions
 * Error handling
 * Ajax communication
 * New module capabilities
 * General utilities
 * Anything!
 */
/*
Ajax Extension Jobs
* Hide Ajax communication details
* Provide common request interface
* Provide common response interface
* Manage server failures
 */
// Ajax/XML
function ajaxXML() {
    var xhr = new XMLHttpRequest();
    xhr.open("get", "/ajax?name=value", true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200 || xhr.status == 304) {
                var statusNode = xhr.responseXML.getElementsByTagName("status")[0],
                    dataNode = xhr.responseXML.getElementsByTagName("data")[0];

                if (statusNode.firstChild.nodeValue == "ok") {
                    handleSuccess(processData(dataNode));
                } else {
                    handleFailure();
                }

            } else {
                handleFailure();
            }
        }
    }

    xhr.send(null);
}
