/**
 * Created by ya on 1/20/14.
 */
/*
Each module's job is to create a meaningful user experience
The modules know nothing except that the sandBox exists
    They have no knowledge of one another or the rest of the architecture
Module Rules:
* Hands to yourself
*   * Only call your own methods or those on sandbox
*   * Don't access DOM elements outside of you box
*   * Don't access non-native global objects
* Ask, don't take
*   * Anything else you need, ask the sandbox
* Don't leave your toys around
*   * Don't create global objects
* Don't talk to strangers
*   * Don't directly reference other modules
 */
Core.register("tree", function (sandbox) {
    return{
        init: function () {
            //constructor
            //not sure I'm allowed...
            if (sandbox.iCanHazCheezburgger()) {
                alert('thx u');
            }
        },
        destroy: function () {
            //destructor
        }
    }
});

Core.register("timeline-filter", function (sandBox) {
        return {
            changeFilter: function (filter) {
                sandBox.notify({
                    type: "timeline-filter-change",
                    data: filter
                });
            }
        };
    }
);

Core.register("status-poster", function (sandBox) {
        return {
            changeFilter: function (filter) {
                sandBox.notify({
                    type: "new-status",
                    data: statusText
                });
            }
        };
    }
);

Core.register("timeline", function (sandBox) {
    return{
        init: function () {
            sandBox.listen([
                "timeline-filter-change",
                "post-status"
            ], this.handleNotification, this);
        },

        handleNotification: function (note) {
            switch (note.type) {
                case "timeline-filter-change":
                    this.applyFilter(note.data);
                    return;
                case "post-status":
                    this.post(note.data);
                    return;
            }
        }
    }
});

Core.startAll();