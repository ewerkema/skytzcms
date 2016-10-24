
function Request(url){
    this.url = url;
    this.type = 'GET';
    this.form = '';
    this.fields = [];
}

Request.prototype.setType = function(type) {
    this.type = type;
};

Request.prototype.setForm = function(form) {
    this.form = $(form);
};

Request.prototype.getType = function() {
    console.log(this.type);
};

Request.prototype.findInForm = function (name) {
    return this.form.find('[name='+name+']');
};

Request.prototype.addField = function(name, type = "text", defaultVal = "", optional = false) {
    var field = {};
    field.type = type;
    field.default = defaultVal;
    field.optional = optional;

    this.fields[name] = field;
};

Request.prototype.addFields = function(names) {
    var _this = this;
    names.forEach(function(name) {
        _this.addField(name);
    });
};

Request.prototype.addOptionalFields = function(names) {
    var _this = this;
    names.forEach(function(name) {
        _this.addField(name, "text", "", true);
    });
};

Request.prototype.processFields = function() {
    var data = {};
    for (var name in this.fields) {
        var el = this.fields[name];

        switch (el.type) {
            case "checkbox":
                data[name] = (this.findInForm(name).is(":checked")) ? 1 : 0;
                break;
            default:
                data[name] = (this.findInForm(name).val()) ? this.findInForm(name).val() : el.default;
                break;
        }

        if (el.optional) {
            if (data[name] == "")
                delete data[name];
        }
    }
    return data;
};

Request.prototype.showError = function(data) {
    var errors = data.responseJSON;
    var errorMessage = "";

    if (errors === undefined)
        errorMessage += "Er ging iets fout, we zullen er zo spoedig mogelijk naar kijken.";
    else {
        for (var error in errors) {
            errorMessage += errors[error]+"<br />";
        }
    }

    this.form.find('.form-message').addClass("alert-danger").html(errorMessage).show();
};

Request.prototype.onSubmit = function (callback) {
    var _this = this;
    this.form.submit(function(e){
        e.preventDefault();
        var data = _this.processFields();

        $.ajax({
            url: _this.url,
            type: _this.type,
            data: data,
            success: function (successData) {
                callback(successData);
            },
            error: function (errorData) {
                _this.showError(errorData)
            }
        });
    });
};

Request.prototype.send = function (callback) {
    var data = this.processFields();

    $.ajax({
        url: this.url,
        type: this.type,
        data: data,
        success: function (successData) {
            callback(successData);
        },
        error: function (errorData) {
            this.showError(errorData)
        }
    });
};