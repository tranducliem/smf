/**
 * Created by liemtd on 12/17/13.
 */
(function ($) {
    $(function () {
        // generate a slug when the user types a title in
        pyro.generate_slug('#definition-content-tab input[name="title"]', '#definition-content-tab input[name="slug"]');
    });
})(jQuery);