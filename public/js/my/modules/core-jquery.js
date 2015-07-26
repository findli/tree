/**
 * Created by ya on 1/20/14.
 */
/*
 Application Core Jobs
 * Manage module lifecycle
 * Enable inter-module communication
 * General error handling
 * Be extensible
 */
Core = function () {
    var moduleData = {}, debug = false;

    function createInstance(moduleId) {
        var instance = moduleData[moduleId].creator(new SandBox(this)), name, method;

        if (!debug) {
            for (name in instance) {
                method = instance[name];
                if (typeof method == "function") {
                    instance[name] = function (name, method) {
                        return function () {
                            try {
                                return method.apply(this, arguments);
                            }
                            catch (ex) {
                                add_log(1, name + "(): " + ex.message);
                            }
                        };
                    }(name, method);
                }
            }
        }

        return instance;
    }

    return {
        register: function (moduleId, creator) {
            moduleData[moduleId] = {
                creator: creator,
                instance: null
            };
        },
        start: function (moduleId) {
            moduleData[moduleId].instance = moduleData[moduleId].creator(new SandBox(this));
            moduleData[moduleId].instance.init();
        },

        stop: function (moduleId) {
            var data = moduleData[moduleId];
            if (data.instance) {
                data.destroy();
                data.instance = null;
            }
        },

        startAll: function () {
            for (var moduleId in moduleData) {
                if (moduleData.hasOwnProperty(moduleId)) {
                    this.start(moduleId);
                }
            }
        },

        stopAll: function () {
            for (var moduleId in moduleData) {
                if (moduleData.hasOwnProperty(moduleId)) {
                    this.stop(moduleId);
                }
            }
        }
    }
}


/*

 Ideally, only the application core has any idea what base library is being used
    no other part of the architecture should need to know
 Base Library Jobs
* Browser normalization
*   Only the base library knows which browser is being used
* General-purpose utilities
*   * Parsers/serializers for XML, JSON, etc.
*   * Object manipulation
*   * DOM manipulation
*   * Ajax communication
* Provide low-level extensibility
 */