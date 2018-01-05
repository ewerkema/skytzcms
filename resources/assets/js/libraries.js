const _ = require('lodash');
const Bootstrap = require('bootstrap-sass');
const ContentTools = require('../../../public/plugins/ContentTools/build/content-tools');
const Awesomplete = require('awesomplete');
const jqueryUi = require('jquery-ui');
const Jcrop = require('jquery-jcrop/js/jquery.Jcrop');

_.mixin({
    'findByValues': function(collection, property, values) {
        return _.filter(collection, function(item) {
            return _.contains(values, item[property]);
        });
    }
});