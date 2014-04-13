/**
 * Created by Yury Smidovich (Stmol)
 * Date: 17.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

FTApp.Views.ExerciseFormView = Backbone.View.extend({

  events: {
    'click #add-parameter': 'onAddParamClick',
    'click .remove-parameter': 'onRemoveParamClick'
  },

  initialize: function () {
    // Variables
    this.$exerciseParamsList = this.$('#exercise-parameters');

    this.$exerciseParamsList
      .data('index', this.$exerciseParamsList.find(':input').length)
      .children('div').each(function () {
        $(this).children('label').remove();
      });
  },

  onAddParamClick: function (e) {
    e.preventDefault();
    this.addParamForm();
  },

  onRemoveParamClick: function (e) {
    e.preventDefault();
    this.removeParamForm(e.target);
  },

  addParamForm: function () {
    var prototype = this.$exerciseParamsList.data('prototype');
    var index = this.$exerciseParamsList.data('index');
    var $newParamForm = $(prototype.replace(/__name__/g, index));

    this.$exerciseParamsList
      .data('index', index + 1)
      .append($newParamForm);
  },

  removeParamForm: function (element) {
    $(element).parent('div').remove();
  }
});
