(function($) {
 
    $.fn.inputFileText = function(userOptions) {
        // Shortcut for plugin reference
        var P = $.fn.inputFileText;

        var options = P.getOptions(userOptions);

        if(P.shouldRemoveInputFileText(this, options.remove)) {
            return P.removeInputFileText(this);
        }
        else if(P.hasInputFileText(this)) {
            return this;
        }

        // Keep track of input file element's display setting
        this.attr(P.DISPLAY_ATTRIBUTE, this.css('display'));

        // Hide input file element
        this.css({
            display: 'none'
            //width:  0
        });

        // Insert button after input file element
        var button = $(
            '<input type="button" value="' + options.text + '" class="' + options.buttonClass + '" />'
            ).insertAfter(this);

        // Insert text after button element
        var text = $(
            '<span style="margin-left: 5px" class="' + options.textClass + '"></span>'
            ).insertAfter(button);

        // Open input file dialog when button clicked
        var self = this;
        button.click(function() {
            self.click();
        });

        // Update text when input file chosen
        this.change(function() {
            // Chrome puts C:\fakepath\... for file path
            text.text(self.val().replace('C:\\fakepath\\', ''));
        });
 
        // Mark that this plugin has been applied to the input file element
        return this.attr(P.MARKER_ATTRIBUTE, 'true');
    };

    $.fn.inputFileText.MARKER_ATTRIBUTE = 'data-inputFileText';
    $.fn.inputFileText.DISPLAY_ATTRIBUTE = 'data-inputFileText-display';

    $.fn.inputFileText.getOptions = function(userOptions) {
        return $.extend({
            // Defaults
            text: 'Choose File',
            remove: false,
            buttonClass: '',
            textClass: ''
        }, userOptions);
    };

    /**
    Check if plugin has already been applied to input file element.
    */
    $.fn.inputFileText.hasInputFileText = function(inputFileElement) {
        return inputFileElement.attr($.fn.inputFileText.MARKER_ATTRIBUTE) === 'true';
    };

    /**
    Check if plugin should be removed from input file element.
    */
    $.fn.inputFileText.shouldRemoveInputFileText = function(inputFileElement, remove) {
        return remove && $.fn.inputFileText.hasInputFileText(inputFileElement);
    };

    /**
    Remove plugin from input file element.
    */
    $.fn.inputFileText.removeInputFileText = function(inputFileElement) {
        var P = $.fn.inputFileText;

        inputFileElement.next('input[type=button]').remove();
        inputFileElement.next('span').remove();
        return inputFileElement.attr(P.MARKER_ATTRIBUTE, null)
            .css({
                display: inputFileElement.attr(P.DISPLAY_ATTRIBUTE)
            })
            .attr(P.DISPLAY_ATTRIBUTE, null);
    };
 
}(jQuery));