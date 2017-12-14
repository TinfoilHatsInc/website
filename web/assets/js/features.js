/**
 * Created by matthijs on 14-12-17.
 */
var $collectionHolder;

// setup an "add a feature" link
var $addFeatureLink = $('<a href="#" class="add_feature_link">Add a feature</a>');
var $newLinkLi = $('<div></div>').append($addFeatureLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of features
    $collectionHolder = $('ul.features');

    // add the "add a feature" anchor and li to the features ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    // add a delete link to all of the existing feature form li elements
    $collectionHolder.find('li').each(function() {
        addfeatureFormDeleteLink($(this));
    });

    $addFeatureLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new feature form (see next code block)
        addFeatureForm($collectionHolder, $newLinkLi);
    });
});

function addFeatureForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a feature" link li
    var $newFormLi = $('<li></li>').append(newForm).append('<hr>');

    // also add a remove button, just for this example
    // $newFormLi.append('<a href="#" class="remove-feature">x</a>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-feature').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });

    // add a delete link to the new form
    addfeatureFormDeleteLink($newFormLi);
}

function addfeatureFormDeleteLink($featureFormLi) {
    var $removeFormA = $('<a href="#">delete this feature</a>');
    $featureFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the feature form
        $featureFormLi.remove();
    });
}