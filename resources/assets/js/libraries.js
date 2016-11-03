const _ = require('lodash');
const Bootstrap = require('bootstrap-sass');
const ContentTools = require('contenttools');
const Awesomplete = require('awesomplete');
const jqueryUi = require('jquery-ui');

_.mixin({
    'findByValues': function(collection, property, values) {
        return _.filter(collection, function(item) {
            return _.contains(values, item[property]);
        });
    }
});