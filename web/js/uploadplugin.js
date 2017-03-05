$.Redactor.prototype.advanced = function() {
    return {
        init: function() {
            var button = this.button.add('advanced', 'Додати зображення');
            this.iid = this.$textarea.attr('id');

            this.button.setAwesome('advanced', 'fa-upload');

            this.button.addCallback(button, this.advanced.testButton);

            this.$editor.off('drop.redactor');
        },

        testButton: function() {
            $('#' + this.iid + 'upload').click();
        }
    };
};
