/**
 * Created by Yury Smidovich (Stmol)
 * Date: 17.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

FTApp.Views.ExerciseFormView = Backbone.View.extend({

  events: {
    'click #add-param': 'onAddParamClick'
  },

  initialize: function () {
    // Variables
    var self = this;
    this.$exerciseParamsList = this.$('#exercise_params');

    this.$exerciseParamsList
      .data('index', this.$exerciseParamsList.find(':input').length)
      .children('div').each(function () {
        $(this).children('label').remove();
        self.addRemoveFormLink(this);
      });
  },

  onAddParamClick: function () {
    this.addParamForm();
  },

  addParamForm: function () {
    var prototype = this.$exerciseParamsList.data('prototype');
    var index = this.$exerciseParamsList.data('index');
    var $newParamForm = $(prototype.replace(/__name__label__/, '').replace(/__name__/g, index));

    this.addRemoveFormLink($newParamForm);

    this.$exerciseParamsList
      .data('index', index + 1)
      .append($newParamForm);
  },

  removeParamForm: function (e) {
    e.preventDefault();
    $(e.target).parent('div').remove();
  },

  addRemoveFormLink: function (element) {
    var $removeFormA = $('<a href="#">Удалить</a>');

    $removeFormA
      .on('click', this.removeParamForm)
      .appendTo(element);
  }
});
