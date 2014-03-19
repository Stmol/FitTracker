/**
 * Created by Yury Smidovich (Stmol)
 * Date: 13.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */
'use strict';

FTApp.Views.WorkoutSetsFormView = Backbone.View.extend({

  events: {
    'click .exercises-list li': 'onExerciseClick',
    'click .workout-sets li':   'onWorkoutSetClick',
    'click #save':              'onSaveBtnClick',
    'click #clear':             'onClearBtnClick'
  },

  initialize: function () {
    // Elements
    this.$workoutSetsList = this.$('.workout-sets');

    // Vars
    this._deletedSets = [];

    // Events
    this.listenTo(this.collection, 'remove', this.removeSet);
  },

  onExerciseClick: function (e) {
    // Add new Set to collection
    this.collection.add({
      exercise_id: $(e.target).data('id'),
      workout_id: this.$workoutSetsList.data('workout-id'),
      order: this.collection.nextOrder()
    });

    // Animate
    $('<li/>', {
      text: $(e.target).text()
    }).appendTo(this.$workoutSetsList);

    this.$workoutSetsList
      .animate({ scrollTop: this.$workoutSetsList.find('li').length * 33 });
  },

  onWorkoutSetClick: function (e) {
    var elementIndex = $(e.target).index() + 1;

    // Remove Set
    this.collection.remove(this.collection.findWhere({order: elementIndex}));

    // Animate
    $(e.target).remove();
  },

  onSaveBtnClick: function () {
    this.collection.save();
//    this.destroySets();
  },

  removeSet: function (setModel) {
    if (!setModel.isNew()) {
      this._deletedSets[setModel.id] = setModel;
    }

    this.collection.recalcOrder();
  },

  destroySets: function () {
    this._deletedSets.forEach(function (setModel) {
      setModel.destroy();
    });
  },

  onClearBtnClick: function () {
    this.collection.reset();
    this.$workoutSetsList.empty();
  }

});
