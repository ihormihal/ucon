(function($) {
    $.fn.imSelect = function(options){
        var settings = $.extend({

        }, options);

        var that = this;

        $(document).on('click', function(event){
            if(!$(event.target).parents('.dropdown.multiselect')[0]){
                $('.dropdown.multiselect').removeClass('focus in');
            }
        });

        function animate(element) {
            if (element.hasClass('focus')) {
                element.addClass('in');
            }
        }

        var Dropdown = function(el){
            var that = this;
            that.el = el;
            that.show = function(){
                that.el.addClass('focus');
                setTimeout(animate, 50, that.el);
            };
            that.hide = function(){
                that.el.removeClass('focus in');
            };
            that.isVisible = function(){
                return that.el.hasClass('focus in');
            };
        };

        var Input = function(el) {
            var that = this;
            that.el = el;
            that.setValue = function(value){
                that.el.val(value.value);

            };
            that.setValues = function(values){
                that.el.val('');
                for(var i = 0; i < values.length; i++){
                    var option = that.el.find('option[value='+values[i].value+']');
                    option.prop('selected', true);
                }
            };
        };

        var Search = function(el) {
            var that = this;
            that.el = el;
        };

        var Icon = function(el) {
            var that = this;
            that.el = el;
            that.setValue = function(icon){
                that.el.innerHTML = '<img src="'+icon+'">';
            };
        };

        var Select = function(el) {
            var that = this;
            that.el = el;

            that.placeholder = that.el.text();

            that.getValues = function(){
                var values = [];
                var items =
                that.el.find('.item').each(function(){
                    var item = {text: $(this).text(), value: $(this).attr('data-value') || $(this).text()};
                    values.push({
                        text: $(this).text(),
                        value: $(this).attr('data-value') || $(this).text()
                    });
                });
                if(values.length == 0){
                    that.el.html(that.placeholder);
                }
                return values;
            };

            that.setValue = function(value){
                that.el.val(value.text);
                if(value.icon){
                    that.el.parent().find('.icon').html('<img src="' + value.icon + '">');
                    that.el.addClass('has-icon');
                }else{
                    that.el.parent().find('.icon').html('');
                    that.el.removeClass('has-icon');
                }
            };

            that.setValues = function(values){
                that.el.html('');
                for(var i = 0; i < values.length; i++){
                    var iconHtml = '';
                    if(values[i].icon){
                       iconHtml =  '<img class="icon" src="' + values[i].icon + '">';
                    }
                    that.el.append('<div data-value="'+values[i].value+'" class="item">'+iconHtml+values[i].text+'<i class="fa fa-times"></i></div>');
                }
            };
            
        };

        var Collection = function(el) {
            var that = this;
            that.el = el;

            that.list = $(that.el).find('ul');

            that.clear = function(){
                this.el.find('li').removeClass('selected active');
            };

            that.getValue = function(){
                var selected = that.el.find('li.selected');
                var icon = false;
                if(selected.find('.icon').length > 0){
                    icon = selected.find('.icon').attr('src')
                }
                return {
                    text: selected.text(),
                    value: selected.attr('data-value') || selected.text(),
                    icon: icon
                };
            };

            that.getValues = function(){
                var values = [];
                that.el.find('li.selected').each(function(){
                    var icon = false;
                    if($(this).find('.icon').length > 0){
                        icon = $(this).find('.icon').attr('src')
                    }
                    values.push({
                        text: $(this).text(),
                        value: $(this).attr('data-value') || $(this).text(),
                        icon: icon
                    });
                });
                return values;
            };

            that.setValue = function(value){
                that.clear();
                that.el.find('li').each(function(){
                    if($(this).attr('data-value') == value.value || $(this).text() == value.text){
                        $(this).addClass('selected active');
                    }
                });
            };

            that.setValues = function(values){
                that.clear();
                for(var i = 0; i < values.length; i++){
                    that.el.find('li').each(function(){
                        if($(this).attr('data-value') == values[i].value || $(this).text() == values[i].text){
                            $(this).addClass('selected active');
                        }
                    });
                }
            };

            that.isMultiple = function(){
                return that.el.hasClass('multiple');
            };

        };

        var init = function(el){

            var name = el.getAttribute('name') || "";
            var placeholder = el.getAttribute('placeholder') || "";
            var searchPlaceholder = el.getAttribute('data-search-placeholder') || 'Search...';
            var multiple = el.getAttribute('multiple') !== null ? true : false;
            var classes = el.className + ' dropdown-select dropdown';

            var collection = '';
            var options = el.getElementsByTagName('option');
            var selectedOptions = [];
            var icon = '';
            var iconHtml = '';

            $(el).find('option:selected').each(function(){
                selectedOptions.push(this.index);
            })

            for (var i = 0; i < options.length; i++) {
                var className = selectedOptions.indexOf(options[i].index) !== -1 ? 'selected' : '';
                if(options[i].getAttribute('data-icon')){
                    icon = '<img class="icon" src="'+options[i].getAttribute('data-icon')+'"/>';
                }
                collection += '<li data-value="'+options[i].value+'" class="'+className+'">'+icon+options[i].innerHTML+'</li>';
            }

            var template = document.createElement("DIV");
            if(el.id) template.id = el.id;
            template.className = classes;

            if(multiple){
                template.innerHTML = '<div class="select selection full">'+placeholder+'</div>' +
                    '<div class="collection multiple"><div class="input-icon"><input class="search" type="text" placeholder="'+searchPlaceholder+'"><i class="fa fa-search"></i></div>' +
                        '<ul>'+ collection +'</ul>' +
                    '</div>';
            }else{
                template.innerHTML = '<input class="select full" type="text" readonly placeholder="'+placeholder+'">' + '<div class="icon">' + iconHtml + '</div>' +
                    '<div class="collection"><div class="input-icon"><input class="search" type="text" placeholder="'+searchPlaceholder+'"><i class="fa fa-search"></i></div>' +
                        '<ul>'+ collection +'</ul>' +
                    '</div>';
            }
            $(el).hide();
            $(el).after(template);
            initSelect(template,el);
        };

        function initSelect (template,el) {

            var selectedData = null;

            var input = new Input($(el));
            var dropdown = new Dropdown($(template));
            var select = new Select($(template).find('.select'));
            var search = new Search($(template).find('.search'));
            var collection = new Collection($(template).find('.collection'));


            if(collection.isMultiple()){
                select.setValues(collection.getValues());
            }else{
                select.setValue(collection.getValue());
            }

            //actions

            //click select
            select.el.on('click', function() {
                if(dropdown.isVisible()){
                    dropdown.hide();
                }else{
                    dropdown.show();
                }
            });

            //searching
            search.el.on('keyup change', function() {
                var value = this.value;
                if (value.length > 0) {
                    collection.el.find('li').show().filter(function() {
                        return $(this).text().toLowerCase().indexOf(value.toLowerCase()) == -1;
                    }).hide();
                } else {
                    collection.el.find('li').show();
                }
            });

            //click on collection
            collection.el.on('click', 'li', function(event) {

                $(el).change();

                if(!collection.isMultiple()){
                    collection.clear();
                }

                if($(this).hasClass('active') || $(this).hasClass('selected')) {
                    $(this).removeClass('selected active');
                }else{
                    $(this).addClass('active selected');
                }

                if(collection.isMultiple()){
                    selectedData = collection.getValues();
                    select.setValues(selectedData);
                    input.setValues(selectedData);
                }else{
                    select.setValue(collection.getValue());
                    input.setValue(collection.getValue());
                }
            });

            //only for multiple
            select.el.on('click', '.item i', function(event){
                event.stopPropagation();
                $(this).parent().remove();

                selectedData = select.getValues();

                collection.clear();
                collection.setValues(selectedData);
                input.setValues(selectedData);

            });


        };

        for (var i = 0; i < that.length; i++) {
            init(that[i]);
        }

    };
})(jQuery);