//
// Dynamic listview update.
//

var listviewUpdateTimeout;

function dynamicListviewUpdate(listviewId, serializeClass, delay) {
    // Default delay value.
    delay = typeof delay !== 'undefined' ? delay : 500;

    clearTimeout(listviewUpdateTimeout);

    listviewUpdateTimeout = setTimeout(
        function() {
            $.fn.yiiListView.update(
                // The id of the CListView.
                listviewId,
                // Serialize whole filter class.
                { data: $('.' + serializeClass).serialize() }
            )
        },
        delay
    );

    return false;
};
